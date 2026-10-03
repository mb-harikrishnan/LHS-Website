<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CurricularController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');
  }


  public function co_curricular_list()
  {
    $this->load->view('header');
    $this->load->view('co_curricular_list');
    $this->load->view('footer');
  }

  public function activities_list()
  {
    $this->load->view('header');
    $this->load->view('activities_list');
    $this->load->view('footer');
  }






}