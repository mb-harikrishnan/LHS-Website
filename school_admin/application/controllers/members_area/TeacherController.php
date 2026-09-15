<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherController extends CI_Controller {


   
    public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
        $this->load->library('form_validation');
		$this->load->helper('date');
        $this->load->model('Teacher_Model');


        if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}

			
	}


public function teacherdashboard()
{
 
    $data['exam_groups']   = $this->Teacher_Model->get_dashboard_exams();
    $data['ongoing_count'] = $this->Teacher_Model->count_ongoing_exams();
 
    $this->load->view('members_area/header');
    $this->load->view('members_area/teacherdashboard', $data);
    $this->load->view('members_area/footer');
}


}