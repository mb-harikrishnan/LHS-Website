<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fee management
 *
 * Tables
 *   fee_master             fee definition per academic year (fmAmount = amount of ONE installment)
 *   fee_master_class       optional class-wise amount (overrides fmAmount for that class)
 *   student_fee_allocation fee allotted to a student for a year (sfaAmount = amount of one installment)
 *   student_fee_due        one row per installment of an allotted fee  (the "dues")
 *   fee_receipt            a collection; fee_receipt_detail = amount + fine paid against each due
 *
 * Paid amount of a due = SUM(frdAmount) of receipts that are not cancelled.
 * Balance = sfdAmount - paid. Partial payment simply leaves a balance on the due.
 */
class Fee_model extends CI_Model
{
    /* step = months between installments, max = installments possible in a year */
    public static $frequencies = array(
        'MONTHLY'     => array('label' => 'Monthly',     'step' => 1,  'max' => 12, 'default' => 12),
        'QUARTERLY'   => array('label' => 'Quarterly',   'step' => 3,  'max' => 4,  'default' => 4),
        'HALF_YEARLY' => array('label' => 'Half Yearly', 'step' => 6,  'max' => 2,  'default' => 2),
        'ANNUAL'      => array('label' => 'Annually',    'step' => 12, 'max' => 1,  'default' => 1),
        'ONE_TIME'    => array('label' => 'One Time',    'step' => 12, 'max' => 1,  'default' => 1),
    );

    public static $fine_types = array(
        'NONE'    => 'No Fine',
        'FIXED'   => 'Fixed amount (once)',
        'PER_DAY' => 'Per day late',
    );

    public static $pay_modes = array('Cash', 'UPI', 'Card', 'Cheque', 'Bank Transfer', 'DD');

    /* stop with a clear message when the fee migration has not been run on this database */
    public function require_schema()
    {
        if (!$this->db->table_exists('student_fee_due') || !$this->db->field_exists('fmOrder', 'fee_master')) {
            show_error('The fee module tables are not set up on this database yet. Log in as admin and open '
                . site_url('Migration') . ' once to create them.', 500, 'Fee module not installed');
        }
    }

    /* ================================================================
       ACADEMIC YEAR
       ================================================================ */
    public function get_am_id()
    {
        $amId = (int)$this->session->userdata('selected_am_id');
        if ($amId > 0) return $amId;
        $row = $this->db->select('amId')->where('amIsCurrent', 1)->get('academic_master')->row();
        return $row ? (int)$row->amId : 0;
    }

    public function get_year($amId)
    {
        return $this->db->where('amId', (int)$amId)->get('academic_master')->row();
    }

    public function get_years()
    {
        return $this->db->order_by('amYear', 'DESC')->get('academic_master')->result();
    }

    /* "2026-27" -> 2026 */
    private function _year_start($amId)
    {
        $y = $this->get_year($amId);
        if ($y && preg_match('/^(\d{4})/', (string)$y->amYear, $m)) return (int)$m[1];
        return (int)date('Y');
    }

    /* ================================================================
       FEE MASTER
       ================================================================ */
    public function get_fees($amId, $active_only = false, $special = null)
    {
        $this->db->where('fmAmId', (int)$amId);
        if ($active_only) $this->db->where('fmActive', 1);
        if ($special !== null) $this->db->where('fmIsSpecial', $special ? 1 : 0);
        $rows = $this->db->order_by('fmOrder', 'ASC')->order_by('fmName', 'ASC')->get('fee_master')->result();

        // attach class-wise amounts {cmId: amount}
        $map = $this->_class_amount_map(array_map(function ($r) { return (int)$r->fmId; }, $rows));
        foreach ($rows as $r) {
            $r->class_amounts = isset($map[$r->fmId]) ? $map[$r->fmId] : array();
            $r->alloc_count   = 0;
        }
        if ($rows) {
            $ids = array_map(function ($r) { return (int)$r->fmId; }, $rows);
            $cnt = $this->db->select('sfaFmId, COUNT(*) c', false)->where_in('sfaFmId', $ids)->where('sfaActive', 1)
                            ->group_by('sfaFmId')->get('student_fee_allocation')->result();
            $c = array();
            foreach ($cnt as $x) $c[$x->sfaFmId] = (int)$x->c;
            foreach ($rows as $r) $r->alloc_count = isset($c[$r->fmId]) ? $c[$r->fmId] : 0;
        }
        return $rows;
    }

