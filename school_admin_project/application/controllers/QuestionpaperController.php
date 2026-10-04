<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionpaperController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');
  }


  public function questionpaper_list()
  {
    $this->load->view('header');
    $this->load->view('questionpaper_list');
    $this->load->view('footer');
  }


  public function slider_list()
  {
    $this->load->view('header');
    $this->load->view('slider_list');
    $this->load->view('footer');
  }




  public function accademic_list()
  {
    $this->load->view('header');
    $this->load->view('accademic_list');
    $this->load->view('footer');
  }


  public function term_list()
  {
    $this->load->view('header');
    $this->load->view('term_list');
    $this->load->view('footer');
  }



  public function user_role_list()
  {
    $this->load->view('header');
    $this->load->view('user_role_list');
    $this->load->view('footer');
  }



  public function menu_list()
  {
    $this->load->view('header');
    $this->load->view('menu_list');
    $this->load->view('footer');
  }




  public function add_menu_permission()
  {
    $this->load->view('header');
    $this->load->view('add_menu_permission');
    $this->load->view('footer');
  }
















}