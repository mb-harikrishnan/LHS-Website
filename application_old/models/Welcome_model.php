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
  public function fee_structure()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='fee_structure' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function anual_academic_calendar()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='anual_academic_calendar' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function school_managment_comitte()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='school_managment_comitte' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function pta_members()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='pta_members' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function three_yers_board_exam()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='3_yers_board_exam' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function staff_details()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='staff_details' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function video()
   {
      $sql = "SELECT * FROM infrastructure_videos WHERE c_status='Y' AND c_type ='infrastructure' and links!='' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_news()
   {
      $sql = "SELECT * FROM school_news WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_Q_paper()
   {
      $sql = "SELECT * FROM question_paper WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_certificates()
   {
      $sql = "SELECT * FROM transfer_certificate WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_images()
   {
      $sql = "SELECT * FROM school_event_images WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_types()
   {
      $sql = "SELECT distinct(c_type) as c_type FROM school_event_images WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_vacancy()
   {
      $sql = "SELECT * FROM school_vacancy WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_co_images()
   {
      $sql = "SELECT * FROM co_curricular_activities WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }

   



}