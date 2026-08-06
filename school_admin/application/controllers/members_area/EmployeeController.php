<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('Employee_model');


          if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}
	}



    public function employee_list()
    {   




        $image['details'] = $this->Employee_model->employee_list();

        $this->load->view('members_area/header');
        $this->load->view('members_area/employee_list', $image);
        $this->load->view('members_area/footer');
    }


    public function add_employee()
    {
        $this->load->view('members_area/header');
        $this->load->view('members_area/add_employee');
        $this->load->view('members_area/footer');
    }


    public function insert_employee()
    {
        $data = array(
            'emActive' => 'Y',
            'emTS' => date('Y-m-d'),
            'emName' => $this->input->post('name'),
            'emPassword'  => md5($this->input->post('password')),
            'emClass'  => $this->input->post('class_id'),
            'emDiv'  => $this->input->post('division_id'),
            'emPhoneNo'  => $this->input->post('mobile'),

        );

        $result = $this->db->insert('employee_master', $data);

        if ($result)
        {
           
            
            $this->session->set_flashdata('success', ' added successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Failed to add');
        }

        redirect('add_employee');
    }





      public function delete_employee()
    {
        $id = $this->input->post('id');

        $result = $this->Employee_model->delete_employee($id);

        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }



public function check_name_exist()
{
    $name = $this->input->post('name');
    $employee_id = $this->input->post('employee_id');

    $this->db->where('emName', $name);

    // Ignore current record while editing
    if (!empty($employee_id)) {
        $this->db->where('emId !=', $employee_id);
    }

    $count = $this->db->count_all_results('employee_master');

    if ($count > 0) {
        echo "false"; // Name exists
    } else {
        echo "true";  // Name available
    }
}

















}