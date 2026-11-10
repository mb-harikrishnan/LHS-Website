<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class promotion_Model extends CI_Model
{
    const FINAL_RANK = 22;      // 10 + 12 => class 12 (never promoted)

    private $map_cache = null;

    /* =====================================================
       CLASS ORDER
       Play School/Group = 0, LKG = 1, UKG = 2,
       1..12 (digits OR roman I..XII) = 11..22, unknown = 999
       ===================================================== */
    private function class_rank($name)
    {
        $n = strtolower(trim($name));
        $n = trim(preg_replace('/\b(class|std|standard|grade)\b\.?/', '', $n));

        if (strpos($n, 'play') !== false || strpos($n, 'nursery') !== false || strpos($n, 'pre') === 0) return 0;
        if (preg_match('/^(l\.?\s?k\.?\s?g\.?|lower\s*kg)$/', $n)) return 1;
        if (preg_match('/^(u\.?\s?k\.?\s?g\.?|upper\s*kg)$/', $n)) return 2;

        if (preg_match('/^[ivx]+$/', $n)) return 10 + $this->roman_to_int($n);   // I..XII
        if (preg_match('/(\d+)/', $n, $m)) return 10 + (int)$m[1];               // 1..12, 5th, Class 5

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

    /* fromClassId => ['to' => nextClassId, 'toName' => nextClassName]
       Class 12 and unrecognised class names are NOT in this map, so they are never promoted. */
    private function class_map()
    {
        if ($this->map_cache !== null) return $this->map_cache;

        $rows = array_filter($this->db->get('class_master')->result(), function ($c) {
            return $this->class_rank($c->cmName) < 999;
        });
        $rows = $this->sort_classes(array_values($rows));

        $map = [];
        for ($i = 0; $i < count($rows) - 1; $i++) {
            if ($this->class_rank($rows[$i]->cmName) === self::FINAL_RANK) continue;
            $map[(int)$rows[$i]->cmId] = [
                'to'     => (int)$rows[$i + 1]->cmId,
                'toName' => $rows[$i + 1]->cmName,
            ];
        }
        return $this->map_cache = $map;
    }

    /* =====================================================
       ACADEMIC YEARS
       ===================================================== */

    /* Current year. If several rows have amIsCurrent = 1, the latest year is used. */
    public function get_current_year()
    {
        return $this->db->where('amIsCurrent', 1)
                        ->order_by('amYear', 'DESC')
                        ->limit(1)
                        ->get('academic_master')->row();
    }

    public function count_current_years()
    {
        return (int)$this->db->where('amIsCurrent', 1)->count_all_results('academic_master');
    }

    public function get_year_by_name($name)
    {
        return $this->db->where('amYear', $name)->get('academic_master')->row();
    }

    /* =====================================================
       STATS
       ===================================================== */

    /* Boys / girls / total per promotable class, in school order, with the class they move to */
 public function get_all_class_stats($yearId)
{
    $map  = $this->class_map();
    $rows = $this->db->query("
        SELECT c.cmId, c.cmName,
               COUNT(s.smClass)                AS total,
               COALESCE(SUM(s.smGender = 1),0) AS boys,
               COALESCE(SUM(s.smGender = 0),0) AS girls
        FROM class_master c
        LEFT JOIN students_master s
               ON s.smClass = c.cmId AND s.smAmId = ? AND s.smActive = 1
        GROUP BY c.cmId, c.cmName", [(int)$yearId])->result();

    $out = [];
    foreach ($rows as $r) {
        if (!isset($map[(int)$r->cmId])) continue;
        $r->toName = $map[(int)$r->cmId]['toName'];
        $out[] = $r;
    }
    return $this->sort_classes($out);
}

    /* Students of $fromYear that are not yet in $toYear (promotable classes only) */
public function count_pending($fromYearId, $toYearId)
{
    $ids = array_keys($this->class_map());
    if (!$ids) return 0;

    return (int)$this->db->query("
        SELECT COUNT(*) AS c
        FROM students_master s
        WHERE s.smAmId = ?
          AND s.smActive = 1
          AND s.smClass IN (" . implode(',', $ids) . ")
          AND NOT EXISTS (
              SELECT 1 FROM students_master n
              WHERE n.smId = s.smId AND n.smAmId = ?
          )", [(int)$fromYearId, (int)$toYearId])->row()->c;
}


    /* =====================================================
       PROMOTION
       One transaction:
         1) target year must exist, differ from the source and NOT be current
         2) lock the source year: only if amIsCurrent = 1 AND c_status = 0  ->  c_status = 1
         3) copy every student into the target year with smAmId = target and smClass = NEXT class
       Class 12 is never copied.
       Returns: -1 = already promoted / not current, false = failed, otherwise number copied
       ===================================================== */
//     public function promote_all($fromYearId, $toYearId)
// {
//     $map = $this->class_map();
//     if (!$map) return false;

//     $case = '';
//     foreach ($map as $from => $m) {
//         $case .= ' WHEN ' . (int)$from . ' THEN ' . (int)$m['to'];
//     }
//     $ids = implode(',', array_keys($map));

//     // every column of students_master INCLUDING smId (same smId carried to the new year)
//     $insertCols = []; $selectCols = [];
//     foreach ($this->db->field_data('students_master') as $f) {
//         $insertCols[] = '`' . $f->name . '`';
//         if ($f->name === 'smClass')      $selectCols[] = "CASE s.`smClass` $case END";
//         elseif ($f->name === 'smAmId')   $selectCols[] = '?';
//         else                             $selectCols[] = 's.`' . $f->name . '`';
//     }
//     if (!in_array('`smClass`', $insertCols, true) || !in_array('`smAmId`', $insertCols, true)) return false;
//     $insertCols = implode(', ', $insertCols);
//     $selectCols = implode(', ', $selectCols);

//     $this->db->trans_begin();

//     // 1) target year check
//     $to = $this->db->query("SELECT amId FROM academic_master
//                             WHERE amId = ? AND amIsCurrent = 0 AND amId <> ?",
//                             [(int)$toYearId, (int)$fromYearId])->row();
//     if (!$to) {
//         $this->db->trans_rollback();
//         return false;
//     }

//     // 2) lock the source year
//     $this->db->query("UPDATE academic_master
//                       SET c_status = 1
//                       WHERE amId = ? AND amIsCurrent = 1 AND c_status = 0", [(int)$fromYearId]);

//     if ($this->db->affected_rows() === 0) {
//         $this->db->trans_rollback();
//         return -1;
//     }

//     // 3) copy ACTIVE students only, same smId, new smAmId + NEXT smClass
//     $this->db->query("
//         INSERT INTO students_master ($insertCols)
//         SELECT $selectCols
//         FROM students_master s
//         WHERE s.smAmId = ?
//           AND s.smActive = 1
//           AND s.smClass IN ($ids)
//           AND NOT EXISTS (
//               SELECT 1 FROM students_master n
//               WHERE n.smId = s.smId AND n.smAmId = ?
//           )", [(int)$toYearId, (int)$fromYearId, (int)$toYearId]);

//     $count = $this->db->affected_rows();

//     if ($this->db->trans_status() === false) {
//         $this->db->trans_rollback();
//         return false;
//     }
//     $this->db->trans_commit();
//     return $count;
// }



public function promote_all($fromYearId, $toYearId)
{
    $map = $this->class_map();
    if (!$map) return false;

    $case = '';
    foreach ($map as $from => $m) {
        $case .= ' WHEN ' . (int)$from . ' THEN ' . (int)$m['to'];
    }
    $ids = implode(',', array_keys($map));

    // every column EXCEPT smId (auto-increment generates a new one)
    $insertCols = []; $selectCols = [];
    foreach ($this->db->field_data('students_master') as $f) {
        if ($f->name === 'smId') continue;
        $insertCols[] = '`' . $f->name . '`';
        if ($f->name === 'smClass')      $selectCols[] = "CASE s.`smClass` $case END";
        elseif ($f->name === 'smAmId')   $selectCols[] = '?';
        else                             $selectCols[] = 's.`' . $f->name . '`';
    }
    if (!in_array('`smClass`', $insertCols, true) || !in_array('`smAmId`', $insertCols, true)) return false;
    $insertCols = implode(', ', $insertCols);
    $selectCols = implode(', ', $selectCols);

    $this->db->trans_begin();

    $to = $this->db->query("SELECT amId FROM academic_master
                            WHERE amId = ? AND amIsCurrent = 0 AND amId <> ?",
                            [(int)$toYearId, (int)$fromYearId])->row();
    if (!$to) { $this->db->trans_rollback(); return false; }

    $this->db->query("UPDATE academic_master SET c_status = 1
                      WHERE amId = ? AND amIsCurrent = 1 AND c_status = 0", [(int)$fromYearId]);
    if ($this->db->affected_rows() === 0) { $this->db->trans_rollback(); return -1; }

    $this->db->query("
        INSERT INTO students_master ($insertCols)
        SELECT $selectCols
        FROM students_master s
        WHERE s.smAmId = ?
          AND s.smActive = 1
          AND s.smClass IN ($ids)
          AND NOT EXISTS (
              SELECT 1 FROM students_master n
              WHERE n.smId = s.smId AND n.smAmId = ?
          )", [(int)$toYearId, (int)$fromYearId, (int)$toYearId]);

    $count = $this->db->affected_rows();

    if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return false; }
    $this->db->trans_commit();
    return $count;
}


}