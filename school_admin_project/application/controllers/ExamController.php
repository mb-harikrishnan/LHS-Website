<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExamController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    $this->load->model('Exam_Model');

     if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
  }


  ////////////////////////////////////

/* ---------- LIST ---------- */
public function exam_list()
{
    $data['exams'] = $this->Exam_Model->fetch_all_exams();
    $data['terms'] = $this->Exam_Model->fetch_term();

    $this->load->view('header');
    $this->load->view('exam_list', $data);
    $this->load->view('footer');
}

/* ---------- ADD + EDIT (POST) ---------- */
public function save_exam()
{
    if ($this->input->method() !== 'post') {
        return $this->_exam_json(false, 'Invalid request.');
    }

    $id      = (int)$this->input->post('id');
    $is_edit = $id > 0;

    $name    = trim(preg_replace('/\s+/', ' ', (string)$this->input->post('name')));   // full name -> emDisplayName
    $abbr    = trim(preg_replace('/\s+/', ' ', (string)$this->input->post('abbr')));   // short name -> emName
    $term_id = (int)$this->input->post('term_id');

    if ($name === '')            return $this->_exam_json(false, 'Please enter the exam name.');
    if (mb_strlen($name) > 100)  return $this->_exam_json(false, 'Exam name must be 100 characters or fewer.');
    if ($abbr === '')            return $this->_exam_json(false, 'Please enter the abbreviation.');
    if (mb_strlen($abbr) > 20)   return $this->_exam_json(false, 'Abbreviation must be 20 characters or fewer.');
    if ($term_id <= 0 || !$this->Exam_Model->term_exists($term_id)) {
        return $this->_exam_json(false, 'Please select a valid term.');
    }
    if ($this->Exam_Model->abbr_exists($abbr, $id)) {
        return $this->_exam_json(false, 'This abbreviation already exists.');
    }

    $row = array(
        'emName'        => $abbr,
        'emDisplayName' => $name,
        'emTmId'        => $term_id,
        'emIsOpened'    => $this->input->post('opened')  ? 1 : 0,
        'emIsOngoing'   => $this->input->post('ongoing') ? 1 : 0,
        'emIsGrade'     => $this->input->post('grade')   ? 1 : 0,
        'emActive'      => $this->input->post('active')  ? 1 : 0
    );

    if ($is_edit) {
        if (!$this->Exam_Model->get_exam($id)) {
            return $this->_exam_json(false, 'Exam not found.');
        }
        $ok = $this->Exam_Model->update_exam($id, $row);
        return $this->_exam_json($ok, $ok ? 'Exam updated successfully.' : 'Update failed.');
    }

    $row['emStatus']       = 1;
    $row['emDisplayOrder'] = $this->Exam_Model->next_order();
    $ok = $this->Exam_Model->insert_exam($row);
    return $this->_exam_json($ok, $ok ? 'Exam added successfully.' : 'Could not save. Please try again.');
}
/* ---------- DELETE (POST) ---------- */
public function delete_exam()
{
    if ($this->input->method() !== 'post') {
        return $this->_exam_json(false, 'Invalid request.');
    }

    $id = (int)$this->input->post('id');
    if ($id <= 0 || !$this->Exam_Model->get_exam($id)) {
        return $this->_exam_json(false, 'Exam not found.');
    }

    $ok = $this->Exam_Model->delete_exam($id);
    return $this->_exam_json($ok, $ok ? 'Exam deleted successfully.' : 'Delete failed.');
}

private function _exam_json($status, $msg)
{
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'status' => (bool)$status,
            'msg'    => $msg,
            'csrf'   => $this->security->get_csrf_hash()
        )));
}



  ////////////////////////////////////////

 /* ---------- LIST ---------- */
public function allocation_list()
{
    $data['details']  = $this->Exam_Model->fetch_all_allocation_list();
    $data['classes']  = $this->Exam_Model->fetch_all_class();
    $data['exams']    = $this->Exam_Model->fetch_open_exams();
    $data['subjects'] = $this->Exam_Model->fetch_all_subjects();

    $this->load->view('header');
    $this->load->view('allocation_list', $data);
    $this->load->view('footer');
}

