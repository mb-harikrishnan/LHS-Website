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

    // page
    public function change_password()
    {
        $user = $this->ChangePassword_model->get_login((int) $this->session->userdata('id'));
        if (!$user) {
            redirect('login', 'refresh');
        }

        $data['username'] = $user->c_username;

        $this->load->view('header');
        $this->load->view('change_password', $data);
        $this->load->view('footer');
    }

    // AJAX: change username and/or password
    public function update_account()
    {
        $uid  = (int) $this->session->userdata('id');
        $user = $this->ChangePassword_model->get_login($uid);
        if (!$user) {
            return $this->_json(false, 'Session expired. Please log in again.');
        }

        // do not xss-clean passwords, it can change them
        $username = trim((string) $this->input->post('username'));
        $current  = (string) $this->input->post('current_password');
        $new      = (string) $this->input->post('new_password');
        $confirm  = (string) $this->input->post('confirm_password');

        if ($current === '') {
            return $this->_json(false, 'Please enter your current password.');
        }

        // Employee login (employe_id set and no parent) -> password is in the employee master
        $use_emp = !empty($user->employe_id) && empty($user->parent_id);

        // 1) validate current password
        $stored = $this->ChangePassword_model->get_stored_hash($user, $use_emp);
        if ($stored === null) {
            return $this->_json(false, 'Account record not found.');
        }
        if (!hash_equals(strtolower($stored), md5($current))) {
            return $this->_json(false, 'Current password is incorrect.');
        }

        // 2) username
        $change_user = ($username !== $user->c_username);
        if ($change_user) {
            if (!preg_match('/^[A-Za-z0-9._@-]{3,50}$/', $username)) {
                return $this->_json(false, 'Username must be 3-50 characters (letters, numbers, . _ @ - only).');
            }
            if ($this->ChangePassword_model->username_exists($username, $uid)) {
                return $this->_json(false, 'This username is already taken.');
            }
        }

        // 3) new password (optional)
        $new_hash = null;
        if ($new !== '') {
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
            $new_hash = md5($new);   // same hashing as the existing login
        }

        if (!$change_user && $new_hash === null) {
            return $this->_json(false, 'Nothing to update.');
        }

        // 4) save
        $ok = $this->ChangePassword_model->save(
            $user,
            $use_emp,
            $change_user ? $username : null,
            $new_hash
        );
        if (!$ok) {
            return $this->_json(false, 'Could not update. Please try again.');
        }

        $parts = array();
        if ($change_user)         $parts[] = 'Username';
        if ($new_hash !== null)   $parts[] = 'Password';
        return $this->_json(true, implode(' and ', $parts) . ' updated successfully.');
    }

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