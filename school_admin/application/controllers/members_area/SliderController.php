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




    public function edit_slider($id)
{
    $data['slider'] = $this->db
        ->where('n_slno', $id)
        ->get('school_sliders')
        ->row();

        $this->load->view('members_area/header');
        $this->load->view('members_area/edit_slider', $data);
        $this->load->view('members_area/footer');

}


// public function update_slider()
// {
//     $id = $this->input->post('id');

//     $data = array(
//         'c_title'       => $this->input->post('title'),
//         'c_description' => $this->input->post('description')
//     );

//     $this->db->where('n_slno', $id);
//     $update = $this->db->update('school_sliders', $data);

//     if($update){
//         redirect('slider_list');
//     }
// }


public function update_slider()
{
    $id = $this->input->post('id');

    $updateData = array();

    // TITLE
    if($this->input->post('title') != ''){
        $updateData['c_title'] = $this->input->post('title');
    }

    // DESCRIPTION
    if($this->input->post('description') != ''){
        $updateData['c_description'] = $this->input->post('description');
    }

    // UPLOAD TYPE
    if($this->input->post('upload_type') != ''){
        $upload_type = $this->input->post('upload_type');

        $updateData['c_upload_type'] = $upload_type;

        // =====================
        // LINK
        // =====================

        if($upload_type == 'link'){

            if($this->input->post('external_link') != ''){

                $updateData['c_file'] = $this->input->post('external_link');

            }

        }

        // =====================
        // IMAGE
        // =====================

        if($upload_type == 'image'){

            if(!empty($_FILES['news_image']['name'])){

                $config['upload_path']   = '../assets/images/gallery/';
                $config['allowed_types'] = 'jpg|jpeg|png|webp';

                $this->load->library('upload', $config);

                if($this->upload->do_upload('news_image')){

                    $uploadData = $this->upload->data();

                    $updateData['c_file'] = $uploadData['file_name'];

                } else {

                    echo $this->upload->display_errors();
                    exit;
                }
            }
        }

        // =====================
        // VIDEO
        // =====================

        if($upload_type == 'video'){

            if(!empty($_FILES['news_video']['name'])){

                $config['upload_path']   = '../assets/images/gallery/';
                $config['allowed_types'] = 'webm|mp4';

                $this->upload->initialize($config);

                if($this->upload->do_upload('news_video')){

                    $uploadData = $this->upload->data();

                    $updateData['c_file'] = $uploadData['file_name'];

                } else {

                    echo $this->upload->display_errors();
                    exit;
                }
            }
        }
    }

    // UPDATE
    $this->db->where('n_slno', $id);

    $update = $this->db->update('school_sliders', $updateData);

    if($update){

        redirect('slider_list');

    } else {

        echo "Update Failed";
    }
}

}