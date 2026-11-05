<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class divisionAllocation_Model extends CI_Model
{
    /* CHANGE: the table that says which divisions belong to which class.
       Set these three names to match your real table / columns. */
    const ALLOC_TABLE = 'class_division_allocation';
    const ALLOC_CLASS = 'cdaCmId';     // column holding the class id  (class_master.cmId)
    const ALLOC_DIV   = 'cdaDmId';       // column holding the division id (division_master.dmId)

    /* =====================================================
       CLASS ORDER: Play School/Group, LKG, UKG, then 1..12 (digits or roman)
       ===================================================== */
    private function class_rank($name)
    {
        $n = strtolower(trim($name));
        $n = trim(preg_replace('/\b(class|std|standard|grade)\b\.?/', '', $n));

        if (strpos($n, 'play') !== false || strpos($n, 'nursery') !== false || strpos($n, 'pre') === 0) return 0;
        if (preg_match('/^(l\.?\s?k\.?\s?g\.?|lower\s*kg)$/', $n)) return 1;
        if (preg_match('/^(u\.?\s?k\.?\s?g\.?|upper\s*kg)$/', $n)) return 2;

        if (preg_match('/^[ivx]+$/', $n)) return 10 + $this->roman_to_int($n);
        if (preg_match('/(\d+)/', $n, $m)) return 10 + (int)$m[1];

        return 999;
    }

    private function roman_to_int($s)
    {
        $map = ['i' => 1, 'v' => 5, 'x' => 10];
        $total = 0; $prev = 0;
        foreach (array_reverse(str_split($s)) as $ch) {
            $v = isset($map[$ch]) ? $map[$ch] : 0;
            if ($v < $prev) { $total -= $v; } else { $total += $v; $prev = $v; }
        }
        return $total;
    }

    private function sort_classes(array $rows)
    {
        usort($rows, function ($a, $b) {
            $ra = $this->class_rank($a->cmName);
            $rb = $this->class_rank($b->cmName);
            return ($ra <=> $rb) ?: strcmp($a->cmName, $b->cmName);
        });
        return $rows;
    }

    /* =====================================================
       YEAR  (current AND not promoted yet)
       ===================================================== */
    public function get_active_year()
    {
        return $this->db->where('amIsCurrent', 1)
                        ->where('c_status', 0)
                        ->order_by('amYear', 'DESC')
                        ->limit(1)
                        ->get('academic_master')->row();
    }

    /* =====================================================
       CLASSES (all classes, school order, with active student count of the year)
       ===================================================== */
    public function get_classes($yearId)
    {
        $rows = $this->db->query("
            SELECT c.cmId, c.cmName, COUNT(s.smId) AS total
            FROM class_master c
            LEFT JOIN students_master s
                   ON s.smClass = c.cmId AND s.smAmId = ? AND s.smActive = 1
            GROUP BY c.cmId, c.cmName", [(int)$yearId])->result();

        return $this->sort_classes($rows);
    }

    public function class_exists($classId)
    {
        return $this->db->where('cmId', (int)$classId)->count_all_results('class_master') > 0;
    }

    /* =====================================================
       DIVISIONS of one class (from the allocation table)
       ===================================================== */
    public function get_divisions($classId)
    {
        return $this->db->query("
            SELECT DISTINCT d.dmId, d.dmName
            FROM " . self::ALLOC_TABLE . " a
            JOIN division_master d ON d.dmId = a." . self::ALLOC_DIV . "
            WHERE a." . self::ALLOC_CLASS . " = ?
            ORDER BY d.dmName ASC", [(int)$classId])->result();
    }

    /* =====================================================
       STUDENTS of one class: active only, girls (0) first, then boys (1), name A-Z
       ===================================================== */
    public function get_students($classId, $yearId)
    {
        return $this->db->query("
            SELECT s.smId, s.smAdmissionNo, s.smName, s.smGender, s.smDiv, d.dmName AS divName
            FROM students_master s
            LEFT JOIN division_master d ON d.dmId = s.smDiv
            WHERE s.smAmId = ? AND s.smClass = ? AND s.smActive = 1
            ORDER BY CASE WHEN s.smGender = '0' THEN 0
                          WHEN s.smGender = '1' THEN 1
                          ELSE 2 END ASC,
                     TRIM(s.smName) ASC", [(int)$yearId, (int)$classId])->result();
    }

    /* =====================================================
       SAVE
       $assign = [smId => divisionId]   (0 = no division)
       $tc     = [smId, ...]            (smActive = 0)
       Only active students of this class and year can be changed.
       Returns ['ok' => bool, 'msg' => string]
       ===================================================== */
    public function save($classId, $yearId, array $assign, array $tc)
    {
        $classId = (int)$classId;
        $yearId  = (int)$yearId;

        // students that may be changed
        $rows  = $this->db->select('smId')
                          ->where('smClass', $classId)
                          ->where('smAmId', $yearId)
                          ->where('smActive', 1)
                          ->get('students_master')->result();
        $valid = [];
        foreach ($rows as $r) $valid[(int)$r->smId] = true;

        // divisions that may be used
        $allowed = [];
        foreach ($this->get_divisions($classId) as $d) $allowed[(int)$d->dmId] = true;

        $tcSet = [];
        foreach ($tc as $id) {
            $id = (int)$id;
            if (!isset($valid[$id])) return ['ok' => false, 'msg' => 'Invalid student in the TC list.'];
            $tcSet[$id] = true;
        }

        $byDiv = [];
        foreach ($assign as $sid => $did) {
            $sid = (int)$sid; $did = (int)$did;
            if (!isset($valid[$sid]))                      return ['ok' => false, 'msg' => 'Invalid student in the list.'];
            if ($did !== 0 && !isset($allowed[$did]))      return ['ok' => false, 'msg' => 'A division is not allocated to this class.'];
            if (isset($tcSet[$sid])) continue;             // TC students keep their division
            $byDiv[$did][] = $sid;
        }

        $this->db->trans_begin();

        foreach ($byDiv as $did => $ids) {
            foreach (array_chunk($ids, 200) as $chunk) {
                $this->db->where_in('smId', $chunk)
                         ->where('smClass', $classId)
                         ->where('smAmId', $yearId)
                         ->update('students_master', ['smDiv' => (int)$did]);
            }
        }

        if ($tcSet) {
            foreach (array_chunk(array_keys($tcSet), 200) as $chunk) {
                $this->db->where_in('smId', $chunk)
                         ->where('smClass', $classId)
                         ->where('smAmId', $yearId)
                         ->update('students_master', ['smActive' => 0]);
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return ['ok' => false, 'msg' => 'Saving failed. Please try again.'];
        }
        $this->db->trans_commit();
        return ['ok' => true, 'msg' => 'Saved successfully.'];
    }
}