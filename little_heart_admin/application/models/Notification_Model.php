<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notification_Model extends CI_Model
{


   public function get_all_notification()
   {

      $sql="SELECT * FROM investment_notifications WHERE c_status='Y' ORDER BY d_date DESC ";  
      $query = $this->db->query($sql);
      return $query->result();

   }



}