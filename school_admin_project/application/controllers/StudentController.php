<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentController extends CI_Controller
{
    

   function __construct()
    {
        parent::__construct();
        // $this->load->model('DashboardModel');
    }

public function divition_list()
    {
        $this->load->model('Division_model');
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
       
        $this->load->view('header');
        $this->load->view('class_divition_list');
        $this->load->view('footer');
    }




    public function students_list()
    {
       
        $this->load->view('header');
        $this->load->view('students_list');
        $this->load->view('footer');
    }


























}