    public function get_fee($fmId)
    {
        $r = $this->db->where('fmId', (int)$fmId)->get('fee_master')->row();
        if ($r) {
            $map = $this->_class_amount_map(array((int)$fmId));
            $r->class_amounts = isset($map[$r->fmId]) ? $map[$r->fmId] : array();
        }
        return $r;
    }

    private function _class_amount_map($fmIds)
    {
        $map = array();
        if (empty($fmIds)) return $map;
        $rows = $this->db->where_in('fmcFmId', $fmIds)->get('fee_master_class')->result();
        foreach ($rows as $r) $map[$r->fmcFmId][(string)$r->fmcCmId] = (float)$r->fmcAmount;
        return $map;
    }

    public function fee_name_exists($name, $amId, $exclude_id = 0)
    {
        $this->db->where('LOWER(fmName)', mb_strtolower($name))->where('fmAmId', (int)$amId);
        if ($exclude_id) $this->db->where('fmId !=', (int)$exclude_id);
        return $this->db->count_all_results('fee_master') > 0;
    }

    /* installment amount of a fee for a class */
    public function resolve_amount($fee, $cmId)
    {
        if (is_numeric($fee)) $fee = $this->get_fee($fee);
        if (!$fee) return 0.0;
        $k = (string)(int)$cmId;
        return isset($fee->class_amounts[$k]) ? (float)$fee->class_amounts[$k] : (float)$fee->fmAmount;
    }

