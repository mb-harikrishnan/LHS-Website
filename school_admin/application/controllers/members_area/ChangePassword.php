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

        $user_id = $this->session->userdata('c_username');

        $this->db->where('PC_USERNAME', $user_id);
        $query = $this->db->get('bc_login');

        $row = $query->row();

        if($row)
        {

           
            $match = ($currentPassword == $row->C_PASSWORD);

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

        $this->form_validation->set_rules('currentPassword','Current Password','required|callback_check_old_password');

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

            $user_id = $this->session->userdata('c_username');


            $newPassword = md5($this->input->post('newPassword'));


            $this->db->where('PC_USERNAME', $user_id);

            $update = $this->db->update('bc_login', array(
                'C_PASSWORD' => $newPassword,
                'd_last_password_change' => $currentdate
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







public function check_old_password($currentPassword)
{
    $pass=md5($currentPassword);

    $user_id = $this->session->userdata('c_username');

    $this->db->where('PC_USERNAME', $user_id);

    $query = $this->db->get('bc_login');

    $row = $query->row();

    if($row)
    {

        // NORMAL PASSWORD
        if($pass == $row->C_PASSWORD)
        {
            return TRUE;
        }
        else
        {

            $this->form_validation->set_message(
                'check_old_password',
                'Current password is incorrect'
            );

            return FALSE;
        }

    }
    else
    {

        $this->form_validation->set_message(
            'check_old_password',
            'User not found'
        );

        return FALSE;
    }

}





}