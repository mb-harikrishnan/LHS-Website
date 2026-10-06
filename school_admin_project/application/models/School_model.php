<?php defined('BASEPATH') OR exit('No direct script access allowed');

class School_model extends CI_Model
{
    public function get_all_news()
    {
        return $this->db
            ->where_in('c_status', array('Y', 'N'))
            ->order_by('n_slno', 'DESC')
            ->get('school_news')
            ->result();
    }

    public function insert_news($data)
    {
        return $this->db->insert('school_news', $data);
    }

    public function update_news($id, $data)
    {
        $this->db->where('n_slno', $id);
        return $this->db->update('school_news', $data);
    }

    // soft delete
    public function delete_news($id)
    {
        $this->db->where('n_slno', $id);
        return $this->db->update('school_news', array('c_status' => 'D'));
    }





    // types for the dropdown
public function get_event_types()
{
    return $this->db->where('c_status', 1)
                    ->order_by('name', 'ASC')
                    ->get('events')->result();
}

public function get_event_type($id)
{
    return $this->db->where(array('slno' => (int) $id, 'c_status' => 1))
                    ->get('events')->row();
}

// one card per type: photo count, latest cover, latest date
public function get_gallery_albums()
{
    $sql = "SELECT t.slno AS type_id, t.name,
                   COUNT(i.n_slno) AS total,
                   MAX(i.d_date)   AS last_date,
                   (SELECT c_image FROM school_event_images
                     WHERE c_type = t.slno AND c_status = 'Y'
                     ORDER BY n_slno DESC LIMIT 1) AS cover
            FROM events t
            LEFT JOIN school_event_images i
                   ON i.c_type = t.slno AND i.c_status = 'Y'
            WHERE t.c_status = 1
            GROUP BY t.slno, t.name
            ORDER BY MAX(i.n_slno) DESC, t.name ASC";

    return $this->db->query($sql)->result();
}

// all images of one type
public function get_images_by_type($type_id)
{
    return $this->db->where(array('c_type' => (int) $type_id, 'c_status' => 'Y'))
                    ->order_by('n_slno', 'DESC')
                    ->get('school_event_images')->result();
}

public function insert_gallery_image($data)
{
    return $this->db->insert('school_event_images', $data);
}






////////////////////////////////////////////


public function get_all_activities($type = null)
{
    $this->db->where('c_status', 'Y');
    if ($type) {
        $this->db->where('c_type', $type);
    }
    $this->db->order_by('n_slno', 'DESC');
    return $this->db->get('co_curricular_activities')->result();
}
 
/** All types from master table */
public function get_all_types()
{
    return $this->db->select('c_type')
                    ->where('c_status', 'Y')
                    ->order_by('c_type', 'ASC')
                    ->get('co_curricular_activities')
                    ->result();
}
 
/** Types already used in the listing (active rows) */
public function get_used_types()
{
    $rows = $this->db->distinct()->select('c_type')
                     ->where('c_status', 'Y')
                     ->get('co_curricular_activities')
                     ->result();
    return array_map(function ($r) { return $r->c_type; }, $rows);
}
 
public function type_is_valid($type)
{
    return $this->db->where('c_type', $type)->where('c_status', 'Y')
                    ->count_all_results('co_curricular_activities') > 0;
}
 
/** Does this type already exist? ($exclude_id = row being edited) */
public function type_exists($type, $exclude_id = 0)
{
    $this->db->where('c_type', $type)->where('c_status', 'Y');
    if ($exclude_id) {
        $this->db->where('n_slno !=', (int) $exclude_id);
    }
    return $this->db->count_all_results('co_curricular_activities') > 0;
}
 
public function insert_activities($data)
{
    $data['c_status'] = 'Y';
    return $this->db->insert('co_curricular_activities', $data);
}
 
public function update_activities($id, $data)
{
    $this->db->where('n_slno', (int) $id);
    return $this->db->update('co_curricular_activities', $data);
}
 
public function delete_activities($id)
{
    $this->db->where('n_slno', (int) $id);
    return $this->db->update('co_curricular_activities', array('c_status' => 'D'));
}

















}