<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Daily attendance (Students > Attendance) and attendance reports (Reports > Student Reports).
 * Admin can pick any class / division; other users are locked to the class + division of their employee record.
 */
class AttendanceController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Attendance_model', 'Att');
        $this->Att->require_schema();
        $this->load->model('Subject_Model');
        $this->load->helper('fee');
    }

    private function _am_id()
    {
        $amId = (int)$this->session->userdata('selected_am_id');
        if ($amId > 0) return $amId;
        $r = $this->db->select('amId')->where('amIsCurrent', 1)->get('academic_master')->row();
        return $r ? (int)$r->amId : 0;
    }

    /* permissions + class lock for non-admin users */
    private function _scope($link)
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link($link));
        $s = array('perm' => is_array($perm) ? $perm : array(), 'is_admin' => $role === 1, 'class' => '', 'division' => '', 'assigned' => true);
        if (!$s['is_admin']) {
            $emp = $this->Subject_Model->get_employee_class_div((int)$this->session->userdata('id'));
            if ($emp && $emp->emClass && $emp->emDiv) { $s['class'] = (string)$emp->emClass; $s['division'] = (string)$emp->emDiv; }
            else $s['assigned'] = false;
        }
        return $s;
    }

    private function _json($status, $msg, $extra = array())
    {
        $this->output->set_content_type('application/json')->set_output(json_encode(array_merge(
            array('status' => (bool)$status, 'msg' => $msg, 'csrf' => $this->security->get_csrf_hash()), $extra)));
    }

    private function _deny()
    {
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
    }

    private function _date($v)
    {
        $d = DateTime::createFromFormat('Y-m-d', (string)$v);
        return ($d && $d->format('Y-m-d') === $v) ? $v : '';
    }

    /* ================================================================
       MARK ATTENDANCE
       ================================================================ */
    public function attendance()
    {
        $sc = $this->_scope('attendance');
        if (empty($sc['perm']['can_view'])) return $this->_deny();

        $amId  = $this->_am_id();
        $date  = $this->_date((string)$this->input->get('date')) ?: date('Y-m-d');
        if ($date > date('Y-m-d')) $date = date('Y-m-d');
        $class = $sc['is_admin'] ? (ctype_digit((string)$this->input->get('class')) ? (string)$this->input->get('class') : '') : $sc['class'];
        $div   = $sc['is_admin'] ? (ctype_digit((string)$this->input->get('division')) ? (string)$this->input->get('division') : '') : $sc['division'];

        $rows = ($class !== '' && $div !== '') ? $this->Att->day_sheet($amId, $class, $div, $date) : array();
        $month = ($class !== '' && $div !== '') ? $this->Att->class_month_days($amId, $class, $div, substr($date, 0, 7)) : array();

        $this->load->view('header', array('pageTitle' => 'Attendance', 'activePage' => 'ATTENDANCE',
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view('attendance', array(
            'scope' => $sc, 'date' => $date, 'class' => $class, 'division' => $div, 'rows' => $rows, 'month' => $month,
            'classes' => $this->Subject_Model->get_all_classes(), 'div_map' => $this->Subject_Model->get_class_division_map(),
            'status' => Attendance_model::$status,
        ));
        $this->load->view('footer');
    }

    public function attendance_save()
    {
        if ($this->input->method() !== 'post') return $this->_json(false, 'Invalid request.');
        $sc = $this->_scope('attendance');
        if (empty($sc['perm']['can_add']) && empty($sc['perm']['can_edit'])) return $this->_json(false, 'You do not have permission to mark attendance.');
        if (!$sc['is_admin'] && !$sc['assigned']) return $this->_json(false, 'No class and division is assigned to your account.');

        $date  = $this->_date((string)$this->input->post('date'));
        $class = $sc['is_admin'] ? (int)$this->input->post('class') : (int)$sc['class'];
        $div   = $sc['is_admin'] ? (int)$this->input->post('division') : (int)$sc['division'];
        if ($date === '' || $date > date('Y-m-d')) return $this->_json(false, 'Choose a valid date (not in the future).');
        if (!$class || !$div) return $this->_json(false, 'Choose the class and division.');

        if ($this->input->post('clear') === '1') {
            if (empty($sc['perm']['can_delete'])) return $this->_json(false, 'You do not have permission to clear attendance.');
            $this->Att->clear_day($this->_am_id(), $class, $div, $date);
            return $this->_json(true, 'Attendance for ' . date('d-m-Y', strtotime($date)) . ' cleared (holiday / not a working day).');
        }

        $marks = array();
        $st = (array)$this->input->post('status');
        $rm = (array)$this->input->post('remark');
        foreach ($st as $smId => $s) {
            if (!isset(Attendance_model::$status[$s])) return $this->_json(false, 'Invalid status.');
            $r = trim((string)(isset($rm[$smId]) ? $rm[$smId] : ''));
            if (mb_strlen($r) > 100) return $this->_json(false, 'Remarks must be 100 characters or fewer.');
            $marks[(int)$smId] = array('status' => $s, 'remark' => $r);
        }
        if (!$marks) return $this->_json(false, 'No students to save.');
        $n = $this->Att->save_day($this->_am_id(), $class, $div, $date, $marks, (int)$this->session->userdata('id'));
        if ($n < 0) return $this->_json(false, 'Could not save. Please try again.');
        $abs = 0; foreach ($marks as $m) if ($m['status'] === 'A') $abs++;
        return $this->_json(true, 'Attendance saved for ' . $n . ' students (' . $abs . ' absent).');
    }

    /* ================================================================
       REPORTS (permission: Student Reports)
       ================================================================ */
    private function _report_scope()
    {
        $sc = $this->_scope('reports/students');
        if (!empty($sc['perm']['can_view'])) return $sc;
        $this->_deny();
        return null;
    }

    private function _filters($sc)
    {
        $g = function ($k) { return trim((string)$this->input->get($k)); };
        $f = array(
            'class' => ctype_digit($g('class')) ? $g('class') : '', 'division' => ctype_digit($g('division')) ? $g('division') : '',
            'from' => $this->_date($g('from')) ?: date('Y-m-01'), 'to' => $this->_date($g('to')) ?: date('Y-m-d'),
            'q' => '', 'student' => 0, 'gender' => '', 'fee' => 0, 'fee_type' => '', 'mode' => '', 'group' => '', 'as_of' => date('Y-m-d'),
            'only_balance' => $g('below') === '1' ? '1' : '0',
        );
        return $f;
    }

    private function _render($spec, $f)
    {
        $amId = $this->_am_id();   // may run its own query, so not inside the chain below
        $y = $this->db->select('amYear')->where('amId', $amId)->get('academic_master')->row();
        $spec += array('menu' => 'STUDENT_REPORTS', 'hub' => 'reports/students', 'year' => $y ? $y->amYear : '');
        $this->load->library('Report_view');
        $this->report_view->output($spec, array('f' => $f, 'classes' => $this->Subject_Model->get_all_classes(),
            'div_map' => $this->Subject_Model->get_class_division_map()));
    }

    /* month register: students x days */
    public function attendance_register()
    {
        $sc = $this->_report_scope(); if (!$sc) return;
        $f = $this->_filters($sc);
        $ym = preg_match('/^\d{4}-\d{2}$/', (string)$this->input->get('month')) ? (string)$this->input->get('month') : date('Y-m');
        $from = $ym . '-01'; $to = date('Y-m-t', strtotime($from));
        $f['from'] = $from; $f['to'] = $to;
        $rows = array(); $need = '';
        $cols = array(array('key' => 'sl', 'label' => '#'), array('key' => 'name', 'label' => 'Student'));
        if ($f['class'] === '' || $f['division'] === '') {
            $need = 'Choose the class and division.';
        } else {
            $amId = $this->_am_id();
            $grid = $this->Att->grid($amId, $f['class'], $f['division'], $from, $to);
            $days = (int)date('t', strtotime($from));
            $marked = array();
            foreach ($grid as $d) foreach ($d as $date => $s) $marked[$date] = true;
            for ($i = 1; $i <= $days; $i++) {
                $date = $ym . '-' . sprintf('%02d', $i);
                if (isset($marked[$date])) $cols[] = array('key' => 'd' . $i, 'label' => (string)$i);
            }
            $cols = array_merge($cols, array(array('key' => 'p', 'label' => 'P', 'type' => 'int', 'total' => true),
                array('key' => 'a', 'label' => 'A', 'type' => 'int', 'total' => true), array('key' => 'pct', 'label' => '%')));
            $students = $this->Att->day_sheet($amId, $f['class'], $f['division'], $to);
            foreach ($students as $n => $s) {
                $r = array('sl' => $n + 1, 'name' => $s->smName); $p = 0; $a = 0; $h = 0; $d = 0;
                $sg = isset($grid[(int)$s->smId]) ? $grid[(int)$s->smId] : array();
                for ($i = 1; $i <= $days; $i++) {
                    $date = $ym . '-' . sprintf('%02d', $i);
                    if (!isset($marked[$date])) continue;
                    $v = isset($sg[$date]) ? $sg[$date] : '';
                    $r['d' . $i] = $v;
                    if ($v !== '') { $d++; if ($v === 'P') $p++; elseif ($v === 'A' || $v === 'L') $a++; elseif ($v === 'H') $h++; }
                }
                $r['p'] = $p + 0.5 * $h; $r['a'] = $a + 0.5 * $h;
                $pc = Attendance_model::percent($p, $h, $d);
                $r['pct'] = $pc === null ? '' : $pc . '%';
                $rows[] = $r;
            }
        }
        $months = array();
        for ($i = 0; $i < 12; $i++) { $m = date('Y-m', strtotime(date('Y-m-01') . " -$i month")); $months[$m] = date('M Y', strtotime($m . '-01')); }
        $this->_render(array(
            'title' => 'Attendance Register', 'route' => 'reports/attendance_register', 'filters' => array('class', 'division'), 'need' => $need,
            'selects' => array(array('name' => 'month', 'label' => 'Month', 'options' => $months, 'value' => $ym, 'all' => 'This month')),
            'note' => 'P present, A absent, H half day, L leave. Only days on which attendance was marked are shown. Half days count 0.5.',
            'columns' => $cols, 'rows' => $rows, 'count_label' => 'students',
        ), $f);
    }

    /* period summary per student, optional "below 75%" filter */
    public function attendance_summary()
    {
        $sc = $this->_report_scope(); if (!$sc) return;
        $f = $this->_filters($sc);
        $amId = $this->_am_id();
        $where = array('smAmId' => $amId);
        if ($f['class'] !== '') $where['smClass'] = (int)$f['class'];
        if ($f['division'] !== '') $where['smDiv'] = (int)$f['division'];
        $students = $this->db->select('s.smId, s.smAdmissionNo, s.smName, s.smMobile, c.cmName, d.dmName, s.smClass')
            ->from('students_master s')->join('class_master c', 'c.cmId = s.smClass', 'left')->join('division_master d', 'd.dmId = s.smDiv', 'left')
            ->where($where)->order_by('s.smClass')->order_by('d.dmName')->order_by('s.smName')->get()->result();
        $sum = $this->Att->summary(array_map(function ($s) { return (int)$s->smId; }, $students), $f['from'], $f['to']);
        $rows = array();
        foreach ($students as $s) {
            $x = isset($sum[(int)$s->smId]) ? $sum[(int)$s->smId] : array('P' => 0, 'A' => 0, 'H' => 0, 'L' => 0, 'days' => 0, 'pct' => null);
            if ($f['only_balance'] === '1' && ($x['pct'] === null || $x['pct'] >= 75)) continue;
            $rows[] = array('_group' => trim($s->cmName . ' - ' . $s->dmName, ' -'), 'adm' => $s->smAdmissionNo, 'name' => $s->smName, 'mobile' => $s->smMobile,
                'days' => $x['days'], 'p' => $x['P'], 'a' => $x['A'], 'h' => $x['H'], 'l' => $x['L'], 'pct' => $x['pct'] === null ? '' : $x['pct'] . '%');
        }
        $this->_render(array(
            'title' => 'Attendance Summary', 'route' => 'reports/attendance_summary', 'filters' => array('from', 'to', 'class', 'division'), 'group' => true,
            'selects' => array(array('name' => 'below', 'label' => 'Show', 'options' => array('1' => 'Below 75% only'), 'value' => $f['only_balance'], 'all' => 'All students')),
            'note' => 'From ' . date('d-m-Y', strtotime($f['from'])) . ' to ' . date('d-m-Y', strtotime($f['to'])) . '. % = (present + half days / 2) / days marked.',
            'columns' => array(
                array('key' => 'adm', 'label' => 'Adm No'), array('key' => 'name', 'label' => 'Name'), array('key' => 'mobile', 'label' => 'Mobile'),
                array('key' => 'days', 'label' => 'Days', 'type' => 'int'), array('key' => 'p', 'label' => 'Present', 'type' => 'int'),
                array('key' => 'a', 'label' => 'Absent', 'type' => 'int'), array('key' => 'h', 'label' => 'Half', 'type' => 'int'),
                array('key' => 'l', 'label' => 'Leave', 'type' => 'int'), array('key' => 'pct', 'label' => 'Attendance %'),
            ),
            'rows' => $rows, 'count_label' => 'students',
        ), $f);
    }
}
