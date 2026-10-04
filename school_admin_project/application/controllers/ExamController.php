<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExamController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');
  }


  public function exam_list()
  {
    $this->load->view('header');
    $this->load->view('exam_list');
    $this->load->view('footer');
  }

  public function allocation_list()
  {
    $this->load->view('header');
    $this->load->view('allocation_list');
    $this->load->view('footer');
  }

  public function Marksentry_list()
  {
    $this->load->view('header');
    $this->load->view('Marksentry_list');
    $this->load->view('footer');
  }












}