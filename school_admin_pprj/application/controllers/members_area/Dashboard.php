<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {


   
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




	public function index()
	{

	    $this->load->view('members_area/header');
	    $this->load->view('members_area/dashboard');
	    $this->load->view('members_area/footer');
	}





























}