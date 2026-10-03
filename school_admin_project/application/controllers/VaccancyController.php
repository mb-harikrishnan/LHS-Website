<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VaccancyController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');
  }


  public function vaccancy_list()
  {
    $this->load->view('header');
    $this->load->view('vaccancy_list');
    $this->load->view('footer');
  }
  public function apply_members()
  {
    $this->load->view('header');
    $this->load->view('apply_members');
    $this->load->view('footer');
  }















}