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
        return $this->db->update('teacher_news', $data);
    }

    // soft delete
    public function delete_news($id)
    {
        $this->db->where('n_slno', $id);
        return $this->db->update('teacher_news', array('c_status' => 'D'));
    }

    public function get_all_teacher_news()
    {
        return $this->db
            ->where_in('c_status', array('Y', 'N'))
            ->order_by('n_slno', 'DESC')
            ->get('teacher_news')
            ->result();
    }

    public function insert_teacher_news($data)
    {
        return $this->db->insert('teacher_news', $data);
    }

    public function update_teacher_news($id, $data)
    {
        $this->db->where('n_slno', $id);
        return $this->db->update('teacher_news', $data);
    }

    // soft delete
    public function delete_teacher_news($id)
    {
        $this->db->where('n_slno', $id);
        return $this->db->update('teacher_news', array('c_status' => 'D'));
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
// public function get_gallery_albums()
// {
//     $sql = "SELECT t.slno AS type_id, t.name,
//                    COUNT(i.n_slno) AS total,
//                    MAX(i.d_date)   AS last_date,
//                    (SELECT c_image FROM school_event_images
//                      WHERE c_type = t.slno AND c_status = 'Y'
//                      ORDER BY n_slno DESC LIMIT 1) AS cover
//             FROM events t
//             LEFT JOIN school_event_images i
//                    ON i.c_type = t.slno AND i.c_status = 'Y'
//             WHERE t.c_status = 1
//             GROUP BY t.slno, t.name
//             ORDER BY MAX(i.n_slno) DESC, t.name ASC";

//     return $this->db->query($sql)->result();
// }

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






    private $table = 'all_activities';
    private $pk    = 'n_slno';
 
    /* single source of truth for the type list: slug => label */
    public function get_types()
    {
        return array(
            'Library'                      => 'Library',
            'extra_curricular_activities'  => 'Extra Curricular Activities',
            'sports'                       => 'Sports',
            'volley_ball'                  => 'Volley Ball',
            'basket_ball'                  => 'Basket Ball',
            'foot_ball'                    => 'Foot Ball',
            'cricket'                      => 'Cricket',
            'kho_kho'                      => 'Kho-Kho',
            'badminton'                    => 'Badminton',
            'Roll_ball'                    => 'Roll Ball',
            'dance'                        => 'Dance',
            'music'                        => 'Music',
            'yoga'                         => 'Yoga',
            'karate'                       => 'Karate',
            'drawing'                      => 'Drawing',
            'Painting'                     => 'Painting',
            'roller_skating'               => 'Roller Skating',
            'transportation_facility'      => 'Transportation Facility',
            'educational_tours_excursions' => 'Educational Tours / Excursions',
            'computer_labs'                => 'Computer Labs',
            'science_labs'                 => 'Science Labs',
            'smart_class_facilities'       => 'Smart Class Facilities',
            'stationary_to_students'       => 'Stationary to Students',
            'low_achievers'                => 'Low Achievers',
        );
    }
 
    /* all active rows, newest first */
    public function get_all()
    {
        return $this->db
            ->select($this->pk . ' AS n_slno, c_type, d_date, c_images AS c_images', FALSE)
            ->from($this->table)
            ->where('c_status', 'Y')
            ->order_by('d_date', 'DESC')
            ->order_by($this->pk, 'DESC')
            ->get()->result();
    }
 
    /* one active row or NULL */
    public function get($id)
    {
        return $this->db
            ->select($this->pk . ' AS n_slno, c_type, d_date, c_images AS c_images', FALSE)
            ->from($this->table)
            ->where($this->pk, (int)$id)
            ->where('c_status', 'Y')
            ->get()->row();
    }
 
    /* does an active row with this type exist (ignoring $exclude_id)? */
    public function type_exists_s($type, $exclude_id = 0)
    {
        $this->db->where('c_type', $type)->where('c_status', 'Y');
        if ($exclude_id) {
            $this->db->where($this->pk . ' !=', (int)$exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
 
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }
 
    public function update($id, $data)
    {
        return $this->db->where($this->pk, (int)$id)->update($this->table, $data);
    }
 
    /* soft delete: keeps the row and the image file */
    public function soft_delete($id)
    {
        return $this->db->where($this->pk, (int)$id)->update($this->table, array('c_status' => 'N'));
    }




public function get_gallery_albums()
{
    $sql = "SELECT t.slno AS type_id,
                   t.name,
                   COALESCE(NULLIF(t.c_cover, ''),
                       (SELECT s.c_image
                          FROM school_event_images s
                         WHERE s.c_type = t.slno AND s.c_status = 'Y'
                         ORDER BY s.n_slno DESC LIMIT 1)) AS cover,
                   COUNT(i.n_slno) AS total,
                   MAX(i.d_date)   AS last_date
            FROM events t
            LEFT JOIN school_event_images i
                   ON i.c_type = t.slno AND i.c_status = 'Y'
            WHERE t.c_status = 1
            GROUP BY t.slno, t.name, t.c_cover
            ORDER BY t.slno DESC";

    return $this->db->query($sql)->result();
}

public function event_type_exists($name)
{
    return $this->db->where('name', $name)->count_all_results('events') > 0;
}

public function insert_event_type($data)
{
    $this->db->insert('events', $data);
    return $this->db->insert_id();
}





// public function delete_image($id)
// {
//     $this->db->where('n_slno', (int) $id)
//              ->update('school_event_images', array('c_status' => 'N'));
//     return $this->db->affected_rows() > 0;
// }

// // soft delete album + all its images
// public function delete_event_type($id)
// {
//     $id = (int) $id;

//     $this->db->trans_start();
//     $this->db->where('slno', $id)->update('events', array('c_status' => 0));
//     $this->db->where('c_type', $id)->update('school_event_images', array('c_status' => 'N'));
//     $this->db->trans_complete();

//     return $this->db->trans_status();
// }


public function delete_event_type($id)
{
    $this->db->where('slno', (int) $id)
             ->update('events', array('c_status' => 0));

    return $this->db->affected_rows() > 0;
}


// delete ONE image  (n_slno)
public function delete_image($id)
{
    $this->db->where('n_slno', (int) $id)
             ->update('school_event_images', array('c_status' => 'N'));
    return $this->db->affected_rows() > 0;
}

// delete ALL images of an album  (c_type)
public function delete_images_by_type($type_id)
{
    $this->db->where('c_type', (int) $type_id)
             ->where('c_status', 'Y')
             ->update('school_event_images', array('c_status' => 'N'));
    return $this->db->affected_rows() > 0;
}


}