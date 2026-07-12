<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Subject_Model extends CI_Model
{


  public function fetch_all_class()
  {

    $select = "SELECT * FROM class_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
  }


  public function fetch_all_exams()
  {

    $select = "SELECT * FROM exam_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
  }
  public function fetch_all_subjects()
  {

    $select = "SELECT * FROM subject_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
  }


  

  public function fetch_all_country()
  {

    $select = "SELECT name,country_id FROM country ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;

  }
  public function fetch_all_state()
  {

    $select = "SELECT code,name FROM country_states WHERE country_id=99 ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;

  }




public function fetch_all_division()
{
    $select = "SELECT * FROM division_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
}















  
  


}