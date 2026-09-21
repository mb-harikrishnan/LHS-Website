<?php
Class Login_db extends CI_Model
{


   
public function fetch_roles()
{
    $sql = "SELECT role_id, role_name FROM user_roles WHERE status = 1";
    $query = $this->db->query($sql);
    return $query->result();   // array of objects — NOT ->row()
}







	

	



    function login_checking($username, $password)
	{





		$this -> db -> select('c_username, c_password');
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




		$query = $this->db->select("c_username AS c_username, user_role_id,sl_no AS sl_no, SYSDATE() AS currentdate", false)
                  ->from('admin_login')
                  ->where('c_username', $username)
                  ->get();
	
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