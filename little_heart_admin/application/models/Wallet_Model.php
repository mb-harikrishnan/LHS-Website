<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Wallet_Model extends CI_Model
{


    public function roi_wallet_balance($user_id) {
        $this->db->select('n_amount');
        $this->db->from('investment_roi_wallet_master');
        $this->db->where('n_id', $user_id);
        $query = $this->db->get();
        return $query->row()->n_amount;
    }

    public function level_wallet_balance($user_id) {
        $this->db->select('n_amount');
        $this->db->from('investment_level_wallet_master');
        $this->db->where('n_id', $user_id);
        $query = $this->db->get();
        return $query->row()->n_amount;
    }

    public function tire_wallet_balance($user_id) {
        $this->db->select('n_amount');
        $this->db->from('investment_tire_wallet_master');
        $this->db->where('n_id', $user_id);
        $query = $this->db->get();
        return $query->row()->n_amount;
    }






}