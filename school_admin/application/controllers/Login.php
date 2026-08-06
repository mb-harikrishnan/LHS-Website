<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


   
    public function __construct()
	{
		parent::__construct();
		$this->load->model('login_db');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('security');

		

		
	}




	public function member_login()
	{
    $data['user_roles'] = $this->login_db->fetch_roles();
		$this->load->view('member_login',$data);
	}





public function check_username()
{
    $username = $this->input->post('username');
    $role_id  = $this->input->post('role_id');

    $role = $this->db
                ->select('role_name')
                ->where('role_id', $role_id)
                ->get('user_roles')
                ->row();

        $role_name = $role ? $role->role_name : '';

    


    if($role_name == 'Admin')
    {
       $table = 'admin_login';
        $name = 'c_username';
    }
    elseif($role_name == 'Parent')
    {
      $table = 'parents_master';
        $name = 'pmName';
    }
    elseif($role_name == 'Teacher')
    {
        $table = 'employee_master';

        $name = 'emName';
    }

    $check = $this->db
                ->where($name, $username)   // confirm this column name exists in employee_master / parent_master too
                ->get($table);
             

    echo ($check->num_rows() > 0) ? 'true' : 'false';
}

public function check_password()
{
    $username = $this->input->post('username');
    $password = $this->input->post('password');
    $role_id  = $this->input->post('role_id');

    
    $role = $this->db
                ->select('role_name')
                ->where('role_id', $role_id)
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
    $check = $this->db
                ->where($name, $username)
                ->where($pass, md5($password))
                ->get($table);

    echo ($check->num_rows() > 0) ? 'true' : 'false';
}






    public function member_login_check()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
    	$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_login_check');
		

		if ($this->form_validation->run() == FALSE)
		{
            echo "error validation";
			    echo validation_errors();

		}
		else
		{


          redirect('dashboard');


        }


    }



    
	public function login_check($password)
	{
		
   
		  
		$login_flag=FALSE;
		$login_active_flag=FALSE;
		$username = $this->input->post('username');
    $user_role_id = $this->input->post('role_id');
		$login_time = "";
		
		//query the database
		$result = $this->login_db->login_checking($username, $password,$user_role_id);
		
		if($result)
		{
		
		  $login_flag=TRUE;
		  //return TRUE;
		}
		else
		{
		  
		  $this->form_validation->set_message('login_check', 'Invalid Username or password');
		  return FALSE;
		  $login_flag=FALSE;
		  //echo "error";
		}
		if($login_flag==TRUE)
		{
			$result = $this->login_db->login_validation_step2($username,$user_role_id);
			$id=0;
			if($result)
			{

                $keep_me_logged_in = $this->input->post('keep_me_logged_in');




			    $sess_array = array();
			    foreach($result as $row)
                {
				  $login_time = $row->currentdate;
				  $id=$row->n_slno;

				 
                    $sess_array = array(
                    'id' => $row->sl_no,
                    'c_username' => $row->c_username,
                    'login_time' => $row->currentdate,
                    'user_role_id' => $user_role_id
                    );


                    if ($keep_me_logged_in) {
                      $this->config->set_item('sess_expiration', 15 * 24 * 60 * 60);

                      $this->load->helper('cookie');
                      $cookie_data = json_encode($sess_array);
                      $cookie = array(
                          'name'   => 'remember_me',
                          'value'  => $cookie_data,
                          'expire' => 15 * 24 * 60 * 60,
                          'secure' => true,
                          'httponly' => true,
                      );
                      set_cookie($cookie);
                    }else {
                      $this->session->set_userdata($sess_array);

                    }
                  $this->session->set_userdata(SESSION_VARIABLE, $sess_array);
                }
                $login_active_flag=TRUE;
			  
			}
			
			if($login_active_flag==TRUE)
			{	

	           						
			  return TRUE;
			}
			else
			{
				//echo "error2";
			  $this->form_validation->set_message('login_check', 'Invalid Username (User is expired / Disabled');
			  return FALSE;
			}			
		}
    }




















}

