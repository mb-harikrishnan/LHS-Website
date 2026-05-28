<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_model extends CI_Model
{
    // 🔹 Check login
    public function check_login($username, $password)
    {
        $password = md5($password);
        // 🔹 Count check
        $this->db->select('COUNT(c_username) AS count');
        $this->db->from('admin_login');
        $this->db->where('c_username', $username);
        $this->db->where('c_password', $password);

        $query  = $this->db->get();
        $result = $query->row(); // ✅ IMPORTANT

        if ($result->count == 1) {

            // 🔹 Fetch user details
            $this->db->select('sl_no, c_username');
            $this->db->from('admin_login');
            $this->db->where('c_username', $username);

            $query2 = $this->db->get();

            if ($query2->num_rows() == 1) {
                return $query2->row(); // ✅ success
            } else {
                return false;
            }

        } else {
            return false; // ❌ invalid login
        }
    }

    public function username_exists($username)
    {
        $this->db->where('c_username', $username);
        $query = $this->db->get('admin_login');

        return ($query->num_rows() > 0) ? true : false;
    }



































}



?>