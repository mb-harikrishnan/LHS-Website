<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class DivisionAllocationController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('divisionAllocation_Model');

        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
    }

    /* Page */
    public function division_allocation()
    {
        $year = $this->divisionAllocation_Model->get_active_year();

        $data['title']   = 'Division Allocation';
        $data['year']    = $year;
        $data['classes'] = $year ? $this->divisionAllocation_Model->get_classes($year->amId) : [];

        $this->load->view('header');
        $this->load->view('division_allocation', $data);
        $this->load->view('footer');
    }

    /* AJAX (GET): divisions + students of one class */
    public function students()
    {
        $year = $this->divisionAllocation_Model->get_active_year();
        if (!$year) {
            return $this->json(['status' => false, 'msg' => 'No active academic year (current and not promoted).']);
        }

        $classId = (int)$this->input->get('class_id');
        if ($classId <= 0 || !$this->divisionAllocation_Model->class_exists($classId)) {
            return $this->json(['status' => false, 'msg' => 'Please select a class.']);
        }

        $divisions = [];
        foreach ($this->divisionAllocation_Model->get_divisions($classId) as $d) {
            $divisions[] = ['id' => (int)$d->dmId, 'name' => $d->dmName];
        }

        $students = [];
        foreach ($this->divisionAllocation_Model->get_students($classId, $year->amId) as $s) {
            $students[] = [
                'id'      => (int)$s->smId,
                'adm'     => $s->smAdmissionNo,
                'name'    => $s->smName,
                'gender'  => ($s->smGender === null || $s->smGender === '') ? 2 : (int)$s->smGender,
                'div'     => (int)$s->smDiv,
                'divName' => $s->divName
            ];
        }

        $this->json(['status' => true, 'divisions' => $divisions, 'students' => $students]);
    }

    /* AJAX (POST): save divisions + TC */
    public function save()
    {
        if ($this->input->method() !== 'post') {
            return $this->json(['status' => false, 'msg' => 'Invalid request.']);
        }

        $year = $this->divisionAllocation_Model->get_active_year();
        if (!$year) {
            return $this->json(['status' => false, 'msg' => 'No active academic year (current and not promoted).']);
        }

        $classId = (int)$this->input->post('class_id');
        if ($classId <= 0 || !$this->divisionAllocation_Model->class_exists($classId)) {
            return $this->json(['status' => false, 'msg' => 'Please select a class.']);
        }

        $assign = json_decode((string)$this->input->post('assign'), true);
        $tc     = json_decode((string)$this->input->post('tc'), true);
        if (!is_array($assign)) $assign = [];
        if (!is_array($tc))     $tc     = [];

        if (!$assign && !$tc) {
            return $this->json(['status' => false, 'msg' => 'Nothing to save.']);
        }

        $r = $this->divisionAllocation_Model->save($classId, $year->amId, $assign, $tc);
        $this->json(['status' => $r['ok'], 'msg' => $r['msg']]);
    }

    private function json($arr)
    {
        $arr['csrf'] = $this->security->get_csrf_hash();
        $this->output->set_content_type('application/json')->set_output(json_encode($arr));
    }
}