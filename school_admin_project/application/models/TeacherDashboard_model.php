<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherDashboard_model extends CI_Model
{
    // ---- CHECK THESE: class / subject master tables (guessed from your column names) ----
    const CLASS_TABLE   = 'class_master';     // columns: cmId, cmName
    const SUBJECT_TABLE = 'subject_master';   // columns: smId, smName

    // ---- OPTIONAL: teacher -> class + subject mapping table ----
    // Leave TCH_TABLE empty ('') and teachers will see all active exams.
    // Fill it and a teacher will see only exams for the classes/subjects assigned to them.
    const TCH_TABLE = '';        // e.g. 'teacher_subject'
    const TCH_EMP   = 'employe_id';   // column = admin_login.employe_id
    const TCH_CM    = 'cmId';         // class id column
    const TCH_SM    = 'smId';         // subject id column

    public function get_user($id)
    {
        return $this->db
            ->select('sl_no, c_username, employe_id, user_role_id, parent_id')
            ->where('sl_no', (int) $id)
            ->where('c_status', 'Y')
            ->get('admin_login')
            ->row();
    }

    // optional teacher scope (adds one bound parameter)
    private function scope($emp_id, array &$binds)
    {
        if ($emp_id === null || self::TCH_TABLE === '') {
            return '';
        }
        $binds[] = (int) $emp_id;
        return ' AND EXISTS (SELECT 1 FROM ' . self::TCH_TABLE . ' t'
             . ' WHERE t.' . self::TCH_EMP . ' = ?'
             . ' AND t.' . self::TCH_CM . ' = d.emdCmId'
             . ' AND t.' . self::TCH_SM . ' = d.emdSmId)';
    }

    // numbers for the 4 stat cards
    public function get_exam_stats($emp_id = null)
    {
        $binds = array();
        $scope = $this->scope($emp_id, $binds);

        $sql = "SELECT COUNT(DISTINCT e.emId) AS exams,
                       COUNT(DISTINCT CASE WHEN e.emIsOngoing = 1 THEN e.emId END) AS ongoing,
                       COUNT(DISTINCT d.emdCmId) AS classes,
                       COUNT(DISTINCT d.emdSmId) AS subjects
                FROM exam_master e
                LEFT JOIN exam_master_detail d ON d.emdEmId = e.emId
                WHERE e.emActive = 1" . $scope;

        return $this->db->query($sql, $binds)->row();
    }

    // active exams, one row per exam + class
    public function get_active_exams($emp_id = null)
    {
        $binds = array();
        $scope = $this->scope($emp_id, $binds);

        $sql = "SELECT e.emId, e.emName, e.emDisplayName, e.emIsOpened, e.emIsOngoing, e.emIsGrade,
                       d.emdCmId AS class_id,
                       c.cmName  AS class_name,
                       COUNT(DISTINCT d.emdSmId) AS subj_count,
                       GROUP_CONCAT(DISTINCT s.smName ORDER BY s.smId SEPARATOR ', ') AS subjects,
                       MAX(d.emdMaxMark) AS max_mark
                FROM exam_master e
                LEFT JOIN exam_master_detail d ON d.emdEmId = e.emId
                LEFT JOIN " . self::CLASS_TABLE . " c ON c.cmId = d.emdCmId
                LEFT JOIN " . self::SUBJECT_TABLE . " s ON s.smId = d.emdSmId
                WHERE e.emActive = 1" . $scope . "
                GROUP BY e.emId, e.emName, e.emDisplayName, e.emIsOpened, e.emIsOngoing, e.emIsGrade,
                         e.emDisplayOrder, d.emdCmId, c.cmName
                ORDER BY e.emDisplayOrder, e.emId, d.emdCmId";

        return $this->db->query($sql, $binds)->result();
    }
}