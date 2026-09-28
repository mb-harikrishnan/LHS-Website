<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Class_Model extends CI_Model
{


public function fetch_all_class()
{
    $select = "SELECT * FROM class_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
}



public function fetch_all_divisions()
{
    $select = "SELECT * FROM division_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
}
public function fetch_class()
{
    $select = "SELECT * FROM class_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
}
public function fetch_divition()
{
    $select = "SELECT * FROM division_master ";
    $query = $this->db->query($select);
    $result = $query->result();
    return $result;
}




public function insert_class_division($cmId, $dmIds)
{
    $success = true;

    foreach ($dmIds as $dmId) {
        // skip duplicate combos already saved
        $this->db->where('cdaCmId', $cmId);
        $this->db->where('cdaDmId', $dmId);
        $exists = $this->db->get('class_division_allocation')->row(); // your actual join table

        if (!$exists) {
            $data = array(
                'cdaCmId' => $cmId,
                'cdaDmId' => $dmId
            );

            $inserted = $this->db->insert('class_division_allocation', $data);

            if (!$inserted) {
                $success = false;
            }
        }
    }

    return $success;
}



public function fetch_all_details()
{
    $this->db->select('
        c.cmId AS cmId,
        c.cmName,
        GROUP_CONCAT(d.dmName ORDER BY d.dmName SEPARATOR ", ") AS divisions
    ');
    $this->db->from('class_division_allocation a');
    $this->db->join('class_master c', 'c.cmId = a.cdaCmId');
    $this->db->join('division_master d', 'd.dmId = a.cdaDmId');
    $this->db->group_by('a.cdaCmId');

    $query = $this->db->get();

    return $query->result();
}


public function get_class_division($id)
{
    return $this->db
            ->where('cmId',$id)
            ->get('class_master')
            ->row();
}



public function get_selected_divisions($classId)
{
    $this->db->select('cdaDmId');
    $this->db->from('class_division_allocation');
    $this->db->where('cdaCmId',$classId);

    $result = $this->db->get()->result();

    $ids = array();

    foreach($result as $row)
    {
        $ids[] = $row->cdaDmId;
    }

    return $ids;
}





public function delete_divition($id)
{
    $this->db->where('cdaCmId', $id);
    return $this->db->delete('class_division_allocation');
}
public function delete_divition_table($id)
{
    $this->db->where('dmId', $id);
    return $this->db->delete('division_master');
}

public function delete_class($id)
{
    $this->db->where('cmId', $id);
    return $this->db->delete('class_master');
}





}