/* ---------- ADD + EDIT (POST) ---------- */
public function save_allocation()
{
    if ($this->input->method() !== 'post') {
        return $this->_exam_json(false, 'Invalid request.');
    }

    $exam_id   = (int)$this->input->post('exam_id');
    $class_id  = (int)$this->input->post('class_id');
    $old_exam  = (int)$this->input->post('old_exam');
    $old_class = (int)$this->input->post('old_class');
    $is_edit   = ($old_exam > 0 && $old_class > 0);

    $list = json_decode((string)$this->input->post('subjects'), true);

    if ($exam_id <= 0 || !$this->Exam_Model->get_exam($exam_id)) {
        return $this->_exam_json(false, 'Please select a valid exam.');
    }
    if ($class_id <= 0 || !$this->Exam_Model->class_exists($class_id)) {
        return $this->_exam_json(false, 'Please select a valid class.');
    }
    if (!is_array($list) || empty($list)) {
        return $this->_exam_json(false, 'Please select at least one subject.');
    }

    $subjects = array();
    $seen     = array();
    foreach ($list as $s) {
        $sid   = isset($s['id']) ? (int)$s['id'] : 0;
        $marks = isset($s['marks']) ? $s['marks'] : '';
        if ($sid <= 0 || isset($seen[$sid])) {
            return $this->_exam_json(false, 'Invalid subject selection.');
        }
        if (!ctype_digit((string)$marks) || (int)$marks <= 0 || (int)$marks > 1000) {
            return $this->_exam_json(false, 'Enter valid marks for every subject.');
        }
        $seen[$sid] = true;
        $subjects[] = array('id' => $sid, 'marks' => (int)$marks);
    }
    if (!$this->Exam_Model->subjects_exist(array_keys($seen))) {
        return $this->_exam_json(false, 'One or more subjects are invalid.');
    }

    // same exam + class already has an allocation (unless it is the one being edited)
    $same_pair = $is_edit && $old_exam === $exam_id && $old_class === $class_id;
    if (!$same_pair && $this->Exam_Model->allocation_exists($exam_id, $class_id)) {
        return $this->_exam_json(false, 'This class and exam already have an allocation. Edit it from the list.');
    }

    $ok = $this->Exam_Model->save_allocation($exam_id, $class_id, $subjects, $is_edit ? $old_exam : 0, $is_edit ? $old_class : 0);
    return $this->_exam_json($ok, $ok
        ? ($is_edit ? 'Mark allocation updated successfully.' : 'Mark allocation added successfully.')
        : 'Could not save. Please try again.');
}

/* ---------- DELETE (POST) ---------- */
public function delete_allocation()
{
    if ($this->input->method() !== 'post') {
        return $this->_exam_json(false, 'Invalid request.');
    }

    $exam_id  = (int)$this->input->post('exam_id');
    $class_id = (int)$this->input->post('class_id');

    if ($exam_id <= 0 || $class_id <= 0 || !$this->Exam_Model->allocation_exists($exam_id, $class_id)) {
        return $this->_exam_json(false, 'Allocation not found.');
    }

    $ok = $this->Exam_Model->delete_allocation($exam_id, $class_id);
    return $this->_exam_json($ok, $ok ? 'Mark allocation deleted successfully.' : 'Delete failed.');
}



















////////////////////////////////////////////
/* allowed grades for grade-based exams */
private $grades = array('A+', 'A', 'B+', 'B', 'C+', 'C', 'D', 'E');

/* role, permission, and (for non-admin) the user's own class + division */
private function _marks_scope()
{
    $role = (int)$this->session->userdata('user_role_id');
    $uid  = (int)$this->session->userdata('user_id');

    $menu = $this->Exam_Model->get_menu_id_by_link('Marksentry_list');
    $perm = $this->Exam_Model->get_permissions($role, $menu);

    $scope = array(
        'is_admin' => ($role === 1),
        'perm'     => is_array($perm) ? $perm : array(),
        'assigned' => true,
        'class'    => '',
        'division' => ''
    );

    if (!$scope['is_admin']) {
        $emp = $this->Exam_Model->get_employee_class_div($uid);
        if ($emp && !empty($emp->emClass) && !empty($emp->emDiv)) {
            $scope['class']    = (string)$emp->emClass;
            $scope['division'] = (string)$emp->emDiv;
        } else {
            $scope['assigned'] = false;
        }
    }
    return $scope;
}

private function _marks_json($status, $msg, $extra = array())
{
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array_merge(array(
            'status' => (bool)$status,
            'msg'    => $msg,
            'csrf'   => $this->security->get_csrf_hash()
        ), $extra)));
}

/* exam + class + division from POST. Non-admin always gets their own class/division.
   Returns an array, or an error string. */
