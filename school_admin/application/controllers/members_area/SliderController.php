<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SliderController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('Slider_model');
	}



    public function slider_list()
    {   



        $image['image'] = $this->Slider_model->get_all_images();

        $this->load->view('members_area/header');
        $this->load->view('members_area/slider_list', $image);
        $this->load->view('members_area/footer');
    }


    public function add_slider()
    {
        $this->load->view('members_area/header');
        $this->load->view('members_area/add_slider');
        $this->load->view('members_area/footer');
    }


  public function insert_slider()
{

    date_default_timezone_set('Asia/Kolkata');

    $title         = $this->input->post('title');
    $description   = $this->input->post('description');
    $upload_type   = $this->input->post('upload_type');
    $external_link = $this->input->post('external_link');

    $file_name = "";

    /* Image Upload */

    if($upload_type == "image")
    {

        if(!empty($_FILES['news_image']['name']))
        {

            $config['upload_path']   = '../assets/images/gallery/';
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('news_image'))
            {

                $upload_data = $this->upload->data();

                $file_name = $upload_data['file_name'];

            }
            else
            {

                $this->session->set_flashdata('error', $this->upload->display_errors());

                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    /* Video Upload */

    if($upload_type == "video")
    {

        if(!empty($_FILES['news_video']['name']))
        {

            $config['upload_path']   = '../assets/images/gallery/';
            $config['allowed_types'] = 'mp4|avi|mov|mkv';
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('news_video'))
            {

                $upload_data = $this->upload->data();

                $file_name = $upload_data['file_name'];

            }
            else
            {

                $this->session->set_flashdata('error', $this->upload->display_errors());

                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    /* Link */

    if($upload_type == "link")
    {
        $file_name = $external_link;
    }

    /* Insert */

    $insert = array(

        'c_title'         => $title,
        'c_description'   => $description,
        'c_upload_type'   => $upload_type,
        'c_file'          => $file_name,
        'd_date'          => date('Y-m-d'),
        'c_status'        => 'A'

    );

    $this->db->insert('school_sliders', $insert);

    if($this->db->affected_rows() > 0)
    {

        $this->session->set_flashdata('success', ' Added Successfully');

    }
    else
    {

        $this->session->set_flashdata('error', 'Something Went Wrong');

    }

    redirect('add_slider');

}




      public function delete_slider()
    {
        $id = $this->input->post('id');

        $result = $this->Slider_model->delete_slider($id);

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