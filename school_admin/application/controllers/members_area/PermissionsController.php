<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionsController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		
	}


    public function accademic_list()
    {
        $data['academic'] = $this->db->get('academic_master')->result();

        $this->load->view('members_area/header');
        $this->load->view('members_area/accademic_list', $data);
        $this->load->view('members_area/footer');

    }

public function delete_accademic()
{
    $id = $this->input->post('id');

    $this->db->where('amId', $id);
    $result = $this->db->update('academic_master', ['amIsCurrent' => 0]);

    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
}



public function add_academic()
{
    
    $this->load->view('members_area/header');
    $this->load->view('members_area/add_academic');
    $this->load->view('members_area/footer');
}



public function check_academic_year()
{
    $year = $this->input->post('academic_year');

    if(!preg_match('/^\d{4}-\d{2}$/',$year))
    {
        echo json_encode([
            "status"=>"invalid"
        ]);
        return;
    }

    $count = $this->db
        ->where('amYear',$year)
        ->count_all_results('academic_master');

    if($count>0)
    {
        echo json_encode([
            "status"=>"exists"
        ]);
    }
    else
    {
        echo json_encode([
            "status"=>"available"
        ]);
    }
}

public function insert_academic()
{
    $year = trim($this->input->post('academic_year'));

    // Format validation
    if (!preg_match('/^\d{4}-\d{2}$/', $year)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Academic Year format.'
        ]);
        return;
    }

    // Duplicate year check
    $exists = $this->db->where('amYear', $year)
                       ->count_all_results('academic_master');

    if ($exists > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Academic Year already exists.'
        ]);
        return;
    }

    // Check if an active year already exists
    $active = $this->db->where('amIsCurrent', 1)
                       ->count_all_results('academic_master');

    if ($active > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'An active Academic Year already exists. Please delete or update it first.'
        ]);
        return;
    }

    // Insert
    $result = $this->db->insert('academic_master', [
        'amYear'      => $year,
        'amIsCurrent' => 1
    ]);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Academic Year added successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Something went wrong.'
        ]);
    }
}







 public function term_list()
    {
        $data['term'] = $this->db->get('term_master')->result();

        $this->load->view('members_area/header');
        $this->load->view('members_area/term_list', $data);
        $this->load->view('members_area/footer');

    }
public function delete_term()
{
    $id = $this->input->post('id');

    $this->db->where('tmId', $id);   
    $result = $this->db->delete('term_master');

    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
}




public function add_term()
{
    
    $this->load->view('members_area/header');
    $this->load->view('members_area/add_term');
    $this->load->view('members_area/footer');
}


public function check_term()
{
    $term = trim($this->input->post('term'));
    $code = trim($this->input->post('code'));

    $termExists = $this->db
        ->where('tmName',$term)
        ->count_all_results('term_master');

    $codeExists = $this->db
        ->where('tmCode',$code)
        ->count_all_results('term_master');

    echo json_encode([
        'term'=>$termExists ? 'exists' : 'available',
        'code'=>$codeExists ? 'exists' : 'available'
    ]);
}
public function insert_term()
{
    $term = trim($this->input->post('term'));
    $code = trim($this->input->post('code'));

    if($term=="" || $code=="")
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'All fields are required.'
        ]);
        return;
    }

    $exists = $this->db
        ->group_start()
            ->where('tmName',$term)
            ->or_where('tmCode',$code)
        ->group_end()
        ->count_all_results('term_master');

    if($exists>0)
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'Term or Code already exists.'
        ]);
        return;
    }

    $result = $this->db->insert('term_master',[
        'tmName'=>$term,
        'tmCode'=>$code
    ]);

    if($result)
    {
        echo json_encode([
            'status'=>'success',
            'message'=>'Term added successfully.'
        ]);
    }
    else
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'Something went wrong.'
        ]);
    }
}








 public function user_role_list()
    {
        $data['role'] = $this->db->get('user_roles')->result();

        $this->load->view('members_area/header');
        $this->load->view('members_area/user_role_list', $data);
        $this->load->view('members_area/footer');

    }



    public function update_role_status()
{
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->db->where('role_id', $id);
    $result = $this->db->update('user_roles', [
        'status' => $status
    ]);

    echo $result ? 1 : 0;
}



public function add_user_role()
{


     $this->load->view('members_area/header');
    $this->load->view('members_area/add_user_role');
    $this->load->view('members_area/footer');

}


public function check_role()
{
    $role = trim($this->input->post('role_name'));

    $exists = $this->db->where('role_name', $role)
                       ->count_all_results('user_roles');

    if($exists > 0)
    {
        echo json_encode([
            'status' => 'exists'
        ]);
    }
    else
    {
        echo json_encode([
            'status' => 'available'
        ]);
    }
}


public function insert_role()
{
    $role = trim($this->input->post('role_name'));

    if($role == "")
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Role Name is required.'
        ]);
        return;
    }

    $exists = $this->db->where('role_name', $role)
                       ->count_all_results('user_roles');

    if($exists > 0)
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Role already exists.'
        ]);
        return;
    }

    $result = $this->db->insert('user_roles', [
        'role_name' => $role,
        'status'    => 1
    ]);

    if($result)
    {
        echo json_encode([
            'status' => 'success',
            'message' => 'Role added successfully.'
        ]);
    }
    else
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Something went wrong.'
        ]);
    }
}





































}