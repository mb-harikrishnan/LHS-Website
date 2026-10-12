<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherController extends CI_Controller
{
    // user_role_id => label shown on the dashboard. Edit to match your roles.
    private $role_names = array(
        1 => 'Administrator',
        7 => 'Teacher'
    );

    // roles that see ALL active exams. Everyone else sees only their own (see Dashboard_model).
    private $see_all_roles = array(1);

    public function __construct()
    {
        parent::__construct();
        $this->load->model('TeacherDashboard_model');

        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
    }

    public function teacherdashboard()
    {
        $uid  = (int) $this->session->userdata('id');          // admin_login.sl_no
        $user = $this->TeacherDashboard_model->get_user($uid);
        if (!$user) {
            redirect('login', 'refresh');
        }

        $role = (int) $this->session->userdata('user_role_id');
        if (!$role) {
            $role = (int) $user->user_role_id;                 // fallback to DB
        }

        $see_all = in_array($role, $this->see_all_roles, true);
        $emp_id  = $see_all ? null : (int) $user->employe_id;

        // academic year (June to May)
        $y     = (int) date('Y');
        $start = ((int) date('n') >= 6) ? $y : $y - 1;

        // quick actions: 'roles' => null means everyone. Add more tiles here.
        $all_actions = array(
            array('label' => 'Account Settings', 'url' => site_url('change_password'), 'color' => '#4f46e5', 'icon' => 'user',  'roles' => null),
            array('label' => 'Photo Gallery',    'url' => site_url('gallery'),         'color' => '#7c3aed', 'icon' => 'image', 'roles' => array(1)),
        );
        $actions = array();
        foreach ($all_actions as $a) {
            if ($a['roles'] === null || in_array($role, $a['roles'], true)) {
                $actions[] = $a;
            }
        }

        $data = array(
            'user_name'    => $user->c_username,
            'role_id'      => $role,
            'role_name'    => isset($this->role_names[$role]) ? $this->role_names[$role] : 'Staff',
            'is_employee'  => !empty($user->employe_id) && empty($user->parent_id),
            'see_all'      => $see_all,
            'ay'           => $start . '–' . ($start + 1),
            'stats'        => $this->TeacherDashboard_model->get_exam_stats($emp_id),
            'exams'        => $this->TeacherDashboard_model->get_active_exams($emp_id),
            'actions'      => $actions
        );

        $this->load->view('header');
        $this->load->view('teacherdashboard', $data);
        $this->load->view('footer');
    }
}