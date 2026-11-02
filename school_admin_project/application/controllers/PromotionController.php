
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionController extends CI_Controller
{


  function __construct()
  {
    parent::__construct();

    $this->load->model('promotion_Model');

     if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}

  }




  public function promotion()
  {
    $data['title'] = 'Promotion';
    $data['students'] = $this->promotion_Model->get_students();
    $this->load->view('promotion', $data);
  }





}