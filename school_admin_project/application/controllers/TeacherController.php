<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherController extends CI_Controller
{
    // user_role_id => label shown on the dashboard. Edit to match your roles.
    private $role_names = array(
        1 => 'Administrator',
        7 => 'Teacher'
    );

    // roles that see ALL active exams. Everyone else sees only their own (see Dashboard_model).
    private $see_all_roles = array(1);

    public function __construct()
    {
        parent::__construct();
        $this->load->model('TeacherDashboard_model');

        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
    }

   public function teacherdashboard()
{
    $this->load->model('Exam_Model');

    $emp_id  = (int)$this->session->userdata('id');   // adjust to your login session key
    $teacher = $this->TeacherDashboard_model->get_teacher($emp_id);

    $class = $teacher ? (int)$teacher->emClass : 0;
    $div   = $teacher ? $teacher->emDiv : '';

    // running exams = open exams that have a mark allocation for this teacher's class
    $running = array();
    if ($class > 0) {
        $class_exam_ids = $this->TeacherDashboard_model->exam_ids_for_class($class);
        foreach ($this->Exam_Model->fetch_open_exams() as $e) {
            if (in_array((int)$e->emId, $class_exam_ids, true)) { $running[] = $e; }
        }
    }

    $sql= "SELECT dmName FROM division_master WHERE dmId =$div";
    $query = $this->db->query($sql);
    $res = $query->row()->dmName;

    $data['teacher'] = $teacher;
    $data['stats']   = $this->TeacherDashboard_model->student_stats($class, $div);
    $data['exams']   = $running;
    $data['news']    = $this->TeacherDashboard_model->latest_news(5);
    $data['division'] =$res;

    $this->load->view('header');
    $this->load->view('teacherdashboard', $data);
    $this->load->view('footer');
}












}