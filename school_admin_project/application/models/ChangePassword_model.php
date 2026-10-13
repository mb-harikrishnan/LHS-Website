<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePassword_model extends CI_Model
{
    const LOGIN_TABLE  = 'user_login';

    const EMP_TABLE    = 'employee_master';
    const EMP_LINK     = 'user_log_id';     // employee_master.user_log_id = user_login.user_id
    const EMP_PASSWORD = 'emPassword';

    // one active login row
    public function get_login($user_id)
    {
        return $this->db
            ->where('user_id', (int) $user_id)
            ->where('status', 1)
            ->get(self::LOGIN_TABLE)
            ->row();
    }

    // typed password vs stored: md5 (employees) or plain text (old admin row)
    public function verify_password($input, $stored)
    {
        $stored = (string) $stored;
        if ($stored === '') {
            return false;
        }
        return hash_equals(strtolower($stored), md5($input))
            || hash_equals($stored, (string) $input);
    }

    // save the new password hash
    public function save_password($user_id, $hash)
    {
        $this->db->trans_start();

        // 1) user_login
        $this->db->where('user_id', (int) $user_id)
                 ->update(self::LOGIN_TABLE, array(
                     'password'   => $hash,
                     'updated_at' => date('Y-m-d H:i:s')
                 ));

        // 2) employee_master (only the row linked to this login, if any)
        $this->db->where(self::EMP_LINK, (int) $user_id)
                 ->update(self::EMP_TABLE, array(self::EMP_PASSWORD => $hash));

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }
}