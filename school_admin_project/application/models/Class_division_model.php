<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Class_division_model extends CI_Model
{
    private $alloc = 'class_division_allocation';

    /* all classes (for the dropdown) */
    public function get_classes()
    {
        return $this->db->select('cmId, cmName')->order_by('cmId', 'ASC')
                        ->get('class_master')->result();
    }

    /* all divisions (for the checkboxes) */
    public function get_divisions()
    {
        return $this->db->select('dmId, dmName')->order_by('dmName', 'ASC')
                        ->get('division_master')->result();
    }

    /* one class or NULL */
    public function get_class_division($id)
    {
        return $this->db->where('cmId', (int)$id)->get('class_master')->row();
    }

    /* division ids already allocated to a class */
    public function get_selected_divisions($classId)
    {
        $result = $this->db->select('cdaDmId')
                           ->from($this->alloc)
                           ->where('cdaCmId', (int)$classId)
                           ->get()->result();
        $ids = array();
        foreach ($result as $row) {
            $ids[] = (int)$row->cdaDmId;
        }
        return array_values(array_unique($ids));
    }

    /* every class that has divisions: [ {cmId, cmName, divisions:[{dmId, dmName}]} ] */
    public function get_all_allocations()
    {
        $sql = "SELECT c.cmId, c.cmName, d.dmId, d.dmName
                FROM class_division_allocation a
                JOIN class_master c    ON c.cmId = a.cdaCmId
                JOIN division_master d ON d.dmId = a.cdaDmId
                ORDER BY c.cmId ASC, d.dmName ASC";
        $rows = $this->db->query($sql)->result();

        $out  = array();
        $seen = array();
        foreach ($rows as $r) {
            $key = $r->cmId . '-' . $r->dmId;
            if (isset($seen[$key])) { continue; }          // ignore duplicate allocation rows
            $seen[$key] = true;

            if (!isset($out[$r->cmId])) {
                $o = new stdClass();
                $o->cmId      = (int)$r->cmId;
                $o->cmName    = $r->cmName;
                $o->divisions = array();
                $out[$r->cmId] = $o;
            }
            $d = new stdClass();
            $d->dmId   = (int)$r->dmId;
            $d->dmName = $r->dmName;
            $out[$r->cmId]->divisions[] = $d;
        }
        return array_values($out);
    }

    /* which of these division ids really exist in division_master? */
    public function count_valid_divisions($ids)
    {
        if (empty($ids)) { return 0; }
        return (int)$this->db->where_in('dmId', $ids)->count_all_results('division_master');
    }

    /* make the class's divisions exactly $div_ids (only inserts new / deletes removed rows) */
    public function save_allocation($class_id, $div_ids)
    {
        $class_id = (int)$class_id;
        $current  = $this->get_selected_divisions($class_id);
        $div_ids  = array_values(array_unique(array_map('intval', $div_ids)));

        $to_delete = array_values(array_diff($current, $div_ids));
        $to_add    = array_values(array_diff($div_ids, $current));

        $this->db->trans_start();

        if (!empty($to_delete)) {
            $this->db->where('cdaCmId', $class_id)
                     ->where_in('cdaDmId', $to_delete)
                     ->delete($this->alloc);
        }
        if (!empty($to_add)) {
            $batch = array();
            foreach ($to_add as $dm) {
                $batch[] = array('cdaCmId' => $class_id, 'cdaDmId' => $dm);
            }
            $this->db->insert_batch($this->alloc, $batch);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* remove every division from a class */
    public function delete_allocation($class_id)
    {
        return $this->db->where('cdaCmId', (int)$class_id)->delete($this->alloc);
    }
}