<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Withdrawal_Model extends CI_Model
{


    public function get_roi_wallet_balance($user_id) {
        $this->db->select('n_amount');
        $this->db->from('investment_roi_wallet_master');
        $this->db->where('n_id', $user_id);
        $query = $this->db->get();
        return $query->row()->n_amount;
    }

    public function get_level_wallet_balance($user_id) {
        $this->db->select('n_amount');
        $this->db->from('investment_level_wallet_master');
        $this->db->where('n_id', $user_id);
        $query = $this->db->get();
        return $query->row()->n_amount;
    }

    public function get_tire_wallet_balance($user_id) {
        $this->db->select('n_amount');
        $this->db->from('investment_tire_wallet_master');
        $this->db->where('n_id', $user_id);
        $query = $this->db->get();
        return $query->row()->n_amount;
    }



    public function check_security_password($user_name, $password)
    {
        $user = $this->db->where('PC_USERNAME', $user_name)
                ->where('C_PASSWORD', $password)
                        ->get('bc_login')
                        ->row();

        if (!$user) {
            return 'not_found';
        }

        // 🔒 If using MD5
        if ($user->C_PASSWORD !== $password) {
            return 'invalid';
        }

        return 'valid';
    }


    public function get_wallet_balance($user_id, $table)
    {
        $this->db->select('n_amount');
        $this->db->from($table);
        $this->db->where('n_id', $user_id);
        $query = $this->db->get();
        return $query->row()->n_amount;
    }


    public function get_roi_withdrawal_history($user_id,$date)
    {
        $this->db->select('*');
        $this->db->from('investmet_roi_withdrawals_request');
        $this->db->where('n_id', $user_id);
        $this->db->where('DATE(d_transcation)', $date);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_level_withdrawal_history($user_id,$date)
    {
        $this->db->select('*');
        $this->db->from('investmet_level_withdrawals_request');
        $this->db->where('n_id', $user_id);
        $this->db->where('DATE(d_transcation)', $date);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_tire_withdrawal_history($user_id,$date)
    {
        $this->db->select('*');
        $this->db->from('investmet_tire_withdrawals_request');
        $this->db->where('n_id', $user_id);
        $this->db->where('DATE(d_transcation)', $date);
        $query = $this->db->get();
        return $query->result();
    }


    public function filter_roi_withdrawals($user_id, $from_date, $to_date, $status)
    {
        $this->db->select('*');
        $this->db->from('investmet_roi_withdrawals_request');
        $this->db->where('n_id', $user_id);

        // ✅ DATE BETWEEN FILTER
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where("DATE(d_transcation) BETWEEN '$from_date' AND '$to_date'");
        }


        // ✅ Status filter
        if (!empty($status)) {
            if ($status === 'pending') {
                $this->db->where('c_delivery_status', 'Pending');
            } elseif ($status === 'completed') {
                $this->db->where('c_delivery_status', 'Transfered');
            }
        }

        $query = $this->db->get();
        return $query->result();
    }
    public function filter_level_withdrawals($user_id, $from_date, $to_date, $status)
    {
        $this->db->select('*');
        $this->db->from('investmet_level_withdrawals_request');
        $this->db->where('n_id', $user_id);

        // ✅ DATE BETWEEN FILTER
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where("DATE(d_transcation) BETWEEN '$from_date' AND '$to_date'");
        }


        // ✅ Status filter
        if (!empty($status)) {
            if ($status === 'pending') {
                $this->db->where('c_delivery_status', 'Pending');
            } elseif ($status === 'completed') {
                $this->db->where('c_delivery_status', 'Transfered');
            }
        }

        $query = $this->db->get();
        return $query->result();
    }
    public function filter_tire_withdrawals($user_id, $from_date, $to_date, $status)
    {
        $this->db->select('*');
        $this->db->from('investmet_tire_withdrawals_request');
        $this->db->where('n_id', $user_id);

        // ✅ DATE BETWEEN FILTER
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where("DATE(d_transcation) BETWEEN '$from_date' AND '$to_date'");
        }


        // ✅ Status filter
        if (!empty($status)) {
            if ($status === 'pending') {
                $this->db->where('c_delivery_status', 'Pending');
            } elseif ($status === 'completed') {
                $this->db->where('c_delivery_status', 'Transfered');
            }
        }

        $query = $this->db->get();
        return $query->result();
    }





    public function insert_withdrawal_request($user_id, $wallet_type, $withdraw_amount)
    {

        date_default_timezone_set('GMT');
        $temp= strtotime("+5 hours 30 minutes"); 
        $currentdateandtime = date("Y-m-d h:i:s",$temp);
        $currentdate = date("Y-m-d",$temp);


        $roi_wallet_master = 0;
        $sqlr="SELECT n_amount FROM investment_roi_wallet_master WHERE n_id='$user_id' ";
        $resultr = $this->db->query($sqlr);
        $resultr = $resultr->result();

        if($resultr)
        {
            
            foreach($resultr as $rows)
            {
              $roi_wallet_master = $rows->n_amount;
            }
        }

        $level_wallet_master =0;
        $sqlr="SELECT n_amount FROM investment_level_wallet_master WHERE n_id='$user_id' ";
        $resultr = $this->db->query($sqlr);
        $resultr = $resultr->result();
        if($resultr)
        {
            
            foreach($resultr as $rows)
            {
              $level_wallet_master = $rows->n_amount;
            }
        }

        $tire_wallet_master =0;
        $sqlr="SELECT n_amount FROM investment_tire_wallet_master WHERE n_id='$user_id' ";
        $resultr = $this->db->query($sqlr);
        $resultr = $resultr->result();
        if($resultr)
        {
            
            foreach($resultr as $rows)
            {
              $tire_wallet_master = $rows->n_amount;
            }
        }



         if($wallet_type=='roi')
        {
            $total_wallet_amount =$roi_wallet_master;
            
        }
        elseif($wallet_type=='level')
        {
            $total_wallet_amount=$level_wallet_master;
        }
        elseif($wallet_type=='tire')
        {
            $total_wallet_amount = $tire_wallet_master;
        }
        

        if($total_wallet_amount>=$withdraw_amount)
        {
            // ✅ STARTED TRANSACTION 
            $this->db->trans_start();

            $balance_amount=$total_wallet_amount - $withdraw_amount;


            if($wallet_type=='roi')
            {
               
                $sql="SELECT MAX(n_slno) N_SLNO FROM investment_roi_wallet_transcation_master";

                $result = $this->db->query($sql);	
                $result = $result->result();

                if($result)

                {

                    foreach($result as $row)
                    {

                        $incomewallet_transferslno = $row->N_SLNO;
                    }

                }
                $incomewallet_transferslno=$incomewallet_transferslno+1;
                        
                    //MAX TRANSACTION NO FROM INCOME WALLET TRANSACTION MASTER
                $incomewallet_transactionslno=0;

                $sql1="SELECT MAX(n_transcation_no) n_transcation_no FROM investment_roi_wallet_transcation_master";

                $result1 = $this->db->query($sql1);
                $result1 = $result1->result();			

                if($result1)
                {

                foreach($result1 as $row1)
                {

                    $incomewallet_transactionslno = $row1->n_transcation_no;
                }

                }
                $incomewallet_transactionslno=$incomewallet_transactionslno+1;


            }elseif($wallet_type=='level')
            {

                $sql="SELECT MAX(n_slno) N_SLNO FROM investment_level_wallet_transcation_master";

                $result = $this->db->query($sql);	
                $result = $result->result();

                if($result)

                {

                    foreach($result as $row)
                    {

                        $incomewallet_transferslno = $row->N_SLNO;
                    }

                }
                $incomewallet_transferslno=$incomewallet_transferslno+1;
                        
                    //MAX TRANSACTION NO FROM INCOME WALLET TRANSACTION MASTER
                $incomewallet_transactionslno=0;

                $sql1="SELECT MAX(n_transcation_no) n_transcation_no FROM investment_level_wallet_transcation_master";

                $result1 = $this->db->query($sql1);		
                $result1 = $result1->result();	

                if($result1)
                {

                foreach($result1 as $row1)
                {

                    $incomewallet_transactionslno = $row1->n_transcation_no;
                }

                }
                $incomewallet_transactionslno=$incomewallet_transactionslno+1;


            }elseif($wallet_type=='tire')
            {
                $sql="SELECT MAX(n_slno) N_SLNO FROM investment_tire_wallet_transcation_master";

                $result = $this->db->query($sql);	
                $result = $result->result();

                if($result)

                {

                    foreach($result as $row)
                    {

                        $incomewallet_transferslno = $row->N_SLNO;
                    }

                }
                $incomewallet_transferslno=$incomewallet_transferslno+1;
                        
                    //MAX TRANSACTION NO FROM INCOME WALLET TRANSACTION MASTER
                $incomewallet_transactionslno=0;

                $sql1="SELECT MAX(n_transcation_no) n_transcation_no FROM investment_tire_wallet_transcation_master";

                $result1 = $this->db->query($sql1);		
                $result1 = $result->result();	

                if($result1)
                {

                foreach($result1 as $row1)
                {

                    $incomewallet_transactionslno = $row1->n_transcation_no;
                }

                }
                $incomewallet_transactionslno=$incomewallet_transactionslno+1;

            }


            if($wallet_type=='roi')
            {
                $query_request_withdrawals	="INSERT INTO investmet_roi_withdrawals_request (n_id,n_transcation_no,n_trans_amount,d_transcation,c_status,deduction,net_amount) 
						                      VALUES('$user_id','$incomewallet_transactionslno','$withdraw_amount','$currentdate','Y','0','0')";

                $this->db->query($query_request_withdrawals);
                $last_insert_id = $this->db->insert_id();
                
                $query_binarytranscation_master	="INSERT INTO investment_roi_wallet_transcation_master (N_SLNO,bankwithdrawal_request_id,n_transcation_no,n_from_id,n_to_id,n_accbalance_before,n_trans_amount,n_accbalance_after,d_transcation,c_trans_type,c_status)
                    VALUES ('$incomewallet_transferslno','$last_insert_id','$incomewallet_transactionslno',$user_id,'-1',$roi_wallet_master,$withdraw_amount,$balance_amount,'$currentdate',' withdrawal Request','Y')";

                $this->db->query($query_binarytranscation_master);		

                $query_binarywallet_master	=	"update investment_roi_wallet_master set n_amount='$balance_amount' where n_id=$user_id ";	
                $this->db->query($query_binarywallet_master);
            }
            if($wallet_type=='level')
            {
                $query_request_withdrawals	="INSERT INTO investmet_level_withdrawals_request (n_id,n_transcation_no,n_trans_amount,d_transcation,c_status,deduction,net_amount) 
						                      VALUES('$user_id','$incomewallet_transactionslno','$withdraw_amount','$currentdate','Y','0','0')";

                $this->db->query($query_request_withdrawals);
                $last_insert_id = $this->db->insert_id();
                
                $query_binarytranscation_master	="INSERT INTO investment_level_wallet_transcation_master (N_SLNO,bankwithdrawal_request_id,n_transcation_no,n_from_id,n_to_id,n_accbalance_before,n_trans_amount,n_accbalance_after,d_transcation,c_trans_type,c_status)
                    VALUES ('$incomewallet_transferslno','$last_insert_id','$incomewallet_transactionslno',$user_id,'-1',$level_wallet_master,$withdraw_amount,$balance_amount,'$currentdate',' withdrawal Request','Y')";

                $this->db->query($query_binarytranscation_master);		

                $query_binarywallet_master	=	"update investment_level_wallet_master set n_amount='$balance_amount' where n_id=$user_id ";	
                $this->db->query($query_binarywallet_master);
            }
            if($wallet_type=='tire')
            {
                $query_request_withdrawals	="INSERT INTO investmet_tire_withdrawals_request (n_id,n_transcation_no,n_trans_amount,d_transcation,c_status,deduction,net_amount) 
						                      VALUES('$user_id','$incomewallet_transactionslno','$withdraw_amount','$currentdate','Y','0','0')";

                $this->db->query($query_request_withdrawals);
                $last_insert_id = $this->db->insert_id();
                
                $query_binarytranscation_master	="INSERT INTO investment_tire_wallet_transcation_master (N_SLNO,bankwithdrawal_request_id,n_transcation_no,n_from_id,n_to_id,n_accbalance_before,n_trans_amount,n_accbalance_after,d_transcation,c_trans_type,c_status)
                    VALUES ('$incomewallet_transferslno','$last_insert_id','$incomewallet_transactionslno',$user_id,'-1',$tire_wallet_master,$withdraw_amount,$balance_amount,'$currentdate',' withdrawal Request','Y')";

                $this->db->query($query_binarytranscation_master);		

                $query_binarywallet_master	=	"update investment_tire_wallet_master set n_amount='$balance_amount' where n_id=$user_id ";	
                $this->db->query($query_binarywallet_master);
            }




              // ✅ CHECK STATUS
                if ($this->db->trans_status() === FALSE)
                {
                    // ❌ SOMETHING FAILED → ROLLBACK
                    $this->db->trans_rollback();
                    return false;
                }
                else
                {
                    // ✅ ALL GOOD → COMMIT
                    $this->db->trans_commit();
                    return true;
                }

        }else
        {
            return false;
        }





    }






    public function get_today_withdrawal($user_id, $wallet_type)
    {
        date_default_timezone_set('GMT');
        $temp= strtotime("+5 hours 30 minutes"); 
        $currentdateandtime = date("Y-m-d h:i:s",$temp);
        $currentdate = date("Y-m-d",$temp);


        if($wallet_type=='roi')
        {
            $table = 'investmet_roi_withdrawals_request';
        }
        elseif($wallet_type=='level')
        {
            $table = 'investmet_level_withdrawals_request';
        }elseif($wallet_type=='tire')
        {
            $table = 'investmet_tire_withdrawals_request';
        }

        $this->db->select_sum('n_trans_amount');
        $this->db->where('n_id', $user_id);
        $this->db->where('c_delivery_status', 'Transfered');
        $this->db->where('DATE(d_transcation)', $currentdate);

        $result = $this->db->get($table)->row();

        return ($result && $result->n_trans_amount) ? $result->n_trans_amount : 0;
    }




    public function get_today_withdrawal_amount($user_id, $wallet_type)
    {
        date_default_timezone_set('GMT');
        $temp= strtotime("+5 hours 30 minutes"); 
        $currentdateandtime = date("Y-m-d h:i:s",$temp);
        $currentdate = date("Y-m-d",$temp);


        if($wallet_type=='roi')
        {
            $table = 'investmet_roi_withdrawals_request';
        }
        elseif($wallet_type=='level')
        {
            $table = 'investmet_level_withdrawals_request';
        }elseif($wallet_type=='tire')
        {
            $table = 'investmet_tire_withdrawals_request';
        }

        $this->db->select_sum('n_trans_amount');
        $this->db->where('n_id', $user_id);
        $this->db->where('c_delivery_status', 'Transfered');
        $this->db->where('DATE(d_transcation)', $currentdate);

        $result = $this->db->get($table)->row();

        return ($result && $result->n_trans_amount) ? $result->n_trans_amount : 0;
    }




    public function fetch_user($username,$password)
    {
        $sql="SELECT C_PASSWORD FROM bc_login WHERE C_PASSWORD='$password' AND PC_USERNAME ='$username'";
        $query=$this->db->query($sql);
        return $query->row();
    }



   
























}