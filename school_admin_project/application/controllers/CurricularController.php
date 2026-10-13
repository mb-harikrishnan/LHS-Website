<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CurricularController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    $this->load->model('School_model');


     if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
  }



/** Listing page */
public function co_curricular_list()
{
    $data['rows']      = $this->School_model->get_all_activities();   // listing
    $data['all_types'] = $this->School_model->get_all_types();        // master types (DB)
    $data['used_types']= $this->School_model->get_used_types();       // already inserted
 
    $this->load->view('header');
    $this->load->view('co_curricular_list', $data);
    $this->load->view('footer');
}
 
/** Insert + Update (AJAX, returns JSON) */
public function save_co_curricular()
{
    $id   = (int) $this->input->post('id');           // 0 = insert, >0 = edit
    $type = trim($this->input->post('type'));
    $date = $this->input->post('date');
 
    if ($type === '' || $date === '') {
        return $this->_json(false, 'Type and date are required.');
    }
    // once a type exists, it is not allowed again
    if ($this->School_model->type_exists($type, $id)) {
        return $this->_json(false, 'This type already exists.');
    }
 
    $data = array('c_type' => $type, 'd_date' => $date);
 
    // image upload (required on insert, optional on edit)
    if (!empty($_FILES['image']['name'])) {
       $path = FCPATH . '../assets/images/gallery/';
        if (!is_dir($path)) { mkdir($path, 0755, true); }
 
        $this->load->library('upload', array(
            'upload_path'   => $path,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size'      => 5120,           // KB
            'encrypt_name'  => TRUE,
        ));
        if (!$this->upload->do_upload('image')) {
            return $this->_json(false, strip_tags($this->upload->display_errors('', '')));
        }
        $data['c_images'] = $this->upload->data('file_name');
    } elseif (!$id) {
        return $this->_json(false, 'Please choose an image.');
    }
 
    if ($id) {
        $this->School_model->update_activities($id, $data);
        return $this->_json(true, 'Updated successfully.');
    }
   $ok = $this->School_model->insert_activities($data);
if (!$ok) {
    $err = $this->db->error();
    return $this->_json(false, 'Save failed: ' . $err['message']);
}
return $this->_json(true, 'Added successfully.');
}
 
/** Delete (AJAX, returns JSON) */
public function delete_co_curricular()
{
    $id = (int) $this->input->post('id');
    if (!$id) { return $this->_json(false, 'Invalid request.'); }
 
    $this->School_model->delete_activities($id);
    return $this->_json(true, 'Deleted successfully.');
}
 
private function _json($ok, $msg)
{
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(array(
             'status' => $ok, 'msg' => $msg,
             'csrf'   => $this->security->get_csrf_hash(),
         )));
}












  /* ---- settings ---- *//* ---- settings ---- */
private $act_upload_path = '../assets/images/gallery/';

/* ---------- LIST PAGE ---------- */
public function activities_list()
{
    $this->load->model('School_model');

    $data['rows']      = $this->School_model->get_all();
    $data['type_list'] = $this->School_model->get_types();

    $this->load->view('header');
    $this->load->view('activities_list', $data);
    $this->load->view('footer');
}

/* normalise $_FILES[$field] (single or multiple) into a flat array of files */
private function _act_files($field)
{
    $out = array();
    if (empty($_FILES[$field]['name'])) return $out;

    $f = $_FILES[$field];
    if (!is_array($f['name'])) {
        if ($f['name'] !== '') $out[] = $f;
        return $out;
    }
    foreach ($f['name'] as $i => $name) {
        if ($name === '') continue;
        $out[] = array(
            'name'     => $name,
            'type'     => $f['type'][$i],
            'tmp_name' => $f['tmp_name'][$i],
            'error'    => $f['error'][$i],
            'size'     => $f['size'][$i],
        );
    }
    return $out;
}



