<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherController extends CI_Controller
{


  

    public function __construct()
    {
        parent::__construct();
        $this->load->model('TeacherDashboard_model');

        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
    }

//    public function teacherdashboard()
// {
//     $this->load->model('Exam_Model');

//     $emp_id  = (int)$this->session->userdata('id');   // adjust to your login session key
//     $teacher = $this->TeacherDashboard_model->get_teacher($emp_id);

//     $class = $teacher ? (int)$teacher->emClass : 0;
//     $div   = $teacher ? $teacher->emDiv : '';

//     // running exams = open exams that have a mark allocation for this teacher's class
//     $running = array();
//     if ($class > 0) {
//         $class_exam_ids = $this->TeacherDashboard_model->exam_ids_for_class($class);
//         foreach ($this->Exam_Model->fetch_open_exams() as $e) {
//             if (in_array((int)$e->emId, $class_exam_ids, true)) { $running[] = $e; }
//         }
//     }

//     $sql= "SELECT dmName FROM division_master WHERE dmId =$div";
//     $query = $this->db->query($sql);
//     $res = $query->row()->dmName;

//     $data['teacher'] = $teacher;
//     $data['stats']   = $this->TeacherDashboard_model->student_stats($class, $div);
//     $data['exams']   = $running;
//     $data['news']    = $this->TeacherDashboard_model->latest_news(5);
//     $data['division'] =$res;

//     $this->load->view('header');
//     $this->load->view('teacherdashboard', $data);
//     $this->load->view('footer');
// }


public function teacherdashboard()
{
    $this->load->model('Exam_Model');

    $emp_id   = (int)$this->session->userdata('id');
    $is_admin = ($emp_id === 1);
    $teacher  = $this->TeacherDashboard_model->get_teacher($emp_id);

    // Admin: no class / division filter
    $class = $is_admin ? 0 : ($teacher ? (int)$teacher->emClass : 0);
    $div   = $is_admin ? 0 : ($teacher ? (int)$teacher->emDiv   : 0);

    // Running exams
    $running = array();
    if ($is_admin) {
        $running = $this->Exam_Model->fetch_open_exams();          // all open exams
    } elseif ($class > 0) {
        $class_exam_ids = $this->TeacherDashboard_model->exam_ids_for_class($class);
        foreach ($this->Exam_Model->fetch_open_exams() as $e) {
            if (in_array((int)$e->emId, $class_exam_ids, true)) { $running[] = $e; }
        }
    }

    // Division name (only when a division exists)
    $division = '';
    if ($div > 0) {
        $row = $this->db->select('dmName')->where('dmId', $div)
                        ->get('division_master')->row();
        $division = $row ? $row->dmName : '';
    }

    $data['is_admin'] = $is_admin;
    $data['teacher']  = $teacher;
    $data['stats']    = $this->TeacherDashboard_model->student_stats($class, $div);
    $data['exams']    = $running;
    $data['news']     = $this->TeacherDashboard_model->latest_news(5);
    $data['division'] = $division;

    $this->load->view('header');
    $this->load->view('teacherdashboard', $data);
    $this->load->view('footer');
}









}