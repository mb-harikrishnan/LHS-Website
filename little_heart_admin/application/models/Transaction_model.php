<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaction_model extends CI_Model
{
   
    public function get_next_order_id()
    {
        $this->db->select_max('VAL');
        $this->db->where('ID', 'INVESTMENT_ORDER_ID');
        $query = $this->db->get('maxtab'); // change table name if different

        $row = $query->row();

        return ($row->VAL) ? $row->VAL + 1 : 1;
    }


    public function insert_transaction($data)
    {
       return  $this->db->insert('investment_activation_master', $data);
    }


    public function get_user_investments($user_id, $current_date)
    {
        $this->db->where('n_id', $user_id);
        $this->db->where('d_date <=', $current_date);
        $query = $this->db->get('investment_activation_master');
        return $query->result();
    }

    public function filter_investments($user_id, $from_date, $to_date)
    {
       $this->db->where('n_id', $user_id);

        if ($from_date && $to_date) {
            $this->db->where("DATE(d_date) BETWEEN '$from_date' AND '$to_date'");
        }

        $query = $this->db->get('investment_activation_master');
        return $query->result();
    }





      public function fetch_investment_reg_id()
      {

        $userid = $this->session->userdata('id');

        $sql = "SELECT INVEST_REF_ID FROM bc_master WHERE pn_id ='$userid'  "; 
        $query = $this->db->query($sql);
        return $query->row()->INVEST_REF_ID;





      }





















}



