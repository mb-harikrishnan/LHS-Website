<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class promotion_Model extends CI_Model
{


const FINAL_RANK = 22;   // 10 + 12  => class 12

/* Play School = 0, LKG = 1, UKG = 2, "1".."12" = 11..22, unknown = 999 */
/* Play School/Group = 0, LKG = 1, UKG = 2, 1..12 (digits OR roman) = 11..22, unknown = 999 */
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
        $v = $map[$ch] ?? 0;
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

/* IDs of every class that CAN be promoted (everything except class 12) */
private function promotable_class_ids()
{
    $ids = [];
    foreach ($this->db->get('class_master')->result() as $c) {
        if ($this->class_rank($c->cmName) !== self::FINAL_RANK) $ids[] = (int)$c->cmId;
    }
    return $ids ?: [0];   // [0] keeps "IN ?" valid when nothing qualifies
}


public function get_current_year()
{
    return $this->db->where('amIsCurrent', 1)->get('academic_master')->row();
}

public function get_year_by_name($name)
{
    return $this->db->where('amYear', $name)->get('academic_master')->row();
}

public function get_classes()
{
    return $this->db->order_by('cmName', 'ASC')->get('class_master')->result(); // CHANGE
}

public function get_stats($classId, $yearId)
{
    return $this->db->select("COUNT(*) AS total,
                              SUM(smGender = 1)   AS boys,
                              SUM(smGender = 0) AS girls", false)
                    ->where('smClass', $classId)
                    ->where('smAmId', $yearId)
                    ->get('students_master')   // CHANGE
                    ->row();
}

/* Copy students of the current year into the next year (same class).
   Students already present in the next year are skipped, so a double click can't duplicate them. */
// public function promote($classId, $fromYearId, $toYearId)
// {
//     $this->db->trans_begin();

//     // CHANGE: list every column to copy, except the primary key
//     $this->db->query("
//         INSERT INTO students_master (smAdmissionNo, smName, smGender, smClass, smAmId)
//         SELECT s.smAdmissionNo, s.smName, s.smGender, s.smClass, ?
//         FROM students_master s
//         WHERE s.smClass = ? AND s.smAmId = ?
//           AND NOT EXISTS (
//               SELECT 1 FROM students_master n
//               WHERE n.smAdmissionNo = s.smAdmissionNo AND n.smAmId = ?
//           )", [$toYearId, $classId, $fromYearId, $toYearId]);

//     $count = $this->db->affected_rows();

//     if ($this->db->trans_status() === false) {
//         $this->db->trans_rollback();
//         return false;
//     }
//     $this->db->trans_commit();
//     return $count;
// }



// /* Boys / girls / total for EVERY class in the given year */
// public function get_all_class_stats($yearId)
// {
//     return $this->db->query("
//         SELECT c.cmId, c.cmName,
//                COUNT(s.smClass)               AS total,
//                COALESCE(SUM(s.smGender = 1),0) AS boys,
//                COALESCE(SUM(s.smGender = 0),0) AS girls
//         FROM class_master c
//         LEFT JOIN students_master s
//                ON s.smClass = c.cmId AND s.smAmId = ?
//         GROUP BY c.cmId, c.cmName
//         ORDER BY c.cmName ASC", [$yearId])->result();
// }



/* Boys / girls / total per class, class 12 left out, in school order */
public function get_all_class_stats($yearId)
{
    $rows = $this->db->query("
        SELECT c.cmId, c.cmName,
               COUNT(s.smClass)                AS total,
               COALESCE(SUM(s.smGender = 1),0) AS boys,
               COALESCE(SUM(s.smGender = 0),0) AS girls
        FROM class_master c
        LEFT JOIN students_master s
               ON s.smClass = c.cmId AND s.smAmId = ?
        GROUP BY c.cmId, c.cmName", [$yearId])->result();

    $rows = array_filter($rows, function ($r) {
        return $this->class_rank($r->cmName) !== self::FINAL_RANK;
    });

       return $this->sort_classes(array_values($rows));
}


/* Students of $fromYear not yet in $toYear (class 12 excluded) */
public function count_pending($fromYearId, $toYearId)
{
    return (int)$this->db->query("
        SELECT COUNT(*) AS c
        FROM students_master s
        WHERE s.smAmId = ?
          AND s.smClass IN ?
          AND NOT EXISTS (
              SELECT 1 FROM students_master n
              WHERE n.smAdmissionNo = s.smAdmissionNo AND n.smAmId = ?
          )", [$fromYearId, $this->promotable_class_ids(), $toYearId])->row()->c;
}
/* Copy students from the CURRENT year into the NEXT year only.
   The year rule is checked again here, inside the query itself. */
/* Lock + copy in ONE transaction.
   Returns: -1 = already promoted (c_status = 1), false = failed, otherwise number copied */
// public function promote_all($fromYearId, $toYearId)
// {
//     $this->db->trans_begin();

//     // 1) Lock the year. Only succeeds if it is current AND not promoted yet.
//     $this->db->query("UPDATE academic_master
//                       SET c_status = 1
//                       WHERE amId = ? AND amIsCurrent = 1 AND c_status = 0", [$fromYearId]);

//     if ($this->db->affected_rows() === 0) {
//         $this->db->trans_rollback();
//         return -1;
//     }

//     // 2) Copy students. CHANGE: list every column except the primary key
//     $this->db->query("
//         INSERT INTO students_master (smAdmissionNo, smName, smGender, smClass, smAmId)
//         SELECT s.smAdmissionNo, s.smName, s.smGender, s.smClass, ?
//         FROM students_master s
//         WHERE s.smAmId = ?
//           AND NOT EXISTS (
//               SELECT 1 FROM students_master n
//               WHERE n.smAdmissionNo = s.smAdmissionNo AND n.smAmId = ?
//           )", [$toYearId, $fromYearId, $toYearId]);

//     $count = $this->db->affected_rows();

//     if ($this->db->trans_status() === false) {
//         $this->db->trans_rollback();
//         return false;
//     }
//     $this->db->trans_commit();
//     return $count;
// }



/* Single class promote: class 12 is refused */
public function promote($classId, $fromYearId, $toYearId)
{
    if (!in_array((int)$classId, $this->promotable_class_ids(), true)) {
        return false;
    }

    $this->db->trans_begin();

    $this->db->query("
        INSERT INTO students_master (smAdmissionNo, smName, smGender, smClass, smAmId)
        SELECT s.smAdmissionNo, s.smName, s.smGender, s.smClass, ?
        FROM students_master s
        WHERE s.smClass = ? AND s.smAmId = ?
          AND NOT EXISTS (
              SELECT 1 FROM students_master n
              WHERE n.smAdmissionNo = s.smAdmissionNo AND n.smAmId = ?
          )", [$toYearId, $classId, $fromYearId, $toYearId]);

    $count = $this->db->affected_rows();

    if ($this->db->trans_status() === false) {
        $this->db->trans_rollback();
        return false;
    }
    $this->db->trans_commit();
    return $count;
}

/* Lock + copy in ONE transaction. Class 12 is never copied.
   Returns: -1 = already promoted, false = failed, otherwise number copied */
public function promote_all($fromYearId, $toYearId)
{
    $this->db->trans_begin();

    $this->db->query("UPDATE academic_master
                      SET c_status = 1
                      WHERE amId = ? AND amIsCurrent = 1 AND c_status = 0", [$fromYearId]);

    if ($this->db->affected_rows() === 0) {
        $this->db->trans_rollback();
        return -1;
    }

    $this->db->query("
        INSERT INTO students_master (smAdmissionNo, smName, smGender, smClass, smAmId)
        SELECT s.smAdmissionNo, s.smName, s.smGender, s.smClass, ?
        FROM students_master s
        WHERE s.smAmId = ?
          AND s.smClass IN ?
          AND NOT EXISTS (
              SELECT 1 FROM students_master n
              WHERE n.smAdmissionNo = s.smAdmissionNo AND n.smAmId = ?
          )", [$toYearId, $fromYearId, $this->promotable_class_ids(), $toYearId]);

    $count = $this->db->affected_rows();

    if ($this->db->trans_status() === false) {
        $this->db->trans_rollback();
        return false;
    }
    $this->db->trans_commit();
    return $count;
}


}