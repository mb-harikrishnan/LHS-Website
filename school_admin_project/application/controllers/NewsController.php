<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('NewsModel');
  }


  public function school_news()
  {
    $this->load->view('header');
    $this->load->view('school_news');
    $this->load->view('footer');
  }

















}