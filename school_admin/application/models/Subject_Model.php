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







  public function fetch_all_exam()
  {

    $select = "SELECT * FROM exam_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
  }



  public function getStudents($class,$division)
{
    return $this->db
            ->where('smClass',$class)
            ->where('smDiv',$division)
            ->order_by('smName')
            ->get('students_master')
            ->result();
}



public function getExamSubjects($class,$exam)
{
    $sql = "SELECT smId,smName FROM exam_master a , exam_master_detail b , subject_master c 

    WHERE a.emId=b.emdEmId AND b.emdSmId=c.smId AND emdCmId='$class' AND emdSmId ='$exam'
    
    
     ";

     $query = $this->db->query($sql);
     $result = $query->result();
     return $result;
}







  
  


}