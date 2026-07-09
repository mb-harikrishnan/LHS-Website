<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {


   
    public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
  

        if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}

			
	}


	public function member_logout()
	{
		   // Destroy all session data
        $this->session->sess_destroy();
		$this->session->unset_userdata(SESSION_VARIABLE);

        // Redirect to login page
        redirect('member_login', 'refresh');
	}














}