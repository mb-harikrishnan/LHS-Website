<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_Model extends CI_Model
{
    // Designation = role name from user_roles (emDesigId = role_id)
    public function get_employees()
    {
        return $this->db->select('e.*, c.cmName, d.dmName, r.role_name')
            ->from('employee_master e')
            ->join('class_master c', 'c.cmId = e.emClass', 'left')
            ->join('division_master d', 'd.dmId = e.emDiv', 'left')
            ->join('user_roles r', 'r.role_id = e.emDesigId', 'left')
            ->where('e.emActive', 1)
            ->order_by('e.emId', 'DESC')
            ->get()->result();
    }

    public function get_classes()
    {
        return $this->db->select('cmId, cmName')
                        ->order_by('cmId', 'ASC')
                        ->get('class_master')->result();
    }

    public function get_divisions($class_id)
    {
        return $this->db->select('dmId, dmName')
                        ->where('cmId', (int) $class_id)
                        ->order_by('dmName', 'ASC')
                        ->get('division_master')->result();
    }

    public function mobile_exists($mobile, $exclude_id = 0)
    {
        $this->db->where('emPhoneNo', $mobile)->where('emActive', 1);
        if ($exclude_id) $this->db->where('emId !=', (int) $exclude_id);
        return $this->db->count_all_results('employee_master') > 0;
    }

    public function employee_exists($id)
    {
        return $this->db->where('emId', (int) $id)->where('emActive', 1)
                        ->count_all_results('employee_master') > 0;
    }

    public function insert_employee($d, $password)
    {
        $this->db->trans_start();

        $this->db->insert('employee_master', [
            'emActive'   => 1,
            'emTS'       => date('Y-m-d H:i:s'),
            'emName'     => $d['name'],
            'emPassword' => md5($password),
            'emClass'    => $d['class_id'],
            'emDiv'      => $d['division_id'],
            'emPhoneNo'  => $d['mobile'],
            'emDesigId'  => $d['role_id'],
            'user_id'    => $d['role_id'],   // remove this line if employee_master has no user_id column
        ]);
        $emp_id = $this->db->insert_id();

        $this->db->insert('admin_login', [
            'employe_id' => $emp_id,
            'user_id'    => $d['role_id'],
            'c_username' => $d['name'],
            'c_password' => md5($password),
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update_employee($id, $d, $password = '')
    {
        $this->db->trans_start();

        $emp = [
            'emName'    => $d['name'],
            'emClass'   => $d['class_id'],
            'emDiv'     => $d['division_id'],
            'emPhoneNo' => $d['mobile'],
            'emDesigId' => $d['role_id'],
            'user_id'   => $d['role_id'],    // remove if no user_id column
        ];
        $login = [
            'user_id'    => $d['role_id'],
            'c_username' => $d['name'],
        ];
        if ($password !== '') {
            $emp['emPassword']   = md5($password);
            $login['c_password'] = md5($password);
        }

        $this->db->where('emId', (int) $id)->update('employee_master', $emp);
        $this->db->where('employe_id', (int) $id)->update('admin_login', $login);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete_employee($id)
    {
        $this->db->trans_start();
        $this->db->where('emId', (int) $id)->update('employee_master', ['emActive' => 0]);
        $this->db->where('employe_id', (int) $id)->delete('admin_login');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}