private function _marks_target($scope)
{
    $exam_id = (int)$this->input->post('exam_id');

    if ($scope['is_admin']) {
        $class = (int)$this->input->post('class_id');
        $div   = (int)$this->input->post('division_id');
    } else {
        if (!$scope['assigned']) {
            return 'No class and division is assigned to your account.';
        }
        $class = (int)$scope['class'];
        $div   = (int)$scope['division'];
    }

    $exam = $exam_id > 0 ? $this->Exam_Model->get_exam($exam_id) : null;
    if (!$exam)                                              return 'Please select a valid exam.';
    if (!$this->Exam_Model->class_exists_by_id($class))      return 'Please select a valid class.';
    if (!$this->Exam_Model->division_exists_by_id($div))     return 'Please select a valid division.';

    return array('exam' => $exam, 'exam_id' => $exam_id, 'class' => $class, 'div' => $div);
}

/* ---------- LIST ---------- */
public function Marksentry_list()
{
    $scope = $this->_marks_scope();

    if (empty($scope['perm']['can_view'])) {
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
        return;
    }

    $details = array();
    if ($scope['is_admin'] || $scope['assigned']) {
        // admin: everything; others: only their class + division
        $details = $this->Exam_Model->fetch_marklists($scope['class'], $scope['division']);
    }

    $data['details']     = $details;
    $data['perm']        = $scope['perm'];
    $data['is_admin']    = $scope['is_admin'];
    $data['assigned']    = $scope['assigned'];
    $data['scope_class'] = $scope['class'];
    $data['scope_div']   = $scope['division'];
    $data['classes']     = $this->Exam_Model->fetch_all_class();
    $data['divisions']   = $this->Exam_Model->get_all_divisions();
    $data['div_map']     = $this->Exam_Model->get_class_division_map();
    $data['exams']       = $this->Exam_Model->fetch_open_exams();
    $data['grades']      = $this->grades;

    $this->load->view('header');
    $this->load->view('marksentry_list', $data);
    $this->load->view('footer');
}

/* ---------- LOAD SHEET (POST, ajax) ---------- */
public function marks_sheet()
{
    if ($this->input->method() !== 'post') {
        return $this->_marks_json(false, 'Invalid request.');
    }
    $scope = $this->_marks_scope();
    if (empty($scope['perm']['can_view'])) {
        return $this->_marks_json(false, 'You do not have permission to do this.');
    }

    $t = $this->_marks_target($scope);
    if (is_string($t)) {
        return $this->_marks_json(false, $t);
    }

    return $this->_marks_json(true, 'OK', array(
        'is_grade' => (int)$t['exam']->emIsGrade === 1,
        'subjects' => $this->Exam_Model->get_allocation_subjects($t['exam_id'], $t['class']),
        'students' => $this->Exam_Model->get_class_students($t['class'], $t['div']),
        'marks'    => $this->Exam_Model->get_marks_map($t['exam_id'], $t['class'], $t['div']),
        'exists'   => $this->Exam_Model->marks_exist($t['exam_id'], $t['class'], $t['div']),
        'final'    => $this->Exam_Model->is_final($t['exam_id'], $t['class'], $t['div'])
    ));
}

