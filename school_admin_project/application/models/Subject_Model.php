<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Subject_Model extends CI_Model
{




public function get_employee_class_div($user_id)
{
    return $this->db->select('emClass, emDiv')
                    ->where('user_id', (int)$user_id)
                    ->get('employee_master')
                    ->row();
}
 
/* filters: name (name or admission no), class (cmId), division (dmId) */
private function _apply_student_filters($filters)
{
    if (!empty($filters['name'])) {
        $this->db->group_start();
        $this->db->like('s.smName', $filters['name']);
        $this->db->or_like('s.smAdmissionNo', $filters['name']);
        $this->db->group_end();
    }
    if (isset($filters['class']) && $filters['class'] !== '') {
        $this->db->where('s.smClass', $filters['class']);
    }
    if (isset($filters['division']) && $filters['division'] !== '') {
        $this->db->where('s.smDiv', $filters['division']);
    }
}
 
public function count_all_students($filters = array())
{
    $this->db->from('students_master s');
    $this->_apply_student_filters($filters);
    return (int)$this->db->count_all_results();
}
 
/* one page of students, with class and division names */
public function fetch_all_student_details($limit, $start, $filters = array())
{
    $this->db->select('
        s.smId, s.smAdmissionNo, s.smAadharNo, s.smName, s.smClass, s.smDiv,
        s.smGender, s.smMobile, s.smDOB, s.smAddress, s.smReligion, s.smCaste,
        s.smMotherTongue, s.smCountry, s.smState,
        COALESCE(c.cmName, s.smClass) AS className,
        COALESCE(d.dmName, s.smDiv)   AS divName
    ', FALSE);
    $this->db->from('students_master s');
    $this->db->join('class_master c', 'c.cmId = s.smClass', 'left');
    $this->db->join('division_master d', 'd.dmId = s.smDiv', 'left');
 
    $this->_apply_student_filters($filters);
 
    // Class -> Division -> Girls (0) -> Boys (1) -> Name
    $this->db->order_by('s.smClass', 'ASC');
    $this->db->order_by('s.smDiv', 'ASC');
    $this->db->order_by('s.smGender', 'ASC');
    $this->db->order_by('s.smName', 'ASC');
 
    $this->db->limit((int)$limit, (int)$start);
 
    return $this->db->get()->result();
}
 
/* class id => [ {id, name} ] of the divisions assigned to that class */
public function get_class_division_map()
{
    $sql = "SELECT a.cdaCmId AS cid, d.dmId, d.dmName
            FROM class_division_allocation a
            JOIN division_master d ON d.dmId = a.cdaDmId
            ORDER BY a.cdaCmId ASC, d.dmName ASC";
    $map  = array();
    $seen = array();
    foreach ($this->db->query($sql)->result() as $r) {
        $key = $r->cid . '-' . $r->dmId;
        if (isset($seen[$key])) { continue; }
        $seen[$key] = true;
        $map[(string)$r->cid][] = array('id' => (string)$r->dmId, 'name' => $r->dmName);
    }
    return $map;
}
 
/* ---------- single student ---------- */
public function get_student($id)
{
    return $this->db->where('smId', (int)$id)->get('students_master')->row();
}
 
/* admission number already used by another student? (case-insensitive) */
public function admission_no_exists($adm_no, $exclude_id = 0)
{
    $sql = "SELECT COUNT(*) AS c FROM students_master
            WHERE LOWER(smAdmissionNo) = LOWER(?) AND smId != ?";
    $row = $this->db->query($sql, array($adm_no, (int)$exclude_id))->row();
    return ((int)$row->c) > 0;
}
 
public function class_exists_by_id($id)
{
    return $this->db->where('cmId', $id)->count_all_results('class_master') > 0;
}
 
public function division_exists_by_id($id)
{
    return $this->db->where('dmId', $id)->count_all_results('division_master') > 0;
}
 
public function insert_student($data)
{
    return $this->db->insert('students_master', $data);
}
 
public function update_student($id, $data)
{
    return $this->db->where('smId', (int)$id)->update('students_master', $data);
}
 
/* hard delete (students_master has no status column in the fields you showed) */
public function delete_student($id)
{
    return $this->db->where('smId', (int)$id)->delete('students_master');
}



/* permissions of a role for a menu: can_view / can_add / can_edit / can_delete */
/* menu id for a link, e.g. 'students_list' */
public function get_menu_id_by_link($link)
{
    $link = trim($link, '/');

    $row = $this->db->select('menu_id')
                    ->where_in('menu_link', array($link, '/' . $link, $link . '/', '/' . $link . '/'))
                    ->get('menus')
                    ->row();

    return $row ? (int)$row->menu_id : 0;
}

/* permissions of a role for a menu */
public function get_permissions($role_id, $menu_id)
{
    // Admin (role 1) always gets full access
    if ((int)$role_id === 1) {
        return array('can_view' => 1, 'can_add' => 1, 'can_edit' => 1, 'can_delete' => 1);
    }

    if (!$menu_id) {
        return array();
    }

    $row = $this->db->select('can_view, can_add, can_edit, can_delete')
                    ->where('role_id', (int)$role_id)
                    ->where('menu_id', (int)$menu_id)
                    ->get('user_roles_menu_permissions')
                    ->row();

    if (!$row) {
        return array();
    }

    return array(
        'can_view'   => (int)$row->can_view,
        'can_add'    => (int)$row->can_add,
        'can_edit'   => (int)$row->can_edit,
        'can_delete' => (int)$row->can_delete
    );
}

public function get_all_classes()
{
    return $this->db->order_by('cmName', 'ASC')->get('class_master')->result();
}

public function get_all_divisions()
{
    return $this->db->order_by('dmName', 'ASC')->get('division_master')->result();
}
 


}