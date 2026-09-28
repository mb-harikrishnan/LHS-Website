<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employee_model extends CI_Model
{


public function employee_list()
{
  

    $sql="SELECT * FROM employee_master WHERE emActive=1 ";
    $query = $this->db->query($sql);

    return $query->result();



}



  public function delete_employee   ($id)
    {
        $this->db->where('emId', $id);

        return $this->db->update('employee_master', array(
            'emActive' => '0'
        ));
    }


}