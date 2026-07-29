<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubjectController extends CI_Controller {


   
    public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
        $this->load->library('form_validation');
		$this->load->helper('date');
        $this->load->model('Subject_Model');


        if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('member_login', 'refresh');
		}

			
	}



    public function add_subject()
    {

    
        $data['classes'] = $this->Subject_Model->fetch_all_class();
        $data['exams'] = $this->Subject_Model->fetch_all_exams();
        $data['subjects'] = $this->Subject_Model->fetch_all_subjects();

        $this->load->view('members_area/header');
        $this->load->view('members_area/add_subject',$data);
        $this->load->view('members_area/footer');

    }


    public function add_class()
    {


        $this->load->view('members_area/header');
        $this->load->view('members_area/add_class');
        $this->load->view('members_area/footer');

    }

public function check_class_name()
{
    $class_name = strtoupper(trim($this->input->post('class_name')));

    // Convert 1-12 to Roman numerals
    $roman = [
        '1'  => 'I',
        '2'  => 'II',
        '3'  => 'III',
        '4'  => 'IV',
        '5'  => 'V',
        '6'  => 'VI',
        '7'  => 'VII',
        '8'  => 'VIII',
        '9'  => 'IX',
        '10' => 'X',
        '11' => 'XI',
        '12' => 'XII'
    ];

    if (isset($roman[$class_name])) {
        $class_name = $roman[$class_name];
    }

    // Allow only these text values
    $allowed = ['LKG', 'UKG', 'PLAY GROUP', 'PLAYGROUP'];

    // If input is not Roman numeral and not allowed text
    if (!in_array($class_name, $roman) && !in_array($class_name, $allowed)) {
        echo json_encode([
            'status'  => 'invalid',
            'message' => 'Only classes 1-12, LKG, UKG and PLAY GROUP are allowed.'
        ]);
        return;
    }

    // Duplicate check
    $count = $this->db
        ->where('cmName', $class_name)
        ->count_all_results('class_master');

    if ($count > 0) {
        echo json_encode([
            'status' => 'exists'
        ]);
    } else {
        echo json_encode([
            'status' => 'available'
        ]);
    }
}








public function insert_class()
{
    $class_name = strtoupper(trim($this->input->post('class_name')));

    // Convert 1-12 to Roman numerals
    $roman = [
        '1'  => 'I',
        '2'  => 'II',
        '3'  => 'III',
        '4'  => 'IV',
        '5'  => 'V',
        '6'  => 'VI',
        '7'  => 'VII',
        '8'  => 'VIII',
        '9'  => 'IX',
        '10' => 'X',
        '11' => 'XI',
        '12' => 'XII'
    ];

    if (isset($roman[$class_name])) {
        $class_name = $roman[$class_name];
    }

    // Allow only I-XII, LKG, UKG, PLAY GROUP
    $allowed = array_merge(array_values($roman), ['LKG', 'UKG', 'PLAY GROUP']);

    if (!in_array($class_name, $allowed)) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Only classes I-XII, LKG, UKG and PLAY GROUP are allowed.'
        ]);
        return;
    }

    // Check duplicate
    $exists = $this->db
        ->where('cmName', $class_name)
        ->count_all_results('class_master');

    if ($exists > 0) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Class already exists.'
        ]);
        return;
    }

    // Insert
    $insert = $this->db->insert('class_master', [
        'cmName' => $class_name
    ]);

    if ($insert) {
        echo json_encode([
            'status'  => 'success',
            'message' => 'Class added successfully.'
        ]);
    } else {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Failed to add class.'
        ]);
    }
}




    public function add_division()
    {


        $this->load->view('members_area/header');
        $this->load->view('members_area/add_division');
        $this->load->view('members_area/footer');

    }






public function check_divition()
{
    $division_name = trim($this->input->post('division_name'));

    $count = $this->db
                  ->where('LOWER(dmName)', strtolower($division_name))
                  ->count_all_results('division_master');

    if($count > 0)
    {
        echo json_encode([
            'status'=>'exists'
        ]);
    }
    else
    {
        echo json_encode([
            'status'=>'available'
        ]);
    }
}






