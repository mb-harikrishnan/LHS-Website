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



  public function fetch_all_latest_news()
   {
      $sql = "SELECT * FROM school_news WHERE c_status='Y'";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }


  public function fetch_general_information()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='general_information' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_affiliation()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_affiliation' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_societies()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_societies' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function NOC()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='NOC' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_recognition()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_recognition' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_safty()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_safty' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_fire_and_safety()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_fire_and_safety' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function DEO()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='DEO' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function sanitation()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='sanitation' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function land()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='land' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }


   



}