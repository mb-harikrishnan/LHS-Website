<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Paper_model extends CI_Model
{
    private $table = 'question_paper';

    /* class list: value => label */
     public function get_classes()
    {
        $rows = $this->db->select('cmId, cmName')
                         ->order_by('cmId', 'ASC')
                         ->get('class_master')
                         ->result();
 
        $c = array();
        foreach ($rows as $r) {
            $c[(string)$r->cmId] = $r->cmName;
        }
        return $c;
    }

    /* all active papers between two dates (bound parameters, safe) */
    public function get_all_paper($from_date, $to_date)
    {
        $sql = "SELECT * FROM question_paper
                WHERE c_status = 'Y' AND d_date BETWEEN ? AND ?
                ORDER BY n_slno DESC";
        return $this->db->query($sql, array($from_date, $to_date))->result();
    }

    /* one active paper or NULL */
    public function get_paper($id)
    {
        return $this->db->where('n_slno', (int)$id)
                        ->where('c_status', 'Y')
                        ->get($this->table)->row();
    }

    public function insert_paper($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update_paper($id, $data)
    {
        return $this->db->where('n_slno', (int)$id)->update($this->table, $data);
    }

    /* soft delete */
    public function delete_papper($id)
    {
        $this->db->where('n_slno', (int)$id);
        return $this->db->update($this->table, array('c_status' => 'D'));
    }
}