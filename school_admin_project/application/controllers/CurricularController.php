<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CurricularController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    $this->load->model('School_model');
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
    if (!$this->School_model->type_is_valid($type)) {
        return $this->_json(false, 'Invalid type selected.');
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
    $this->School_model->insert_activities($data);
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












  public function activities_list()
  {
    $this->load->view('header');
    $this->load->view('activities_list');
    $this->load->view('footer');
  }






}