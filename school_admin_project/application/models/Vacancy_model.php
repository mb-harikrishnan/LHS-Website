
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vacancy_model extends CI_Model
{
    public function get_all_vacancy($from_date, $to_date)
    {
        $sql = "SELECT * FROM school_vacancy
                WHERE c_status = 'Y' AND d_date BETWEEN ? AND ?
                ORDER BY n_slno DESC";
        return $this->db->query($sql, array($from_date, $to_date))->result();
    }

    /* one active vacancy or NULL */
    public function get_vacancy($id)
    {
        return $this->db->where('n_slno', (int)$id)
                        ->where('c_status', 'Y')
                        ->get('school_vacancy')->row();
    }

    public function insert_vacancy($data)
    {
        return $this->db->insert('school_vacancy', $data);
    }

    public function update_vacancy($id, $data)
    {
        return $this->db->where('n_slno', (int)$id)->update('school_vacancy', $data);
    }
public function delete_vacancy($id)
{
    $this->db->where('n_slno', (int)$id);

    return $this->db->update('school_vacancy', array(
        'c_status' => 'D'
    ));
}

}