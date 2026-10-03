<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
        $this->load->model('login_db');
    }

    // GET /login  -> shows the page
    public function index()
    {
        if ($this->session->userdata('c_username')) {
            redirect($this->session->userdata('user_role_id') == 1 ? 'dashboard' : 'teacherdashboard');
        }
        $this->load->view('login');   // application/views/login.php
    }

    // POST /login_submit  -> returns JSON for the AJAX call
    public function login_submit()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[30]|regex_match[/^[a-zA-Z0-9._]+$/]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[50]');

        if ($this->form_validation->run() === FALSE) {
            return $this->_json(['status' => 'error', 'message' => strip_tags(validation_errors())]);
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');           // don't xss-clean/trim passwords
        $remember = (int) $this->input->post('remember');

        $user = $this->login_db->login_checking($username, $password);

        if (!$user) {
            return $this->_json(['status' => 'error', 'message' => 'Invalid username or password']);
        }

        // prevent session fixation
        $this->session->sess_regenerate(TRUE);

        $sess_array = [
            'id'           => $user->sl_no,
            'c_username'   => $user->c_username,
            'user_role_id' => $user->user_role_id,
            'login_time'   => date('Y-m-d H:i:s'),
            'remember'     => $remember
        ];

        $this->session->set_userdata($sess_array);
        $this->session->set_userdata(SESSION_VARIABLE, $sess_array);

        // adjust "1" to your real admin role id
        $redirect = ($user->user_role_id == 1) ? base_url('dashboard') : base_url('teacherdashboard');

        return $this->_json(['status' => 'success', 'redirect' => $redirect]);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    private function _json($data)
    {
        $data['csrf_hash'] = $this->security->get_csrf_hash();   // harmless if CSRF is off
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($data));
    }
}