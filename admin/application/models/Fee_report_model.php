<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data for Student + Fee reports.
 * $f filters: class, division, gender, q (name / adm no), student, fee, special ('' | 0 | 1), mode
 */
class Fee_report_model extends CI_Model
{
    /* paid + fine paid per due (non-cancelled receipts) */
    private $paidSql = "SELECT frd.frdSfdId, SUM(frd.frdAmount) AS paid, SUM(frd.frdFine) AS fine_paid
                        FROM fee_receipt_detail frd
                        JOIN fee_receipt fr ON fr.frId = frd.frdFrId AND fr.frCancelled = 0
                        GROUP BY frd.frdSfdId";

    private function _student_where($f, &$where, &$params, $alias = 's')
    {
        if (!empty($f['class']))    { $where[] = "$alias.smClass = ?"; $params[] = (int)$f['class']; }
        if (!empty($f['division'])) { $where[] = "$alias.smDiv = ?";   $params[] = (int)$f['division']; }
        if (isset($f['gender']) && $f['gender'] !== '') { $where[] = "$alias.smGender = ?"; $params[] = (int)$f['gender']; }
        if (!empty($f['student']))  { $where[] = "$alias.smId = ?";    $params[] = (int)$f['student']; }
        if (!empty($f['q'])) {
            $where[]  = "($alias.smName LIKE ? OR $alias.smAdmissionNo LIKE ?)";
            $params[] = '%' . $f['q'] . '%';
            $params[] = '%' . $f['q'] . '%';
        }
    }

    /* ---------------- students ---------------- */
    public function students($amId, $f)
    {
        $where  = array('s.smAmId = ?');
        $params = array((int)$amId);
        $this->_student_where($f, $where, $params);
        $sql = "SELECT s.smId, s.smAdmissionNo, s.smName, s.smGender, s.smDOB, s.smMobile, s.smFatherName, s.smMotherName,
                       s.smAddress, s.smClass, s.smDiv, c.cmName, d.dmName
                FROM students_master s
                LEFT JOIN class_master c ON c.cmId = s.smClass
                LEFT JOIN division_master d ON d.dmId = s.smDiv
                WHERE " . implode(' AND ', $where) . "
                ORDER BY s.smClass, d.dmName, s.smName";
        return $this->db->query($sql, $params)->result();
    }

    /* ---------------- dues (installments) of the year ---------------- */
    /**
     * $due_till : only installments due on/before this date
     * $open_only: only installments with a balance
     */
    public function dues($amId, $f, $due_till = null, $open_only = false)
    {
        $where  = array('f.sfdAmId = ?');
        $params = array((int)$amId);
        $this->_student_where($f, $where, $params);
        if (!empty($f['fee']))                           { $where[] = 'f.sfdFmId = ?';   $params[] = (int)$f['fee']; }
        if (isset($f['special']) && $f['special'] !== '') { $where[] = 'm.fmIsSpecial = ?'; $params[] = (int)$f['special']; }
        if ($due_till)                                   { $where[] = 'f.sfdDueDate <= ?'; $params[] = $due_till; }
        if ($open_only)                                  { $where[] = 'f.sfdAmount - COALESCE(p.paid, 0) > 0'; }

        $sql = "SELECT f.sfdId, f.sfdSmId, f.sfdFmId, f.sfdInstNo, f.sfdPeriod, f.sfdDueDate, f.sfdAmount,
                       m.fmName, m.fmIsSpecial, m.fmFineType, m.fmFineAmount, m.fmFineGraceDays, m.fmFineMax, m.fmOrder,
                       s.smAdmissionNo, s.smName, s.smMobile, s.smClass, s.smDiv, c.cmName, d.dmName,
                       COALESCE(p.paid, 0) AS paid, COALESCE(p.fine_paid, 0) AS fine_paid
                FROM student_fee_due f
                JOIN fee_master m ON m.fmId = f.sfdFmId
                JOIN students_master s ON s.smId = f.sfdSmId AND s.smAmId = f.sfdAmId
                LEFT JOIN class_master c ON c.cmId = s.smClass
                LEFT JOIN division_master d ON d.dmId = s.smDiv
                LEFT JOIN ({$this->paidSql}) p ON p.frdSfdId = f.sfdId
                WHERE " . implode(' AND ', $where) . "
                ORDER BY s.smClass, d.dmName, s.smName, f.sfdSmId, f.sfdDueDate, m.fmOrder, m.fmName";
        $rows = $this->db->query($sql, $params)->result();
        foreach ($rows as $r) {
            $r->paid      = (float)$r->paid;
            $r->fine_paid = (float)$r->fine_paid;
            $r->balance   = round((float)$r->sfdAmount - $r->paid, 2);
        }
        return $rows;
    }

