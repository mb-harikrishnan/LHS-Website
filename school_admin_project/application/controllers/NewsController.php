<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsController extends CI_Controller
{
    

  function __construct()
  {
    parent::__construct();
        $this->load->model('School_model');
  }

public function school_news()
    {
        $data['news'] = $this->School_model->get_all_news();

        $this->load->view('header');
        $this->load->view('school_news', $data);
        $this->load->view('footer');
    }

    // add + edit (AJAX)
    public function save_news()
    {
        $id     = (int) $this->input->post('id');
        $title  = trim($this->input->post('title', TRUE));
        $news   = trim($this->input->post('description', TRUE));
        $status = $this->input->post('status') === 'N' ? 'N' : 'Y';
        $date   = $this->input->post('date');

        if ($title === '') {
            return $this->_json(false, 'Please enter the news title.');
        }
        if (!$date || !strtotime($date)) {
            $date = date('Y-m-d');
        }

        $data = array(
            'c_title'  => $title,
            'c_news'   => $news,
            'c_status' => $status,
            'd_date'   => $date
        );

        $ok = $id > 0
            ? $this->School_model->update_news($id, $data)
            : $this->School_model->insert_news($data);

        return $this->_json($ok, $ok ? 'Saved successfully.' : 'Could not save news.');
    }

    // delete (AJAX)
    public function delete_news()
    {
        $id = (int) $this->input->post('id');
        $ok = $id > 0 && $this->School_model->delete_news($id);

        return $this->_json($ok, $ok ? 'Deleted.' : 'Could not delete.');
    }

    private function _json($ok, $msg)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => (bool) $ok,
                'msg'    => $msg,
                'csrf'   => $this->security->get_csrf_hash()   // refreshed token
            )));
    }

















}