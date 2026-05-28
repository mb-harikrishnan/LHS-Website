<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('Login_model');
	}


	public function index()
	{
		$this->load->view('login');
	}


	public function login_check()
	{
		// Validation
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('login');
		} else {

			$username = $this->input->post('username');
			$password = $this->input->post('password');

			// Check login
			$user = $this->Login_model->check_login($username, $password);

			if ($user) {

				// ✅ Set session
				$session_data = array(
					'id'         => $user->pn_id,
					'username'   => $user->c_username,
					'logged_in'  => TRUE
				);
				$this->session->set_userdata($session_data);

				// ✅ Success redirect
				redirect('dashboard');

			} else {

				// ❌ Error
				$this->session->set_flashdata('error', 'Invalid Username or Password');
				redirect(base_url());
			}
		}
	}


	public function check_username()
	{
		$username = $this->input->post('username');

		$exists = $this->Login_model->username_exists($username);

		if ($exists) {
			echo "true";   // ✅ for jQuery validation
		} else {
			echo "false";  // ❌ username not exist
		}
	}







}
