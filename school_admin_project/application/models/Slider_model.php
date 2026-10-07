<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Save as: application/models/Slider_model.php
 * (If you already have a model with get_all_images / delete_slider you can merge
 *  these methods into it instead and change the model name in the controller.)
 *
 * Table: school_sliders
 *   n_slno          INT AUTO_INCREMENT PRIMARY KEY
 *   c_title         VARCHAR(150)
 *   c_description   TEXT
 *   c_upload_type   VARCHAR(10)    'image' | 'video' | 'link'
 *   c_file          VARCHAR(255)   file name, or the video URL when type = 'link'
 *   d_date          DATE
 *   c_status        CHAR(1)        'A' = active, 'D' = deleted
 */
class Slider_model extends CI_Model
{
    private $table = 'school_sliders';

    /* all active slides, newest first */
    public function get_all_images()
    {
        return $this->db->where('c_status', 'A')
                        ->order_by('n_slno', 'DESC')
                        ->get($this->table)->result();
    }

    /* one active slide or NULL */
    public function get_slider($id)
    {
        return $this->db->where('n_slno', (int)$id)
                        ->where('c_status', 'A')
                        ->get($this->table)->row();
    }

    public function insert_slider($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update_slider($id, $data)
    {
        return $this->db->where('n_slno', (int)$id)->update($this->table, $data);
    }

    /* soft delete */
    public function delete_slider($id)
    {
        $this->db->where('n_slno', (int)$id);
        return $this->db->update($this->table, array('c_status' => 'D'));
    }
}