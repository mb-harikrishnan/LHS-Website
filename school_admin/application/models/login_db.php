<?php
Class Login_db extends CI_Model
{
	

	



    function login_checking($username, $password)
	{
		$this -> db -> select('c_username,c_password');
		$this -> db -> from('admin_login');
		 $where = "c_username='".$username."' and c_password='".md5($password)."'  "; 
		 $this->db->where($where);
		$this -> db -> limit(1);

		$query = $this -> db -> get();
		$query -> num_rows();
		if($query -> num_rows() == 1)
		{
			return $query->result();
			 
		}
		else
		{
			return false;
		}

	}



    function login_validation_step2($username)
	{
		 $query = $this->db->query("SELECT c_username,sl_no,SYSDATE() currentdate FROM admin_login  WHERE  c_username='".$username."'");
        
		$query -> num_rows();
        
		if($query -> num_rows() == 1)
		{
			return $query->result();
			 
		}
		else
		{
			return false;
		}

	}	



















}