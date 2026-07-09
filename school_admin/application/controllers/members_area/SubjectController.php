<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubjectController extends CI_Controller {


   
    public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
        $this->load->library('form_validation');
		$this->load->helper('date');
        $this->load->model('Subject_Model');


        if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}

			
	}



    public function add_subject()
    {

    
        $data['classes'] = $this->Subject_Model->fetch_all_class();
        $data['exams'] = $this->Subject_Model->fetch_all_exams();
        $data['subjects'] = $this->Subject_Model->fetch_all_subjects();

        $this->load->view('members_area/header');
        $this->load->view('members_area/add_subject',$data);
        $this->load->view('members_area/footer');

    }





}