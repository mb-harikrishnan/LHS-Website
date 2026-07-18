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





public function delete_students($id)
{
    $this->db->where('smId', $id);
    return $this->db->delete('students_master');
}
public function delete_exam($id)
{
    $this->db->where('emId', $id);
    return $this->db->delete('exam_master');
}



  public function fetch_all_exam()
  {

    $select = "SELECT * FROM exam_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
  }
  public function fetch_all_allocation_list()
  {

    $sql = "SELECT 
                emd.emdEmId,
                emd.emdCmId,
                cm.cmName,
                em.emDisplayName,
                GROUP_CONCAT(
                    CONCAT(sm.smName, ' (', emd.emdMaxMark, ')') 
                    ORDER BY sm.smDisplayOrder 
                    SEPARATOR ', '
                ) AS subjectsWithMarks
            FROM exam_master_detail emd
            JOIN class_master cm     ON cm.cmId = emd.emdCmId
            JOIN exam_master em      ON em.emId = emd.emdEmId
            JOIN subject_master sm   ON sm.smId = emd.emdSmId
            GROUP BY emd.emdEmId, emd.emdCmId
            ORDER BY emd.emdEmId DESC";

    return $this->db->query($sql)->result();
  }
  public function exam_list()
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
    $sql = "SELECT smId,smName FROM  exam_master_detail b , subject_master c 

    WHERE  b.emdSmId=c.smId AND emdCmId='$class' AND emdEmId ='$exam'
    
    
     ";

     $query = $this->db->query($sql);
     $result = $query->result();
     return $result;
}



public function fetch_all_student_details()
{

  $sql = " SELECT smId,smAdmissionNo,smAadharNo,smName,smClass,smDiv,smGender,smMobile,smDOB,smAddress,smReligion,smCaste,smMotherTongue,
             smCountry,smState FROM students_master  " ;

             $query = $this->db->query($sql);
             $result = $query->result();

             return $result;


}







  public function delete_allocation_list()
{
    $emId = $this->input->post('emId');
    $cmId = $this->input->post('cmId');

    $this->db->where('emdEmId', $emId);
    $this->db->where('emdCmId', $cmId);
    $result = $this->db->delete('exam_master_detail');

    echo $result ? "1" : "0";
}




public function saveMarks($exam_id, $class_id, $division_id, $marks)
{
    $this->db->trans_begin();

    // Cache esId per subject so we don't repeat the lookup/insert for every student
    $subjectEsIdMap = [];

    foreach ($marks as $row) {
        $subject_id = $row['subject_id'];
        $student_id = $row['student_id'];
        $mark       = $row['mark'];

        // 1. Get or create the exam_summary row for this subject
        if (!isset($subjectEsIdMap[$subject_id])) {

            $this->db->where([
                'esEmId' => $exam_id,
                'esCmId' => $class_id,
                'esDmId' => $division_id,
                'esSmId' => $subject_id
            ]);
            $existing = $this->db->get('exam_summary')->row();

            if ($existing) {
                $esId = $existing->esId;
            } else {
                $this->db->insert('exam_summary', [
                    'esEmId' => $exam_id,
                    'esCmId' => $class_id,
                    'esDmId' => $division_id,
                    'esSmId' => $subject_id
                ]);
                $esId = $this->db->insert_id();
            }

            $subjectEsIdMap[$subject_id] = $esId;
        }

        $esId = $subjectEsIdMap[$subject_id];

        // 2. Insert or update the exam_detail row for this student/subject
        $this->db->where([
            'edEsId' => $esId,
            'edSmId' => $student_id
        ]);
        $existingDetail = $this->db->get('exam_detail')->row();

        if ($existingDetail) {
            $this->db->where('edId', $existingDetail->edId);
            $this->db->update('exam_detail', ['edMark' => $mark]);
        } else {
            $this->db->insert('exam_detail', [
                'edEsId' => $esId,
                'edSmId' => $student_id,
                'edMark' => $mark
            ]);
        }
    }

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        return false;
    }

    $this->db->trans_commit();
    return true;
}









public function getExistingMarks($exam_id, $class_id, $division_id)
{
    // Find all exam_summary rows (i.e. subjects) for this exam/class/division
    $this->db->select('esId, esSmId');
    $this->db->where([
        'esEmId' => $exam_id,
        'esCmId' => $class_id,
        'esDmId' => $division_id
    ]);
    $summaries = $this->db->get('exam_summary')->result();

    if (empty($summaries)) {
        return [];
    }

    $esIds = array_map(function($s){ return $s->esId; }, $summaries);

    // Map esId -> subject_id so we can rebuild subject_id from edEsId
    $esToSubject = [];
    foreach ($summaries as $s) {
        $esToSubject[$s->esId] = $s->esSmId;
    }

    $this->db->select('edEsId, edSmId, edMark');
    $this->db->where_in('edEsId', $esIds);
    $details = $this->db->get('exam_detail')->result();

    $marks = [];
    foreach ($details as $d) {
        $subject_id = $esToSubject[$d->edEsId];
        $student_id = $d->edSmId;
        $marks[] = [
            'student_id' => $student_id,
            'subject_id' => $subject_id,
            'mark'       => $d->edMark
        ];
    }

    return $marks;
}



  


}