    /**
     * Insert / update a fee.
     * $class_amounts : [cmId => amount] (only classes that differ from the default amount)
     * $apply_existing: re-price already allotted students to the new amount (unpaid installments only)
     */
    public function save_fee($fmId, $data, $class_amounts, $apply_existing = false)
    {
        $this->db->trans_start();
        if ($fmId) {
            $this->db->where('fmId', (int)$fmId)->update('fee_master', $data);
        } else {
            $this->db->insert('fee_master', $data);
            $fmId = (int)$this->db->insert_id();
        }

        $this->db->where('fmcFmId', (int)$fmId)->delete('fee_master_class');
        foreach ($class_amounts as $cmId => $amt) {
            $this->db->insert('fee_master_class', array('fmcFmId' => $fmId, 'fmcCmId' => (int)$cmId, 'fmcAmount' => $amt));
        }

        if ($apply_existing) {
            $fee = $this->get_fee($fmId);
            $allocs = $this->db->select('a.sfaId, s.smClass')
                ->from('student_fee_allocation a')
                ->join('students_master s', 's.smId = a.sfaSmId AND s.smAmId = a.sfaAmId', 'left')
                ->where('a.sfaFmId', (int)$fmId)->get()->result();
            foreach ($allocs as $a) {
                $this->db->where('sfaId', $a->sfaId)->update('student_fee_allocation',
                    array('sfaAmount' => $this->resolve_amount($fee, $a->smClass)));
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->sync_fee_dues($fmId);   // installments / dates may have changed
            return $fmId;
        }
        return 0;
    }

    public function set_fee_status($fmId, $active)
    {
        return $this->db->where('fmId', (int)$fmId)->update('fee_master', array('fmActive' => $active ? 1 : 0));
    }

    public function fee_has_allocations($fmId)
    {
        return $this->db->where('sfaFmId', (int)$fmId)->count_all_results('student_fee_allocation') > 0;
    }

    public function delete_fee($fmId)
    {
        $this->db->trans_start();
        $this->db->where('fmcFmId', (int)$fmId)->delete('fee_master_class');
        $this->db->where('fmId', (int)$fmId)->delete('fee_master');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* copy fees (and class amounts) of one year into another; skips names that already exist */
    public function copy_fees($fromAmId, $toAmId, $user)
    {
        $copied = 0;
        foreach ($this->get_fees($fromAmId) as $f) {
            if ($this->fee_name_exists($f->fmName, $toAmId)) continue;
            $row = (array)$f;
            unset($row['fmId'], $row['fmTs'], $row['class_amounts'], $row['alloc_count']);
            $row['fmAmId'] = (int)$toAmId;
            $row['fmUser'] = $user;
            if ($this->save_fee(0, $row, $f->class_amounts)) $copied++;
        }
        return $copied;
    }

    /* ================================================================
       INSTALLMENT SCHEDULE
       ================================================================ */
    /* [ [inst, period, due_date], ... ] for a fee in a year */
    public function schedule($fee, $amId)
    {
        $freq  = isset(self::$frequencies[$fee->fmFrequency]) ? $fee->fmFrequency : 'ANNUAL';
        $cfg   = self::$frequencies[$freq];
        $n     = max(1, min($cfg['max'], (int)$fee->fmInstallments));
        $month = max(1, min(12, (int)$fee->fmStartMonth));
        $day   = max(1, min(28, (int)$fee->fmDueDay));
        // academic year "2026-27": April-December fall in 2026, January-March in 2027
        $yr    = $this->_year_start($amId) + ($month <= 3 ? 1 : 0);
        $start = new DateTime(sprintf('%04d-%02d-01', $yr, $month));
        $year  = $this->get_year($amId);
        $ylab  = $year ? $year->amYear : '';

        $out = array();
        for ($i = 1; $i <= $n; $i++) {
            $d = clone $start;
            $d->modify('+' . (($i - 1) * $cfg['step']) . ' month');
            $e = clone $d;
            $e->modify('+' . ($cfg['step'] - 1) . ' month');

            switch ($freq) {
                case 'MONTHLY':     $label = $d->format('M Y'); break;
                case 'QUARTERLY':   $label = 'Q' . $i . ' (' . $d->format('M') . '-' . $e->format('M Y') . ')'; break;
                case 'HALF_YEARLY': $label = 'H' . $i . ' (' . $d->format('M') . '-' . $e->format('M Y') . ')'; break;
                case 'ANNUAL':      $label = 'Annual ' . $ylab; break;
                default:            $label = 'One Time'; break;
            }
            $out[] = array('inst' => $i, 'period' => $label, 'due_date' => $d->format('Y-m-') . sprintf('%02d', $day));
        }
        return $out;
    }

    /* ================================================================
       ALLOCATION + DUES
       ================================================================ */
    /* paid amount per due id (cancelled receipts ignored) */
    public function paid_map($sfdIds)
    {
        $map = array();
        if (empty($sfdIds)) return $map;
        $rows = $this->db->select('d.frdSfdId, SUM(d.frdAmount) paid, SUM(d.frdFine) fine_paid', false)
            ->from('fee_receipt_detail d')
            ->join('fee_receipt r', 'r.frId = d.frdFrId AND r.frCancelled = 0')
            ->where_in('d.frdSfdId', $sfdIds)
            ->group_by('d.frdSfdId')->get()->result();
        foreach ($rows as $r) $map[$r->frdSfdId] = array('paid' => (float)$r->paid, 'fine_paid' => (float)$r->fine_paid);
        return $map;
    }

    /**
     * Make student_fee_due match the fee settings + allotted amount.
     * Installments that already have payments are never removed, and their
     * amount is never reduced below what was paid.
     */
    public function sync_allocation_dues($sfaId)
    {
        $a = $this->db->where('sfaId', (int)$sfaId)->get('student_fee_allocation')->row();
        if (!$a) return;
        $fee = $this->get_fee($a->sfaFmId);

        $existing = $this->db->where('sfdSfaId', (int)$sfaId)->get('student_fee_due')->result();
        $paid     = $this->paid_map(array_map(function ($d) { return (int)$d->sfdId; }, $existing));
        $byInst   = array();
        foreach ($existing as $d) $byInst[(int)$d->sfdInstNo] = $d;

        $schedule = ($fee && (int)$a->sfaActive === 1) ? $this->schedule($fee, $a->sfaAmId) : array();
        $amount   = round((float)$a->sfaAmount, 2);
        $keep     = array();

        foreach ($schedule as $s) {
            $keep[$s['inst']] = true;
            if (isset($byInst[$s['inst']])) {
                $d = $byInst[$s['inst']];
                $p = isset($paid[$d->sfdId]) ? $paid[$d->sfdId]['paid'] : 0;
                $this->db->where('sfdId', $d->sfdId)->update('student_fee_due', array(
                    'sfdPeriod'  => $s['period'],
                    'sfdDueDate' => $s['due_date'],
                    'sfdAmount'  => max($amount, $p),
                ));
            } else {
                $this->db->insert('student_fee_due', array(
                    'sfdSfaId'   => (int)$a->sfaId,
                    'sfdSmId'    => (int)$a->sfaSmId,
                    'sfdFmId'    => (int)$a->sfaFmId,
                    'sfdAmId'    => (int)$a->sfaAmId,
                    'sfdInstNo'  => $s['inst'],
                    'sfdPeriod'  => $s['period'],
                    'sfdDueDate' => $s['due_date'],
                    'sfdAmount'  => $amount,
                ));
            }
        }
        // installments no longer in the schedule: remove if nothing was paid
        foreach ($byInst as $inst => $d) {
            if (isset($keep[$inst])) continue;
            if (empty($paid[$d->sfdId]) || $paid[$d->sfdId]['paid'] <= 0) {
                $this->db->where('sfdId', $d->sfdId)->delete('student_fee_due');
            }
        }
    }

    public function sync_fee_dues($fmId)
    {
        $ids = $this->db->select('sfaId')->where('sfaFmId', (int)$fmId)->get('student_fee_allocation')->result();
        foreach ($ids as $r) $this->sync_allocation_dues($r->sfaId);
    }

    public function sync_all_dues()
    {
        $ids = $this->db->select('sfaId')->get('student_fee_allocation')->result();
        foreach ($ids as $r) $this->sync_allocation_dues($r->sfaId);
    }

    /* total paid (not cancelled) against one allocation */
    public function allocation_paid($sfaId)
    {
        $r = $this->db->select('COALESCE(SUM(d.frdAmount),0) paid', false)
            ->from('fee_receipt_detail d')
            ->join('fee_receipt r', 'r.frId = d.frdFrId AND r.frCancelled = 0')
            ->join('student_fee_due f', 'f.sfdId = d.frdSfdId')
            ->where('f.sfdSfaId', (int)$sfaId)->get()->row();
        return $r ? (float)$r->paid : 0.0;
    }

    /* fees allotted to a student in a year: [fmId => row(sfaId, sfaAmount, paid)] */
    public function get_student_allocations($smId, $amId)
    {
        $rows = $this->db->select('a.*, f.fmName')
            ->from('student_fee_allocation a')
            ->join('fee_master f', 'f.fmId = a.sfaFmId')
            ->where('a.sfaSmId', (int)$smId)->where('a.sfaAmId', (int)$amId)->where('a.sfaActive', 1)
            ->get()->result();
        $out = array();
        foreach ($rows as $r) {
            $r->paid = $this->allocation_paid($r->sfaId);
            $out[(int)$r->sfaFmId] = $r;
        }
        return $out;
    }

    /* allot (or re-price) a fee to a student, then build its dues */
    public function allocate($smId, $amId, $fmId, $amount, $user = null)
    {
        $row = $this->db->where(array('sfaSmId' => (int)$smId, 'sfaFmId' => (int)$fmId, 'sfaAmId' => (int)$amId))
                        ->get('student_fee_allocation')->row();
        $data = array('sfaAmount' => round((float)$amount, 2), 'sfaActive' => 1, 'sfaUser' => $user);
        if ($row) {
            $this->db->where('sfaId', $row->sfaId)->update('student_fee_allocation', $data);
            $sfaId = (int)$row->sfaId;
        } else {
            $data += array('sfaSmId' => (int)$smId, 'sfaFmId' => (int)$fmId, 'sfaAmId' => (int)$amId);
            $this->db->insert('student_fee_allocation', $data);
            $sfaId = (int)$this->db->insert_id();
        }
        $this->sync_allocation_dues($sfaId);
        return $sfaId;
    }

    /* remove a fee from a student. Returns false when payments exist (cancel the receipts first) */
    public function deallocate($smId, $amId, $fmId)
    {
        $row = $this->db->where(array('sfaSmId' => (int)$smId, 'sfaFmId' => (int)$fmId, 'sfaAmId' => (int)$amId))
                        ->get('student_fee_allocation')->row();
        if (!$row) return true;
        if ($this->allocation_paid($row->sfaId) > 0) return false;
        // dues may still be referenced by cancelled receipts; keep those rows, drop the rest
        $used = $this->db->select('DISTINCT d.frdSfdId', false)->from('fee_receipt_detail d')
            ->join('student_fee_due f', 'f.sfdId = d.frdSfdId')->where('f.sfdSfaId', $row->sfaId)->get()->result();
        if ($used) {
            $this->db->where('sfaId', $row->sfaId)->update('student_fee_allocation', array('sfaActive' => 0));
            $this->sync_allocation_dues($row->sfaId);
        } else {
            $this->db->where('sfdSfaId', $row->sfaId)->delete('student_fee_due');
            $this->db->where('sfaId', $row->sfaId)->delete('student_fee_allocation');
        }
        return true;
    }

    /* any non-cancelled payment by this student (any year)? */
    public function student_has_payments($smId)
    {
        return $this->db->where('frSmId', (int)$smId)->where('frCancelled', 0)->count_all_results('fee_receipt') > 0;
    }

    /* used when a student record is deleted */
    public function delete_student_fees($smId)
    {
        $this->db->where('sfdSmId', (int)$smId)->delete('student_fee_due');
        $this->db->where('sfaSmId', (int)$smId)->delete('student_fee_allocation');
    }

    /* ================================================================
       FINE
       ================================================================ */
    /**
     * Fine still payable on a due as of $asOf (Y-m-d).
     * $d needs: sfdDueDate, balance, fine_paid, fmFineType, fmFineAmount, fmFineGraceDays, fmFineMax
     */
    public function compute_fine($d, $asOf = null)
    {
        $asOf = $asOf ?: date('Y-m-d');
        if ((float)$d->balance <= 0 || $d->fmFineType === 'NONE' || (float)$d->fmFineAmount <= 0) return 0.0;

        $due   = new DateTime($d->sfdDueDate);
        $grace = clone $due;
        $grace->modify('+' . max(0, (int)$d->fmFineGraceDays) . ' day');
        $today = new DateTime($asOf);
        if ($today <= $grace) return 0.0;

        if ($d->fmFineType === 'FIXED') {
            $fine = (float)$d->fmFineAmount;
        } else {   // PER_DAY, counted from the end of the grace period
            $fine = (float)$d->fmFineAmount * (int)$grace->diff($today)->days;
        }
        if ((float)$d->fmFineMax > 0) $fine = min($fine, (float)$d->fmFineMax);
        return max(0.0, round($fine - (float)$d->fine_paid, 2));
    }

    /* ================================================================
       STUDENTS (lookup)
       ================================================================ */
    public function search_students($q, $amId, $class = '', $div = '', $limit = 20)
    {
        $this->db->select('s.smId, s.smAdmissionNo, s.smName, s.smClass, s.smDiv, c.cmName, d.dmName')
            ->from('students_master s')
            ->join('class_master c', 'c.cmId = s.smClass', 'left')
            ->join('division_master d', 'd.dmId = s.smDiv', 'left')
            ->where('s.smAmId', (int)$amId);
        if ($q !== '') {
            $this->db->group_start()->like('s.smName', $q)->or_like('s.smAdmissionNo', $q)->group_end();
        }
        if ($class !== '') $this->db->where('s.smClass', (int)$class);
        if ($div !== '')   $this->db->where('s.smDiv', (int)$div);
        return $this->db->order_by('s.smName', 'ASC')->limit((int)$limit)->get()->result();
    }

    public function get_student($smId, $amId)
    {
        return $this->db->select('s.smId, s.smAdmissionNo, s.smName, s.smClass, s.smDiv, s.smMobile, s.smFatherName, s.smGender, c.cmName, d.dmName')
            ->from('students_master s')
            ->join('class_master c', 'c.cmId = s.smClass', 'left')
            ->join('division_master d', 'd.dmId = s.smDiv', 'left')
            ->where('s.smId', (int)$smId)->where('s.smAmId', (int)$amId)
            ->get()->row();
    }

    /* students of a class (+division) with their allocation of one fee */
    public function class_students_for_fee($amId, $cmId, $dmId, $fmId)
    {
        $this->db->select('s.smId, s.smAdmissionNo, s.smName, s.smDiv, d.dmName, a.sfaId, a.sfaAmount, a.sfaActive')
            ->from('students_master s')
            ->join('division_master d', 'd.dmId = s.smDiv', 'left')
            ->join('student_fee_allocation a', 'a.sfaSmId = s.smId AND a.sfaAmId = s.smAmId AND a.sfaFmId = ' . (int)$fmId . ' AND a.sfaActive = 1', 'left')
            ->where('s.smAmId', (int)$amId)->where('s.smClass', (int)$cmId);
        if ($dmId !== '') $this->db->where('s.smDiv', (int)$dmId);
        $rows = $this->db->order_by('d.dmName', 'ASC')->order_by('s.smName', 'ASC')->get()->result();
        foreach ($rows as $r) $r->paid = $r->sfaId ? $this->allocation_paid($r->sfaId) : 0;
        return $rows;
    }

    /* ================================================================
       DUES OF A STUDENT
       ================================================================ */
    /**
     * Installments of a student with paid / balance / fine.
     * $amId null = every academic year (old balance included)
     */
    public function get_student_dues($smId, $amId = null, $only_open = true, $asOf = null)
    {
        $this->db->select('f.*, m.fmName, m.fmIsSpecial, m.fmFineType, m.fmFineAmount, m.fmFineGraceDays, m.fmFineMax, m.fmOrder, y.amYear')
            ->from('student_fee_due f')
            ->join('student_fee_allocation a', 'a.sfaId = f.sfdSfaId')
            ->join('fee_master m', 'm.fmId = f.sfdFmId')
            ->join('academic_master y', 'y.amId = f.sfdAmId', 'left')
            ->where('f.sfdSmId', (int)$smId);
        if ($amId !== null) $this->db->where('f.sfdAmId', (int)$amId);
        $rows = $this->db->order_by('y.amYear', 'ASC')->order_by('f.sfdDueDate', 'ASC')
                         ->order_by('m.fmOrder', 'ASC')->order_by('m.fmName', 'ASC')->get()->result();

        $paid = $this->paid_map(array_map(function ($r) { return (int)$r->sfdId; }, $rows));
        $out  = array();
        foreach ($rows as $r) {
            $p = isset($paid[$r->sfdId]) ? $paid[$r->sfdId] : array('paid' => 0, 'fine_paid' => 0);
            $r->paid      = $p['paid'];
            $r->fine_paid = $p['fine_paid'];
            $r->balance   = round((float)$r->sfdAmount - $p['paid'], 2);
            $r->fine      = $this->compute_fine($r, $asOf);
            if ($only_open && $r->balance <= 0) continue;
            $out[] = $r;
        }
        return $out;
    }

    /* ================================================================
       COLLECTION
       ================================================================ */
    /**
     * Save a receipt.
     * $lines : [ sfdId => ['amount' => x, 'fine' => y], ... ]
     * $meta  : date, mode, reference, remarks, user
     * returns ['ok' => bool, 'msg' => string, 'id' => frId]
     */
    public function collect($smId, $amId, $lines, $meta)
    {
        $ids = array_map('intval', array_keys($lines));
        if (empty($ids)) return array('ok' => false, 'msg' => 'Select at least one installment to collect.');

        $this->db->trans_begin();

        // lock the dues so two counters cannot over-collect the same installment
        $dues = $this->db->query('SELECT * FROM student_fee_due WHERE sfdId IN (' . implode(',', $ids) . ') FOR UPDATE')->result();
        $byId = array();
        foreach ($dues as $d) $byId[(int)$d->sfdId] = $d;
        $paid = $this->paid_map($ids);

        $total_amt = 0.0;
        $total_fine = 0.0;
        $details = array();
        foreach ($lines as $sfdId => $l) {
            $sfdId = (int)$sfdId;
            $amt   = round((float)$l['amount'], 2);
            $fine  = round((float)$l['fine'], 2);
            if ($amt <= 0 && $fine <= 0) continue;

            if (!isset($byId[$sfdId]) || (int)$byId[$sfdId]->sfdSmId !== (int)$smId) {
                $this->db->trans_rollback();
                return array('ok' => false, 'msg' => 'An installment does not belong to this student. Reload and try again.');
            }
            $d   = $byId[$sfdId];
            $bal = round((float)$d->sfdAmount - (isset($paid[$sfdId]) ? $paid[$sfdId]['paid'] : 0), 2);
            if ($amt < 0 || $fine < 0) {
                $this->db->trans_rollback();
                return array('ok' => false, 'msg' => 'Amounts cannot be negative.');
            }
            if ($amt > $bal + 0.001) {
                $this->db->trans_rollback();
                return array('ok' => false, 'msg' => $d->sfdPeriod . ': amount ' . $amt . ' is more than the balance ' . $bal . '. Reload and try again.');
            }
            $total_amt  += $amt;
            $total_fine += $fine;
            $details[] = array(
                'frdSfdId' => $sfdId, 'frdSmId' => (int)$smId, 'frdFmId' => (int)$d->sfdFmId,
                'frdAmId' => (int)$d->sfdAmId, 'frdAmount' => $amt, 'frdFine' => $fine,
            );
        }
        if (empty($details) || ($total_amt + $total_fine) <= 0) {
            $this->db->trans_rollback();
            return array('ok' => false, 'msg' => 'Enter the amount to collect.');
        }

        // receipt number: <year>/<seq>, sequence per academic year
        $seqRow = $this->db->query('SELECT COALESCE(MAX(frSeq),0) + 1 AS n FROM fee_receipt WHERE frAmId = ? FOR UPDATE', array((int)$amId))->row();
        $seq    = (int)$seqRow->n;
        $year   = $this->get_year($amId);
        $no     = ($year ? $year->amYear : $amId) . '/' . str_pad($seq, 5, '0', STR_PAD_LEFT);

        $this->db->insert('fee_receipt', array(
            'frAmId'      => (int)$amId,
            'frSeq'       => $seq,
            'frReceiptNo' => $no,
            'frSmId'      => (int)$smId,
            'frDate'      => $meta['date'],
            'frMode'      => $meta['mode'],
            'frReference' => $meta['reference'],
            'frRemarks'   => $meta['remarks'],
            'frAmount'    => round($total_amt, 2),
            'frFine'      => round($total_fine, 2),
            'frTotal'     => round($total_amt + $total_fine, 2),
            'frUser'      => $meta['user'],
        ));
        $frId = (int)$this->db->insert_id();
        foreach ($details as &$x) $x['frdFrId'] = $frId;
        unset($x);
        $this->db->insert_batch('fee_receipt_detail', $details);

        if ($this->db->trans_status() === false || !$frId) {
            $this->db->trans_rollback();
            return array('ok' => false, 'msg' => 'Could not save the receipt. Please try again.');
        }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Receipt ' . $no . ' saved.', 'id' => $frId);
    }

    /* ================================================================
       RECEIPTS
       ================================================================ */
    public function get_receipt($frId)
    {
        $r = $this->db->select('r.*, y.amYear')->from('fee_receipt r')
            ->join('academic_master y', 'y.amId = r.frAmId', 'left')
            ->where('r.frId', (int)$frId)->get()->row();
        if (!$r) return null;
        $r->student = $this->get_student($r->frSmId, $r->frAmId);
        $r->lines = $this->db->select('d.*, f.sfdPeriod, f.sfdDueDate, m.fmName, y.amYear')
            ->from('fee_receipt_detail d')
            ->join('student_fee_due f', 'f.sfdId = d.frdSfdId', 'left')
            ->join('fee_master m', 'm.fmId = d.frdFmId', 'left')
            ->join('academic_master y', 'y.amId = d.frdAmId', 'left')
            ->where('d.frdFrId', (int)$frId)
            ->order_by('f.sfdDueDate', 'ASC')->order_by('m.fmName', 'ASC')->get()->result();
        $r->user_name = $this->user_name($r->frUser);
        return $r;
    }

    /* employee name of a login, else the login name */
    public function user_name($userId)
    {
        if (!$userId) return '';
        $e = $this->db->select('emName')->where('emUserId', (int)$userId)->get('employee_master')->row();
        if ($e && $e->emName !== '') return $e->emName;
        $u = $this->db->select('name')->where('user_id', (int)$userId)->get('user_login')->row();
        return $u ? $u->name : '';
    }

    private function _receipt_filters($f)
    {
        $this->db->from('fee_receipt r')
            ->join('students_master s', 's.smId = r.frSmId AND s.smAmId = r.frAmId', 'left')
            ->join('class_master c', 'c.cmId = s.smClass', 'left')
            ->join('division_master d', 'd.dmId = s.smDiv', 'left')
            ->where('r.frAmId', (int)$f['am_id']);
        if (!empty($f['from'])) $this->db->where('r.frDate >=', $f['from']);
        if (!empty($f['to']))   $this->db->where('r.frDate <=', $f['to']);
        if (!empty($f['q'])) {
            $this->db->group_start()->like('s.smName', $f['q'])->or_like('s.smAdmissionNo', $f['q'])
                     ->or_like('r.frReceiptNo', $f['q'])->group_end();
        }
        if (isset($f['status']) && $f['status'] !== '') $this->db->where('r.frCancelled', (int)$f['status']);
        if (!empty($f['student'])) $this->db->where('r.frSmId', (int)$f['student']);
    }

    public function count_receipts($f)
    {
        $this->_receipt_filters($f);
        return (int)$this->db->count_all_results();
    }

    public function list_receipts($f, $limit = 0, $offset = 0)
    {
        $this->db->select('r.*, s.smName, s.smAdmissionNo, c.cmName, d.dmName');
        $this->_receipt_filters($f);
        $this->db->order_by('r.frDate', 'DESC')->order_by('r.frId', 'DESC');
        if ($limit) $this->db->limit((int)$limit, (int)$offset);
        return $this->db->get()->result();
    }

    public function cancel_receipt($frId, $reason, $user)
    {
        return $this->db->where('frId', (int)$frId)->where('frCancelled', 0)->update('fee_receipt', array(
            'frCancelled'    => 1,
            'frCancelReason' => $reason,
            'frCancelledBy'  => $user,
            'frCancelledAt'  => date('Y-m-d H:i:s'),
        )) && $this->db->affected_rows() > 0;
    }
}
