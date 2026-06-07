<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vacancy_model extends CI_Model
{


public function get_all_vacancy($from_date , $to_date)
{
  

    $sql="SELECT * FROM school_vacancy WHERE c_status='Y' AND d_date BETWEEN '$from_date' AND '$to_date' 
    ORDER BY n_slno DESC";
    $query = $this->db->query($sql);

    return $query->result();



}



  public function delete_vacancy($id)
    {
        $this->db->where('n_slno', $id);

        return $this->db->update('school_vacancy', array(
            'c_status' => 'D'
        ));
    }


}