public function insert_divition()
{
    $division_name = trim($this->input->post('division_name'));

    if($division_name=="")
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'Division Name is required.'
        ]);
        return;
    }

    // Convert to title case (optional)
    $division_name = ucwords(strtolower($division_name));

    $exists = $this->db
                   ->where('LOWER(dmName)', strtolower($division_name))
                   ->count_all_results('division_master');

    if($exists > 0)
    {
        echo json_encode([
            'status'=>'error',
            'message'=>'Division already exists.'
        ]);
        return;
    }

    $this->db->insert('division_master',[
        'dmName'=>$division_name
    ]);

    echo json_encode([
        'status'=>'success',
        'message'=>'Division added successfully.'
    ]);
}








    public function add_student()
    {

        $data['class'] = $this->Subject_Model->fetch_all_class();
        $data['divition'] = $this->Subject_Model->fetch_all_division();
        $data['state'] = $this->Subject_Model->fetch_all_state();
        $data['country'] = $this->Subject_Model->fetch_all_country();

        $this->load->view('members_area/header');
        $this->load->view('members_area/add_student',$data);
        $this->load->view('members_area/footer');

    }


    public function check_admission_number()
{
    $admission_no = $this->input->post('admission_no');

    $count = $this->db
                  ->where('smAdmissionNo', $admission_no)
                  ->count_all_results('students_master');

    if ($count > 0) {
        echo "false";
    } else {
        echo "true";
    }
}





public function insert_student()
{



    $data = array(

        'smAdmissionNo' => $this->input->post('admission_no'),
        'smAadharNo' => $this->input->post('aadhar_no'),
        'smName' => $this->input->post('student_name'),
        'smGender' => $this->input->post('gender'),
        'smDOB' => $this->input->post('dob'),
        'smMobile' => $this->input->post('mobile'),
        'smClass' => $this->input->post('class'),
        'smDiv' => $this->input->post('division'),
        'smReligion' => $this->input->post('religion'),
        'smCaste' => $this->input->post('caste'),
        'smMotherTongue' => $this->input->post('mother_tongue'),
        'smAddress' => $this->input->post('address'),
        'smCountry' => $this->input->post('country'),
        'smState' => $this->input->post('state'),
        'smActive' => 1

    );

    if ($this->db->insert('students_master', $data)) {

        echo json_encode(array(
            'status' => 1,
            'message' => 'Student added successfully.'
        ));

    } else {

        echo json_encode(array(
            'status' => 0,
            'message' => 'Failed to save student.'
        ));

    }

}





   public function add_exam()
    {
        $data['term'] = $this->Subject_Model->fetch_term();
        $this->load->view('members_area/header');
        $this->load->view('members_area/add_exam',$data);
        $this->load->view('members_area/footer');
    }


