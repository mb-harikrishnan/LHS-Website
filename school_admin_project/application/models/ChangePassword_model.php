<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePassword_model extends CI_Model
{
    // ---- CHANGE THESE to match your employee master table ----
    const EMP_TABLE    = 'employee_master';   // table name
    const EMP_PK       = 'emId';              // column that equals admin_login.employe_id
    const EMP_PASSWORD = 'emPassword';        // password column (from your screenshot)

    // one admin_login row (active only)
    public function get_login($id)
    {
        return $this->db
            ->where('sl_no', (int) $id)
            ->where('c_status', 'Y')
            ->get('admin_login')
            ->row();
    }

    // current password hash: employee master for employee logins, admin_login otherwise
    public function get_stored_hash($user, $use_emp)
    {
        if ($use_emp) {
            $row = $this->db
                ->select(self::EMP_PASSWORD . ' AS pw', FALSE)
                ->where(self::EMP_PK, $user->employe_id)
                ->get(self::EMP_TABLE)
                ->row();
            return $row ? (string) $row->pw : null;
        }
        return (string) $user->c_password;
    }

    public function username_exists($username, $except_id)
    {
        return $this->db
            ->where('c_username', $username)
            ->where('sl_no !=', (int) $except_id)
            ->count_all_results('admin_login') > 0;
    }

    // $username / $hash may be null (= leave unchanged)
    public function save($user, $use_emp, $username, $hash)
    {
        $this->db->trans_start();

        $login = array();
        if ($username !== null)              $login['c_username'] = $username;
        if ($hash !== null && !$use_emp)     $login['c_password'] = $hash;
        if ($login) {
            $this->db->where('sl_no', (int) $user->sl_no)->update('admin_login', $login);
        }

        if ($hash !== null && $use_emp) {
            $this->db->where(self::EMP_PK, $user->employe_id)
                     ->update(self::EMP_TABLE, array(self::EMP_PASSWORD => $hash));
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }
}