<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Teacher_Model extends CI_Model
{


public function get_dashboard_exams()
{
    $user_role_id = $this->session->userdata('user_role_id');

    // Resolve class restriction FIRST, using its own isolated query
    $class_filter = null;

    if ($user_role_id != 1) {
        $res = $this->db
            ->select('emClass')
            ->where('user_id', $user_role_id)
            ->get('employee_master')
            ->row();

        if (!$res) {
            return [];
        }

        $class_filter = $res->emClass;
    }

    // NOW build the main query — builder state is clean
    $this->db->distinct();
    $this->db->select('exam_master.*');
    $this->db->from('exam_master');
    $this->db->join('exam_master_detail', 'exam_master_detail.emdEmId = exam_master.emId', 'left');
    $this->db->where('exam_master.emActive', 1);

    if ($class_filter !== null) {
        $this->db->where('exam_master_detail.emdCmId', $class_filter);
    }
    // Admin (role_id == 1): no class filter — see everything

    $this->db->order_by('exam_master.emTmId', 'ASC');
    $this->db->order_by('exam_master.emDisplayOrder', 'ASC');
    $query = $this->db->get();

    $rows = $query->result();

    $grouped = [];
    foreach ($rows as $row) {
        $grouped[$row->emTmId][] = $row;
    }

    return $grouped;
}

public function count_ongoing_exams()
{
    $user_role_id = $this->session->userdata('user_role_id');

    $class_filter = null;

    if ($user_role_id != 1) {
        $res = $this->db
            ->select('emClass')
            ->where('user_id', $user_role_id)
            ->get('employee_master')
            ->row();

        if (!$res) {
            return 0;
        }

        $class_filter = $res->emClass;
    }

    $this->db->select('exam_master.emId', TRUE);
    $this->db->distinct();
    $this->db->from('exam_master');
    $this->db->join('exam_master_detail', 'exam_master_detail.emdEmId = exam_master.emId', 'inner');
    $this->db->where('exam_master.emActive', 1);
    $this->db->where('exam_master.emStatus', 1);

    if ($class_filter !== null) {
        $this->db->where('exam_master_detail.emdCmId', $class_filter);
    }

    return $this->db->count_all_results();
}

}