    /* balance of other academic years per student: [smId => amount] */
    public function old_balance_map($amId, $smIds)
    {
        $out = array();
        if (empty($smIds)) return $out;
        $in  = implode(',', array_map('intval', $smIds));
        $sql = "SELECT f.sfdSmId, SUM(GREATEST(f.sfdAmount - COALESCE(p.paid, 0), 0)) AS bal
                FROM student_fee_due f
                LEFT JOIN ({$this->paidSql}) p ON p.frdSfdId = f.sfdId
                WHERE f.sfdAmId <> ? AND f.sfdSmId IN ($in)
                GROUP BY f.sfdSmId";
        foreach ($this->db->query($sql, array((int)$amId))->result() as $r) $out[(int)$r->sfdSmId] = (float)$r->bal;
        return $out;
    }

    /* receipt numbers per due: [sfdId => "R1 (dd-mm-yyyy), ..."] */
    public function receipts_by_due($sfdIds)
    {
        $out = array();
        if (empty($sfdIds)) return $out;
        $rows = $this->db->select('d.frdSfdId, r.frReceiptNo, r.frDate')
            ->from('fee_receipt_detail d')
            ->join('fee_receipt r', 'r.frId = d.frdFrId AND r.frCancelled = 0')
            ->where_in('d.frdSfdId', $sfdIds)->order_by('r.frDate')->get()->result();
        foreach ($rows as $r) {
            $out[$r->frdSfdId][] = $r->frReceiptNo . ' (' . date('d-m-Y', strtotime($r->frDate)) . ')';
        }
        foreach ($out as $k => $v) $out[$k] = implode(', ', $v);
        return $out;
    }

    /* ---------------- collections (receipt lines) ---------------- */
    public function collection_lines($amId, $f, $from, $to)
    {
        $where  = array('r.frAmId = ?', 'r.frCancelled = 0');
        $params = array((int)$amId);
        if ($from) { $where[] = 'r.frDate >= ?'; $params[] = $from; }
        if ($to)   { $where[] = 'r.frDate <= ?'; $params[] = $to; }
        if (!empty($f['mode'])) { $where[] = 'r.frMode = ?'; $params[] = $f['mode']; }
        if (!empty($f['fee']))  { $where[] = 'frd.frdFmId = ?'; $params[] = (int)$f['fee']; }
        if (isset($f['special']) && $f['special'] !== '') { $where[] = 'm.fmIsSpecial = ?'; $params[] = (int)$f['special']; }
        $this->_student_where($f, $where, $params);

        $sql = "SELECT r.frId, r.frReceiptNo, r.frDate, r.frMode, r.frReference, r.frSmId,
                       frd.frdFmId, frd.frdAmount, frd.frdFine, frd.frdAmId, m.fmName, m.fmIsSpecial, m.fmOrder, f.sfdPeriod,
                       s.smAdmissionNo, s.smName, s.smClass, s.smDiv, c.cmName, d.dmName
                FROM fee_receipt_detail frd
                JOIN fee_receipt r ON r.frId = frd.frdFrId
                JOIN fee_master m ON m.fmId = frd.frdFmId
                LEFT JOIN student_fee_due f ON f.sfdId = frd.frdSfdId
                LEFT JOIN students_master s ON s.smId = r.frSmId AND s.smAmId = r.frAmId
                LEFT JOIN class_master c ON c.cmId = s.smClass
                LEFT JOIN division_master d ON d.dmId = s.smDiv
                WHERE " . implode(' AND ', $where) . "
                ORDER BY r.frDate, r.frSeq, m.fmOrder, m.fmName";
        return $this->db->query($sql, $params)->result();
    }

    /* receipts whose header totals do not match their lines */
    public function receipt_total_mismatch($amId)
    {
        $sql = "SELECT r.frId, r.frReceiptNo, r.frDate, r.frAmount, r.frFine, r.frTotal,
                       COALESCE(SUM(frd.frdAmount), 0) AS line_amt, COALESCE(SUM(frd.frdFine), 0) AS line_fine,
                       MAX(s.smAdmissionNo) AS smAdmissionNo, MAX(s.smName) AS smName,
                       MAX(c.cmName) AS cmName, MAX(d.dmName) AS dmName
                FROM fee_receipt r
                LEFT JOIN fee_receipt_detail frd ON frd.frdFrId = r.frId
                LEFT JOIN students_master s ON s.smId = r.frSmId AND s.smAmId = r.frAmId
                LEFT JOIN class_master c ON c.cmId = s.smClass
                LEFT JOIN division_master d ON d.dmId = s.smDiv
                WHERE r.frAmId = ? AND r.frCancelled = 0
                GROUP BY r.frId
                HAVING ABS(r.frAmount - line_amt) > 0.009 OR ABS(r.frFine - line_fine) > 0.009
                    OR ABS(r.frTotal - (r.frAmount + r.frFine)) > 0.009";
        return $this->db->query($sql, array((int)$amId))->result();
    }
}
