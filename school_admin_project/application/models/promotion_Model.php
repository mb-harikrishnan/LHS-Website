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










}