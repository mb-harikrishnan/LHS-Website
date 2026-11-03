<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class promotion_Model extends CI_Model
{







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
public function promote($classId, $fromYearId, $toYearId)
{
    $this->db->trans_begin();

    // CHANGE: list every column to copy, except the primary key
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



/* Boys / girls / total for EVERY class in the given year */
public function get_all_class_stats($yearId)
{
    return $this->db->query("
        SELECT c.cmId, c.cmName,
               COUNT(s.smClass)               AS total,
               COALESCE(SUM(s.smGender = 1),0) AS boys,
               COALESCE(SUM(s.smGender = 0),0) AS girls
        FROM class_master c
        LEFT JOIN students_master s
               ON s.smClass = c.cmId AND s.smAmId = ?
        GROUP BY c.cmId, c.cmName
        ORDER BY c.cmName ASC", [$yearId])->result();
}


/* Copy ALL students of the current year into the next year (same class).
   Students already in the next year are skipped, so a double click can't duplicate them. */
/* Students of $fromYear that are not yet in $toYear */
public function count_pending($fromYearId, $toYearId)
{
    return (int)$this->db->query("
        SELECT COUNT(*) AS c
        FROM students_master s
        WHERE s.smAmId = ?
          AND NOT EXISTS (
              SELECT 1 FROM students_master n
              WHERE n.smAdmissionNo = s.smAdmissionNo AND n.smAmId = ?
          )", [$fromYearId, $toYearId])->row()->c;
}

/* Copy students from the CURRENT year into the NEXT year only.
   The year rule is checked again here, inside the query itself. */
/* Lock + copy in ONE transaction.
   Returns: -1 = already promoted (c_status = 1), false = failed, otherwise number copied */
public function promote_all($fromYearId, $toYearId)
{
    $this->db->trans_begin();

    // 1) Lock the year. Only succeeds if it is current AND not promoted yet.
    $this->db->query("UPDATE academic_master
                      SET c_status = 1
                      WHERE amId = ? AND amIsCurrent = 1 AND c_status = 0", [$fromYearId]);

    if ($this->db->affected_rows() === 0) {
        $this->db->trans_rollback();
        return -1;
    }

    // 2) Copy students. CHANGE: list every column except the primary key
    $this->db->query("
        INSERT INTO students_master (smAdmissionNo, smName, smGender, smClass, smAmId)
        SELECT s.smAdmissionNo, s.smName, s.smGender, s.smClass, ?
        FROM students_master s
        WHERE s.smAmId = ?
          AND NOT EXISTS (
              SELECT 1 FROM students_master n
              WHERE n.smAdmissionNo = s.smAdmissionNo AND n.smAmId = ?
          )", [$toYearId, $fromYearId, $toYearId]);

    $count = $this->db->affected_rows();

    if ($this->db->trans_status() === false) {
        $this->db->trans_rollback();
        return false;
    }
    $this->db->trans_commit();
    return $count;
}






}