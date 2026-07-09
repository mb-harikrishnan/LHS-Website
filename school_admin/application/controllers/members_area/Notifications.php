<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {


   
    public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
        $this->load->model('notification_Model');
  

        if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}

			
	}



    public function notifications()
    {
        $data['all_news'] = $this->notification_Model->fetch_all_news();
        $this->load->view('members_area/header');
        $this->load->view('members_area/notifications',$data);
        $this->load->view('members_area/footer');
    }













}