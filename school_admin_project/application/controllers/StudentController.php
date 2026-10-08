<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentController extends CI_Controller
{
    
    private $student_genders = array('0' => 'Female', '1' => 'Male');

   function __construct()
    {
        parent::__construct();
        $this->load->model('Class_division_model');
        $this->load->model('Division_model');
        $this->load->model('Subject_Model');

         if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
    }

public function divition_list()
    {
        $data['divisions'] = $this->Division_model->get_all_divisions();
 
        $this->load->view('header');
        $this->load->view('divition_list', $data);
        $this->load->view('footer');
    }
 
    /* ---------- helpers ---------- */
    private function _division_json($status, $msg)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => (bool)$status,
                'msg'    => $msg,
                'csrf'   => $this->security->get_csrf_hash()
            )));
    }
 
    /* trim and collapse repeated spaces */
    private function _division_clean($name)
    {
        return trim(preg_replace('/\s+/', ' ', (string)$name));
    }
 
    /* returns an error message, or '' when the name is valid */
    private function _division_error($name, $exclude_id = 0)
    {
        if ($name === '') {
            return 'Please enter a division name.';
        }
        if (mb_strlen($name) > 50) {
            return 'Division name must be 50 characters or fewer.';
        }
        if (!preg_match('/^[A-Za-z0-9 \-]+$/', $name)) {
            return 'Use only letters, numbers, spaces and hyphens.';
        }
        if ($this->Division_model->name_exists($name, $exclude_id)) {
            return 'This division already exists.';
        }
        return '';
    }
 
    /* ---------- ADD (POST) ---------- */
    public function insert_divition()
    {
        if ($this->input->method() !== 'post') {
            return $this->_division_json(false, 'Invalid request.');
        }
        $this->load->model('Division_model');
 
        $name = $this->_division_clean($this->input->post('name'));
        $err  = $this->_division_error($name, 0);
        if ($err !== '') {
            return $this->_division_json(false, $err);
        }
 
        $ok = $this->Division_model->insert_division($name);
        return $this->_division_json($ok, $ok ? 'Division added successfully.' : 'Could not save. Please try again.');
    }
 
    /* ---------- EDIT / UPDATE (POST) ---------- */
    public function update_divition()
    {
        if ($this->input->method() !== 'post') {
            return $this->_division_json(false, 'Invalid request.');
        }
        $this->load->model('Division_model');
 
        $id = (int)$this->input->post('id');
        if (!$id || !$this->Division_model->get_division($id)) {
            return $this->_division_json(false, 'Division not found.');
        }
 
        $name = $this->_division_clean($this->input->post('name'));
        $err  = $this->_division_error($name, $id);   // ignores this division itself
        if ($err !== '') {
            return $this->_division_json(false, $err);
        }
 
        $ok = $this->Division_model->update_division($id, $name);
        return $this->_division_json($ok, $ok ? 'Division updated successfully.' : 'Update failed.');
    }
 
    /* ---------- DELETE (POST) ---------- */
    public function delete_divition_table()
    {
        if ($this->input->method() !== 'post') {
            return $this->_division_json(false, 'Invalid request.');
        }
        $this->load->model('Division_model');
 
        $id = (int)$this->input->post('id');
        if (!$id || !$this->Division_model->get_division($id)) {
            return $this->_division_json(false, 'Division not found.');
        }
 
        // block delete while a class still uses this division
        $used = $this->Division_model->used_count($id);
        if ($used > 0) {
            return $this->_division_json(false, 'This division is used in ' . $used . ' class(es). Remove it from those classes first.');
        }
 
        $ok = $this->Division_model->delete_division($id);
        return $this->_division_json($ok, $ok ? 'Division deleted successfully.' : 'Delete failed.');
    }














    ///////////////////////////////

 public function class_divition_list()
    {
 
        $data['allocations'] = $this->Class_division_model->get_all_allocations();
        $data['classes']     = $this->Class_division_model->get_classes();
        $data['divisions']   = $this->Class_division_model->get_divisions();
 
        $this->load->view('header');
        $this->load->view('class_divition_list', $data);
        $this->load->view('footer');
    }
 
    /* ---------- helper ---------- */
    private function _cd_json($status, $msg)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => (bool)$status,
                'msg'    => $msg,
                'csrf'   => $this->security->get_csrf_hash()
            )));
    }
 
    /* ---------- ADD + EDIT (POST) ----------
       class_id   : class_master.cmId
       divisions[]: division_master.dmId values
       is_edit    : 1 when editing an existing class, 0 when adding */
    public function save_class_division()
    {
        if ($this->input->method() !== 'post') {
            return $this->_cd_json(false, 'Invalid request.');
        }
 
        $class_id = (int)$this->input->post('class_id');
        $is_edit  = (int)$this->input->post('is_edit') === 1;
        $raw      = $this->input->post('divisions');
 
        // class must exist
        if (!$class_id || !$this->Class_division_model->get_class_division($class_id)) {
            return $this->_cd_json(false, 'Please select a valid class.');
        }
 
        // divisions: at least one, all must be real division ids
        $div_ids = array();
        if (is_array($raw)) {
            foreach ($raw as $v) {
                $v = (int)$v;
                if ($v > 0) { $div_ids[] = $v; }
            }
        }
        $div_ids = array_values(array_unique($div_ids));
 
        if (empty($div_ids)) {
            return $this->_cd_json(false, 'Please select at least one division.');
        }
        if ($this->Class_division_model->count_valid_divisions($div_ids) !== count($div_ids)) {
            return $this->_cd_json(false, 'One or more selected divisions are invalid.');
        }
 
        // add: class must not already have divisions / edit: it must have
        $existing = $this->Class_division_model->get_selected_divisions($class_id);
        if (!$is_edit && !empty($existing)) {
            return $this->_cd_json(false, 'This class already has divisions. Edit it from the list.');
        }
        if ($is_edit && empty($existing)) {
            return $this->_cd_json(false, 'Class division not found.');
        }
 
        $ok = $this->Class_division_model->save_allocation($class_id, $div_ids);
        $msg = $is_edit ? 'Updated successfully.' : 'Added successfully.';
        return $this->_cd_json($ok, $ok ? $msg : 'Could not save. Please try again.');
    }
 
    /* ---------- DELETE (POST): removes all divisions from the class ---------- */
    public function delete_class_division()
    {
        if ($this->input->method() !== 'post') {
            return $this->_cd_json(false, 'Invalid request.');
        }
        $this->load->model('Class_division_model');
 
        $class_id = (int)$this->input->post('class_id');
        if (!$class_id || empty($this->Class_division_model->get_selected_divisions($class_id))) {
            return $this->_cd_json(false, 'Class division not found.');
        }
 
        $ok = $this->Class_division_model->delete_allocation($class_id);
        return $this->_cd_json($ok, $ok ? 'Deleted successfully.' : 'Delete failed.');
    }   




    //////////////////////////////


  private function _student_scope()
    {
 
        $role = (int)$this->session->userdata('user_role_id');
        $uid  = (int)$this->session->userdata('user_id');   // login user id; change the key if your login stores it under another name
 
        $menu = $this->Subject_Model->get_menu_id_by_link('students_list');
        $perm = $this->Subject_Model->get_permissions($role, $menu);
 
        $scope = array(
            'is_admin' => ($role === 1),
            'perm'     => is_array($perm) ? $perm : array(),   // keys used: can_view, can_add, can_edit, can_delete
            'assigned' => true,
            'class'    => '',
            'division' => ''
        );
 
        if (!$scope['is_admin']) {
            $emp = $this->Subject_Model->get_employee_class_div($uid);
            if ($emp && !empty($emp->emClass) && !empty($emp->emDiv)) {
                $scope['class']    = (string)$emp->emClass;
                $scope['division'] = (string)$emp->emDiv;
            } else {
                $scope['assigned'] = false;   // no class/division saved for this user
            }
        }
        return $scope;
    }
 
    /* is this student inside the user's class + division? */
    private function _student_in_scope($row, $scope)
    {
        return (string)$row->smClass === $scope['class'] && (string)$row->smDiv === $scope['division'];
    }
 
    private function _student_json($status, $msg)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => (bool)$status,
                'msg'    => $msg,
                'csrf'   => $this->security->get_csrf_hash()
            )));
    }
 
    private function _student_clean($v)
    {
        return trim(preg_replace('/\s+/', ' ', (string)$v));
    }
 
    /* ---------- LIST PAGE ---------- */
    public function students_list($page = 0)
    {
        $this->load->library('pagination');
        $scope = $this->_student_scope();
 
        // no view permission -> message and stop
        if (empty($scope['perm']['can_view'])) {
            $this->load->view('header');
            $this->load->view('no_permission');
            $this->load->view('footer');
            return;
        }
 
        $per_page = 20;
        $page     = max(0, (int)$page);
 
        // filters: admin may choose class / division, everyone else is locked to their own
        $filters = array('name' => trim((string)$this->input->get('name')), 'class' => '', 'division' => '');
        if ($scope['is_admin']) {
            $filters['class']    = (string)$this->input->get('class');
            $filters['division'] = (string)$this->input->get('division');
        } else {
            $filters['class']    = $scope['class'];
            $filters['division'] = $scope['division'];
        }
 
        $total   = 0;
        $details = array();
 
        if ($scope['assigned']) {
            $total = $this->Subject_Model->count_all_students($filters);
 
            // page is beyond the last one (e.g. after deleting the last row): go back to the last page
            if ($page > 0 && $page >= $total) {
                $last = ($total > 0) ? (int)(floor(($total - 1) / $per_page) * $per_page) : 0;
                $qs   = http_build_query((array)$this->input->get());
                redirect(($last > 0 ? 'students_list/' . $last : 'students_list') . ($qs !== '' ? '?' . $qs : ''));
                return;
            }
 
            $details = $this->Subject_Model->fetch_all_student_details($per_page, $page, $filters);
        }
 
        // pagination (page links keep the ?name=&class=&division= filters)
        $config = array(
            'base_url'           => site_url('students_list'),
            'total_rows'         => $total,
            'per_page'           => $per_page,
            'uri_segment'        => 2,
            'num_links'          => 2,
            'reuse_query_string' => TRUE,
            'full_tag_open'      => '<ul class="pager">',
            'full_tag_close'     => '</ul>',
            'num_tag_open'       => '<li>',
            'num_tag_close'      => '</li>',
            'cur_tag_open'       => '<li class="active"><span>',
            'cur_tag_close'      => '</span></li>',
            'prev_tag_open'      => '<li>',
            'prev_tag_close'     => '</li>',
            'next_tag_open'      => '<li>',
            'next_tag_close'     => '</li>',
            'first_link'         => FALSE,
            'last_link'          => FALSE,
            'prev_link'          => '&lsaquo;',
            'next_link'          => '&rsaquo;'
        );
        $this->pagination->initialize($config);
 
        $data['details']  = $details;
        $data['links']    = $this->pagination->create_links();
        $data['start']    = $page;
        $data['per_page'] = $per_page;
        $data['total']    = $total;
        $data['filters']  = $filters;
        $data['res']      = $this->Subject_Model->get_all_classes();
        $data['res1']     = $this->Subject_Model->get_all_divisions();
        $data['div_map']  = $this->Subject_Model->get_class_division_map();
        $data['perm']     = $scope['perm'];
        $data['is_admin'] = $scope['is_admin'];
        $data['assigned'] = $scope['assigned'];
        $data['genders']  = $this->student_genders;
 
        $this->load->view('header');
        $this->load->view('students_list', $data);
        $this->load->view('footer');
    }
 
    /* ---------- ADD + EDIT (POST) ---------- */
    public function save_student()
    {
        if ($this->input->method() !== 'post') {
            return $this->_student_json(false, 'Invalid request.');
        }
        $scope = $this->_student_scope();
 
        $id      = (int)$this->input->post('id');
        $is_edit = $id > 0;
 
        // permission
        if (empty($scope['perm'][$is_edit ? 'can_edit' : 'can_add'])) {
            return $this->_student_json(false, 'You do not have permission to do this.');
        }
        if (!$scope['is_admin'] && !$scope['assigned']) {
            return $this->_student_json(false, 'No class and division is assigned to your account.');
        }
 
        // when editing, the student must exist and (for non-admin) belong to the user's class + division
        if ($is_edit) {
            $old = $this->Subject_Model->get_student($id);
            if (!$old) {
                return $this->_student_json(false, 'Student not found.');
            }
            if (!$scope['is_admin'] && !$this->_student_in_scope($old, $scope)) {
                return $this->_student_json(false, 'You are not allowed to change this student.');
            }
        }
 
        // posted values
        $adm      = $this->_student_clean($this->input->post('adm_no'));
        $aadhar   = trim((string)$this->input->post('aadhar'));
        $name     = $this->_student_clean($this->input->post('name'));
        $gender   = (string)$this->input->post('gender');
        $dob      = trim((string)$this->input->post('dob'));
        $mobile   = trim((string)$this->input->post('mobile'));
        $class    = (string)$this->input->post('class_id');
        $division = (string)$this->input->post('division_id');
        $religion = $this->_student_clean($this->input->post('religion'));
        $caste    = $this->_student_clean($this->input->post('caste'));
        $tongue   = $this->_student_clean($this->input->post('tongue'));
        $address  = trim((string)$this->input->post('address'));
        $country  = $this->_student_clean($this->input->post('country'));
        $state    = $this->_student_clean($this->input->post('state'));
 
        // non-admin: class and division are always the user's own, whatever was posted
        if (!$scope['is_admin']) {
            $class    = $scope['class'];
            $division = $scope['division'];
        }
 
        // validation
        if ($adm === '')                                  return $this->_student_json(false, 'Please enter the admission number.');
        if (mb_strlen($adm) > 30)                         return $this->_student_json(false, 'Admission number must be 30 characters or fewer.');
        if ($this->Subject_Model->admission_no_exists($adm, $id)) return $this->_student_json(false, 'This admission number already exists.');
        if (!preg_match('/^\d{12}$/', $aadhar))           return $this->_student_json(false, 'Aadhar number must be 12 digits.');
        if ($name === '')                                 return $this->_student_json(false, 'Please enter the student name.');
        if (mb_strlen($name) > 100)                       return $this->_student_json(false, 'Student name must be 100 characters or fewer.');
        if (!array_key_exists($gender, $this->student_genders)) return $this->_student_json(false, 'Please select the gender.');
 
        if ($dob !== '') {
            $d = DateTime::createFromFormat('Y-m-d', $dob);
            if (!$d || $d->format('Y-m-d') !== $dob || $dob > date('Y-m-d')) {
                return $this->_student_json(false, 'Please enter a valid date of birth.');
            }
        }
        if ($mobile !== '' && !preg_match('/^\d{10}$/', $mobile)) return $this->_student_json(false, 'Mobile number must be 10 digits.');
 
        if ($class === '' || !$this->Subject_Model->class_exists_by_id($class))        return $this->_student_json(false, 'Please select a valid class.');
        if ($division === '' || !$this->Subject_Model->division_exists_by_id($division)) return $this->_student_json(false, 'Please select a valid division.');
 
        foreach (array($religion, $caste, $tongue, $country, $state) as $v) {
            if (mb_strlen($v) > 50) return $this->_student_json(false, 'Religion, caste, mother tongue, country and state must be 50 characters or fewer.');
        }
        if (mb_strlen($address) > 500) return $this->_student_json(false, 'Address must be 500 characters or fewer.');
 
        $row = array(
            'smAdmissionNo'  => $adm,
            'smAadharNo'     => $aadhar,
            'smName'         => $name,
            'smClass'        => $class,
            'smDiv'          => $division,
            'smGender'       => $gender,
            'smMobile'       => $mobile,
            'smDOB'          => ($dob !== '') ? $dob : NULL,   // smDOB must allow NULL
            'smAddress'      => $address,
            'smReligion'     => $religion,
            'smCaste'        => $caste,
            'smMotherTongue' => $tongue,
            'smCountry'      => $country,
            'smState'        => $state
        );
 
        if ($is_edit) {
            $ok = $this->Subject_Model->update_student($id, $row);
            return $this->_student_json($ok, $ok ? 'Student updated successfully.' : 'Update failed.');
        }
 
        $ok = $this->Subject_Model->insert_student($row);
        return $this->_student_json($ok, $ok ? 'Student added successfully.' : 'Could not save. Please try again.');
    }
 
    /* ---------- DELETE (POST) ---------- */
    public function delete_student()
    {
        if ($this->input->method() !== 'post') {
            return $this->_student_json(false, 'Invalid request.');
        }
        $scope = $this->_student_scope();
 
        if (empty($scope['perm']['can_delete'])) {
            return $this->_student_json(false, 'You do not have permission to do this.');
        }
 
        $id  = (int)$this->input->post('id');
        $row = $id ? $this->Subject_Model->get_student($id) : null;
        if (!$row) {
            return $this->_student_json(false, 'Student not found.');
        }
        if (!$scope['is_admin'] && (!$scope['assigned'] || !$this->_student_in_scope($row, $scope))) {
            return $this->_student_json(false, 'You are not allowed to delete this student.');
        }
 
        $ok = $this->Subject_Model->delete_student($id);
        return $this->_student_json($ok, $ok ? 'Student deleted successfully.' : 'Delete failed.');
    }





















}