<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherController extends CI_Controller
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

    public function teacherdashboard()
    {
       
        $this->load->view('header');
        $this->load->view('teacherdashboard');
        $this->load->view('footer');
    }


}