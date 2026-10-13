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
       

        $this->load->view('header');
        $this->load->view('teacherdashboard');
        $this->load->view('footer');
    }
}