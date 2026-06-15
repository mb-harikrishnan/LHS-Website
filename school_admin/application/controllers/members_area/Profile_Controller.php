<?php
    // defined('BASEPATH') OR exit('No direct script access allowed');

    // class Profile_Controller extends CI_Controller {


    
    //     public function __construct()
    // 	{
    // 		parent::__construct();
    // 		$this->load->library('session');
    //         $this->load->model('Profile_Model');

    //         if($this->session->userdata(SESSION_VARIABLE))		
    // 		{

    //         }
    // 		else
    // 		{
    // 		    redirect('member_login', 'refresh');
    // 		}

                
    // 	}


    //     public function index()
    //     {
    //         $data['user_details'] = $this->Profile_Model->fetch_all_user_details();
    //         $this->load->view('members_area/header');
    //         $this->load->view('members_area/profile',$data);
    //         $this->load->view('members_area/footer');
    //     }




    //     // Controller Function
    // public function update_profile_photo()
    // {

    //     $userid = $this->session->userdata('id');

    //     if(!empty($_FILES['profile_photo']['name']))
    //     {

    //         $config['upload_path']   = './assets/images';
    //         $config['allowed_types'] = 'jpg|jpeg|png';
    //         $config['encrypt_name']  = TRUE;

    //         $this->load->library('upload', $config);

    //         if($this->upload->do_upload('profile_photo'))
    //         {

    //             $uploadData = $this->upload->data();

    //             $file_name = $uploadData['file_name'];

    //             $this->db->where('n_id', $userid);

    //             $update = $this->db->update('address_dtl', [
    //                 'c_profile_photo' => $file_name
    //             ]);

    //             if($update)
    //             {

    //                 echo json_encode([
    //                     'status' => 'success'
    //                 ]);

    //             }
    //             else
    //             {

    //                 echo json_encode([
    //                     'status' => 'error',
    //                     'message' => 'Database update failed'
    //                 ]);

    //             }

    //         }
    //         else
    //         {

    //             echo json_encode([
    //                 'status' => 'error',
    //                 'message' => strip_tags($this->upload->display_errors())
    //             ]);

    //         }

    //     }
    //     else
    //     {

    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'No image selected'
    //         ]);

    //     }

    // }





    // public function edit_profile()
    // {

    //     $this->form_validation->set_rules('pfFullName', 'Full Name', 'required');
    //     $this->form_validation->set_rules('pfEmail', 'Email', 'required|valid_email');
    //     $this->form_validation->set_rules('pfPhone', 'Mobile Number', 'required|numeric|exact_length[10]');
    //     $this->form_validation->set_rules('pfDob', 'Date of Birth', 'required');
    //     $this->form_validation->set_rules('pfGender', 'Gender', 'required');
    //     $this->form_validation->set_rules('pfAddress', 'Address', 'required');
    //     $this->form_validation->set_rules('pfCity', 'City', 'required');
    //     $this->form_validation->set_rules('pfState', 'State', 'required');
    //     $this->form_validation->set_rules('pfPincode', 'Pincode', 'required|numeric|exact_length[6]');

    //     if ($this->form_validation->run() == FALSE)
    //     {

    //         $this->session->set_flashdata('error', validation_errors());

    //         redirect($_SERVER['HTTP_REFERER']);

    //     }
    //     else
    //     {

    //         $dob = $this->input->post('pfDob');

    //         $birthDate = new DateTime($dob);

    //         $today = new DateTime();

    //         $age = $today->diff($birthDate)->y;

    //         if($age < 18)
    //         {

    //             $this->session->set_flashdata('error', 'Age must be 18 or above');

    //             redirect($_SERVER['HTTP_REFERER']);

    //         }

    //         $id = $this->session->userdata('id');

    //         $data = array(

    //             'C_FNAME'     => $this->input->post('pfFullName'),
    //             'C_EMAIL'     => $this->input->post('pfEmail'),
    //             'C_MOBILE'    => $this->input->post('pfPhone'),
    //             'C_DOB'       => $this->input->post('pfDob'),
    //             'C_GENDER'    => $this->input->post('pfGender'),
    //             'C_ADDRESS'   => $this->input->post('pfAddress'),
    //             'C_CITY'      => $this->input->post('pfCity'),
    //             'C_STATE'     => $this->input->post('pfState'),
    //             'C_ZIP_CODE'  => $this->input->post('pfPincode')

    //         );

    //         $this->db->where('n_id', $id);

    //         $update = $this->db->update('address_dtl', $data);

    //         if($update)
    //         {

    //             $this->session->set_flashdata('success', 'Profile updated successfully');

    //         }
    //         else
    //         {

    //             $this->session->set_flashdata('error', 'Something went wrong');

    //         }

    //         redirect('profile');

    //     }

    // }




    // public function update_bank_details()
    // {
    //     $data = array(

    //         'C_ACC_HOLDER' => $this->input->post('kycAccountName'),
    //         'C_BANK'    => $this->input->post('kycBankName'),
    //         'C_ACC_NO'   => $this->input->post('kycAccount_No'),
    //         'C_IFC_CODE'         => $this->input->post('kycIfsc'),
    //         'C_BRANCH'       => $this->input->post('kycBranch'),
    //         'C_ACC_TYPE' => $this->input->post('kycAccountType'),
    //         'C_BANK_APPROVEL' =>'P'

    //     );

    //     $this->db->where('n_id', $this->session->userdata('id'));
    //     $update = $this->db->update('address_dtl', $data);

    //     if($update)
    //     {
    //         echo json_encode([
    //             'status' => 'success'
    //         ]);
    //     }
    //     else
    //     {
    //         echo json_encode([
    //             'status' => 'error',
    //             'message' => 'Update failed'
    //         ]);
    //     }
    // }







    // public function update_pan()
    // {

    //     $member_id = $this->session->userdata('id');

    //     $data = array(

    //         'C_PAN'        => strtoupper($this->input->post('panNumber')),
    //         'C_PAN_NAME'   => $this->input->post('panName'),
    //         'C_FATHER'     => $this->input->post('panFather'),
    //         'C_DOB'        => $this->input->post('panDob'),
    //         'C_PAN_APPROVEL'        => 'P',
            

    //     );


    //     /* =========================
    //        PAN IMAGE UPLOAD
    //     ========================== */

    //     if(!empty($_FILES['panImage']['name']))
    //     {

    //         $config['upload_path']   = 'assets/uploads/pan/';
    //         $config['allowed_types'] = 'jpg|jpeg|png';
    //         $config['encrypt_name']  = TRUE;

    //         $this->load->library('upload', $config);

    //         if($this->upload->do_upload('panImage'))
    //         {

    //             $uploadData = $this->upload->data();

    //             $data['C_PANCARD_FILE'] = $uploadData['file_name'];

    //         }
    //         else
    //         {

    //             echo json_encode(array(

    //                 'status'  => 'error',
    //                 'message' => $this->upload->display_errors()

    //             ));

    //             return;

    //         }

    //     }


    //     /* =========================
    //        UPDATE QUERY
    //     ========================== */

    //     $this->db->where('n_id', $member_id);

    //     $update = $this->db->update('address_dtl', $data);


    //     /* =========================
    //        RESPONSE
    //     ========================== */

    //     if($update)
    //     {

    //         echo json_encode(array(

    //             'status'  => 'success',
    //             'message' => 'PAN details updated successfully'

    //         ));

    //     }
    //     else
    //     {

    //         echo json_encode(array(

    //             'status'  => 'error',
    //             'message' => 'Something went wrong'

    //         ));

    //     }

    // }




    // }
