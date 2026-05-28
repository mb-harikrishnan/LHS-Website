<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_model extends CI_Model
{
    // 🔹 Check login
    public function check_login($username, $password)
    {
        // 🔹 Count check
        $this->db->select('COUNT(PC_USERNAME) AS count');
        $this->db->from('bc_login');
        $this->db->where('PC_USERNAME', $username);
        $this->db->where('C_PASSWORD', $password);

        $query  = $this->db->get();
        $result = $query->row(); // ✅ IMPORTANT

        if ($result->count == 1) {

            // 🔹 Fetch user details
            $this->db->select('pn_id, c_username');
            $this->db->from('bc_master');
            $this->db->where('c_username', $username);
            $this->db->where('c_distributor_active', 'Y');

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

    // 🔹 Login history (insert or update)
    public function login_history($user_id, $username, $ip_address)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('login_history');

        $data = array(
            'last_login' => date('Y-m-d H:i:s'),
            'ip_address' => $ip_address,
            'c_username' => $username
        );

        if ($query->num_rows() > 0) {
            // ✅ Update
            $this->db->where('user_id', $user_id);
            $this->db->update('login_history', $data);
        } else {
            // ✅ Insert
            $data['user_id'] = $user_id;
            $this->db->insert('login_history', $data);
        }
    }



    public function username_exists($username)
    {
        $this->db->where('c_username', $username);
        $this->db->where('c_distributor_active', 'Y');
        $query = $this->db->get('bc_master');

        return ($query->num_rows() > 0) ? true : false;
    }



































}



?>