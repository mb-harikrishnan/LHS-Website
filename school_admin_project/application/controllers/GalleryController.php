<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GalleryController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');
  }


  public function gallery()
  {
    $this->load->view('header');
    $this->load->view('gallery');
    $this->load->view('footer');
  }

















}