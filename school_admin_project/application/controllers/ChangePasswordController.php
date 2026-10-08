<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePasswordController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');

     if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
  }


  public function change_password()
  {
    $this->load->view('header');
    $this->load->view('change_password');
    $this->load->view('footer');
  }




}