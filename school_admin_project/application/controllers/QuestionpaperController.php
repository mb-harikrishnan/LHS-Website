<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionpaperController extends CI_Controller
{
        private $upload_path = '../assets/documents/';   // same folder your list links to
        
    private $slider_upload_path = '../assets/images/gallery/';   // images and v


  function __construct()
  {
    parent::__construct();
    $this->load->model('Paper_model');
    $this->load->model('Slider_model');

     if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
  }



    public function questionpaper_list()
    {
        $from = (string)$this->input->get('from');
        $to   = (string)$this->input->get('to');
        if (!$this->_valid_date($from)) { $from = ''; }
        if (!$this->_valid_date($to))   { $to   = ''; }
 
        $q_from = ($from !== '') ? $from : '2000-01-01';
        $q_to   = (($to !== '') ? $to : '2099-12-31') . ' 23:59:59';
 
        $data['paper']      = $this->Paper_model->get_all_paper($q_from, $q_to);
        $data['class_list'] = $this->Paper_model->get_classes();
        $data['from']       = $from;
        $data['to']         = $to;
 
        $this->load->view('header');
        $this->load->view('questionpaper_list', $data);
        $this->load->view('footer');
    }
 
    /* ---------- helpers ---------- */
    private function _valid_date($d)
    {
        $x = DateTime::createFromFormat('Y-m-d', $d);
        return $x && $x->format('Y-m-d') === $d;
    }
 
    private function _json($status, $msg)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => (bool)$status,
                'msg'    => $msg,
                'csrf'   => $this->security->get_csrf_hash()
            )));
    }
 
    /* ---------- ADD + EDIT (POST) ---------- */
    public function insert_paper()
    {
        if ($this->input->method() !== 'post') {
            return $this->_json(false, 'Invalid request.');
        }
 
        $id    = (int)$this->input->post('id');
        $title = trim((string)$this->input->post('title'));
        $class = (string)$this->input->post('class');
        $date  = trim((string)$this->input->post('date'));
 
        if ($title === '') {
            return $this->_json(false, 'Please enter a title.');
        }
        if (mb_strlen($title) > 150) {
            return $this->_json(false, 'Title must be 150 characters or fewer.');
        }
        if (!array_key_exists($class, $this->Paper_model->get_classes())) {
            return $this->_json(false, 'Please select a valid class.');
        }
        if (!$this->_valid_date($date)) {
            return $this->_json(false, 'Please choose a valid date.');
        }
 
        // when editing, the paper must exist
        $old = null;
        if ($id) {
            $old = $this->Paper_model->get_paper($id);
            if (!$old) {
                return $this->_json(false, 'Question paper not found.');
            }
        }
 
        $has_file = !empty($_FILES['file']['name']);
        if (!$id && !$has_file) {
            return $this->_json(false, 'Please choose a document.');
        }
 
        $data = array(
            'c_title' => $title,
            'c_class' => $class,
            'd_date'  => $date
        );
 
        // document upload
        if ($has_file) {
            if (!is_dir($this->upload_path)) {
                @mkdir($this->upload_path, 0755, TRUE);
            }
            $config = array(
                'upload_path'   => $this->upload_path,
                'allowed_types' => 'pdf|doc|docx|xls|xlsx',
                'max_size'      => 10240,   // KB (10 MB)
                'encrypt_name'  => TRUE
            );
            $this->load->library('upload', $config);
 
            if (!$this->upload->do_upload('file')) {
                return $this->_json(false, strip_tags($this->upload->display_errors('', '')));
            }
            $data['c_document'] = $this->upload->data('file_name');
        }
 
        if ($id) {
            $ok = $this->Paper_model->update_paper($id, $data);
            if ($ok && $has_file && !empty($old->c_document)) {
                @unlink($this->upload_path . $old->c_document);   // remove replaced file
            }
            return $this->_json($ok, $ok ? 'Updated successfully.' : 'Update failed.');
        }
 
        $data['c_status'] = 'Y';
        $ok = $this->Paper_model->insert_paper($data);
        return $this->_json($ok, $ok ? 'Added successfully.' : 'Could not save. Please try again.');
    }
 
    /* ---------- DELETE (POST, soft delete c_status = 'D') ---------- */
    public function delete_papper()
    {
        if ($this->input->method() !== 'post') {
            return $this->_json(false, 'Invalid request.');
        }
 
        $id = (int)$this->input->post('id');
        if (!$id || !$this->Paper_model->get_paper($id)) {
            return $this->_json(false, 'Question paper not found.');
        }
 
        $ok = $this->Paper_model->delete_papper($id);
        return $this->_json($ok, $ok ? 'Deleted successfully.' : 'Delete failed.');
    }



    /////////////////////////////////////////

 public function slider_list()
    {
        $this->load->model('Slider_model');
        $data['sliders'] = $this->Slider_model->get_all_images();
 
        $this->load->view('header');
        $this->load->view('slider_list', $data);
        $this->load->view('footer');
    }
 
    /* ---------- helpers ---------- */
    private function _slider_valid_date($d)
    {
        $x = DateTime::createFromFormat('Y-m-d', $d);
        return $x && $x->format('Y-m-d') === $d;
    }
 
    private function _slider_json($status, $msg)
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
       upload_type: 'image' | 'video' (uploaded file) | 'link' (video URL) */
    public function save_slider()
    {
        if ($this->input->method() !== 'post') {
            return $this->_slider_json(false, 'Invalid request.');
        }
        $this->load->model('Slider_model');
 
        $id    = (int)$this->input->post('id');
        $title = trim((string)$this->input->post('title'));
        $desc  = trim((string)$this->input->post('description'));
        $date  = trim((string)$this->input->post('date'));
        $type  = (string)$this->input->post('upload_type');
 
        if ($title === '') {
            return $this->_slider_json(false, 'Please enter a title.');
        }
        if (mb_strlen($title) > 150) {
            return $this->_slider_json(false, 'Title must be 150 characters or fewer.');
        }
        if (!$this->_slider_valid_date($date)) {
            return $this->_slider_json(false, 'Please choose a valid date.');
        }
        if (!in_array($type, array('image', 'video', 'link'), true)) {
            return $this->_slider_json(false, 'Invalid upload type.');
        }
 
        // when editing, the slide must exist
        $old = null;
        if ($id) {
            $old = $this->Slider_model->get_slider($id);
            if (!$old) {
                return $this->_slider_json(false, 'Slide not found.');
            }
        }
 
        /* decide the new file value (null = keep the current one) */
        $new_file = null;
 
        if ($type === 'link') {
            $link   = trim((string)$this->input->post('link'));
            $scheme = strtolower((string)parse_url($link, PHP_URL_SCHEME));
            if (!filter_var($link, FILTER_VALIDATE_URL) || !in_array($scheme, array('http', 'https'), true)) {
                return $this->_slider_json(false, 'Please enter a valid video link (https://...).');
            }
            $new_file = $link;
        } else {
            $field = ($type === 'image') ? 'image' : 'video';
 
            if (!empty($_FILES[$field]['name'])) {
                if (!is_dir($this->slider_upload_path)) {
                    @mkdir($this->slider_upload_path, 0755, TRUE);
                }
                $config = array(
                    'upload_path'   => $this->slider_upload_path,
                    'allowed_types' => ($type === 'image') ? 'jpg|jpeg|png|webp' : 'mp4|webm|ogg|mov',
                    'max_size'      => ($type === 'image') ? 5120 : 51200,   // KB: 5 MB / 50 MB
                    'encrypt_name'  => TRUE
                );
                $this->load->library('upload', $config);
 
                if (!$this->upload->do_upload($field)) {
                    return $this->_slider_json(false, strip_tags($this->upload->display_errors('', '')));
                }
                $new_file = $this->upload->data('file_name');
            } elseif (!($old && $old->c_upload_type === $type)) {
                // nothing uploaded and nothing of this type to keep
                return $this->_slider_json(false, ($type === 'image') ? 'Please choose an image.' : 'Please choose a video file.');
            }
        }
 
        $data = array(
            'c_title'       => $title,
            'c_description' => $desc,
            'c_upload_type' => $type,
            'd_date'        => $date
        );
        if ($new_file !== null) {
            $data['c_file'] = $new_file;
        }
 
        if ($id) {
            $ok = $this->Slider_model->update_slider($id, $data);
            // remove the replaced file from disk (links have no file)
            if ($ok && $new_file !== null && $old->c_upload_type !== 'link' && !empty($old->c_file)) {
                @unlink($this->slider_upload_path . $old->c_file);
            }
            return $this->_slider_json($ok, $ok ? 'Updated successfully.' : 'Update failed.');
        }
 
        $data['c_status'] = 'A';
        $ok = $this->Slider_model->insert_slider($data);
        return $this->_slider_json($ok, $ok ? 'Added successfully.' : 'Could not save. Please try again.');
    }
 
    /* ---------- DELETE (POST, soft delete c_status = 'D') ---------- */
    public function delete_slider()
    {
        if ($this->input->method() !== 'post') {
            return $this->_slider_json(false, 'Invalid request.');
        }
        $this->load->model('Slider_model');
 
        $id = (int)$this->input->post('id');
        if (!$id || !$this->Slider_model->get_slider($id)) {
            return $this->_slider_json(false, 'Slide not found.');
        }
 
        $ok = $this->Slider_model->delete_slider($id);
        return $this->_slider_json($ok, $ok ? 'Deleted successfully.' : 'Delete failed.');
    }




    /////////////////////////////

