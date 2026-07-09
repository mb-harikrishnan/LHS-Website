<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome_Model extends CI_Model
{

public function fetch_all_states()
{
    $select_state = "SELECT name,code FROM country_states ";
    $query = $this->db->query($select_state);
    $result = $query->result();
    return $result ;
}







}