/* ---------- JSON helper (always returns a fresh CSRF hash) ---------- */
private function _act_json($status, $msg)
{
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'status' => (bool)$status,
            'msg'    => $msg,
            'csrf'   => $this->security->get_csrf_hash()
        )));
}
/* ---------- ADD (many images) + EDIT (one image) ---------- */
public function save_activities_list()
{
    if ($this->input->method() !== 'post') {
        return $this->_act_json(false, 'Invalid request.');
    }
    $this->load->model('School_model');

    $id   = (int)$this->input->post('id');
    $type = trim((string)$this->input->post('type'));
    $date = trim((string)$this->input->post('date'));

    if (!array_key_exists($type, $this->School_model->get_types())) {
        return $this->_act_json(false, 'Please select a valid type.');
    }

    $d = DateTime::createFromFormat('Y-m-d', $date);
    if (!$d || $d->format('Y-m-d') !== $date) {
        return $this->_act_json(false, 'Please choose a valid date.');
    }

    $old = null;
    if ($id) {
        $old = $this->School_model->get($id);
        if (!$old) return $this->_act_json(false, 'Entry not found.');
    }

    $files = $this->_act_files('images');
    if (!$id && !$files) {
        return $this->_act_json(false, 'Please choose at least one image.');
    }
    if ($id) $files = array_slice($files, 0, 1);   // edit = one image only

    if ($files && !is_dir($this->act_upload_path)) {
        @mkdir($this->act_upload_path, 0755, TRUE);
    }

    $this->load->library('upload');
    $config = array(
        'upload_path'   => $this->act_upload_path,
        'allowed_types' => 'jpg|jpeg|png|webp',
        'max_size'      => 5120,   // KB (5 MB) per image
        'encrypt_name'  => TRUE
    );

    $saved = 0; $errors = array(); $new_images = array();

    foreach ($files as $file) {
        $_FILES['act_single'] = $file;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('act_single')) {
            $errors[] = $file['name'] . ': ' . strip_tags($this->upload->display_errors('', ''));
            continue;
        }
        $new_images[] = $this->upload->data('file_name');
    }

    /* ----- EDIT ----- */
    if ($id) {
        if ($files && !$new_images) {
            return $this->_act_json(false, implode(' | ', $errors));
        }
        $data = array('c_type' => $type, 'd_date' => $date);
        if ($new_images) $data['c_images'] = $new_images[0];

        $ok = $this->School_model->update($id, $data);
        if ($ok && $new_images && !empty($old->c_images)) {
            @unlink($this->act_upload_path . $old->c_images);
        }
        return $this->_act_json($ok, $ok ? 'Updated successfully.' : 'Update failed.');
    }

    /* ----- ADD: one row per image ----- */
    foreach ($new_images as $img) {
        $ok = $this->School_model->insert(array(
            'c_type'   => $type,
            'd_date'   => $date,
            'c_images' => $img,
            'c_status' => 'Y'
        ));
        if ($ok) {
            $saved++;
        } else {
            @unlink($this->act_upload_path . $img);
            $errors[] = 'Could not save ' . $img;
        }
    }

    if ($saved === 0) {
        return $this->_act_json(false, implode(' | ', $errors) ?: 'Could not save.');
    }
    $msg = $saved . ' image(s) added successfully.';
    if ($errors) $msg .= ' Skipped: ' . implode(' | ', $errors);
    return $this->_act_json(true, $msg);
}
    /* ---------- DELETE (soft delete: c_status = 'N') ---------- */
    public function delete_activities_list()
    {
        if ($this->input->method() !== 'post') {
            return $this->_act_json(false, 'Invalid request.');
        }
        $this->load->model('School_model');
 
        $id = (int)$this->input->post('id');
        if (!$id || !$this->School_model->get($id)) {
            return $this->_act_json(false, 'Entry not found.');
        }
 
        $ok = $this->School_model->soft_delete($id);
        return $this->_act_json($ok, $ok ? 'Deleted successfully.' : 'Delete failed.');
    }





}