/* ---------- LIST ---------- */
public function accademic_list()
{
    $data['academic'] = $this->db->where('amIsCurrent', 1)
                                 ->order_by('amYear', 'ASC')
                                 ->get('academic_master')
                                 ->result();

    $this->load->view('header');
    $this->load->view('accademic_list', $data);
    $this->load->view('footer');
}

/* ---------- ADD + EDIT (POST) ---------- */
public function save_academic()
{
    if ($this->input->method() !== 'post') {
        return $this->_academic_json(false, 'Invalid request.');
    }

    $id   = (int)$this->input->post('id');
    $year = trim((string)$this->input->post('year'));

    if ($year === '') {
        return $this->_academic_json(false, 'Please enter the academic year.');
    }
    if (!preg_match('/^\d{4}-\d{2}$/', $year)) {
        return $this->_academic_json(false, 'Use the format 2026-27.');
    }
    $start = (int)substr($year, 0, 4);
    $end   = (int)substr($year, 5, 2);
    if (($start + 1) % 100 !== $end) {
        return $this->_academic_json(false, 'The end year must follow the start year (e.g. 2026-27).');
    }

    // does this year already exist (other than the row being edited)?
    $exists = $this->db->where('amYear', $year)
                       ->where('amId !=', $id)
                       ->get('academic_master')
                       ->row();

    if ($id > 0) {                                   // ---- EDIT ----
        $cur = $this->db->where('amId', $id)->where('amIsCurrent', 1)->get('academic_master')->row();
        if (!$cur) {
            return $this->_academic_json(false, 'Academic year not found.');
        }
        if ($exists && (int)$exists->amIsCurrent === 1) {
            return $this->_academic_json(false, 'This academic year already exists.');
        }
        $ok = $this->db->where('amId', $id)->update('academic_master', array('amYear' => $year));
        return $this->_academic_json($ok, $ok ? 'Academic year updated successfully.' : 'Update failed.');
    }

    // ---- ADD ----
    if ($exists) {
        if ((int)$exists->amIsCurrent === 1) {
            return $this->_academic_json(false, 'This academic year already exists.');
        }
        // it was deleted earlier: bring it back instead of adding a duplicate
        $ok = $this->db->where('amId', $exists->amId)->update('academic_master', array('amIsCurrent' => 1));
        return $this->_academic_json($ok, $ok ? 'Academic year added successfully.' : 'Could not save.');
    }

    $ok = $this->db->insert('academic_master', array('amYear' => $year, 'amIsCurrent' => 1));
    return $this->_academic_json($ok, $ok ? 'Academic year added successfully.' : 'Could not save. Please try again.');
}

