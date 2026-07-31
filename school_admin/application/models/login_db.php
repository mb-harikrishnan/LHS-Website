<?php
Class Login_db extends CI_Model
{


   
public function fetch_roles()
{
    $sql = "SELECT role_id, role_name FROM user_roles WHERE status = 1";
    $query = $this->db->query($sql);
    return $query->result();   // array of objects — NOT ->row()
}







	

	



    function login_checking($username, $password,$user_role_id)
	{


		$role = $this->db
					->select('role_name')
					->where('role_id', $user_role_id)
					->get('user_roles')
					->row();

			$role_name = $role ? $role->role_name : '';

		


		if($role_name == 'Admin')
		{
		    $table = 'admin_login';
			$name = 'c_username';
			$pass = 'c_password';
		}
		elseif($role_name == 'Parent')
		{
		$table = 'parents_master';
			$name = 'pmName';
			$pass = 'pmPassword';
		}
		elseif($role_name == 'Teacher')
		{
			$table = 'employee_master';

			$name = 'emName';
			$pass = 'emPassword';

		}


		$this -> db -> select($name,$pass);
		$this -> db -> from($table);
		 $where = "$name='".$username."' and $pass='".md5($password)."'  "; 
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



    function login_validation_step2($username,$user_role_id)
	{


	$role = $this->db
					->select('role_name')
					->where('role_id', $user_role_id)
					->get('user_roles')
					->row();

			$role_name = $role ? $role->role_name : '';

		


		if($role_name == 'Admin')
		{
		    $table = 'admin_login';
			$name = 'c_username';
			$pass = 'c_password';
			$id = 'sl_no';
		}
		elseif($role_name == 'Parent')
		{
		$table = 'parents_master';
			$name = 'pmName';
			$pass = 'pmPassword';
			$id = 'pmId';

		}
		elseif($role_name == 'Teacher')
		{
			$table = 'employee_master';

			$name = 'emName';
			$pass = 'emPassword';
			$id = 'emId';


		}

		 $query = $this->db->query("SELECT $name as c_username,$id as sl_no,SYSDATE() currentdate 
		 FROM $table  WHERE  $name='".$username."'");
        
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