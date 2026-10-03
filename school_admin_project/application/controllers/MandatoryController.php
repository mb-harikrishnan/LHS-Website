<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MandatoryController extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        // $this->load->model('Mandatory_model');
    }



    public function general_information()
    {
        $this->load->view('header');
        $this->load->view('general_information');
        $this->load->view('footer');
    }
    public function Result_and_Staff()
    {
        $this->load->view('header');
        $this->load->view('Result_and_Staff');
        $this->load->view('footer');
    }
    public function infrastructure()
    {
        $this->load->view('header');
        $this->load->view('infrastructure');
        $this->load->view('footer');
    }








}