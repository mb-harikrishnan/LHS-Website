<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MandatoryController extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        // $this->load->model('Mandatory_model');


         if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
    }


private $doc_types = array(
    'general_information'     => 'General Information',
    'copy_of_affiliation'     => 'Copies of Affiliation',
    'copy_of_societies'       => 'Copies of Societies',
    'NOC'                     => 'NOC',
    'copy_of_recognition'     => 'Copies of Recognition',
    'copy_of_safty'           => 'Building Safety Certificate',
    'copy_of_fire_and_safety' => 'Fire Safety Certificate',
    'DEO'                     => 'DEO Certificate',
    'sanitation'              => 'Water, Health and Sanitation Certificates',
    'land'                    => 'Certificate of Land',
);
        // Single source of truth for categories (used by filter, modal and the Type column)
    public function general_information()
{
    // Types that already have an active document (ignores the filter, used by the upload modal)
    $existing = $this->db->select('c_type')
                         ->where('c_status', 'Y')
                         ->group_by('c_type')
                         ->get('document_master')->result();
    $data['existing_types'] = array_map(function ($r) { return $r->c_type; }, $existing);

    // Filtered list for the table
    $type = $this->input->post('type');
    $this->db->where('c_status', 'Y');
    if (!empty($type) && isset($this->doc_types[$type])) {
        $this->db->where('c_type', $type);
    }
    $this->db->order_by('d_date', 'DESC');

    $data['documents'] = $this->db->get('document_master')->result();
    $data['doc_types'] = $this->doc_types;

    $this->load->view('header');
    $this->load->view('general_information', $data);
    $this->load->view('footer');
}

public function upload_document()
{
    $document_type = $this->input->post('document_type');

    if (!isset($this->doc_types[$document_type])) {
        $this->session->set_flashdata('error', 'Please select a valid category.');
        redirect('general_information');
    }

    // Server-side duplicate check (runs BEFORE the file is saved, so no orphan files)
    $exists = $this->db->where('c_type', $document_type)
                       ->where('c_status', 'Y')
                       ->count_all_results('document_master');

    if ($exists > 0) {
        $this->session->set_flashdata(
            'error',
            'A document already exists for "' . $this->doc_types[$document_type] . '". Please delete it first, then upload the new one.'
        );
        redirect('general_information');
    }

    $config['upload_path']   = '../assets/uploads/documents';
    $config['allowed_types'] = 'pdf';
    $config['max_size']      = 10240; // 10MB
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('document_file')) {
        $this->session->set_flashdata('error', $this->upload->display_errors());
    } else {
        $upload_data = $this->upload->data();

        $this->db->insert('document_master', array(
            'c_type'     => $document_type,
            'c_document' => $upload_data['file_name'],
            'd_date'     => date('Y-m-d'),
            'c_status'   => 'Y'
        ));
        $this->session->set_flashdata('success', 'Document uploaded successfully.');
    }
    redirect('general_information');
}

public function delete_document()
{
    // POST only (a GET link could be triggered by crawlers or prefetching)
    if ($this->input->method() !== 'post') {
        show_404();
    }

    $id  = (int) $this->input->post('id');
    $row = $this->db->where('n_slno', $id)->get('document_master')->row();

    if ($row) {
        // soft delete, matching your c_status flag
        $this->db->where('n_slno', $id)->update('document_master', array('c_status' => 'N'));

        // also remove the physical file so it doesn't pile up on the server
        $path = '../assets/uploads/documents/' . $row->c_document;
        if (is_file($path)) {
            @unlink($path);
        }
        $this->session->set_flashdata('success', 'Document deleted. You can now upload a new one.');
    } else {
        $this->session->set_flashdata('error', 'Document not found.');
    }
    redirect('general_information');
}



 ////////////////////////////////////////  RESULT & STAFF  ////////////////////////////////////////



 private $res_types = array(
    'fee_structure'     => 'Fee Structure',
    'anual_academic_calendar'     => 'Annual Academic Calendar',
    'school_managment_comitte'       => 'School Management Committee',
    'pta_members'                     => 'PTA Members',
    '3_yers_board_exam'     => '3 Years Board Exam Result',
    'staff_details'           => 'Staff Details',
    
);
        // Single source of truth for categories (used by filter, modal and the Type column)
    public function Result_and_Staff()
{
    // Types that already have an active document (ignores the filter, used by the upload modal)
    $existing = $this->db->select('c_type')
                         ->where('c_status', 'Y')
                         ->group_by('c_type')
                         ->get('result_and_staff_list')->result();
    $data['existing_types'] = array_map(function ($r) { return $r->c_type; }, $existing);

    // Filtered list for the table
    $type = $this->input->post('type');
    $this->db->where('c_status', 'Y');
    if (!empty($type) && isset($this->res_types[$type])) {
        $this->db->where('c_type', $type);
    }
    $this->db->order_by('d_date', 'DESC');

    $data['documents'] = $this->db->get('result_and_staff_list')->result();
    $data['doc_types'] = $this->res_types;

    $this->load->view('header');
    $this->load->view('Result_and_Staff',$data);
    $this->load->view('footer');
}

