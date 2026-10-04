<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');
  }


  public function employee_list()
  {
    $this->load->view('header');
    $this->load->view('employee_list');
    $this->load->view('footer');
  }




}