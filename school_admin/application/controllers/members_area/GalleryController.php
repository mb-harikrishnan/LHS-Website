<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GalleryController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('Gallery_model');
	}



    public function gallery()
    {   


        $from_date = $this->input->post('fromDate');
        $to_date = $this->input->post('toDate');
        $type = $this->input->post('type');

        if(empty($from_date) && empty($to_date)) {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
        } 

        $image['image'] = $this->Gallery_model->get_all_images($from_date, $to_date, $type);

        $this->load->view('members_area/header');
        $this->load->view('members_area/gallery', $image);
        $this->load->view('members_area/footer');
    }


    public function add_gallery_image()
    {
        $this->load->view('members_area/header');
        $this->load->view('members_area/add_gallery_image');
        $this->load->view('members_area/footer');
    }


    public function insert_school_image()
    {
        $data = array(
            'c_type' => $this->input->post('news_type'),
            'c_status' => 'Y',
            'd_date' => date('Y-m-d')
        );

        // Handle file upload
        if (!empty($_FILES['news_image']['name'])) {
            $config['upload_path'] = '../assets/images/gallery/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // 2MB

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('news_image')) {
                $uploadData = $this->upload->data();
                $data['c_image'] =  $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', 'Image upload failed: ' . $this->upload->display_errors());
                redirect('add_gallery_image');
                return;
            }
        }

        $result = $this->db->insert('school_event_images', $data);

        if ($result)
        {
            $this->session->set_flashdata('success', 'Image added successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Failed to add image');
        }

        redirect('add_gallery_image');
    }





      public function delete_image()
    {
        $id = $this->input->post('id');

        $result = $this->Gallery_model->delete_image($id);

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