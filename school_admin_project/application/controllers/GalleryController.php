<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GalleryController extends CI_Controller
{
    const MAX_FILES = 30;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('School_model');


         if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
    }

    // page 1: albums (one card per type)
    public function gallery()
    {
        $data['albums'] = $this->School_model->get_gallery_albums();
        $data['types']  = $this->School_model->get_event_types();

        $this->load->view('header');
        $this->load->view('gallery', $data);
        $this->load->view('footer');
    }

    // page 2: images of one type
    public function gallery_album($type_id = 0)
    {
        $type = $this->School_model->get_event_type($type_id);
        if (!$type) {
            show_404();
        }

        $data['type']          = $type;
        $data['images']        = $this->School_model->get_images_by_type($type->slno);
        $data['types']         = $this->School_model->get_event_types();
        $data['selected_type'] = $type->slno;

        $this->load->view('header');
        $this->load->view('gallery_album', $data);
        $this->load->view('footer');
    }

    // add images (AJAX, multiple files)
    public function save_gallery_images()
    {
        $type = (int) $this->input->post('type');
        if (!$this->School_model->get_event_type($type)) {
            return $this->_json(false, 'Please select a valid category.');
        }

        if (empty($_FILES['images']['name'][0])) {
            return $this->_json(false, 'Please add at least one image.');
        }

        $files = $_FILES['images'];
        $count = count($files['name']);
        if ($count > self::MAX_FILES) {
            return $this->_json(false, 'Maximum ' . self::MAX_FILES . ' images at a time.');
        }

        $this->load->library('upload');
        $saved  = 0;
        $errors = array();

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = $files['name'][$i] . ': upload error.';
                continue;
            }

            // CI3 uploads one file at a time, so rebuild $_FILES for each
            $_FILES['one'] = array(
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i]
            );

            $this->upload->initialize(array(
                'upload_path'   => '../assets/images/gallery/',
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 5120,
                'encrypt_name'  => TRUE
            ));

            if ($this->upload->do_upload('one')) {
                $up = $this->upload->data();
                $this->School_model->insert_gallery_image(array(
                    'c_type'   => $type,
                    'c_image'  => $up['file_name'],
                    'c_status' => 'Y',
                    'd_date'   => date('Y-m-d')
                ));
                $saved++;
            } else {
                $errors[] = $files['name'][$i] . ': ' . strip_tags($this->upload->display_errors('', ''));
            }
        }

        if ($saved === 0) {
            return $this->_json(false, $errors ? implode(' ', array_slice($errors, 0, 3)) : 'Upload failed.');
        }

        $msg = $saved . ' image(s) added.';
        if ($errors) {
            $msg .= ' Skipped: ' . implode(' ', array_slice($errors, 0, 3));
        }
        return $this->_json(true, $msg);
    }

    // delete one image (AJAX, soft delete)
    public function delete_gallery_image()
    {
        $id = (int) $this->input->post('id');
        $ok = $id > 0 && $this->School_model->delete_image($id);

        return $this->_json($ok, $ok ? 'Image deleted.' : 'Could not delete.');
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
















    
}