public function check_exam_name()
{
    $exam_name = trim($this->input->post('exam_name'));

    if ($exam_name == '') {
        echo json_encode([
            'status' => 'invalid',
            'message' => 'Exam Name is required.'
        ]);
        return;
    }

    $search = strtolower(str_replace(' ', '', $exam_name));

    $query = $this->db->query("
        SELECT emId
        FROM exam_master
        WHERE REPLACE(LOWER(emDisplayName), ' ', '') = ?
    ", array($search));

    if ($query->num_rows() > 0) {
        echo json_encode([
            'status' => 'exists',
            'message' => 'Exam Name already exists.'
        ]);
    } else {
        echo json_encode([
            'status' => 'success'
        ]);
    }
}
public function check_abbreviation()
{
    $abbreviation = trim($this->input->post('abbreviation'));

    if ($abbreviation == '') {
        echo json_encode([
            'status' => 'invalid',
            'message' => 'Abbreviation is required.'
        ]);
        return;
    }

    $search = strtolower(str_replace(' ', '', $abbreviation));

    $query = $this->db->query(
        "SELECT emId
         FROM exam_master
         WHERE REPLACE(LOWER(emName), ' ', '') = ?",
        array($search)
    );

    if ($query->num_rows() > 0) {
        echo json_encode([
            'status' => 'exists',
            'message' => 'Abbreviation already exists.'
        ]);
    } else {
        echo json_encode([
            'status' => 'success'
        ]);
    }
}


public function insert_exam()
{
    $exam_name     = trim($this->input->post('exam_name'));
    $abbreviation  = trim($this->input->post('abbreviation'));
    $term_id       = $this->input->post('term_id');

    $is_opened     = $this->input->post('is_opened') ? 1 : 0;
    $is_ongoing    = $this->input->post('is_ongoing') ? 1 : 0;
    $is_grade      = $this->input->post('is_id_grade') ? 1 : 0;
    $active        = $this->input->post('active') ? 1 : 0;

    // Validation
    if ($exam_name == '' || $abbreviation == '' || $term_id == '') {
        echo json_encode([
            'status'  => 'error',
            'message' => 'All required fields must be filled.'
        ]);
        return;
    }

    // Check Exam Name (ignore spaces & case)
    $exam = strtolower(str_replace(' ', '', $exam_name));

    $checkExam = $this->db->query(
        "SELECT emId
         FROM exam_master
         WHERE REPLACE(LOWER(emDisplayName),' ','') = ?",
        array($exam)
    );

    if ($checkExam->num_rows() > 0) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Exam Name already exists.'
        ]);
        return;
    }

    // Check Abbreviation (ignore spaces & case)
    $abbr = strtolower(str_replace(' ', '', $abbreviation));

    $checkAbbr = $this->db->query(
        "SELECT emId
         FROM exam_master
         WHERE REPLACE(LOWER(emName),' ','') = ?",
        array($abbr)
    );

    if ($checkAbbr->num_rows() > 0) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Abbreviation already exists.'
        ]);
        return;
    }

    // Insert
    $data = array(
        'emDisplayName' => $exam_name,
        'emName'        => $abbreviation,
        'emtmId'          => $term_id,
        'emIsOpened'        => $is_opened,
        'emIsOngoing'     => $is_ongoing,
        'emIsGrade'       => $is_grade,
        'emActive'      => $active
    );

    if ($this->db->insert('exam_master', $data)) {

        echo json_encode([
            'status'  => 'success',
            'message' => 'Exam added successfully.'
        ]);

    } else {

        echo json_encode([
            'status'  => 'error',
            'message' => 'Failed to add exam.'
        ]);

    }
}



// public function save_exam_mark_details()
// {
//     $classId = $this->input->post('cmId');
//     $examId  = $this->input->post('emId');
//     $subjects = $this->input->post('smId');
//     $marks = $this->input->post('marks');

//     foreach($subjects as $subject)
//     {
//         $check = $this->db
//                 ->where('emdCmId',$classId)
//                 ->where('emdEmId',$examId)
//                 ->where('emdSmId',$subject)
//                 ->get('exam_master_detail');

//         if($check->num_rows()>0)
//         {
//             echo json_encode([
//                 "status"=>"error",
//                 "message"=>"Selected Class, Exam and Subject already exists."
//             ]);
//             return;
//         }
//     }

//     foreach($subjects as $subject)
//     {
//         $this->db->insert('exam_master_detail',[
//             'emdCmId'=>$classId,
//             'emdEmId'=>$examId,
//             'emdSmId'=>$subject,
//             'emdMaxMark'=>$marks
//         ]);
//     }

//     echo json_encode([
//         "status"=>"success",
//         "message"=>"Exam details added successfully."
//     ]);
// }



