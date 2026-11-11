<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Parentlogin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
        $this->load->model('parent_login_db');
    }



    public function index()
    {
        $this->load->view('parent_login');
    }





}