<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Paper_model extends CI_Model
{


public function get_all_paper($from_date , $to_date)
{
  

    $sql="SELECT * FROM question_paper WHERE c_status='Y' AND d_date BETWEEN '$from_date' AND '$to_date' 
    ORDER BY n_slno DESC";
    $query = $this->db->query($sql);

    return $query->result();



}



  public function delete_vacancy($id)
{
    $this->db->where('n_slno', $id);

    return $this->db->update('question_paper', array(
        'c_status' => 'D'
    ));
}


}