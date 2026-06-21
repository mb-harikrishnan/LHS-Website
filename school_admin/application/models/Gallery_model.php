<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Gallery_model extends CI_Model
{


public function get_all_images($from_date , $to_date, $type = null)
{
   $qry='';
    if($type) {

    $qry.="AND c_type ='$type'";
    }

    $sql="SELECT * FROM school_event_images WHERE c_status='Y' AND d_date BETWEEN '$from_date' AND '$to_date'  $qry
    ORDER BY n_slno DESC";
    $query = $this->db->query($sql);

    return $query->result();



}



  public function delete_image($id)
    {
        $this->db->where('n_slno', $id);

        return $this->db->update('school_event_images', array(
            'c_status' => 'D'
        ));
    }


}