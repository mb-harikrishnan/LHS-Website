<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DocumentController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('Document_model');
	}


    public function general_information()
    {

       $type = $this->input->post('type');

        $information['information'] = $this->Document_model->fetch_general_information($type);

        $this->load->view('header');
        $this->load->view('topbar');
        $this->load->view('sidebar');
        $this->load->view('general_information' , $information );
        $this->load->view('footer');
    }
   
    public function delete_general_information()
    {
        $id = $this->input->post('id');

        $result = $this->Document_model->delete_general_information($id);

        if($result)
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }




    public function upload_document()
    {


        $this->load->view('header');
        $this->load->view('topbar');
        $this->load->view('sidebar');
        $this->load->view('upload_document');
        $this->load->view('footer');

    }


    public function add_document()
{
    if ($this->input->post()) {

        $document_type  = $this->input->post('document_type');
        $document_title = $this->input->post('document_title');

        // Upload Configuration
        $config['upload_path']   ='../assets/uploads/documents';

        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 10240; // 10MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('document_file')) {

            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors()
            );

        } else {

            $upload_data = $this->upload->data();

            $insert_array = array(
                'c_type'  => $document_type,
                'c_document'  => $upload_data['file_name'],
                'd_date'     => date('Y-m-d'),
                'c_status'  =>'Y'
            );

            $result = $this->Document_model->insert_document($insert_array);

            if ($result) {

                $this->session->set_flashdata(
                    'success',
                    'Document Uploaded Successfully'
                );

                redirect('upload_document');

            } else {

                $this->session->set_flashdata(
                    'error',
                    'Something went wrong'
                );
            }
            redirect('upload_document');
        }
    }

   
}



}