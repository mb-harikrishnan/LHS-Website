<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePasswordController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');
  }


  public function change_password()
  {
    $this->load->view('header');
    $this->load->view('change_password');
    $this->load->view('footer');
  }




}