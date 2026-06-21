<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Slider_model extends CI_Model
{


public function get_all_images()
{
  

    $sql="SELECT * FROM school_sliders WHERE c_status='A' 
    ORDER BY n_slno DESC";
    $query = $this->db->query($sql);

    return $query->result();



}



  public function delete_image($id)
    {
        $this->db->where('n_slno', $id);

        return $this->db->update('school_sliders', array(
            'c_status' => 'D'
        ));
    }


}