<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_db extends CI_Model
{
    // returns one row object or NULL
    public function login_checking($username, $password)
    {
        return $this->db
            ->select('sl_no, c_username, user_role_id')
            ->where('c_username', $username)
            ->where('c_password', md5($password))   // keep md5 only to match your existing DB
            ->limit(1)
            ->get('admin_login')
            ->row();
    }

    public function fetch_roles()
    {
        return $this->db->select('role_id, role_name')
                        ->where('status', 1)
                        ->get('user_roles')
                        ->result();
    }
}