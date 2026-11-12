<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Staff attendance reports (menu: Reports > Staff Attendance Reports). Rendered by libraries/Report_view. */
class StaffAttendanceReports extends CI_Controller
{
    private $f;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Staff_attendance_model', 'Satt');
        $this->Satt->require_schema();
        $this->load->model('Subject_Model');
        $this->load->library('Report_view');
        $this->load->helper('fee');

        $g = function ($k) { return trim((string)$this->input->get($k)); };
        $d = function ($k, $def) use ($g) {
            $v = $g($k); $x = DateTime::createFromFormat('Y-m-d', $v);
            return ($x && $x->format('Y-m-d') === $v && $v >= '2000-01-01') ? $v : $def;
        };
        $st = $g('status');
        $this->f = array(
            'day'      => min($d('day', date('Y-m-d')), date('Y-m-d')),
            'month'    => preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $g('month')) ? $g('month') : date('Y-m'),
            'from'     => $d('from', date('Y-m-01')),
            'to'       => $d('to', date('Y-m-d')),
            'staff'    => (int)$g('staff'),
            'category' => isset(Staff_attendance_model::$categories[$g('category')]) ? $g('category') : '',
            'type'     => (int)$g('type'),
            'status'   => in_array($st, array('APPROVED', 'PENDING', 'REJECTED', 'CANCELLED', 'ALL'), true) ? $st : '',
            'year'     => $d('year', ''),
            // expected by the shared view
            'q' => '', 'class' => '', 'division' => '', 'student' => 0, 'gender' => '', 'fee' => 0, 'fee_type' => '', 'mode' => '',
            'as_of' => date('Y-m-d'), 'only_balance' => '1', 'group' => '', 'from_month' => '', 'to_month' => '',
        );
        if ($this->f['to'] < $this->f['from']) $this->f['to'] = $this->f['from'];
        if ((strtotime($this->f['to']) - strtotime($this->f['from'])) / 86400 > 400) $this->f['to'] = date('Y-m-d', strtotime($this->f['from'] . ' +1 year -1 day'));
    }

    private function _allowed()
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link('reports/staff_attendance'));
        if (!empty($perm['can_view'])) return true;
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
        return false;
    }

    private function _dmy($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; }
    private function _num($n) { return rtrim(rtrim(number_format((float)$n, 1, '.', ''), '0'), '.'); }
    private function _range() { return $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']); }

    private function _render($spec)
    {
        $spec['menu'] = 'STAFF_ATT_REPORTS';
        $spec['hub']  = 'reports/staff_attendance';
        $this->report_view->output($spec, array('f' => $this->f));
    }

    private function _sel_staff()
    {
        $o = array();
        foreach ($this->Satt->staff_on('1900-01-01', '2999-12-31') as $s) $o[$s->peId] = $s->peName . ($s->peCode ? ' (' . $s->peCode . ')' : '');
        return array('name' => 'staff', 'label' => 'Staff', 'options' => $o, 'value' => $this->f['staff'], 'all' => 'All staff');
    }

    private function _sel_category()
    {
        return array('name' => 'category', 'label' => 'Category', 'options' => Staff_attendance_model::$categories, 'value' => $this->f['category']);
    }

    private function _sel_type()
    {
        $o = array(); foreach ($this->Satt->leave_types() as $t) $o[$t->sltId] = $t->sltName;
        return array('name' => 'type', 'label' => 'Leave type', 'options' => $o, 'value' => $this->f['type'] ?: '');
    }

    private function _staff($from, $to)
    {
        return $this->Satt->staff_on($from, $to, $this->f['category'], $this->f['staff']);
    }

    /* ---------------- hub ---------------- */
    public function index()
    {
        if (!$this->_allowed()) return;
        $this->load->view('header', array('pageTitle' => 'Staff Attendance Reports', 'activePage' => 'STAFF_ATT_REPORTS',
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view('report_hub', array('title' => 'Staff Attendance Reports', 'sub' => 'Staff attendance and leave reports.', 'groups' => array(
            'Attendance' => array(
                array('reports/staff_att_daily', 'Daily Attendance', 'Every staff member\'s status and in / out time for a day'),
                array('reports/staff_att_register', 'Monthly Attendance Register', 'Day-by-day grid of a month with totals'),
                array('reports/staff_att_summary', 'Attendance Summary', 'Present, absent, leave, late and loss-of-pay days for a period'),
                array('reports/staff_att_absentees', 'Absentee List', 'Absent, half-day and unpaid-leave days'),
                array('reports/staff_att_late', 'Late Arrivals', 'Days staff came after the late time'),
            ),
            'Leave' => array(
                array('reports/staff_leave_register', 'Leave Register', 'Leave taken / requested between two dates'),
                array('reports/staff_leave_balance', 'Leave Balance', 'Entitlement, taken and balance of every leave type'),
            ),
        )));
        $this->load->view('footer');
    }

    /* ---------------- attendance ---------------- */
    public function daily()
    {
        if (!$this->_allowed()) return;
        $day = $this->f['day'];
        $this->Satt->holidays($day, $day);
        $off = $this->Satt->off_reason($day);
        $late = $this->Satt->settings()['late_after'];
        $rows = array();
        foreach ($this->Satt->day_sheet($day, $this->f['category']) as $s) {
            $a = $s->att;
            $rows[] = array('_group' => Staff_attendance_model::$categories[$s->peCategory] . ' staff', 'code' => $s->peCode, 'name' => $s->peName,
                'desig' => $s->peDesignation, 'status' => $a ? Staff_attendance_model::$status[$a->satStatus] . ($a->sltCode ? ' (' . $a->sltCode . ')' : '') : ($off ? '' : 'Not marked'),
                'in' => $a && $a->satIn ? substr($a->satIn, 0, 5) : '', 'out' => $a && $a->satOut ? substr($a->satOut, 0, 5) : '',
                'late' => $a && $a->satIn && substr($a->satIn, 0, 5) > $late && in_array($a->satStatus, array('P', 'OD'), true) ? 'Late' : '', 'remark' => $a ? $a->satRemark : '');
        }
        $this->_render(array('title' => 'Daily Staff Attendance', 'route' => 'reports/staff_att_daily', 'filters' => array('day'),
            'selects' => array($this->_sel_category()), 'period' => date('l, d-m-Y', strtotime($day)), 'note' => $off ? 'Not a working day: ' . $off : '',
            'group' => true, 'count_label' => 'staff', 'rows' => $rows,
            'columns' => array(array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'), array('key' => 'desig', 'label' => 'Designation'),
                array('key' => 'status', 'label' => 'Status'), array('key' => 'in', 'label' => 'In'), array('key' => 'out', 'label' => 'Out'),
                array('key' => 'late', 'label' => 'Late'), array('key' => 'remark', 'label' => 'Remark'))));
    }

    public function register()
    {
        if (!$this->_allowed()) return;
        $from = $this->f['month'] . '-01'; $to = date('Y-m-t', strtotime($from));
        $staff = $this->_staff($from, $to);
        $ids = array(); foreach ($staff as $s) $ids[] = (int)$s->peId;
        $grid = $this->Satt->grid($from, $to, $ids);
        $sum = $this->Satt->summary($from, min($to, date('Y-m-d')), $staff);
        $this->Satt->holidays($from, $to);
        $cols = array(array('key' => 'name', 'label' => 'Name'));
        $offs = array();
        for ($t = strtotime($from); $t <= strtotime($to); $t = strtotime('+1 day', $t)) {
            $d = date('Y-m-d', $t);
            $offs[$d] = $this->Satt->off_reason($d) !== '';
            $cols[] = array('key' => 'd' . date('j', $t), 'label' => date('j', $t) . ' ' . substr(date('D', $t), 0, 2));
        }
        foreach (array('wd' => 'Work Days', 'p' => 'Present', 'a' => 'Absent', 'l' => 'Leave', 'u' => 'Unmarked', 'lop' => 'LOP') as $k => $l) $cols[] = array('key' => $k, 'label' => $l);
        $rows = array();
        foreach ($staff as $s) {
            $r = array('_group' => Staff_attendance_model::$categories[$s->peCategory] . ' staff', 'name' => $s->peName);
            foreach ($offs as $d => $isOff) {
                $a = isset($grid[(int)$s->peId][$d]) ? $grid[(int)$s->peId][$d] : null;
                $employed = (!$s->peJoinDate || $s->peJoinDate <= $d) && (!$s->peLeaveDate || $s->peLeaveDate >= $d);
                $r['d' . (int)substr($d, 8)] = $a ? ($a->sltCode && in_array($a->satStatus, array('L', 'HL'), true) ? ($a->satStatus === 'HL' ? '½' : '') . $a->sltCode : $a->satStatus)
                                                  : (!$employed ? '' : ($isOff ? '·' : ($d > date('Y-m-d') ? '' : '?')));
            }
            $c = $sum[(int)$s->peId];
            $r += array('wd' => $c['working'], 'p' => $this->_num($c['present']), 'a' => $this->_num($c['absent'] + $c['half'] * 0.5),
                        'l' => $this->_num($c['leave_paid'] + $c['leave_unpaid']), 'u' => $c['unmarked'] ?: '', 'lop' => $this->_num($c['lop']));
            $rows[] = $r;
        }
        $this->_render(array('title' => 'Monthly Staff Attendance Register', 'route' => 'reports/staff_att_register', 'filters' => array('month'),
            'selects' => array($this->_sel_category()), 'period' => date('F Y', strtotime($from)),
            'note' => 'P present · A absent · HD half day · OD on duty · leave shown by its code (½ = half day) · · off day · ? not marked',
            'group' => true, 'count_label' => 'staff', 'rows' => $rows, 'columns' => $cols));
    }

    public function summary()
    {
        if (!$this->_allowed()) return;
        $from = $this->f['from']; $to = min($this->f['to'], date('Y-m-d'));
        $staff = $this->_staff($from, $to);
        $sum = $this->Satt->summary($from, $to, $staff);
        $rows = array();
        foreach ($staff as $s) {
            $c = $sum[(int)$s->peId];
            $rows[] = array('_group' => Staff_attendance_model::$categories[$s->peCategory] . ' staff', 'code' => $s->peCode, 'name' => $s->peName,
                'wd' => $c['working'], 'p' => (float)$c['present'], 'a' => (float)$c['absent'], 'hd' => $c['half'], 'lp' => (float)$c['leave_paid'],
                'lu' => (float)$c['leave_unpaid'], 'od' => $c['od'], 'late' => $c['late'], 'u' => $c['unmarked'], 'lop' => (float)$c['lop'],
                'pct' => $c['working'] ? round($c['present'] / $c['working'] * 100, 1) . '%' : '');
        }
        $n = function ($k, $l) { return array('key' => $k, 'label' => $l, 'type' => 'money', 'total' => true); };
        $i = function ($k, $l) { return array('key' => $k, 'label' => $l, 'type' => 'int', 'total' => true); };
        $this->_render(array('title' => 'Staff Attendance Summary', 'route' => 'reports/staff_att_summary', 'filters' => array('from'),
            'selects' => array($this->_sel_staff(), $this->_sel_category()), 'period' => $this->_range(),
            'note' => 'LOP = absent + ½ for half days + unpaid leave', 'group' => true, 'count_label' => 'staff', 'rows' => $rows,
            'columns' => array(array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'), $i('wd', 'Working Days'),
                $n('p', 'Present'), $n('a', 'Absent'), $i('hd', 'Half Days'), $n('lp', 'Paid Leave'), $n('lu', 'Unpaid Leave'), $i('od', 'On Duty'),
                $i('late', 'Late'), $i('u', 'Not Marked'), $n('lop', 'LOP Days'), array('key' => 'pct', 'label' => 'Attendance %'))));
    }

    public function absentees()
    {
        if (!$this->_allowed()) return;
        $sql = "SELECT a.*, p.peName, p.peCode, p.peCategory, t.sltName, t.sltPaid FROM staff_attendance a JOIN pay_employee p ON p.peId = a.satPeId
                LEFT JOIN staff_leave_type t ON t.sltId = a.satSltId
                WHERE a.satDate BETWEEN ? AND ? AND (a.satStatus IN ('A','HD') OR (a.satStatus IN ('L','HL') AND t.sltPaid = 0))";
        $par = array($this->f['from'], $this->f['to']);
        if ($this->f['staff'])    { $sql .= ' AND a.satPeId = ?'; $par[] = $this->f['staff']; }
        if ($this->f['category']) { $sql .= ' AND p.peCategory = ?'; $par[] = $this->f['category']; }
        $rows = array();
        foreach ($this->db->query($sql . ' ORDER BY a.satDate, p.peName', $par)->result() as $a) {
            $lop = in_array($a->satStatus, array('A', 'L'), true) ? 1 : 0.5;
            $rows[] = array('_group' => date('d-m-Y, l', strtotime($a->satDate)), 'code' => $a->peCode, 'name' => $a->peName,
                'status' => Staff_attendance_model::$status[$a->satStatus] . ($a->sltName ? ' (' . $a->sltName . ')' : ''), 'remark' => $a->satRemark, 'lop' => $lop);
        }
        $this->_render(array('title' => 'Staff Absentee List', 'route' => 'reports/staff_att_absentees', 'filters' => array('from'),
            'selects' => array($this->_sel_staff(), $this->_sel_category()), 'period' => $this->_range(), 'group' => true, 'count_label' => 'entries', 'rows' => $rows,
            'columns' => array(array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'), array('key' => 'status', 'label' => 'Status'),
                array('key' => 'remark', 'label' => 'Remark'), array('key' => 'lop', 'label' => 'LOP Days', 'type' => 'money', 'total' => true))));
    }

    public function late()
    {
        if (!$this->_allowed()) return;
        $late = $this->Satt->settings()['late_after'];
        $sql = "SELECT a.*, p.peName, p.peCode FROM staff_attendance a JOIN pay_employee p ON p.peId = a.satPeId
                WHERE a.satDate BETWEEN ? AND ? AND a.satIn IS NOT NULL AND TIME_FORMAT(a.satIn, '%H:%i') > ? AND a.satStatus IN ('P','OD')";
        $par = array($this->f['from'], $this->f['to'], $late);
        if ($this->f['staff'])    { $sql .= ' AND a.satPeId = ?'; $par[] = $this->f['staff']; }
        if ($this->f['category']) { $sql .= ' AND p.peCategory = ?'; $par[] = $this->f['category']; }
        $rows = array();
        foreach ($this->db->query($sql . ' ORDER BY p.peName, a.satDate', $par)->result() as $a) {
            $mins = (int)round((strtotime('2000-01-01 ' . substr($a->satIn, 0, 5)) - strtotime('2000-01-01 ' . $late)) / 60);
            $rows[] = array('_group' => $a->peName . ($a->peCode ? ' (' . $a->peCode . ')' : ''), 'date' => date('d-m-Y, D', strtotime($a->satDate)),
                            'in' => substr($a->satIn, 0, 5), 'mins' => $mins, 'remark' => $a->satRemark);
        }
        $this->_render(array('title' => 'Late Arrivals', 'route' => 'reports/staff_att_late', 'filters' => array('from'),
            'selects' => array($this->_sel_staff(), $this->_sel_category()), 'period' => $this->_range(), 'note' => 'Late after ' . $late,
            'group' => true, 'count_label' => 'late days', 'rows' => $rows,
            'columns' => array(array('key' => 'date', 'label' => 'Date'), array('key' => 'in', 'label' => 'In Time'),
                array('key' => 'mins', 'label' => 'Minutes Late', 'type' => 'int', 'total' => true), array('key' => 'remark', 'label' => 'Remark'))));
    }

    /* ---------------- leave ---------------- */
    public function leave_register()
    {
        if (!$this->_allowed()) return;
        $st = $this->f['status'] ?: 'APPROVED';
        $rows = array();
        foreach ($this->Satt->list_apps(array('from' => $this->f['from'], 'to' => $this->f['to'], 'staff' => $this->f['staff'], 'type' => $this->f['type'],
                                              'status' => $st === 'ALL' ? '' : $st)) as $a) {
            $rows[] = array('_group' => $a->sltName, 'name' => $a->peName, 'from' => $this->_dmy($a->laFrom), 'to' => $a->laHalf ? 'Half day' : $this->_dmy($a->laTo),
                'days' => (float)$a->laDays, 'status' => ucfirst(strtolower($a->laStatus)), 'by' => $a->laSelf ? 'Staff' : 'Office',
                'reason' => $a->laReason, 'note' => $a->laNote);
        }
        usort($rows, function ($x, $y) { return strcmp($x['_group'], $y['_group']); });
        $this->_render(array('title' => 'Leave Register', 'route' => 'reports/staff_leave_register', 'filters' => array('from'),
            'selects' => array($this->_sel_staff(), $this->_sel_type(),
                array('name' => 'status', 'label' => 'Status', 'options' => array('PENDING' => 'Waiting', 'REJECTED' => 'Rejected', 'CANCELLED' => 'Cancelled', 'ALL' => 'All'),
                      'value' => $st === 'APPROVED' ? '' : $st, 'all' => 'Approved')),
            'period' => $this->_range(), 'group' => true, 'count_label' => 'leave requests', 'rows' => $rows,
            'columns' => array(array('key' => 'name', 'label' => 'Name'), array('key' => 'from', 'label' => 'From'), array('key' => 'to', 'label' => 'To'),
                array('key' => 'days', 'label' => 'Days', 'type' => 'money', 'total' => true), array('key' => 'status', 'label' => 'Status'),
                array('key' => 'by', 'label' => 'Entered by'), array('key' => 'reason', 'label' => 'Reason'), array('key' => 'note', 'label' => 'Note'))));
    }

    public function leave_balance()
    {
        if (!$this->_allowed()) return;
        $ys = $this->Satt->year_start($this->f['year'] ?: date('Y-m-d'));
        $staff = $this->Satt->staff_on($ys, $this->Satt->year_end($ys), $this->f['category'], $this->f['staff']);
        $types = $this->Satt->leave_types(true);
        if ($this->f['type']) $types = array_values(array_filter($types, function ($t) { return (int)$t->sltId === $this->f['type']; }));
        $bal = $this->Satt->balances($ys, $staff, $types);
        $cols = array(array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'));
        foreach ($types as $t) {
            $cols[] = array('key' => 'u' . $t->sltId, 'label' => $t->sltCode . ' Taken', 'type' => 'money', 'total' => true);
            if ($t->sltQuota !== null) $cols[] = array('key' => 'b' . $t->sltId, 'label' => $t->sltCode . ' Balance', 'type' => 'money', 'total' => true);
        }
        $rows = array();
        foreach ($staff as $s) {
            $r = array('code' => $s->peCode, 'name' => $s->peName);
            foreach ($types as $t) {
                $x = $bal[(int)$s->peId][(int)$t->sltId];
                $r['u' . $t->sltId] = $x['used'];
                $r['b' . $t->sltId] = $x['balance'] === null ? '' : $x['balance'];
            }
            $rows[] = $r;
        }
        $years = array(); $cur = $this->Satt->year_start(date('Y-m-d'));
        for ($i = -3; $i <= 1; $i++) { if (!$i) continue; $y = date('Y-m-d', strtotime($cur . " $i year")); $years[$y] = $this->Satt->year_label($y); }
        $this->_render(array('title' => 'Leave Balance', 'route' => 'reports/staff_leave_balance',
            'selects' => array(array('name' => 'year', 'label' => 'Leave year', 'options' => $years, 'value' => $ys === $cur ? '' : $ys, 'all' => $this->Satt->year_label($cur)),
                               $this->_sel_staff(), $this->_sel_category(), $this->_sel_type()),
            'period' => 'Leave year ' . $this->Satt->year_label($ys), 'note' => 'Taken = approved / marked leave', 'count_label' => 'staff', 'rows' => $rows, 'columns' => $cols));
    }
}
