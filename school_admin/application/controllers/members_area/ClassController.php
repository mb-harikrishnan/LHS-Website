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

  public function delete_divition_table()
    {
        $id = $this->input->post('id');

        $result = $this->Class_Model->delete_divition_table($id);

        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
  public function delete_class()
    {
        $id = $this->input->post('id');

        $result = $this->Class_Model->delete_class($id);

        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }







        public function class_list()
    {
      
        $data['details'] = $this->Class_Model->fetch_class();
        $this->load->view('members_area/header');
        $this->load->view('members_area/class_list',$data);
        $this->load->view('members_area/footer');
    }


    public function edit_class($id)
{
    $data['class'] = $this->db
        ->where('cmId', $id)
        ->get('class_master')
        ->row();

    if (!$data['class']) {
        show_404();
    }

    $this->load->view('members_area/header');
    $this->load->view('members_area/edit_class', $data);
    $this->load->view('members_area/footer');
}


public function update_class()
{
    $id = $this->input->post('cmId');
    $class_name = trim($this->input->post('class_name'));

    $check = $this->db
        ->where('cmName', $class_name)
        ->where('cmId !=', $id)
        ->get('class_master');

    if ($check->num_rows() > 0) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Class already exists.'
        ]);
        return;
    }

    $this->db->where('cmId', $id);
    $update = $this->db->update('class_master', [
        'cmName' => $class_name
    ]);

    if ($update) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Class updated successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Update failed.'
        ]);
    }
}

        public function divition_list()
    {
      
        $data['details'] = $this->Class_Model->fetch_divition();
        $this->load->view('members_area/header');
        $this->load->view('members_area/divition_list',$data);
        $this->load->view('members_area/footer');
    }


    public function edit_division($id)
{
    $data['division'] = $this->db
            ->where('dmId', $id)
            ->get('division_master')
            ->row();

    $this->load->view('members_area/header');
    $this->load->view('members_area/edit_division', $data);
    $this->load->view('members_area/footer');
}

public function update_divition()
{
    $id = $this->input->post('division_id');
    $division_name = trim($this->input->post('division_name'));

    $exists = $this->db
            ->where('dmName', $division_name)
            ->where('dmId !=', $id)
            ->get('division_master')
            ->num_rows();

    if ($exists > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Division already exists.'
        ]);
        return;
    }

    $this->db->where('dmId', $id);
    $result = $this->db->update('division_master', [
        'dmName' => $division_name
    ]);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Division updated successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Update failed.'
        ]);
    }
}





}