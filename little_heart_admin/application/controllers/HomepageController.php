<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomepageController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('HomePage_model');
	}


    public function dashboard()
    {
        
        $this->load->view('header');
        $this->load->view('topbar');
        $this->load->view('sidebar');
        $this->load->view('dashboard');
        $this->load->view('footer');
    }








}