public function save_exam_mark_details()
{
    $classId  = $this->input->post('cmId');
    $examId   = $this->input->post('emId');
    $subjects = $this->input->post('smId');
    $marks    = $this->input->post('marks'); // associative array: [subjectId => mark]

    if (empty($classId) || empty($examId) || empty($subjects) || empty($marks)) {
        echo json_encode([
            "status"  => "error",
            "message" => "Invalid Data"
        ]);
        return;
    }

    // Validate: every selected subject must have a valid mark
    foreach ($subjects as $subject) {
        if (!isset($marks[$subject]) || !is_numeric($marks[$subject]) || $marks[$subject] <= 0) {
            echo json_encode([
                "status"  => "error",
                "message" => "Please provide a valid mark for every selected subject."
            ]);
            return;
        }
    }

    // Check duplicates
    foreach ($subjects as $subject) {
        $check = $this->db
                ->where('emdCmId', $classId)
                ->where('emdEmId', $examId)
                ->where('emdSmId', $subject)
                ->get('exam_master_detail');

        if ($check->num_rows() > 0) {
            echo json_encode([
                "status"  => "error",
                "message" => "Selected Class, Exam and Subject already exists."
            ]);
            return;
        }
    }

    // Insert with per-subject marks
    foreach ($subjects as $subject) {
        $this->db->insert('exam_master_detail', [
            'emdCmId'    => $classId,
            'emdEmId'    => $examId,
            'emdSmId'    => $subject,
            'emdMaxMark' => $marks[$subject]
        ]);
    }

    echo json_encode([
        "status"  => "success",
        "message" => "Exam details added successfully."
    ]);
}








    public function add_mark_entry()
    {

        $data['class'] = $this->Subject_Model->fetch_all_class();
        $data['divition'] = $this->Subject_Model->fetch_all_division();
        $data['exam'] = $this->Subject_Model->fetch_all_exam();
        $this->load->view('members_area/header');
        $this->load->view('members_area/add_mark_entry',$data);
        $this->load->view('members_area/footer');
    }






// public function getMarksEntry()
// {
//     $class_id    = $this->input->post('class_id');
//     $division_id = $this->input->post('division_id');
//     $exam_id     = $this->input->post('exam_id');

//     $students = $this->Subject_Model->getStudents($class_id, $division_id);
//     $subjects = $this->Subject_Model->getExamSubjects($class_id, $exam_id);

//     if (empty($students)) {
//         echo json_encode(['status' => 'info', 'message' => 'No students found for the selected Class / Division']);
//         return;
//     }

//     if (empty($subjects)) {
//         echo json_encode(['status' => 'info', 'message' => 'No subjects configured for the selected Class / Exam']);
//         return;
//     }

//     $marks = $this->Subject_Model->getExistingMarks($exam_id, $class_id, $division_id);

//     echo json_encode([
//         'status'   => 'success',
//         'students' => $students,
//         'subjects' => $subjects,
//         'marks'    => $marks   // <-- new
//     ]);
// }




public function getMarksEntry()
{
    $class_id    = $this->input->post('class_id');
    $division_id = $this->input->post('division_id');
    $exam_id     = $this->input->post('exam_id');

    $students = $this->Subject_Model->getStudents($class_id, $division_id);
    $subjects = $this->Subject_Model->getExamSubjects($class_id, $exam_id);
    $is_grade = $this->Subject_Model->fetch_grade($exam_id);



    if (empty($students)) {
        echo json_encode(['status' => 'info', 'message' => 'No students found for the selected Class / Division']);
        return;
    }

    if (empty($subjects)) {
        echo json_encode(['status' => 'info', 'message' => 'No subjects configured for the selected Class / Exam']);
        return;
    }

    $marks    = $this->Subject_Model->getExistingMarks($exam_id, $class_id, $division_id);
    $maxMarks = $this->Subject_Model->getSubjectMaxMarks($exam_id, $class_id); // <-- new

    echo json_encode([
        'status'    => 'success',
        'students'  => $students,
        'subjects'  => $subjects,
        'marks'     => $marks,
        'maxMarks'  => $maxMarks ,
        'isGrade'   => $is_grade

    ]);
}




public function students_list()
{

    $data['details'] = $this->Subject_Model->fetch_all_student_details();
   
    $this->load->view('members_area/header');
    $this->load->view('members_area/students_list',$data);
    $this->load->view('members_area/footer');

}