public function upload_Result_and_Staff()
{
    $document_type = $this->input->post('document_type');

    if (!isset($this->res_types[$document_type])) {
        $this->session->set_flashdata('error', 'Please select a valid category.');
        redirect('Result_and_Staff');
    }

    // Server-side duplicate check (runs BEFORE the file is saved, so no orphan files)
    $exists = $this->db->where('c_type', $document_type)
                       ->where('c_status', 'Y')
                       ->count_all_results('result_and_staff_list');

    if ($exists > 0) {
        $this->session->set_flashdata(
            'error',
            'A document already exists for "' . $this->res_types[$document_type] . '". Please delete it first, then upload the new one.'
        );
        redirect('Result_and_Staff');
    }

    $config['upload_path']   = '../assets/uploads/documents';
    $config['allowed_types'] = 'pdf';
    $config['max_size']      = 10240; // 10MB
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('document_file')) {
        $this->session->set_flashdata('error', $this->upload->display_errors());
    } else {
        $upload_data = $this->upload->data();

        $this->db->insert('result_and_staff_list', array(
            'c_type'     => $document_type,
            'c_document' => $upload_data['file_name'],
            'd_date'     => date('Y-m-d'),
            'c_status'   => 'Y'
        ));
        $this->session->set_flashdata('success', 'Document uploaded successfully.');
    }
    redirect('Result_and_Staff');
}

public function delete_Result_and_Staff()
{
    // POST only (a GET link could be triggered by crawlers or prefetching)
    if ($this->input->method() !== 'post') {
        show_404();
    }

    $id  = (int) $this->input->post('id');
    $row = $this->db->where('n_slno', $id)->get('result_and_staff_list')->row();

    if ($row) {
        // soft delete, matching your c_status flag
        $this->db->where('n_slno', $id)->update('result_and_staff_list', array('c_status' => 'N'));

        // also remove the physical file so it doesn't pile up on the server
        $path = '../assets/uploads/documents/' . $row->c_document;
        if (is_file($path)) {
            @unlink($path);
        }
        $this->session->set_flashdata('success', 'Document deleted. You can now upload a new one.');
    } else {
        $this->session->set_flashdata('error', 'Document not found.');
    }
    redirect('Result_and_Staff');
}






///////////////////////////////////////   

 //////////////////////////////////////// INFRASTRICTURE  ////////////////////////////////////////



// Single source of truth for categories
private $infra_types = array(
    'infrastructure' => 'Infrastructure',
);

// Folder where videos are stored (relative to your front controller, as in your original code)
private $video_dir = '../assets/uploads/videos/';

public function infrastructure()
{
    // Types that already have an active video (used by the upload modal)
    $existing = $this->db->select('c_type')
                         ->where('c_status', 'Y')
                         ->group_by('c_type')
                         ->get('infrastructure_videos')->result();
    $data['existing_types'] = array_map(function ($r) { return $r->c_type; }, $existing);

    // List for the table (optional ?type= filter)
    $type = $this->input->get('type');
    $this->db->where('c_status', 'Y');
    if (!empty($type) && isset($this->infra_types[$type])) {
        $this->db->where('c_type', $type);
    }
    $this->db->order_by('d_date', 'DESC');

    $data['documents'] = $this->db->get('infrastructure_videos')->result();
    $data['doc_types'] = $this->infra_types;   // was $this->res_types

    $this->load->view('header');
    $this->load->view('infrastructure', $data);
    $this->load->view('footer');
}

public function upload_infrastructure()
{
    // POST only
    if ($this->input->method() !== 'post') {
        show_404();
    }

    $document_type = $this->input->post('document_type');

    if (!isset($this->infra_types[$document_type])) {
        $this->session->set_flashdata('error', 'Please select a valid type.');
        redirect('infrastructure');
    }

    // Server-side duplicate check (runs BEFORE the file is saved, so no orphan files)
    $exists = $this->db->where('c_type', $document_type)
                       ->where('c_status', 'Y')
                       ->count_all_results('infrastructure_videos');

    if ($exists > 0) {
        $this->session->set_flashdata(
            'error',
            'A video already exists for "' . $this->infra_types[$document_type] . '". Please delete it first, then upload the new one.'
        );
        redirect('infrastructure');
    }

    // Make sure the upload folder exists
    if (!is_dir($this->video_dir)) {
        @mkdir($this->video_dir, 0755, true);
    }

    $config['upload_path']   = $this->video_dir;
    $config['allowed_types'] = 'mp4|avi|mov|wmv|flv|mkv';   // was "mkvf"
    $config['max_size']      = 204800;                       // 200 MB (value is in KB)
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('document_file')) {
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
    } else {
        $upload_data = $this->upload->data();

        $this->db->insert('infrastructure_videos', array(
            'c_type'   => $document_type,
            'c_videos'  => $upload_data['file_name'],
            'd_date'   => date('Y-m-d'),
            'links'    => '',          // was empty (syntax error); remove if the column doesn't exist
            'c_status' => 'Y'
        ));
        $this->session->set_flashdata('success', 'Video uploaded successfully.');
    }
    redirect('infrastructure');
}

public function delete_infrastructure()
{
    // POST only (a GET link could be triggered by crawlers or prefetching)
    if ($this->input->method() !== 'post') {
        show_404();
    }

    $id  = (int) $this->input->post('id');
    $row = $this->db->where('n_slno', $id)
                    ->where('c_status', 'Y')
                    ->get('infrastructure_videos')->row();

    if ($row) {
        // Soft delete, matching your c_status flag
        $this->db->where('n_slno', $id)->update('infrastructure_videos', array('c_status' => 'N'));

        // Remove the physical file (same folder as upload, correct column c_video)
        $path = $this->video_dir . $row->c_videos;
        if (is_file($path)) {
            @unlink($path);
        }
        $this->session->set_flashdata('success', 'Video deleted. You can now upload a new one.');
    } else {
        $this->session->set_flashdata('error', 'Video not found.');
    }
    redirect('infrastructure');
}








}