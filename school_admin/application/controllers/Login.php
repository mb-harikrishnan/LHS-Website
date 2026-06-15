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
		$this->load->view('member_login');
	}



    public function check_username()
    {
        $username = $this->input->post('username');

        $check = $this->db
                    ->where('c_username',$username)
                    ->get('admin_login');

        if($check->num_rows() > 0)
        {
            echo 'true';
        }
        else
        {
            echo 'false';
        }
    }


    public function check_password()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $check = $this->db
                    ->where('c_username',$username)
                    ->where('c_password',md5($password))
                    ->get('admin_login');

        if($check->num_rows() > 0)
        {
            echo 'true';
        }
        else
        {
            echo 'false';
        }
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
		$login_time = "";
		
		//query the database
		$result = $this->login_db->login_checking($username, $password);
		
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
			$result = $this->login_db->login_validation_step2($username);
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
                    'id' => $row->pn_id,
                    'c_username' => $row->c_username,
                    'login_time' => $row->currentdate,
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

