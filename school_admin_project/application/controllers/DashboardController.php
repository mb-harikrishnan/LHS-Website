<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller
{
    

   function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_Model');

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

        $data['academic']               = $this->Dashboard_Model->fetch_academic_yaar();   
        $data['all_students_count']     = $this->Dashboard_Model->all_students_count();   
        $data['all_employee_count']     = $this->Dashboard_Model->all_employee_count();   
        $data['school_news']            = $this->Dashboard_Model->school_news();   
        $data['exam_master']            = $this->Dashboard_Model->exam_master();   
        $data['active_exam_list']       = $this->Dashboard_Model->active_exam_list();   
        $data['all_school_news']        = $this->Dashboard_Model->all_school_news();   

        $this->load->view('header');
        $this->load->view('dashboard',$data);
        $this->load->view('footer');
    }


























}