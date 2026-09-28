<?php
Class notification_Model extends CI_Model
{

    public function fetch_all_news()
    {
        $sql = "SELECT * FROM news WHERE c_status = 'A'";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result ;

    }
	



}