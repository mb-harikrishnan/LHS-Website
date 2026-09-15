<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Teacher_Model extends CI_Model
{



  public function get_dashboard_exams()
    {
        $this->db->select('*');
        $this->db->from('exam_master');
        $this->db->where('emActive', 1);
        $this->db->order_by('emTmId', 'ASC');
        $this->db->order_by('emDisplayOrder', 'ASC');
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
        $this->db->where('emActive', 1);
        $this->db->where('emIsOngoing', 1);
        return $this->db->count_all_results('exam_master');
    }


}