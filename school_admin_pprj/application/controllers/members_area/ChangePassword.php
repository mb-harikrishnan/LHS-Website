<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePassword extends CI_Controller {


   
    public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
        $this->load->library('form_validation');
		$this->load->helper('date');


        if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}

			
	}


    public function index()
    {   
        $this->load->view('members_area/header');
        $this->load->view('members_area/change_password');
        $this->load->view('members_area/footer');
    }



    public function check_current_password()
    {
        $currentPassword = md5($this->input->post('currentPassword'));

        $user_id = $this->session->userdata('id');


        $user_role_id = $this->session->userdata('user_role_id');

	$role = $this->db
					->select('role_name')
					->where('role_id', $user_role_id)
					->get('user_roles')
					->row();

			$role_name = $role ? $role->role_name : '';

		if($role_name == 'Admin')
		{
		    $table = 'admin_login';
			$name = 'c_username';
			$pass = 'c_password';
			$id = 'sl_no';
		}
		elseif($role_name == 'Parent')
		{
		$table = 'parents_master';
			$name = 'pmName';
			$pass = 'pmPassword';
			$id = 'pmId';

		}
		elseif($role_name == 'Teacher')
		{
			$table = 'employee_master';

			$name = 'emName';
			$pass = 'emPassword';
			$id = 'emId';


		}

        $this->db->where($id, $user_id);
        $query = $this->db->get($table);

        $row = $query->row();

        if($row)
        {

           
            $match = ($currentPassword == $row->$pass);

            if($match)
            {
                echo 'true';
            }
            else
            {
                echo 'false';
            }

        }
        else
        {
            echo 'false';
        }
    }



    public function change_old_password()
    {

    

        //---------- current date and time--------------//
		$format = 'DATE_RFC822';
		$time = time();
		$currentdateandtime = standard_date($format, $time);
		date_default_timezone_set('GMT');
		$temp = strtotime("+5 hours 30 minutes");
		$currentdateandtime = date("Y-m-d H:i:s", $temp);
		$currentdate = date("Y-m-d", $temp);
		//----------------------------------------------//

        $this->form_validation->set_rules('currentPassword','Current Password','required');

        $this->form_validation->set_rules('newPassword','New Password','required|min_length[8]');

        $this->form_validation->set_rules('confirmPassword','Confirm Password','required|matches[newPassword]');

        if ($this->form_validation->run() == FALSE)
        {
            // echo validation_errors();
            $this->load->view('members_area/header');
            $this->load->view('members_area/change_password');
            $this->load->view('members_area/footer');
        }
        else
        {

            $user_id = $this->session->userdata('id');


            $newPassword = md5($this->input->post('newPassword'));

            $user_role_id = $this->session->userdata('user_role_id');

	$role = $this->db
					->select('role_name')
					->where('role_id', $user_role_id)
					->get('user_roles')
					->row();

			$role_name = $role ? $role->role_name : '';

		if($role_name == 'Admin')
		{
		    $table = 'admin_login';
			$name = 'c_username';
			$pass = 'c_password';
			$id = 'sl_no';
		}
		elseif($role_name == 'Parent')
		{
		$table = 'parents_master';
			$name = 'pmName';
			$pass = 'pmPassword';
			$id = 'pmId';

		}
		elseif($role_name == 'Teacher')
		{
			$table = 'employee_master';

			$name = 'emName';
			$pass = 'emPassword';
			$id = 'emId';


		}


            $this->db->where($id, $user_id);

            $update = $this->db->update($table, array(
                $pass => $newPassword,
            ));

            if($update)
            {

                $this->session->set_flashdata(
                    'success',
                    'Password updated successfully'
                );

                redirect('change_password');

            }
            else
            {

                $this->session->set_flashdata(
                    'error',
                    'Something went wrong'
                );

                redirect('change_password');

            }

               

        }

    }












}