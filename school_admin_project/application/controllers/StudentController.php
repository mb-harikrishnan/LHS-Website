<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentController extends CI_Controller
{
    

   function __construct()
    {
        parent::__construct();
        // $this->load->model('DashboardModel');
    }

    public function divition_list()
    {
       
        $this->load->view('header');
        $this->load->view('divition_list');
        $this->load->view('footer');
    }

    public function class_divition_list()
    {
       
        $this->load->view('header');
        $this->load->view('class_divition_list');
        $this->load->view('footer');
    }




    public function students_list()
    {
       
        $this->load->view('header');
        $this->load->view('students_list');
        $this->load->view('footer');
    }


























}