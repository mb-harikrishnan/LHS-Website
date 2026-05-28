<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Document_model extends CI_Model
{


    public function fetch_general_information($type)
    {

       $qry = '';

        if($type != '')
        {
            $qry .= " AND c_type = '$type' ";
        }

      $sql = "SELECT * FROM document_master WHERE c_status ='Y' $qry  ";
       $query = $this->db->query($sql);
       $result = $query->result();

       return $result;

        
    }


    public function delete_general_information($id)
    {
        $this->db->where('n_slno', $id);

        return $this->db->update('document_master', array(
            'c_status' => 'D'
        ));
    }


    public function insert_document($data)
    {
        return $this->db->insert('document_master', $data);
    }



 




}