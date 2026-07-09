<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile_Model extends CI_Model
{



    // public function fetch_all_user_details()
    // {
    //     $userId = $this->session->userdata('id');

    //     $sql = "SELECT  c_username ,c_distributor_active,D_JOIN,C_FNAME,C_GENDER,C_STATE,C_CITY,C_EMAIL,C_PAN,C_FATHER,C_PAN_NAME,C_ACC_TYPE,
    //     C_ADDRESS,C_ZIP_CODE,C_MOBILE,C_BANK,C_BRANCH,C_ACC_NO,C_IFC_CODE,C_PANCARD_FILE,C_ACCOUNT_PROOF,C_DOB,C_ACC_HOLDER,
    //     c_profile_photo,C_PAN_APPROVEL,C_BANK_APPROVEL ,c_reason_for_reject_pan,c_reason_for_reject_bank
    //      FROM address_dtl a ,bc_master b WHERE b.pn_id=a.n_id AND  n_id = ? " ;
    //     $query = $this->db->query($sql,array($userId));
    //     $result = $query->result();
    //     return  $result ; 
    // }




}