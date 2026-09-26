<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Teacher_Model extends CI_Model
{



  public function get_dashboard_exams()
{
    $user_role_id = $this->session->userdata('user_role_id');

    // Parameterized query (avoids SQL injection from string concatenation)
    $query = $this->db->query(
        "SELECT emClass, emDiv FROM employee_master WHERE user_id = ?",
        [$user_role_id]
    );
    $res = $query->row();

    // Guard against no row found
    if (!$res) {
        return [];
    }

    $class = $res->emClass;
    $div   = $res->emDiv;
    $this->db->distinct();
    $this->db->select('exam_master.*');   // add detail columns here if needed, e.g. exam_master_detail.emdCmId
    $this->db->from('exam_master');
    $this->db->join('exam_master_detail', 'exam_master_detail.emdEmId = exam_master.emId', 'inner');
    $this->db->where('exam_master.emActive', 1);
    $this->db->where('exam_master_detail.emdCmId', $class);
    $this->db->order_by('exam_master.emTmId', 'ASC');
    $this->db->order_by('exam_master.emDisplayOrder', 'ASC');
    $query = $this->db->get();

    $rows = $query->result();

    // Group by term id so the view can render a section per term
    $grouped = [];
    foreach ($rows as $row) {
        $grouped[$row->emTmId][] = $row;
    }

    return $grouped;
}
    /**
     * Convenience count, e.g. for a "3 exams live now" summary chip.
     */
public function count_ongoing_exams()
{
    $user_role_id = $this->session->userdata('user_role_id');

    $res = $this->db
        ->select('emClass')
        ->where('user_id', $user_role_id)
        ->get('employee_master')
        ->row();

    if (!$res) {
        return 0;
    }

    $this->db->select('exam_master.emId', TRUE); // TRUE resets any prior select
    $this->db->distinct();
    $this->db->from('exam_master');
    $this->db->join('exam_master_detail', 'exam_master_detail.emdEmId = exam_master.emId', 'inner');
    $this->db->where('exam_master.emActive', 1);       // <-- restored, must match get_dashboard_exams()
    $this->db->where('exam_master.emIsOngoing', 1);
    $this->db->where('exam_master_detail.emdCmId', $res->emClass);

    return $this->db->count_all_results();
}

}