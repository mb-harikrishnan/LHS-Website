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


    public function add_class()
    {


        $this->load->view('members_area/header');
        $this->load->view('members_area/add_class');
        $this->load->view('members_area/footer');

    }

public function check_class_name()
{
    $class_name = strtoupper(trim($this->input->post('class_name')));

    // Convert 1-12 to Roman numerals
    $roman = [
        '1'  => 'I',
        '2'  => 'II',
        '3'  => 'III',
        '4'  => 'IV',
        '5'  => 'V',
        '6'  => 'VI',
        '7'  => 'VII',
        '8'  => 'VIII',
        '9'  => 'IX',
        '10' => 'X',
        '11' => 'XI',
        '12' => 'XII'
    ];

    if (isset($roman[$class_name])) {
        $class_name = $roman[$class_name];
    }

    // Allow only these text values
    $allowed = ['LKG', 'UKG', 'PLAY GROUP', 'PLAYGROUP'];

    // If input is not Roman numeral and not allowed text
    if (!in_array($class_name, $roman) && !in_array($class_name, $allowed)) {
        echo json_encode([
            'status'  => 'invalid',
            'message' => 'Only classes 1-12, LKG, UKG and PLAY GROUP are allowed.'
        ]);
        return;
    }

    // Duplicate check
    $count = $this->db
        ->where('cmName', $class_name)
        ->count_all_results('class_master');

    if ($count > 0) {
        echo json_encode([
            'status' => 'exists'
        ]);
    } else {
        echo json_encode([
            'status' => 'available'
        ]);
    }
}








public function insert_class()
{
    $class_name = strtoupper(trim($this->input->post('class_name')));

    // Convert 1-12 to Roman numerals
    $roman = [
        '1'  => 'I',
        '2'  => 'II',
        '3'  => 'III',
        '4'  => 'IV',
        '5'  => 'V',
        '6'  => 'VI',
        '7'  => 'VII',
        '8'  => 'VIII',
        '9'  => 'IX',
        '10' => 'X',
        '11' => 'XI',
        '12' => 'XII'
    ];

    if (isset($roman[$class_name])) {
        $class_name = $roman[$class_name];
    }

    // Allow only I-XII, LKG, UKG, PLAY GROUP
    $allowed = array_merge(array_values($roman), ['LKG', 'UKG', 'PLAY GROUP']);

    if (!in_array($class_name, $allowed)) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Only classes I-XII, LKG, UKG and PLAY GROUP are allowed.'
        ]);
        return;
    }

    // Check duplicate
    $exists = $this->db
        ->where('cmName', $class_name)
        ->count_all_results('class_master');

    if ($exists > 0) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Class already exists.'
        ]);
        return;
    }

    // Insert
    $insert = $this->db->insert('class_master', [
        'cmName' => $class_name
    ]);

    if ($insert) {
        echo json_encode([
            'status'  => 'success',
            'message' => 'Class added successfully.'
        ]);
    } else {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Failed to add class.'
        ]);
    }
}




    public function add_division()
    {


        $this->load->view('members_area/header');
        $this->load->view('members_area/add_division');
        $this->load->view('members_area/footer');

    }






public function check_divition()
{
    $division_name = trim($this->input->post('division_name'));

    $count = $this->db
                  ->where('LOWER(dmName)', strtolower($division_name))
                  ->count_all_results('division_master');

    if($count > 0)
    {
        echo json_encode([
            'status'=>'exists'
        ]);
    }
    else
    {
        echo json_encode([
            'status'=>'available'
        ]);
    }
}






public function insert_divition()
{
    $division_name = trim($this->input->post('division_name'));

    if($division_name=="")
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'Division Name is required.'
        ]);
        return;
    }

    // Convert to title case (optional)
    $division_name = ucwords(strtolower($division_name));

    $exists = $this->db
                   ->where('LOWER(dmName)', strtolower($division_name))
                   ->count_all_results('division_master');

    if($exists > 0)
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'Division already exists.'
        ]);
        return;
    }

    $this->db->insert('division_master',[
        'dmName'=>$division_name
    ]);

    echo json_encode([
        'status'=>'success',
        'message'=>'Division added successfully.'
    ]);
}








    public function add_student()
    {

        $data['class'] = $this->Subject_Model->fetch_all_class();
        $data['divition'] = $this->Subject_Model->fetch_all_division();
        $data['state'] = $this->Subject_Model->fetch_all_state();
        $data['country'] = $this->Subject_Model->fetch_all_country();

        $this->load->view('members_area/header');
        $this->load->view('members_area/add_student',$data);
        $this->load->view('members_area/footer');

    }


    public function check_admission_number()
{
    $admission_no = $this->input->post('admission_no');

    $count = $this->db
                  ->where('smAdmissionNo', $admission_no)
                  ->count_all_results('students_master');

    if ($count > 0) {
        echo "false";
    } else {
        echo "true";
    }
}





public function insert_student()
{



    $data = array(

        'smAdmissionNo' => $this->input->post('admission_no'),
        'smAadharNo' => $this->input->post('aadhar_no'),
        'smName' => $this->input->post('student_name'),
        'smGender' => $this->input->post('gender'),
        'smDOB' => $this->input->post('dob'),
        'smMobile' => $this->input->post('mobile'),
        'smClass' => $this->input->post('class'),
        'smDiv' => $this->input->post('division'),
        'smReligion' => $this->input->post('religion'),
        'smCaste' => $this->input->post('caste'),
        'smMotherTongue' => $this->input->post('mother_tongue'),
        'smAddress' => $this->input->post('address'),
        'smCountry' => $this->input->post('country'),
        'smState' => $this->input->post('state'),
        'smActive' => 1

    );

    if ($this->db->insert('students_master', $data)) {

        echo json_encode(array(
            'status' => 1,
            'message' => 'Student added successfully.'
        ));

    } else {

        echo json_encode(array(
            'status' => 0,
            'message' => 'Failed to save student.'
        ));

    }

}








}