public function Marksentry_list()
{
    $this->db->select('
        exam_summary.esId,
        exam_summary.esCmId,
        exam_summary.esEmId,
        exam_summary.esDmId,
        exam_master.emName,
        class_master.cmName,
        division_master.dmName
    ');

    $this->db->from('exam_summary');

    $this->db->join('exam_master','exam_master.emId=exam_summary.esEmId');
    $this->db->join('class_master','class_master.cmId=exam_summary.esCmId');
    $this->db->join('division_master','division_master.dmId=exam_summary.esDmId');

    $this->db->group_by(array(
        'esEmId',
        'esCmId',
        'esDmId'
    ));

    $data['details']=$this->db->get()->result();

        $this->load->view('members_area/header');
    $this->load->view('members_area/marksentry_list',$data);
    $this->load->view('members_area/footer');
}






// public function view_marks_students($exam, $class, $division)
// {
//     // Subjects
//     $this->db->select("exam_summary.esSmId, subject_master.smName");
//     $this->db->from("exam_summary");
//     $this->db->join("subject_master", "subject_master.smId = exam_summary.esSmId");
//     $this->db->where("exam_summary.esEmId", $exam);
//     $this->db->where("exam_summary.esCmId", $class);
//     $this->db->where("exam_summary.esDmId", $division);
//     $this->db->group_by("exam_summary.esSmId");

//     $data['subjects'] = $this->db->get()->result();
//     $this->db->reset_query();

//     // Max marks per subject (from exam_master_detail)
//     $this->db->select("emdSmId, emdMaxMark");
//     $this->db->from("exam_master_detail");
//     $this->db->where("emdEmId", $exam);
//     $this->db->where("emdCmId", $class);

//     $maxMarkResult = $this->db->get()->result();
//     $this->db->reset_query();

//     $maxMarks = [];
//     foreach ($maxMarkResult as $row) {
//         $maxMarks[$row->emdSmId] = $row->emdMaxMark;
//     }
//     $data['maxMarks'] = $maxMarks;

//     // Students
//     $this->db->select("smId, smAdmissionNo, smName");
//     $this->db->from("students_master");
//     $this->db->where("smClass", $class);
//     $this->db->where("smDiv", $division);
//     $this->db->order_by("smName", "asc");

//     $data['students'] = $this->db->get()->result();
//     $this->db->reset_query();

//     // Marks (existing ones only)
//     $this->db->select("
//         exam_detail.edSmId AS student_id,
//         exam_summary.esSmId AS subject_id,
//         exam_detail.edMark
//     ");
//     $this->db->from("exam_detail");
//     $this->db->join("exam_summary", "exam_summary.esId = exam_detail.edEsId");
//     $this->db->where("exam_summary.esEmId", $exam);
//     $this->db->where("exam_summary.esCmId", $class);
//     $this->db->where("exam_summary.esDmId", $division);

//     $result = $this->db->get()->result();

//     $marks = [];
//     foreach ($result as $row) {
//         $marks[$row->student_id][$row->subject_id] = $row->edMark;
//     }



//      $sql = "SELECT emIsGrade FROM exam_master WHERE emId = ?";
//     $query = $this->db->query($sql, [$exam]);
//     $results = $query->row();

//     // Return a plain scalar 0 or 1, never the row object
//     if ($results === null) {
//        $grade = 0;
//     }

//     $grade =$results->emIsGrade;

//     $data['exam']     = $exam;
//     $data['class']    = $class;
//     $data['division'] = $division;
//     $data['marks']    = $marks;
//     $data['isGrade']  = $grade;

//     $this->load->view('members_area/header');
//     $this->load->view('members_area/view_marks', $data);
//     $this->load->view('members_area/footer');
// }



public function view_marks_students($exam, $class, $division)
{
    // Subjects
    $this->db->select("exam_summary.esSmId, subject_master.smName");
    $this->db->from("exam_summary");
    $this->db->join("subject_master", "subject_master.smId = exam_summary.esSmId");
    $this->db->where("exam_summary.esEmId", $exam);
    $this->db->where("exam_summary.esCmId", $class);
    $this->db->where("exam_summary.esDmId", $division);
    $this->db->group_by("exam_summary.esSmId");

    $data['subjects'] = $this->db->get()->result();
    $this->db->reset_query();

    // Max marks per subject (from exam_master_detail)
    $this->db->select("emdSmId, emdMaxMark");
    $this->db->from("exam_master_detail");
    $this->db->where("emdEmId", $exam);
    $this->db->where("emdCmId", $class);

    $maxMarkResult = $this->db->get()->result();
    $this->db->reset_query();

    $maxMarks = [];
    foreach ($maxMarkResult as $row) {
        $maxMarks[$row->emdSmId] = $row->emdMaxMark;
    }
    $data['maxMarks'] = $maxMarks;

    // Students
    $this->db->select("smId, smAdmissionNo, smName");
    $this->db->from("students_master");
    $this->db->where("smClass", $class);
    $this->db->where("smDiv", $division);
    $this->db->order_by("smName", "asc");

    $data['students'] = $this->db->get()->result();
    $this->db->reset_query();

    // Marks (existing ones only)
    $this->db->select("
        exam_detail.edSmId AS student_id,
        exam_summary.esSmId AS subject_id,
        exam_detail.edMark
    ");
    $this->db->from("exam_detail");
    $this->db->join("exam_summary", "exam_summary.esId = exam_detail.edEsId");
    $this->db->where("exam_summary.esEmId", $exam);
    $this->db->where("exam_summary.esCmId", $class);
    $this->db->where("exam_summary.esDmId", $division);

    $result = $this->db->get()->result();

    $marks = [];
    foreach ($result as $row) {
        $marks[$row->student_id][$row->subject_id] = $row->edMark;
    }

    $sql = "SELECT emIsGrade FROM exam_master WHERE emId = ?";
    $query = $this->db->query($sql, [$exam]);
    $results = $query->row();

    // Return a plain scalar 0 or 1, never the row object
    $grade = ($results === null) ? 0 : (int) $results->emIsGrade;

    $data['exam']     = $exam;
    $data['class']    = $class;
    $data['division'] = $division;
    $data['marks']    = $marks;
    $data['isGrade']  = $grade;

    $this->load->view('members_area/header');
    $this->load->view('members_area/view_marks', $data);
    $this->load->view('members_area/footer');
}








public function updateMarks()
{
    if ($this->input->method() !== 'post') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        return;
    }

    $exam     = $this->input->post('exam');
    $class    = $this->input->post('class');
    $division = $this->input->post('division');
    $marks    = $this->input->post('marks'); // array of {student_id, subject_id, mark}

    if (empty($marks) || !is_array($marks) || !$exam || !$class || !$division) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required data']);
        return;
    }

    $result = $this->Subject_Model->updateMarks($exam, $class, $division, $marks);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Marks updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update marks']);
    }
}


































