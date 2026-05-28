<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home_Model extends CI_Model
{


  public function fetch_total_wallet_balance($user_id)
  {
    $roi =$level =$tire=$total_balance= 0;

    $this->db->select('n_amount');
    $this->db->from('investment_roi_wallet_master');
    $this->db->where('n_id', $user_id);
    $query = $this->db->get();
    $roi = $query->row()->n_amount;


    $this->db->select('n_amount');
    $this->db->from('investment_level_wallet_master');
    $this->db->where('n_id', $user_id);
    $query = $this->db->get();
    $level = $query->row()->n_amount;


    $this->db->select('n_amount');
    $this->db->from('investment_tire_wallet_master');
    $this->db->where('n_id', $user_id);
    $query = $this->db->get();
    $tire = $query->row()->n_amount;

    $total_balance = $roi + $level + $tire ;

   return [
        'roi' => $roi,
        'level' => $level,
        'tire' => $tire,
        'total_balance' => $total_balance
    ];
      
  }





///////////////////




public function fetch_total_investment($user_id)
{

    $this->db->select_sum('n_investment_amount');
    $this->db->from('investment_activation_master');
    $this->db->where('n_id', $user_id);
    $this->db->where('c_status', 'Y');
    $query = $this->db->get();
    return $query->row()->n_investment_amount ?? 0;
   
}




public function fetch_total_LEVEL($user_id)
{
   $this->db->select_sum('n_gross_amt');
    $this->db->from('investment_roi_payout_hdr');
    $this->db->where('n_id', $user_id);
    $query = $this->db->get();
    return $query->row()->n_gross_amt ?? 0;
}

public function fetch_total_TIRE($user_id)
{
   $this->db->select_sum('n_gross_amt');
    $this->db->from('investment_tire_payout_hdr');
    $this->db->where('n_id', $user_id);
    $query = $this->db->get();
    return $query->row()->n_gross_amt ?? 0;
}





  public function fetch_total_withdrawels($user_id)
  {
    $roi =$level =$tire=$total_balance= 0;

    $this->db->select('n_trans_amount');
    $this->db->from('investmet_roi_withdrawals_request');
    $this->db->where('n_id', $user_id);
    $this->db->where('c_delivery_status', 'Transfered');

    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        $roi = $query->row()->n_trans_amount;
    } else {
        $roi = 0;
    }

    $this->db->select('n_trans_amount');
    $this->db->from('investmet_level_withdrawals_request');
    $this->db->where('n_id', $user_id);
    $this->db->where('c_delivery_status', 'Transfered');

    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        $level = $query->row()->n_trans_amount;
    } else {
        $level = 0;
    }

    $this->db->select('n_trans_amount');
    $this->db->from('investmet_tire_withdrawals_request');
    $this->db->where('n_id', $user_id);
    $this->db->where('c_delivery_status', 'Transfered');
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        $tire = $query->row()->n_trans_amount;
    } else {
        $tire = 0;
    }
    $total_balance = $roi + $level + $tire ;

    return $total_balance;
      
  }











/////////////////////////////


public function fetch_User_Details($userid)
{


   $sql="SELECT c_username ,C_FNAME,C_EMAIL,C_COUNTRY,C_MOBILE,invest_approval_status
   FROM bc_master a , address_dtl b
   WHERE  a.pn_id= b.n_id AND a.pn_id='$userid' ";

   $query = $this->db->query($sql);
   $result=$query->row();

    if($result->invest_approval_status=='Approved')
    {
       $status ='ACTIVE';
    }else{
       $status ='INACTIVE';
    }

    return [
        'c_username' => $result->c_username,
        'C_FNAME' => $result->C_FNAME,
        'C_EMAIL' => $result->C_EMAIL,
        'C_COUNTRY' => $result->C_COUNTRY,
        'C_MOBILE' => $result->C_MOBILE,
        'invest_approval_status' => $status
    ];


}






//////////////////////////////////////////////



public function fetch_Downline_details($user_id)
{


      $sql1="SELECT COUNT(N_REF_ID) as N_REF_ID FROM bc_master WHERE N_REF_ID='$user_id'";
     $query1 = $this->db->query($sql1);
     $total_ref=  $query1->row()->N_REF_ID;


        $sql2="SELECT COUNT(N_REF_ID) as N_REF_ID FROM bc_master WHERE N_REF_ID='$user_id' AND c_distributor_active='Y'";
     $query2 = $this->db->query($sql2);
     $total_active =  $query2->row()->N_REF_ID;


       $levels = [];
        $level_counts = [];
        $current_ids = [$user_id];
        $level = 1;
        $total_count = 0;

    while (!empty($current_ids)) {

        $this->db->select('pn_id');
        $this->db->from('bc_master');

        $this->db->where_in('n_ref_id', $current_ids);
        $this->db->group_by('pn_id');

        $query = $this->db->get()->result_array();

        if (empty($query)) break;

        $levels[$level] = $query;

        // 👉 Count for this level
        $level_counts[$level] = count($query);

        // 👉 Add to total
        $total_count += $level_counts[$level];

        // 👉 Prepare next level
        $current_ids = array_column($query, 'pn_id');

        $level++;
    }

    return [
        'Total_Referrals' => $total_ref,
        'Active_Referrals' => $total_active,
        'Total_Team_Members' => $total_count
    ];


    
}










public function fetch_Coin_details($userid,$type)
{
  $sql = "SELECT  SUM(n_coin_qty) as qty FROM investment_activation_master WHERE n_id='$userid'  AND  c_coin_type='$type'" ;
  $query = $this->db->query($sql);
  return $query->row()->qty;
}























}