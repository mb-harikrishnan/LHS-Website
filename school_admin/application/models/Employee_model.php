<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employee_model extends CI_Model
{


public function employee_list()
{
  

    $sql="SELECT * FROM employeemaster WHERE emActive=1 ";
    $query = $this->db->query($sql);

    return $query->result();



}



  public function delete_employee   ($id)
    {
        $this->db->where('emId', $id);

        return $this->db->update('employeemaster', array(
            'emActive' => '0'
        ));
    }


}