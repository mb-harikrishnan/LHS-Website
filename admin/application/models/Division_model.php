<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Save as: application/models/Division_model.php
 * (You can also merge these methods into your existing class model and change
 *  the model name in the controller.)
 *
 * Tables:
 *   division_master            dmId (PK), dmName
 *   class_division_allocation  cdaCmId (class id), cdaDmId (division id)
 */
class Division_model extends CI_Model
{
    /* all divisions with the number of classes that use each one */
    public function get_all_divisions()
    {
        $sql = "SELECT d.dmId, d.dmName, COUNT(a.cdaDmId) AS used_count
                FROM division_master d
                LEFT JOIN class_division_allocation a ON a.cdaDmId = d.dmId
                GROUP BY d.dmId, d.dmName
                ORDER BY d.dmName ASC";
        return $this->db->query($sql)->result();
    }

    /* one division or NULL */
    public function get_division($id)
    {
        return $this->db->where('dmId', (int)$id)->get('division_master')->row();
    }

    /* does a division with this name already exist? (case-insensitive, ignores $exclude_id) */
    public function name_exists($name, $exclude_id = 0)
    {
        $sql = "SELECT COUNT(*) AS c FROM division_master
                WHERE LOWER(dmName) = LOWER(?) AND dmId != ?";
        $row = $this->db->query($sql, array($name, (int)$exclude_id))->row();
        return ((int)$row->c) > 0;
    }

    public function insert_division($name)
    {
        return $this->db->insert('division_master', array('dmName' => $name));
    }

    public function update_division($id, $name)
    {
        return $this->db->where('dmId', (int)$id)
                        ->update('division_master', array('dmName' => $name));
    }

    /* how many classes use this division */
    public function used_count($id)
    {
        return (int)$this->db->where('cdaDmId', (int)$id)
                             ->count_all_results('class_division_allocation');
    }

    public function delete_division($id)
    {
        return $this->db->where('dmId', (int)$id)->delete('division_master');
    }
}