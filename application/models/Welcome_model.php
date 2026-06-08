<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome_model extends CI_Model
{


   public function fetch_all_sliders()
   {
      $sql = "SELECT * FROM school_sliders WHERE c_status='A'";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }


   public function fetch_all_homepage_video()
   {
      $sql = "SELECT * 
               FROM infrastructure_videos 
               WHERE c_status = 'Y' 
               AND c_type = 'infrastructure'
               ORDER BY 
                  CASE 
                     WHEN links IS NOT NULL 
                     AND links != '' 
                     THEN 0 
                     ELSE 1 
                  END
               LIMIT 1";

      $query = $this->db->query($sql);
      return $query->result();
   }



}