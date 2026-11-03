<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_db extends CI_Model
{
    // returns one row object or NULL
    public function login_checking($username, $password)
    {
        return $this->db
            ->select('user_id, name, user_role_id,user_type ')
            ->where('name', $username)
            ->where('password', md5($password))   // keep md5 only to match your existing DB
            ->limit(1)
            ->get('user_login')
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