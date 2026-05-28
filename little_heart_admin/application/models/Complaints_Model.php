<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Complaints_Model extends CI_Model
{



    public function insert_Complaints($data)
    {
        return $this->db->insert('investment_complaint', $data);
    }


    public function get_user_complaints($user_id,$date)
    {
        $this->db->where('n_id', $user_id);
        $this->db->where('c_status', 'Pending');
        $this->db->where('d_date >=', $date);
        $query = $this->db->get('investment_complaint');
        return $query->result();
    }


    public function get_complaints_by_user($user_id,$from_date,$to_date)
    {
        $this->db->where('n_id', $user_id);
        $this->db->where('c_status', 'Pending');
        if ($from_date && $to_date) {
            $this->db->where("DATE(d_date) BETWEEN '$from_date' AND '$to_date'");
        }        $query = $this->db->get('investment_complaint');
        return $query->result();
    }


    public function get_adminreplay_complaints($user_id,$date)
    {
        $this->db->where('n_id', $user_id);
        $this->db->where('c_status', 'Replied');
        $this->db->where('d_replied_date >=', $date);
        $query = $this->db->get('investment_complaint');
        return $query->result();
    }

    public function get_adminreplay_complaints_by_user($user_id,$from_date,$to_date)
    {
        $this->db->where('n_id', $user_id);
        $this->db->where('c_status', 'Replied');
        if ($from_date && $to_date) {
            $this->db->where("DATE(d_replied_date) BETWEEN '$from_date' AND '$to_date'");
        }        $query = $this->db->get('investment_complaint');
        return $query->result();
    }





}