public function edit_students($id)
{
    $data['students']  = $this->db->where('smId', $id)->get('students_master')->row();
    $data['class']     = $this->db->get('class_master')->result();
    $data['divition']  = $this->db->get('division_master')->result();
    $data['country']   = $this->db->get('country')->result();
    $data['state']     = $this->db->get('country_states')->result();

    $this->load->view('members_area/header');
    $this->load->view('members_area/edit_students', $data);
    $this->load->view('members_area/footer');
}


public function update_student()
{
    $id = $this->input->post('smId');

    $updateData = array(
        'smAdmissionNo'   => $this->input->post('admission_no'),
        'smAadharNo'      => $this->input->post('aadhar_no'),
        'smName'   => $this->input->post('student_name'),
        'smGender'         => $this->input->post('gender'),
        'smDOB'            => $this->input->post('dob'),
        'smMobile'         => $this->input->post('mobile'),
        'smClass'          => $this->input->post('class'),
        'smDiv'       => $this->input->post('division'),
        'smReligion'       => $this->input->post('religion'),
        'smCaste'          => $this->input->post('caste'),
        'smMotherTongue'  => $this->input->post('mother_tongue'),
        'smAddress'        => $this->input->post('address'),
        'smCountry'        => $this->input->post('country'),
        'smState'          => $this->input->post('state'),
    );


    $this->db->where('smId', $id);
    $updated = $this->db->update('students_master', $updateData);

    if ($updated) {
        echo json_encode(array('status' => 1, 'message' => 'Student updated successfully.'));
    } else {
        echo json_encode(array('status' => 0, 'message' => 'Something went wrong. Please try again.'));
    }
}







