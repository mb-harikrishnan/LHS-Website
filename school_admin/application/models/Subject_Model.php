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


  public function get_exam_list($id)
{
    return $this->db
            ->where('emId',$id)
            ->get('exam_master')
            ->row();
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




// public function fetch_all_marksentry_list()
// {
//     $sql = "SELECT *
//      FROM exam_summary a , exam_detail b ,class_master c ,division_master d , subject_master e ,
//         exam_master f , students_master g
//             WHERE a.esId=b.edEsId  AND  c.cmId=a.esCmId AND d.dmId = a.esDmId  AND e.smId = a.esSmId  
//             AND f.emId = esEmId AND g.smId = b.edSmId
//     " ;

//     $query = $this->db->query($sql);
//     $result = $this->result();
//     return $result ;



// }


// public function fetch_all_marksentry_list()
// {
//     $sql = "SELECT g.smId AS studentId, g.smName AS studentName,
//                    c.cmName AS className, d.dmName AS divisionName,
//                    e.smName AS subjectName, b.edMark AS marks
//             FROM exam_summary a
//             JOIN exam_detail b ON a.esId = b.edEsId
//             JOIN class_master c ON c.cmId = a.esCmId
//             JOIN division_master d ON d.dmId = a.esDmId
//             JOIN subject_master e ON e.smId = a.esSmId
//             JOIN exam_master f ON f.emId = a.esEmId
//             JOIN students_master g ON g.smId = b.edSmId
//             ORDER BY c.cmName, d.dmName, g.smName";

//     $query = $this->db->query($sql);
//     return $query->result();
// }



public function fetch_all_marksentry_list($classId = '', $divId = '', $examId = '')
{
    $sql = "SELECT g.smId AS studentId, g.smName AS studentName,
                   c.cmId AS classId, c.cmName AS className,
                   d.dmId AS divId, d.dmName AS divisionName,
                   f.emId AS examId, f.emName AS examName,
                   e.smName AS subjectName, b.edMark AS marks
            FROM exam_summary a
            JOIN exam_detail b ON a.esId = b.edEsId
            JOIN class_master c ON c.cmId = a.esCmId
            JOIN division_master d ON d.dmId = a.esDmId
            JOIN subject_master e ON e.smId = a.esSmId
            JOIN exam_master f ON f.emId = a.esEmId
            JOIN students_master g ON g.smId = b.edSmId
            WHERE 1=1";

    // build filters conditionally, using query bindings to avoid SQL injection
    $params = [];

    if (!empty($classId)) {
        $sql .= " AND c.cmId = ?";
        $params[] = $classId;
    }
    if (!empty($divId)) {
        $sql .= " AND d.dmId = ?";
        $params[] = $divId;
    }
    if (!empty($examId)) {
        $sql .= " AND f.emId = ?";
        $params[] = $examId;
    }

    $sql .= " ORDER BY c.cmName, d.dmName, g.smName";

    $query = $this->db->query($sql, $params);
    return $query->result();
}

// for populating the dropdowns
public function get_classes()
{
    return $this->db->get('class_master')->result();
}

public function get_divisions()
{
    return $this->db->get('division_master')->result();
}

public function get_exams()
{
    return $this->db->get('exam_master')->result();
}










// public function get_exam_subjects_with_marks($studentId, $examId)
// {
//     $this->db->select('es.esId, es.esSmId, sm.smName, ed.edMark, ed.edId')
//              ->from('exam_summary es')
//              ->join('subject_master sm', 'sm.smId = es.esSmId')
//              ->join('exam_detail ed', 'ed.edEsId = es.esId AND ed.edSmId = ' . (int)$studentId, 'left')
//              ->where('es.esEmId', $examId)
//              ->order_by('sm.smName', 'ASC');

//     return $this->db->get()->result();
// }

public function get_exam_subjects_with_marks($studentId, $examId)
{
    $this->db->select('es.esId, es.esSmId, sm.smName, ed.edMark, ed.edId')
        ->from('exam_summary es')
        ->join('subject_master sm', 'sm.smId = es.esSmId')
        ->join('exam_detail ed',
            'ed.edEsId = es.esId  AND ed.edSmId = ' . (int)$studentId,
            'left')
        ->where('es.esEmId', $examId)
        ->order_by('sm.smName', 'ASC');

    return $this->db->get()->result();
}

public function get_student($studentId)
{
    return $this->db->select('st.smId, st.smName, cm.cmName AS className, dm.dmName AS divName')
        ->from('students_master st')
        ->join('class_master cm', 'cm.cmId = st.smClass', 'left')
        ->join('division_master dm', 'dm.dmId = st.smDiv', 'left')
        ->where('st.smId', $studentId)
        ->get()->row();
}

public function get_exam($examId)
{
    return $this->db->get_where('exam_master', ['emId' => $examId])->row();
}

public function save_marks($studentId, $examId, $marks)
{
    if (empty($marks)) return false;

    foreach ($marks as $esId => $markValue) {
        $existing = $this->db->get_where('exam_detail', [
            'edEsId'      => $esId,
            'edSmId' => $studentId
        ])->row();

        if ($existing) {
            $this->db->where('edId', $existing->edId)
                     ->update('exam_detail', ['edMark' => $markValue]);
        } else {
            $this->db->insert('exam_detail', [
                'edEsId'      => $esId,
                'edSmId'      => $this->db->select('esSmId')
                                            ->get_where('exam_summary', ['esId' => $esId])
                                            ->row()->esSmId,
                'edSmId' => $studentId,
                'edMark'      => $markValue
            ]);
        }
    }
    return true;
}







public function get_allocation_details($emId,$cmId)
{
    $this->db->select('
        exam_master_detail.emdId,
        exam_master_detail.emdEmId,
        exam_master_detail.emdCmId,
        exam_master_detail.emdSmId,
        exam_master_detail.emdMaxMark,
        class_master.cmName,
        exam_master.emDisplayName,
        subject_master.smName
    ');

    $this->db->from('exam_master_detail');
    $this->db->join('class_master','class_master.cmId=exam_master_detail.emdCmId');
    $this->db->join('exam_master','exam_master.emId=exam_master_detail.emdEmId');
    $this->db->join('subject_master','subject_master.smId=exam_master_detail.emdSmId');

    $this->db->where('emdEmId',$emId);
    $this->db->where('emdCmId',$cmId);

    return $this->db->get()->result();
}



public function update_allocation($oldCmId,$oldEmId,$cmId,$emId,$subjects,$marks)
{
    $this->db->where('emdCmId',$oldCmId);
    $this->db->where('emdEmId',$oldEmId);
    $this->db->delete('exam_master_detail');

    foreach($subjects as $subject)
    {
        $data = array(
            'emdCmId'    => $cmId,
            'emdEmId'    => $emId,
            'emdSmId'    => $subject,
            'emdMaxMark' => $marks
        );

        $this->db->insert('exam_master_detail',$data);
    }
}




public function updateMarks($exam, $class, $division, $marks)
{
    $this->db->trans_start();

    foreach ($marks as $row) {

        $studentId = $row['student_id']; // exam_detail.edSmId
        $subjectId = $row['subject_id']; // exam_summary.esSmId
        $markValue = $row['mark'];

        if ($studentId === '' || $subjectId === '' || $markValue === '') {
            continue;
        }

        // Find the exam_summary row (esId) for this exam + class + division + subject
        $this->db->select('esId');
        $this->db->from('exam_summary');
        $this->db->where('esEmId', $exam);
        $this->db->where('esCmId', $class);
        $this->db->where('esDmId', $division);
        $this->db->where('esSmId', $subjectId);
        $summaryRow = $this->db->get()->row();

        if (!$summaryRow) {
            // No matching exam_summary row exists for this subject/exam/class/division combo
            continue;
        }

        $esId = $summaryRow->esId;

        // Check if exam_detail already has this student + esId
        $existing = $this->db->get_where('exam_detail', [
            'edSmId' => $studentId,
            'edEsId' => $esId
        ])->row();

        if ($existing) {
            $this->db->where('edSmId', $studentId);
            $this->db->where('edEsId', $esId);
            $this->db->update('exam_detail', [
                'edMark' => $markValue
            ]);
        } else {
            $this->db->insert('exam_detail', [
                'edSmId' => $studentId,
                'edEsId' => $esId,
                'edMark' => $markValue
            ]);
        }
    }

    $this->db->trans_complete();

    return $this->db->trans_status();
}



  


}