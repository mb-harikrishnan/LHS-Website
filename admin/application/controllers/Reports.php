<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reports_model');
        // $this->load->library('session');   // add your login check here if needed
    }

    public function mark_entry_list()
    {
        $examId = $this->input->get('exam');          // optional filter (?exam=2)

        $data['title'] = 'Mark Entry List';
        $data['exams'] = $this->Reports_model->get_exams();
        $data['exam']  = $examId;
        $data['rows']  = $this->Reports_model->get_final_submitted_list($examId);

        $this->load->view('header', $data);
        $this->load->view('mark_entry_list', $data);
        $this->load->view('footer', $data);
    }
}