/* ---------- DELETE (POST) ---------- */
public function delete_accademic()
{
    if ($this->input->method() !== 'post') {
        return $this->_academic_json(false, 'Invalid request.');
    }

    $id = (int)$this->input->post('id');
    if ($id <= 0) {
        return $this->_academic_json(false, 'Academic year not found.');
    }

    $ok = $this->db->where('amId', $id)->update('academic_master', array('amIsCurrent' => 0));
    return $this->_academic_json($ok, $ok ? 'Academic year deleted successfully.' : 'Delete failed.');
}

/* ---------- JSON helper ---------- */
private function _academic_json($status, $msg)
{
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'status' => (bool)$status,
            'msg'    => $msg,
            'csrf'   => $this->security->get_csrf_hash()
        )));
}










  //////////////////////////


/* ---------- LIST ---------- */
public function term_list()
{
    $data['term'] = $this->db->order_by('tmId', 'ASC')
                             ->get('term_master')
                             ->result();

    $this->load->view('header');
    $this->load->view('term_list', $data);
    $this->load->view('footer');
}

/* ---------- ADD + EDIT (POST) ---------- */
public function save_term()
{
    if ($this->input->method() !== 'post') {
        return $this->_term_json(false, 'Invalid request.');
    }

    $id   = (int)$this->input->post('id');
    $name = trim(preg_replace('/\s+/', ' ', (string)$this->input->post('name')));
    $code = trim((string)$this->input->post('code'));

    if ($name === '') {
        return $this->_term_json(false, 'Please enter the term.');
    }
    if (mb_strlen($name) > 60) {
        return $this->_term_json(false, 'Term must be 60 characters or fewer.');
    }
    if ($code === '') {
        return $this->_term_json(false, 'Please enter the code.');
    }
    if (mb_strlen($code) > 20) {
        return $this->_term_json(false, 'Code must be 20 characters or fewer.');
    }

    // duplicate name (case-insensitive), ignoring the row being edited
    $dup_name = $this->db->query(
        'SELECT tmId FROM term_master WHERE LOWER(tmName) = LOWER(?) AND tmId != ? LIMIT 1',
        array($name, $id)
    )->row();
    if ($dup_name) {
        return $this->_term_json(false, 'This term already exists.');
    }

    // duplicate code (case-insensitive), ignoring the row being edited
    $dup_code = $this->db->query(
        'SELECT tmId FROM term_master WHERE LOWER(tmCode) = LOWER(?) AND tmId != ? LIMIT 1',
        array($code, $id)
    )->row();
    if ($dup_code) {
        return $this->_term_json(false, 'This code is already used.');
    }

    $row = array('tmName' => $name, 'tmCode' => $code);

    if ($id > 0) {                                   // ---- EDIT ----
        $exists = $this->db->where('tmId', $id)->get('term_master')->row();
        if (!$exists) {
            return $this->_term_json(false, 'Term not found.');
        }
        $ok = $this->db->where('tmId', $id)->update('term_master', $row);
        return $this->_term_json($ok, $ok ? 'Term updated successfully.' : 'Update failed.');
    }

    // ---- ADD ----
    $ok = $this->db->insert('term_master', $row);
    return $this->_term_json($ok, $ok ? 'Term added successfully.' : 'Could not save. Please try again.');
}

