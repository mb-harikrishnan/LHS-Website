<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePasswordController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ChangePassword_model');

        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
    }

    // logged-in user_login.user_id (session key 'id' - change if yours differs)
    private function _uid()
    {
        return (int) $this->session->userdata('id');
    }

    // ---------------------------------------------------------
    // PAGE
    // ---------------------------------------------------------
    public function change_password()
    {
        $user = $this->ChangePassword_model->get_login($this->_uid());
        if (!$user) {
            redirect('login', 'refresh');
        }

        $data['username'] = $user->mobile;   // display only, cannot be changed here

        $this->load->view('header');
        $this->load->view('change_password', $data);
        $this->load->view('footer');
    }

    // ---------------------------------------------------------
    // AJAX: change password only
    // ---------------------------------------------------------
    public function update_account()
    {
        $user = $this->ChangePassword_model->get_login($this->_uid());
        if (!$user) {
            return $this->_json(false, 'Session expired. Please log in again.');
        }

        // do not xss-clean passwords, it can change them
        $current = (string) $this->input->post('current_password');
        $new     = (string) $this->input->post('new_password');
        $confirm = (string) $this->input->post('confirm_password');

        if ($current === '') {
            return $this->_json(false, 'Please enter your current password.');
        }
        if (!$this->ChangePassword_model->verify_password($current, $user->password)) {
            return $this->_json(false, 'Current password is incorrect.');
        }

        if ($new === '') {
            return $this->_json(false, 'Please enter a new password.');
        }
        if (strlen($new) < 8 || !preg_match('/[A-Z]/', $new) || !preg_match('/[a-z]/', $new)
            || !preg_match('/\d/', $new) || !preg_match('/[^A-Za-z0-9]/', $new)) {
            return $this->_json(false, 'New password does not meet all the rules.');
        }
        if ($new === $current) {
            return $this->_json(false, 'New password must be different from the current password.');
        }
        if ($new !== $confirm) {
            return $this->_json(false, 'New password and confirm password do not match.');
        }

        // same hashing as the existing login
        if (!$this->ChangePassword_model->save_password($user->user_id, md5($new))) {
            return $this->_json(false, 'Could not update. Please try again.');
        }

        return $this->_json(true, 'Password updated successfully.');
    }

    // ---------------------------------------------------------
    private function _json($ok, $msg)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => (bool) $ok,
                'msg'    => $msg,
                'csrf'   => $this->security->get_csrf_hash()
            )));
    }
}