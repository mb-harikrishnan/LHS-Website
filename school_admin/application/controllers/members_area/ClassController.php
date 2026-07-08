<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClassController extends CI_Controller {


   
    public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
        $this->load->library('form_validation');
		$this->load->helper('date');
        $this->load->model('Class_Model');


        if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}

			
	}


    public function class_divition()
    {
         $data['classes'] = $this->Class_Model->fetch_all_class();
         $data['divisions'] = $this->Class_Model->fetch_all_divisions();

        $this->load->view('members_area/header');
        $this->load->view('members_area/add_class_divition',$data);
        $this->load->view('members_area/footer');
    }


public function insert_class_division()
{
    $cmId  = $this->input->post('cmId');
    $dmIds = $this->input->post('dmId'); // array from checkboxes

    if (empty($cmId) || empty($dmIds)) {
        $this->session->set_flashdata('error', 'Please select a class and at least one divition.');
        redirect('class_divition'); // adjust to your actual route
        return;
    }

    $inserted = $this->Class_Model->insert_class_division($cmId, $dmIds);

    if ($inserted) {
        $this->session->set_flashdata('success', 'Class divition added successfully.');
    } else {
        $this->session->set_flashdata('error', 'Something went wrong. Please try again.');
    }

    redirect('class_divition'); // adjust to your actual route
}



public function class_divition_list()
{


    $data['details'] = $this->Class_Model->fetch_all_details();

    $this->load->view('members_area/header');
    $this->load->view('members_area/class_divition_list',$data);
    $this->load->view('members_area/footer');



}


public function edit_class_division($id)
{
    $data['edit'] = $this->Class_Model->get_class_division($id);

    $data['classes'] = $this->Class_Model->fetch_all_class();

    $data['divisions'] = $this->Class_Model->fetch_all_divisions();

    $data['selected_divisions'] = $this->Class_Model->get_selected_divisions($id);

    $this->load->view('members_area/header');
    $this->load->view('members_area/edit_class_division',$data);
    $this->load->view('members_area/footer');
}



public function update_class_division()
{
    $cmId = $this->input->post('cmId');
    $dmIds = $this->input->post('dmId');

    if (!$cmId) {
        die('Class ID not received');
    }

    if (!is_array($dmIds)) {
        $dmIds = array();
    }

    // Delete old divisions
    $this->db->where('cdaCmId', $cmId);
    $this->db->delete('class_division_allocation');

    // Insert selected divisions
    foreach ($dmIds as $dmId) {

        $this->db->insert('class_division_allocation', array(
            'cdaCmId' => $cmId,
            'cdaDmId' => $dmId
        ));
    }

    $this->session->set_flashdata('success', 'Updated Successfully');
    redirect('class_divition_list');
}



  public function delete_divition()
    {
        $id = $this->input->post('id');

        $result = $this->Class_Model->delete_divition($id);

        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }





}