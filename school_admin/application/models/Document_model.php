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



    // ////

    public function fetch_result_and_staff_list($type)
    {

       $qry = '';

        if($type != '')
        {
            $qry .= " AND c_type = '$type' ";
        }

      $sql = "SELECT * FROM result_and_staff_list WHERE c_status ='Y' $qry  ";
       $query = $this->db->query($sql);
       $result = $query->result();

       return $result;

        
    }


    public function delete_details($id)
    {
        $this->db->where('n_slno', $id);

        return $this->db->update('result_and_staff_list', array(
            'c_status' => 'D'
        ));
    }


    public function insert_document_details($data)
    {
        return $this->db->insert('result_and_staff_list', $data);
    }


    /////
    public function fetch_infrastructure_videos($type)
    {

       $qry = '';

        if($type != '')
        {
            $qry .= " AND c_type = '$type' ";
        }

      $sql = "SELECT * FROM infrastructure_videos WHERE c_status ='Y' $qry  ";
       $query = $this->db->query($sql);
       $result = $query->result();

       return $result;

        
    }


    public function delete_videos($id)
    {
        $this->db->where('n_slno', $id);

        return $this->db->update('infrastructure_videos', array(
            'c_status' => 'D'
        ));
    }


    public function insert_infrastructure_videos($data)
    {
        return $this->db->insert('infrastructure_videos', $data);
    }



 




}