/* ---------- SAVE (POST): add + edit ---------- */
public function save_marks()
{
    if ($this->input->method() !== 'post') {
        return $this->_marks_json(false, 'Invalid request.');
    }
    $scope   = $this->_marks_scope();
    $is_edit = (int)$this->input->post('is_edit') === 1;

    if (empty($scope['perm'][$is_edit ? 'can_edit' : 'can_add'])) {
        return $this->_marks_json(false, 'You do not have permission to do this.');
    }

    $t = $this->_marks_target($scope);
    if (is_string($t)) {
        return $this->_marks_json(false, $t);
    }

    $exists = $this->Exam_Model->marks_exist($t['exam_id'], $t['class'], $t['div']);
    if (!$is_edit && $exists) {
        return $this->_marks_json(false, 'Marks for this class, division and exam already exist. Edit them from the list.');
    }
    if ($is_edit && !$exists) {
        return $this->_marks_json(false, 'Marks not found.');
    }
    if ($this->Exam_Model->is_final($t['exam_id'], $t['class'], $t['div'])) {
        return $this->_marks_json(false, 'These marks are final submitted and cannot be changed.');
    }

    $posted = json_decode((string)$this->input->post('marks'), true);
    if (!is_array($posted)) {
        return $this->_marks_json(false, 'Invalid data.');
    }

    $subjects = array();
    foreach ($this->Exam_Model->get_allocation_subjects($t['exam_id'], $t['class']) as $s) {
        $subjects[(int)$s->id] = array('name' => $s->name, 'max' => (float)$s->max);
    }
    $students = array();
    foreach ($this->Exam_Model->get_class_students($t['class'], $t['div']) as $s) {
        $students[(int)$s->id] = $s->name;
    }
    if (empty($subjects)) {
        return $this->_marks_json(false, 'No mark allocation found for this class and exam.');
    }

    $is_grade = ((int)$t['exam']->emIsGrade === 1);
    $batch    = array();

    foreach ($posted as $st_id => $subs) {
        $st_id = (int)$st_id;
        if (!isset($students[$st_id]) || !is_array($subs)) {
            return $this->_marks_json(false, 'Invalid student in the sheet.');
        }
        foreach ($subs as $sub_id => $val) {
            $sub_id = (int)$sub_id;
            if (!isset($subjects[$sub_id])) {
                return $this->_marks_json(false, 'Invalid subject in the sheet.');
            }
            $val = trim((string)$val);
            if ($val === '') {
                continue;
            }
            $who = $students[$st_id] . ' / ' . $subjects[$sub_id]['name'];

            if ($is_grade) {
                $val = strtoupper($val);
                if (!in_array($val, $this->grades, true)) {
                    return $this->_marks_json(false, 'Invalid grade for ' . $who . '. Allowed: ' . implode(', ', $this->grades) . '.');
                }
            } else {
                if (!preg_match('/^\d{1,4}(\.\d{1,2})?$/', $val)) {
                    return $this->_marks_json(false, 'Invalid marks for ' . $who . '.');
                }
                if ((float)$val > $subjects[$sub_id]['max']) {
                    return $this->_marks_json(false, 'Marks for ' . $who . ' cannot exceed ' . $subjects[$sub_id]['max'] . '.');
                }
            }

            $batch[] = array('st' => $st_id, 'sub' => $sub_id, 'mark' => $val);
        }
    }

    if (empty($batch)) {
        return $this->_marks_json(false, 'Please enter marks for at least one student.');
    }

    $uid = (int)$this->session->userdata('user_id');
    $ok  = $this->Exam_Model->save_marks($t['exam_id'], $t['class'], $t['div'], $batch, $uid);
    return $this->_marks_json($ok, $ok
        ? ($is_edit ? 'Marks updated successfully.' : 'Marks saved successfully.')
        : 'Could not save. Please try again.');
}

/* ---------- DELETE (POST) ---------- */
public function delete_marks()
{
    if ($this->input->method() !== 'post') {
        return $this->_marks_json(false, 'Invalid request.');
    }
    $scope = $this->_marks_scope();
    if (empty($scope['perm']['can_delete'])) {
        return $this->_marks_json(false, 'You do not have permission to do this.');
    }

    $t = $this->_marks_target($scope);
    if (is_string($t)) {
        return $this->_marks_json(false, $t);
    }
    if (!$this->Exam_Model->marks_exist($t['exam_id'], $t['class'], $t['div'])) {
        return $this->_marks_json(false, 'Marks not found.');
    }
    if ($this->Exam_Model->is_final($t['exam_id'], $t['class'], $t['div'])) {
        return $this->_marks_json(false, 'Final submitted marks cannot be deleted.');
    }

    $ok = $this->Exam_Model->delete_marks($t['exam_id'], $t['class'], $t['div']);
    return $this->_marks_json($ok, $ok ? 'Marks deleted successfully.' : 'Delete failed.');
}

/* ---------- FINAL SUBMIT (POST) ---------- */
public function final_submit()
{
    if ($this->input->method() !== 'post') {
        return $this->_marks_json(false, 'Invalid request.');
    }
    $scope = $this->_marks_scope();
    if (empty($scope['perm']['can_edit'])) {
        return $this->_marks_json(false, 'You do not have permission to do this.');
    }

    $t = $this->_marks_target($scope);
    if (is_string($t)) {
        return $this->_marks_json(false, $t);
    }
    if (!$this->Exam_Model->marks_exist($t['exam_id'], $t['class'], $t['div'])) {
        return $this->_marks_json(false, 'Marks not found.');
    }
    if ($this->Exam_Model->is_final($t['exam_id'], $t['class'], $t['div'])) {
        return $this->_marks_json(false, 'Already final submitted.');
    }

    $p = $this->Exam_Model->marks_progress($t['exam_id'], $t['class'], $t['div']);
    if ($p['expected'] <= 0 || $p['filled'] < $p['expected']) {
        return $this->_marks_json(false, 'Please fill all marks and update before final submit (' . $p['filled'] . ' of ' . $p['expected'] . ' filled).');
    }

    $ok = $this->Exam_Model->final_submit($t['exam_id'], $t['class'], $t['div'], (int)$this->session->userdata('user_id'));
    return $this->_marks_json($ok, $ok ? 'Marks final submitted successfully.' : 'Final submit failed.');
}










}