public function check_admission_number_edit()
{
    $admission_no = $this->input->post('admission_no');
    $smId         = $this->input->post('smId');

    $this->db->where('smAdmissionNo', $admission_no);

    // Exclude the current record so it doesn't flag its own admission number
    if (!empty($smId)) {
        $this->db->where('smId !=', $smId);
    }

    $exists = $this->db->get('students_master')->row();

    // jQuery remote validation expects: true = valid, false = invalid
    if ($exists) {
        echo json_encode(false); // admission_no belongs to a DIFFERENT record → invalid
    } else {
        echo json_encode(true);  // no conflict → valid
    }
}




  public function delete_students()
    {
        $id = $this->input->post('id');

        $result = $this->Subject_Model->delete_students($id);

        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
  public function delete_exam()
    {
        $id = $this->input->post('id');

        $result = $this->Subject_Model->delete_exam($id);

        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }








    public function exam_list()
    {


        $data['details'] = $this->Subject_Model->fetch_all_exam();
   
        $this->load->view('members_area/header');
        $this->load->view('members_area/exam_list',$data);
        $this->load->view('members_area/footer');

    }


    public function edit_exam($id)
{
    $data['exam'] = $this->Subject_Model->get_exam_list($id);
    $data['term'] = $this->Subject_Model->fetch_term();

    $this->load->view('members_area/header');
    $this->load->view('members_area/edit_exam',$data);
    $this->load->view('members_area/footer');
}



public function update_exam()
{
    $id = $this->input->post('id');

    $data = array(
        'emDisplayName' => trim($this->input->post('exam_name')),
        'emName'        => trim($this->input->post('abbreviation')),
        'emTmId'          => $this->input->post('term_id'),
        'emIsOpened'        => $this->input->post('is_opened') ? 1 : 0,
        'emIsOngoing'     => $this->input->post('is_ongoing') ? 1 : 0,
        'emIsGrade'       => $this->input->post('is_id_grade') ? 1 : 0,
        'emActive'      => $this->input->post('active') ? 1 : 0
    );

    $this->db->where('emId', $id);

    if ($this->db->update('exam_master', $data))
    {
        echo json_encode([
            'status'  => 'success',
            'message' => 'Exam updated successfully.'
        ]);
    }
    else
    {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Update failed.'
        ]);
    }
}





    public function allocation_list()
    {


        $data['details'] = $this->Subject_Model->fetch_all_allocation_list();
   
        $this->load->view('members_area/header');
        $this->load->view('members_area/allocation_list',$data);
        $this->load->view('members_area/footer');

    }





    public function saveMarksEntry()
{
    $class_id    = $this->input->post('class_id');
    $division_id = $this->input->post('division_id');
    $exam_id     = $this->input->post('exam_id');
    $marks       = $this->input->post('marks'); // array of {student_id, subject_id, mark}

    if (empty($class_id) || empty($division_id) || empty($exam_id) || empty($marks)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required data']);
        return;
    }

    $result = $this->Subject_Model->saveMarks($exam_id, $class_id, $division_id, $marks);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Marks saved successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save marks']);
    }
}





public function edit_marks($studentId, $examId)
{
    if ($this->input->method() === 'post') {
        $marks = $this->input->post('marks'); // marks[esId] = value
        $this->Subject_Model->save_marks($studentId, $examId, $marks);
        $this->session->set_flashdata('success', 'Marks updated successfully');
        redirect(base_url('Marksentry_list'));
        return;
    }

    $data['student']   = $this->Subject_Model->get_student($studentId);
    $data['exam']      = $this->Subject_Model->get_exam($examId);
    $data['subjects']  = $this->Subject_Model->get_exam_subjects_with_marks($studentId, $examId);
    $data['studentId'] = $studentId;
    $data['examId']    = $examId;

    $this->load->view('members_area/header');
    $this->load->view('members_area/edit_marks', $data);
    $this->load->view('members_area/footer');
}






public function check_exam_name_edit()
{
$id = $this->input->post('id');
$exam_name = $this->input->post('exam_name');

$this->db->where('emDisplayName', $exam_name);
$this->db->where('emId !=', $id);

$result = $this->db->get('exam_master');

if ($result->num_rows() > 0) {
    echo json_encode(['status' => 'exists']);
} else {
    echo json_encode(['status' => 'available']);
}
}





