<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller
{
    

   function __construct()
    {
        parent::__construct();
        // $this->load->model('DashboardModel');

         if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
    }

    public function dashboard()
    {

       
        $this->load->view('header');
        $this->load->view('dashboard');
        $this->load->view('footer');
    }


























}