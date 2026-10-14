<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Employee_Model');
        $this->load->model('Permissions_Model');


         if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}
    }

    public function employee_list()
    {
        $data['employees'] = $this->Employee_Model->get_employees();
        $data['classes']   = $this->Employee_Model->get_classes();
        $data['roles']     = $this->Permissions_Model->get_all_roles(); // active roles = designations

        $this->load->view('header');
        $this->load->view('employee_list', $data);
        $this->load->view('footer');
    }

    // AJAX: divisions of a class
    public function employee_divisions()
{
    $class_id = (int) $this->input->post('class_id');

    $out = [];
    foreach ($this->Employee_Model->get_divisions($class_id) as $r) {
        $out[] = ['id' => (int) $r->dmId, 'name' => $r->dmName];
    }

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode([
             'success'   => true,
             'message'   => '',
             'divisions' => $out
         ]));
}
    private function _input($self_id = 0)
    {
        $d = [
            'name'        => trim($this->input->post('name', TRUE)),
            'mobile'      => trim($this->input->post('mobile', TRUE)),
            'class_id'    => (int) $this->input->post('class_id'),
            'division_id' => (int) $this->input->post('division_id'),
            'role_id'     => (int) $this->input->post('role_id'),   // designation
        ];
        $pw = (string) $this->input->post('password');

        if ($d['name'] === '')                       return [null, null, 'Please enter the name.'];
        if (!preg_match('/^\d{10}$/', $d['mobile'])) return [null, null, 'Mobile number must be 10 digits.'];
        if ($d['role_id'] <= 0 || !$this->Permissions_Model->role_exists($d['role_id'])) {
            return [null, null, 'Please select a designation.'];
        }
        if ($d['class_id'] <= 0)                     return [null, null, 'Please select a class.'];
        if ($d['division_id'] <= 0)                  return [null, null, 'Please select a division.'];
        if ($pw !== '' && strlen($pw) < 6)           return [null, null, 'Password must be at least 6 characters.'];
        if ($this->Employee_Model->mobile_exists($d['mobile'], $self_id)) {
            return [null, null, 'This mobile number is already used.'];
        }
        return [$d, $pw, null];
    }

    public function employee_save()
    {
        list($d, $pw, $err) = $this->_input(0);
        if ($err) return $this->_jsons(false, $err);
        if ($pw === '') return $this->_jsons(false, 'Please enter a password.');

        return $this->Employee_Model->insert_employee($d, $pw)
            ? $this->_jsons(true, 'Employee added successfully.')
            : $this->_jsons(false, 'Failed to add employee.');
    }
    public function employee_update()
    {
        $emp_id = (int) $this->input->post('emp_id');
        if ($emp_id <= 0 || !$this->Employee_Model->employee_exists($emp_id)) {
            return $this->_jsons(false, 'Employee not found.');
        }

        list($d, $pw, $err) = $this->_input($emp_id);
        if ($err) return $this->_jsons(false, $err);

        // $pw === '' means "keep current password"
        return $this->Employee_Model->update_employee($emp_id, $d, $pw)
            ? $this->_jsons(true, 'Employee updated successfully.')
            : $this->_jsons(false, 'Failed to update employee.');
    }

    public function employee_delete()
    {
        $emp_id = (int) $this->input->post('emp_id');
        if ($emp_id <= 0 || !$this->Employee_Model->employee_exists($emp_id)) {
            return $this->_jsons(false, 'Employee not found.');
        }

        return $this->Employee_Model->delete_employee($emp_id)
            ? $this->_jsons(true, 'Employee deleted successfully.')
            : $this->_jsons(false, 'Failed to delete employee.');
    }

    private function _jsons($success, $message)
    {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => (bool) $success,
                 'message' => $message
             ]));
    }
   


}