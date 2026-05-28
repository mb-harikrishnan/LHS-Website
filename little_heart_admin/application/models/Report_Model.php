<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report_Model extends CI_Model
{

   public function fetch_roi_details($userid)
   {
       $sql="SELECT * FROM investment_roi_payout_hdr WHERE N_ID='$userid'";
       $query = $this->db->query($sql);
       return $query->result();
   }


   public function filter_roi_payout($user_id, $from_date, $to_date)
   {

        $this->db->where('N_ID', $user_id);

        if ($from_date && $to_date) {
            $this->db->where("DATE(D_TO) BETWEEN '$from_date' AND '$to_date'");
        }

        $query = $this->db->get('investment_roi_payout_hdr');
        return $query->result();

   }
   public function fetch_level_roi_details($userid)
   {
       $sql="SELECT * FROM investment_level_payout_hdr WHERE N_ID='$userid'";
       $query = $this->db->query($sql);
       return $query->result();
   }


   public function filter_level_roi_payout($user_id, $from_date, $to_date)
   {

        $this->db->where('N_ID', $user_id);

        if ($from_date && $to_date) {
            $this->db->where("DATE(D_TO) BETWEEN '$from_date' AND '$to_date'");
        }

        $query = $this->db->get('investment_level_payout_hdr');
        return $query->result();

   }





   


  public function level_roi_details($userid)
  {

       $sql="SELECT c_username,d_from_date,n_level,n_deposited_amount	,n_perecentage,n_total_income
       FROM investment_pv_count a ,bc_master b WHERE b.pn_id =a.n_userid AND n_id='$userid'";
       $query = $this->db->query($sql);
       return $query->result();

  }


  public function filter_roi_payout_details($user_id, $from_date, $to_date)
  {


       $this->db->select('b.c_username,
                   a.d_from_date,
                   a.n_level,
                   a.n_deposited_amount,
                   a.n_perecentage,
                   a.n_total_income');

            $this->db->from('investment_pv_count a');
            $this->db->join('bc_master b', 'b.pn_id = a.n_id'); 

            $this->db->where('a.n_id', $user_id);

            if ($from_date && $to_date) {
                $this->db->where("DATE(a.d_from_date) BETWEEN '$from_date' AND '$to_date'");
            }

            $query = $this->db->get();
            return $query->result();

  }

  public function binary_roi_details($userid)
  {

       $sql="SELECT c_username,d_from_date,n_level,n_deposited_amount	,n_perecentage,n_total_income
       FROM investment_referal_pv_count a ,bc_master b WHERE b.pn_id =a.n_userid AND n_id='$userid'";
       $query = $this->db->query($sql);
       return $query->result();

  }


  public function binary_roi_details_filter($user_id, $from_date, $to_date)
  {


       $this->db->select('b.c_username,
                   a.d_from_date,
                   a.n_level,
                   a.n_deposited_amount,
                   a.n_perecentage,
                   a.n_total_income');

            $this->db->from('investment_referal_pv_count a');
            $this->db->join('bc_master b', 'b.pn_id = a.n_id'); 

            $this->db->where('a.n_id', $user_id);

            if ($from_date && $to_date) {
                $this->db->where("DATE(a.d_from_date) BETWEEN '$from_date' AND '$to_date'");
            }

            $query = $this->db->get();
            return $query->result();

  }














   ////////////////


   public function fetch_direct_referal_count($userid)
   {

     $sql="SELECT COUNT(N_REF_ID) as N_REF_ID FROM bc_master WHERE N_REF_ID='$userid'";
     $query = $this->db->query($sql);
     return $query->row()->N_REF_ID;
   }
   public function fetch_active_member_count($userid)
   {

     $sql="SELECT COUNT(N_REF_ID) as N_REF_ID FROM bc_master WHERE N_REF_ID='$userid' AND c_distributor_active='Y'";
     $query = $this->db->query($sql);
     return $query->row()->N_REF_ID;
   }
//    public function fetch_user_details($userid)
//    {

//        $name=$this->session->userdata('fname');
// 		$username=$this->session->userdata('c_username');	
// 		$id=$this->session->userdata('id');	

//         $last_name="";

//         $downline_id3=$level1=array();
// 		if($trackid=="")
// 		{

//     	   $trackid=$this->session->userdata('id');
//         }

//         $trackid_mem = $trackid;


//         $sql="SELECT n_id FROM sun_gene WHERE N_REF_ID='$trackid_mem'";

// 		$result = $this->login_db->get_results($sql);

// 		if($result)
// 		{

// 			foreach($result as $row)
// 			{

// 				$downline_id3[]= $row->n_id;

// 			}	

// 		}

// 		$downlineids=implode(",",$downline_id3);
// 		if($downlineids == "")

// 		  $downlineids=0;	


// 		$username ="";$c_rank="";

// 		$sql="SELECT pn_id,c_username,C_FNAME,C_TFLAG FROM bc_master a, address_dtl b WHERE a.pn_id=b.n_id and  pn_id='$trackid'";

// 		$result = $this->login_db->get_results($sql);

// 		if($result)

// 		{

//             $sess_array = array();

//             foreach($result as $row)

// 		    {

//                 $uid = $row->pn_id;

//                 $name1 = $row->C_FNAME;

//                 $username = $row->c_username;

// 		    }

// 		}	


//         $count=0;

//         $sql="SELECT n_id FROM sun_gene WHERE N_REF_ID='$uid'";

//         $result = $this->login_db->get_results($sql);

//         if($result)

//         {

//             foreach($result as $row)

//             {

//                 $n_id = $row->n_id;	

//                 $count=$count+1;

//                 $level1[$count]=$n_id;

//             }

//         }

//         $levelwisemembers="";

//         $levelwisemembers=$level1=implode(",",$level1);


//         if($levelwisemembers!=""){

	

// 			$count=0;$rank="";//N_SELF_BV,N_GROUP_BV,

// 		 	$sql2="SELECT pn_id,C_FNAME,C_LNAME,c_mobile,c_username,C_EMAIL,c_distributor_active,date_format(d_join, '%d/%m/%Y') d_join ,d_distributor_active 

// 			FROM bc_master a,address_dtl b,sun_gene g WHERE a.pn_id=b.n_id and a.pn_id=g.n_id and  pn_id IN ($levelwisemembers)";

// 			$result2 = $this->login_db->get_results($sql2);

// 			if($result2)

// 			{

// 			  foreach($result2 as $row2)

// 			  {

// 				  $n_id = $row2->pn_id;	

// 				  $C_FNAME = $row2->C_FNAME;

// 				  $C_LNAME = $row2->C_LNAME;

// 				  $name=$C_FNAME;

// 				  $c_mobile = $row2->c_mobile;	

// 				  $c_username = $row2->c_username;	

// 				  $C_EMAIL = $row2->C_EMAIL;	

// 				  $d_join = $row2->d_join;

// 				  //$c_rank = $row2->c_rank;

// 				  $c_distributor_active=$row2->c_distributor_active;
// 				  	$d_distributor_active=$row2->d_distributor_active;

				 
					
// 				 $sqlpack = "SELECT c_package_name FROM packages WHERE pn_package_id = (SELECT max(n_package_id) FROM activation_master WHERE n_id = '$n_id')";

// 					$resultpack = $this->login_db->get_results($sqlpack);

// 					if($resultpack){

// 						$c_package_name = $resultpack[0]->c_package_name;

// 					}else{

// 						$c_package_name = "No Package";

// 					}
// 				  $count=$count+1;
//                   $status="";

//                     if($c_distributor_active=='Y')

//                     {

//                     $status="Active";   

//                     }else{

//                     $status="Not Active"; 

//                     }





//                     if($uid==$id){		






//    }



public function fetch_user_details($userid)
{
    $trackid = $userid;
    $team_investment_amount=0;


      //total Direct_referal
    $sql3 = "SELECT COUNT(pn_id)  as def_ref_count FROM bc_master WHERE INVEST_REF_ID='$trackid'";
    $result3 = $this->db->query($sql3);
    $def_ref_count = $result3->row()->def_ref_count;

    // active_members 
    $sql4 = "SELECT COUNT(pn_id)  as active_ref_count FROM bc_master WHERE INVEST_REF_ID='$trackid' AND c_distributor_active='Y'";
    $result4 = $this->db->query($sql4);
    $active_ref_count = $result4->row()->active_ref_count;


    $sq="SELECT SUM(n_investment_amount) as n_investment_amount FROM investment_activation_master WHERE n_id='$trackid' AND c_status='Y' ";
    $resu = $this->db->query($sq);
    $investment_amount = $resu->row()->n_investment_amount;

    // trackid details

     $details = "SELECT pn_id,C_FNAME,C_LNAME,c_username,
                DATE_FORMAT(D_JOIN, '%d-%m-%Y') d_join,
                c_distributor_active,d_distributor_active 
                FROM bc_master a, address_dtl b 
                WHERE  a.pn_id=b.n_id AND  pn_id='$trackid'";
    $query=$this->db->query($details);
    $values_details=$query->row();


     $values = [
                    'id'   => $values_details->pn_id,
                    'username' => $values_details->c_username,
                    'F_NAME' => $values_details->C_FNAME,
                    'join_date' => $values_details->d_join,
                    'status' => ($values_details->c_distributor_active == 'Y') ? 'Active' : 'Inactive',
                    'n_investment_amount'  =>($investment_amount != '' || !empty($investment_amount) )? $investment_amount : 0,
                    'd_distributor_active' => ($values_details->d_distributor_active != '') ? $values_details->d_distributor_active : 'Not Active',
                ];

    // Level 1 members
    $sql = "SELECT n_id FROM sun_gene WHERE INVEST_REF_ID='$trackid'";
    $result = $this->db->query($sql);
    $result = $result->result();

    $level1 = [];
    if ($result) {
        foreach ($result as $row) {
            $level1[] = $row->n_id;
        }
    }

    $levelwisemembers = !empty($level1) ? implode(",", $level1) : 0;

    $final = [];

    if ($levelwisemembers != 0) {

        $sql2 = "SELECT pn_id,C_FNAME,C_LNAME,c_username,
                DATE_FORMAT(D_JOIN, '%d-%m-%Y') d_join,
                c_distributor_active,d_distributor_active 
                FROM bc_master a, address_dtl b 
                WHERE  a.pn_id=b.n_id AND  pn_id IN ($levelwisemembers)";

        $result2 = $this->db->query($sql2);
        $result2 = $result2->result();

       

        if ($result2) {
            foreach ($result2 as $row2) {

              

                $sql5="SELECT SUM(n_investment_amount) as n_investment_amount FROM investment_activation_master WHERE n_id='$row2->pn_id' AND c_status='Y' ";
                $result5 = $this->db->query($sql5);
                $n_investment_amount = $result5->row()->n_investment_amount;


                $team_investment_amount += $n_investment_amount;


                $final[] = [
                    'id'   => $row2->pn_id,
                    'username' => $row2->c_username,
                    'join_date' => $row2->d_join,
                    'status' => ($row2->c_distributor_active == 'Y') ? 'Active' : 'Inactive',
                    'n_investment_amount'  =>($n_investment_amount != '' || !empty($n_investment_amount) )? $n_investment_amount : 0,
                    'd_distributor_active' => ($row2->d_distributor_active != '') ? $row2->d_distributor_active : 'Not Active',
                ];
            }
        }
    }

     return [
        'members' => $final,
        'values'=>$values,
        'active_count' => $active_ref_count,
        'direct_referal_count' => $def_ref_count,
        'team_investment_amount' => $team_investment_amount,
    ];;
}







   ////////////////


  



/////////////////////   LEVEL   //////////////////////////////////





public function get_level_wise_team($user_id)
{
    $levels = [];
    $current_ids = [$user_id];
    $level = 1;

    while (!empty($current_ids)) {

        $this->db->select('
            a.pn_id,
            a.c_username,
            a.d_join,
            IFNULL(SUM(i.n_investment_amount),0) as total_deposit,
            b.CFNAME as c_fname
        ');

        $this->db->from('bc_master a');
        $this->db->join('address_dtl b', 'b.n_id = a.pn_id', 'left'); // address table
        $this->db->join('investment_activation_master i', 'i.n_id = a.pn_id', 'left');

        $this->db->where_in('a.n_ref_id', $current_ids);
        $this->db->group_by('a.pn_id');

        $query = $this->db->get()->result_array();

        if (empty($query)) break;

        $levels[$level] = $query;

        $current_ids = array_column($query, 'pn_id');

        $level++;
    }

    return $levels;
}












  public function tire_income_details($userid)
  {

       $sql="SELECT c_username,d_from_date,n_level,n_deposited_amount	,n_perecentage,n_total_income
       FROM investment_tire_pv_count a ,bc_master b WHERE b.pn_id =a.n_userid AND n_id='$userid'";
       $query = $this->db->query($sql);
       return $query->result();

  }


  public function filter_tire_income_details($user_id, $from_date, $to_date)
  {


       $this->db->select('b.c_username,
                   a.d_from_date,
                   a.n_level,
                   a.n_deposited_amount,
                   a.n_perecentage,
                   a.n_total_income');

            $this->db->from('investment_tire_pv_count a');
            $this->db->join('bc_master b', 'b.pn_id = a.n_id'); 

            $this->db->where('a.n_id', $user_id);

            if ($from_date && $to_date) {
                $this->db->where("DATE(a.d_from_date) BETWEEN '$from_date' AND '$to_date'");
            }

            $query = $this->db->get();
            return $query->result();

  }




 public function tire_income_amount($userid)
   {
       $sql="SELECT n_amount FROM investment_tire_wallet_master WHERE n_id='$userid'";
       $query = $this->db->query($sql);
       return $query->row()->n_amount;
   }
 public function tire_income($userid)
   {
       $sql="SELECT * FROM investment_tire_payout_hdr WHERE N_ID='$userid'";
       $query = $this->db->query($sql);
       return $query->result();
   }


   public function filter_tire_income($user_id, $from_date, $to_date)
   {

        $this->db->where('N_ID', $user_id);

        if ($from_date && $to_date) {
            $this->db->where("DATE(D_TO) BETWEEN '$from_date' AND '$to_date'");
        }

        $query = $this->db->get('investment_tire_payout_hdr');
        return $query->result();

   }





























}