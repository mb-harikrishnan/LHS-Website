<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EnquiryController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('Employee_Model');
        


         if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
    }





   // list all contact messages
        public function contact()
        {
            $data['contacts'] = $this->db->order_by('n_slno', 'DESC')
                                        ->get('contact_us')->result();

            $this->load->view('header');
            $this->load->view('contact', $data);
            $this->load->view('footer');
        }

        // mark one message as read (AJAX) - status Y -> N, row is NOT removed
        public function read_contact()
        {
            $id = (int) $this->input->post('id');

            if ($id <= 0) {
                return $this->_json(false, 'Invalid message.');
            }

            $this->db->where('n_slno', $id)
                    ->update('contact_us', array('c_status' => 'N'));

            return $this->_json(true, 'Marked as read.');
        }

        private function _json($ok, $msg)
        {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => (bool) $ok,
                    'msg'    => $msg,
                    'csrf'   => $this->security->get_csrf_hash()
                )));
        }





// list all admission enquiries
public function admission()
{
    $data['enquiries'] = $this->db->order_by('id', 'DESC')
                                  ->get('admission_enquiries')->result();

    $this->load->view('header');
    $this->load->view('admission', $data);
    $this->load->view('footer');
}

// mark one enquiry as read (AJAX) - status New -> Read, row is NOT removed
public function read_admission()
{
    $id = (int) $this->input->post('id');

    if ($id <= 0) {
        return $this->_json(false, 'Invalid enquiry.');
    }

    $this->db->where('id', $id)
             ->update('admission_enquiries', array('status' => 'Read'));

    return $this->_json(true, 'Marked as read.');
}








}