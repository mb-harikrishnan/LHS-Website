<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('News_model');
	}



    public function school_news()
    {

        // $from_date = $this->input->post('fromDate');
        // $to_date = $this->input->post('toDate');

        // if(empty($from_date) && empty($to_date)) {
        //     $from_date = date('Y-m-d');
        //     $to_date = date('Y-m-d');
        // } 

        $data['news'] = $this->News_model->get_all_news();

        $this->load->view('members_area/header');
        $this->load->view('members_area/school_news', $data);
        $this->load->view('members_area/footer');
    }


    public function add_news()
    {
        $this->load->view('members_area/header');
        $this->load->view('members_area/add_news');
        $this->load->view('members_area/footer');
    }


    public function insert_school_news()
    {
        $data = array(
            'c_title' => $this->input->post('title'),
            'c_news' => $this->input->post('description'),
            'c_status' => 'Y',
            'd_date' => date('Y-m-d')
        );

        $result = $this->db->insert('school_news', $data);

        if ($result)
        {
            $this->session->set_flashdata('success', 'News added successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Failed to add news');
        }

        redirect('add_news');
    }


      public function delete_news()
    {
        $id = $this->input->post('id');

        $result = $this->News_model->delete_news($id);

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