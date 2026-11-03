<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VaccancyController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
    // $this->load->model('GalleryModel');


     if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
  }


  public function vaccancy_list()
    {
        $this->load->model('Vacancy_model');
 
        $from = (string)$this->input->get('from');
        $to   = (string)$this->input->get('to');
        if (!$this->_vac_valid_date($from)) { $from = ''; }
        if (!$this->_vac_valid_date($to))   { $to   = ''; }
 
        $q_from = ($from !== '') ? $from : '2000-01-01';
        $q_to   = (($to !== '') ? $to : '2099-12-31') . ' 23:59:59';
 
        $data['rows'] = $this->Vacancy_model->get_all_vacancy($q_from, $q_to);
        $data['from'] = $from;
        $data['to']   = $to;
 
        $this->load->view('header');
        $this->load->view('vaccancy_list', $data);
        $this->load->view('footer');
    }
 
    /* ---------- helpers ---------- */
    private function _vac_valid_date($d)
    {
        $x = DateTime::createFromFormat('Y-m-d', $d);
        return $x && $x->format('Y-m-d') === $d;
    }
 
    private function _vac_json($status, $msg)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => (bool)$status,
                'msg'    => $msg,
                'csrf'   => $this->security->get_csrf_hash()
            )));
    }
 
    /* ---------- ADD + EDIT ---------- */
    public function save_vaccancy()
    {
        if ($this->input->method() !== 'post') {
            return $this->_vac_json(false, 'Invalid request.');
        }
        $this->load->model('Vacancy_model');
 
        $id    = (int)$this->input->post('id');
        $title = trim((string)$this->input->post('title'));
        $desc  = trim((string)$this->input->post('description'));
 
        if ($title === '' || $desc === '') {
            return $this->_vac_json(false, 'Please enter both a title and a description.');
        }
        if (mb_strlen($title) > 120) {
            return $this->_vac_json(false, 'Title must be 120 characters or fewer.');
        }
 
        $data = array(
            'c_title'       => $title,
            'c_description' => $desc
        );
 
        if ($id) {
            if (!$this->Vacancy_model->get_vacancy($id)) {
                return $this->_vac_json(false, 'Vacancy not found.');
            }
            $ok = $this->Vacancy_model->update_vacancy($id, $data);
            return $this->_vac_json($ok, $ok ? 'Updated successfully.' : 'Update failed.');
        }
 
        $data['c_status'] = 'Y';
        $data['d_date']   = date('Y-m-d');
        $ok = $this->Vacancy_model->insert_vacancy($data);
        return $this->_vac_json($ok, $ok ? 'Added successfully.' : 'Could not save. Please try again.');
    }
 
    /* ---------- DELETE (uses your delete_vacancy(): c_status = 'D') ---------- */
    public function delete_vaccancy_list()
    {
        if ($this->input->method() !== 'post') {
            return $this->_vac_json(false, 'Invalid request.');
        }
        $this->load->model('Vacancy_model');
 
        $id = (int)$this->input->post('id');
        if (!$id || !$this->Vacancy_model->get_vacancy($id)) {
            return $this->_vac_json(false, 'Vacancy not found.');
        }
 
        $ok = $this->Vacancy_model->delete_vacancy($id);
        return $this->_vac_json($ok, $ok ? 'Deleted successfully.' : 'Delete failed.');
    }
 




   public function apply_members()
    {
        // job title comes from school_vacancy (n_job_id = school_vacancy.n_slno)
        $this->db->select('a.n_slno, a.n_job_id, a.c_name, a.n_mobile, a.c_email, a.c_resume, a.d_date, v.c_title AS job_title');
        $this->db->from('job_applications a');
        $this->db->join('school_vacancy v', 'v.n_slno = a.n_job_id', 'left');
        $this->db->where('a.c_status', 'Y');
        $this->db->order_by('a.d_date', 'DESC');
        $this->db->order_by('a.n_slno', 'DESC');
 
        $res['applications'] = $this->db->get()->result();
 
        $this->load->view('header');
        $this->load->view('apply_members', $res);
        $this->load->view('footer');
    }
 
    /* ---------- DELETE (soft delete: c_status = 'D'), POST only ---------- */
    public function delete_application()
    {
        if ($this->input->method() !== 'post') {
            return $this->_vac_json(false, 'Invalid request.');
        }
 
        $id = (int)$this->input->post('id');
        if (!$id) {
            return $this->_vac_json(false, 'Application not found.');
        }
 
        $exists = $this->db->where('n_slno', $id)
                           ->where('c_status', 'Y')
                           ->count_all_results('job_applications');
        if (!$exists) {
            return $this->_vac_json(false, 'Application not found.');
        }
 
        $ok = $this->db->where('n_slno', $id)
                       ->update('job_applications', array('c_status' => 'D'));
 
        return $this->_vac_json($ok, $ok ? 'Deleted successfully.' : 'Delete failed.');
    }














}