/* ---------- DELETE (POST) ---------- */
public function delete_term()
{
    if ($this->input->method() !== 'post') {
        return $this->_term_json(false, 'Invalid request.');
    }

    $id = (int)$this->input->post('id');
    if ($id <= 0) {
        return $this->_term_json(false, 'Term not found.');
    }

    $ok = $this->db->where('tmId', $id)->delete('term_master');
    return $this->_term_json($ok, $ok ? 'Term deleted successfully.' : 'Delete failed.');
}

/* ---------- JSON helper ---------- */
private function _term_json($status, $msg)
{
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'status' => (bool)$status,
            'msg'    => $msg,
            'csrf'   => $this->security->get_csrf_hash()
        )));
}


  /////////////////////////////////////

public function user_role_list()
{
    $data['roles'] = $this->db->order_by('role_id', 'ASC')->get('user_roles')->result();

    $this->load->view('header');
    $this->load->view('user_role_list', $data);
    $this->load->view('footer');
}

// ADD
public function user_role_save()
{
    $name = trim($this->input->post('role_name', TRUE));

    if ($name === '') {
        return $this->_jsons(false, 'Please enter a role.');
    }
    if ($this->db->where('LOWER(role_name)', strtolower($name))->get('user_roles')->num_rows() > 0) {
        return $this->_jsons(false, 'This role already exists.');
    }

    $this->db->insert('user_roles', ['role_name' => $name, 'status' => 1]);
    return $this->_jsons(true, 'Role added successfully.');
}

// EDIT
public function user_role_update()
{
    $id   = (int) $this->input->post('role_id');
    $name = trim($this->input->post('role_name', TRUE));

    if ($id <= 0 || $name === '') {
        return $this->_jsons(false, 'Please enter a role.');
    }

    $dup = $this->db->where('LOWER(role_name)', strtolower($name))
                    ->where('role_id !=', $id)
                    ->get('user_roles')->num_rows();
    if ($dup > 0) {
        return $this->_jsons(false, 'This role already exists.');
    }

    $this->db->where('role_id', $id)->update('user_roles', ['role_name' => $name]);
    return $this->_jsons(true, 'Role updated successfully.');
}

// DELETE
public function user_role_delete()
{
    $id = (int) $this->input->post('role_id');

    if ($id <= 0) {
        return $this->_jsons(false, 'Invalid role.');
    }

    $this->db->where('role_id', $id)->delete('user_roles');
    return $this->_jsons(true, 'Role deleted successfully.');
}

// ACTIVE (1) / INACTIVE (0)
public function user_role_status()
{
    $id     = (int) $this->input->post('role_id');
    $status = (int) $this->input->post('status') === 1 ? 1 : 0;

    if ($id <= 0) {
        return $this->_jsons(false, 'Invalid role.');
    }

    $this->db->where('role_id', $id)->update('user_roles', ['status' => $status]);
    return $this->_jsons(true, $status ? 'Role activated.' : 'Role deactivated.');
}

// helper (name unchanged)
private function _jsons($success, $message)
{
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['success' => $success, 'message' => $message]));
}



/////////////////////////


////////////////////////////////////////
















}