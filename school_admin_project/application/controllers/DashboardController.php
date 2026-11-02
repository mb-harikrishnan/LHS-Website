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








 public function switch_year() {
    $amId = (int) $this->input->post('am_id');

    $row = $this->db->select('amId, amYear')
                    ->where('amId', $amId)
                    ->get('academic_master')->row();

    if (!$row) {
        echo json_encode(['status' => false, 'msg' => 'Invalid year']);
        return;
    }

    // Remove old value, then set the new one
    $this->session->unset_userdata('selected_am_id');
    $this->session->set_userdata('selected_am_id', (int) $row->amId);

    echo json_encode(['status' => true]);
}

















}