public function check_abbreviation_edit()
{
 $id = $this->input->post('id');
$abbreviation = $this->input->post('abbreviation');

$this->db->where('emName', $abbreviation);
$this->db->where('emId !=', $id);

$result = $this->db->get('exam_master');

if ($result->num_rows() > 0) {
    echo json_encode(['status' => 'exists']);
} else {
    echo json_encode(['status' => 'available']);
}
}




public function edit_allocation($emId,$cmId)
{
 

    $data['allocation']=$this->Subject_Model->get_allocation_details($emId,$cmId);
        $data['subjects']   = $this->Subject_Model->get_available_subjects($emId,$cmId);


    $this->load->view('members_area/header');
    $this->load->view('members_area/edit_allocation',$data);
    $this->load->view('members_area/footer');
}
// public function update_allocation()
// {
//     $ids   = $this->input->post('emdId');
//     $marks = $this->input->post('marks');

//     if(empty($ids) || empty($marks))
//     {
//         echo json_encode([
//             'status' => 'error',
//             'message' => 'Invalid Data'
//         ]);
//         return;
//     }

//     for($i = 0; $i < count($ids); $i++)
//     {
//         $this->db->where('emdId', $ids[$i]);
//         $this->db->update('exam_master_detail', [
//             'emdMaxMark' => $marks[$i]
//         ]);
//     }

//     echo json_encode([
//         'status' => 'success'
//     ]);
// }




public function update_allocation()
{
    $emId = $this->input->post('emId');
    $cmId = $this->input->post('cmId');

    $emdIds = $this->input->post('emdId'); // existing rows being edited
    $marks  = $this->input->post('marks');

    $newSmIds = $this->input->post('newSmId'); // subjects newly added on this page
    $newMarks = $this->input->post('newMarks');

    if (empty($emId) || empty($cmId)) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Invalid Data'
        ]);
        return;
    }

    // ---- Update existing allocation rows ----
    if (!empty($emdIds) && !empty($marks)) {
        for ($i = 0; $i < count($emdIds); $i++) {
            if ($emdIds[$i] === '' || $marks[$i] === '' || $marks[$i] <= 0) {
                continue;
            }
            $this->db->where('emdId', $emdIds[$i]);
            $this->db->update('exam_master_detail', [
                'emdMaxMark' => $marks[$i]
            ]);
        }
    }

    // ---- Insert newly added subjects ----
    if (!empty($newSmIds) && !empty($newMarks)) {
        for ($i = 0; $i < count($newSmIds); $i++) {

            if ($newSmIds[$i] === '' || $newMarks[$i] === '' || $newMarks[$i] <= 0) {
                continue;
            }

            // Guard against duplicates (e.g. double submit, or someone else
            // already allocated this subject in the meantime)
            $existing = $this->db->get_where('exam_master_detail', [
                'emdEmId' => $emId,
                'emdCmId' => $cmId,
                'emdSmId' => $newSmIds[$i]
            ])->row();

            if ($existing) {
                $this->db->where('emdId', $existing->emdId);
                $this->db->update('exam_master_detail', [
                    'emdMaxMark' => $newMarks[$i]
                ]);
            } else {
                $this->db->insert('exam_master_detail', [
                    'emdEmId'    => $emId,
                    'emdCmId'    => $cmId,
                    'emdSmId'    => $newSmIds[$i],
                    'emdMaxMark' => $newMarks[$i]
                ]);
            }
        }
    }

    echo json_encode([
        'status' => 'success'
    ]);
}



public function delete_allocation()
{
    $emdId = $this->input->post('emdId');

    if(empty($emdId) || !is_numeric($emdId))
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid Data'
        ]);
        return;
    }

    $deleted = $this->Subject_Model->delete_allocation_detail($emdId);

    if($deleted)
    {
        echo json_encode(['status' => 'success']);
    }
    else
    {
        echo json_encode([
            'status' => 'error',
            'message' => 'Record not found or already deleted'
        ]);
    }
}






public function update_exam_order()
{
    $order = $this->input->post('order');

    if (!empty($order))
    {
        foreach ($order as $row)
        {
            $this->db->where('emId', $row['id']);
            $this->db->update('exam_master', [
                'emDisplayOrder' => $row['displayOrder']
            ]);
        }
    }

    echo 1;
}





}