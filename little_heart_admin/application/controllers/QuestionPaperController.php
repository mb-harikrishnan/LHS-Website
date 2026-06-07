<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionPaperController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('Paper_model');
	}



    public function questionpaper_list()
    {   


        $from_date = $this->input->post('fromDate');
        $to_date = $this->input->post('toDate');

        if(empty($from_date) && empty($to_date)) {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
        } 

        $image['paper'] = $this->Paper_model->get_all_paper($from_date, $to_date);

        $this->load->view('header');
        $this->load->view('topbar');
        $this->load->view('sidebar');
        $this->load->view('questionpaper_list', $image);
        $this->load->view('footer');
    }


    public function add_paper()
    {
        $this->load->view('header');
        $this->load->view('topbar');
        $this->load->view('sidebar');
        $this->load->view('add_paper');
        $this->load->view('footer');
    }

public function insert_paper()
{
    $config['upload_path']   = '../assets/documents/';
    $config['allowed_types'] = 'pdf';
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('pdf'))
    {
        $upload_data = $this->upload->data();

        $data = array(

            'c_status'   => 'Y',

            'd_date'     => date('Y-m-d'),

            'c_title'    => 'CBSE Sample Question Papers',

            'c_class'    => $this->input->post('class_pdf'),

            'c_document' => $upload_data['file_name']

        );

        $result = $this->db->insert('question_paper', $data);

        if ($result)
        {
            $this->session->set_flashdata('success', 'PDF added successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Database insert failed');
        }
    }
    else
    {
        $this->session->set_flashdata('error', $this->upload->display_errors());
    }

    redirect('add_paper');
}




      public function delete_papper()
    {
        $id = $this->input->post('id');

        $result = $this->Paper_model->delete_papper($id);

        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }




}