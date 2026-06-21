<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VaccancyController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		$this->load->model('Vacancy_model');
	}



    public function vaccancy_list()
    {   


        $from_date = $this->input->post('fromDate');
        $to_date = $this->input->post('toDate');

        if(empty($from_date) && empty($to_date)) {
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
        } 

        $image['vacancy'] = $this->Vacancy_model->get_all_vacancy($from_date, $to_date);

        $this->load->view('members_area/header');
        $this->load->view('members_area/vaccancy_list', $image);
        $this->load->view('members_area/footer');
    }


    public function add_vacancy()
    {
        $this->load->view('members_area/header');
        $this->load->view('members_area/add_vacancy');
        $this->load->view('members_area/footer');
    }


    public function insert_vacancy()
    {
        $data = array(
            'c_status' => 'Y',
            'd_date' => date('Y-m-d'),
            'c_title' => $this->input->post('title'),
            'c_description'  => $this->input->post('description')
        );

        $result = $this->db->insert('school_vacancy', $data);

        if ($result)
        {
            $this->session->set_flashdata('success', ' added successfully');
        }
        else
        {
            $this->session->set_flashdata('error', 'Failed to add');
        }

        redirect('add_vacancy');
    }





      public function delete_vacancy()
    {
        $id = $this->input->post('id');

        $result = $this->Vacancy_model->delete_vacancy($id);

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