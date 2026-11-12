<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Staff attendance: Mark Attendance, Leave Requests, Leave Balances, Holidays & Leave Types, My Attendance & Leave */
class StaffAttendanceController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Staff_attendance_model', 'Satt');
        $this->Satt->require_schema();
        $this->load->model('Subject_Model');
        $this->load->helper('fee');
    }

    private function _perm($link)
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link($link));
        return is_array($perm) ? $perm : array();
    }

    private function _can($link, $what) { $p = $this->_perm($link); return !empty($p[$what]); }

    private function _json($status, $msg, $extra = array())
    {
        $this->output->set_content_type('application/json')->set_output(json_encode(array_merge(
            array('status' => (bool)$status, 'msg' => $msg, 'csrf' => $this->security->get_csrf_hash()), $extra)));
    }

    private function _page($view, $data, $title, $active)
    {
        $this->load->view('header', array('pageTitle' => $title, 'activePage' => $active,
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view($view, $data);
        $this->load->view('footer');
    }

    private function _deny()
    {
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
    }

    private function _post_only() { return $this->input->method() === 'post'; }
    private function _user()      { return (int)$this->session->userdata('id'); }
    private function _clean($v, $max = 255) { return mb_substr(trim(preg_replace('/\s+/', ' ', (string)$v)), 0, $max); }

    private function _date($v)
    {
        $v = (string)$v; $d = DateTime::createFromFormat('Y-m-d', $v);
        return ($d && $d->format('Y-m-d') === $v && $v >= '2000-01-01') ? $v : '';
    }

    private function _time($v)
    {
        $v = trim((string)$v);
        if ($v === '') return '';
        return preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $v) ? substr($v, 0, 5) : false;
    }

    private function _category()
    {
        $c = (string)$this->input->get('category');
        return isset(Staff_attendance_model::$categories[$c]) ? $c : '';
    }

    /* ================================================================
       MARK ATTENDANCE
       ================================================================ */
    public function staff_attendance()
    {
        $perm = $this->_perm('staff_attendance');
        if (empty($perm['can_view'])) return $this->_deny();
        $date = $this->_date($this->input->get('date')) ?: date('Y-m-d');
        if ($date > date('Y-m-d')) $date = date('Y-m-d');
        $cat = $this->_category();
        $month = substr($date, 0, 7) . '-01';
        $this->Satt->holidays($month, date('Y-m-t', strtotime($month)));
        $off = array();
        for ($d = 1, $n = (int)date('t', strtotime($month)); $d <= $n; $d++) {
            $dt = substr($date, 0, 7) . sprintf('-%02d', $d);
            $r = $this->Satt->off_reason($dt);
            if ($r !== '') $off[$dt] = $r;
        }
        $this->_page('staff_attendance', array('date' => $date, 'category' => $cat, 'rows' => $this->Satt->day_sheet($date, $cat),
            'off' => $off, 'marks' => $this->Satt->month_marks($month), 'types' => $this->Satt->leave_types(true), 'perm' => $perm,
            'late_after' => $this->Satt->settings()['late_after'], 'pending' => $this->Satt->pending_count()),
            'Staff Attendance', 'STAFF_ATT_MARK');
    }

    public function staff_attendance_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $date = $this->_date($this->input->post('date'));
        if (!$date) return $this->_json(false, 'Select the date.');
        if ($date > date('Y-m-d')) return $this->_json(false, 'Attendance cannot be marked for a future date.');
        if ($this->input->post('clear') === '1') {
            if (!$this->_can('staff_attendance', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
            $n = $this->Satt->clear_day($date);
            return $this->_json(true, $n . ' mark(s) cleared for ' . date('d-m-Y', strtotime($date)) . '. Leave from approved applications is kept.');
        }
        if (!$this->_can('staff_attendance', 'can_add') && !$this->_can('staff_attendance', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');

        $types = array(); foreach ($this->Satt->leave_types(true) as $t) $types[(int)$t->sltId] = $t;
        $staff = array(); foreach ($this->Satt->staff_on($date) as $s) $staff[(int)$s->peId] = $s;
        $st = (array)$this->input->post('status'); $ty = (array)$this->input->post('type');
        $in = (array)$this->input->post('in');     $out = (array)$this->input->post('out'); $rm = (array)$this->input->post('remark');
        $rows = array();
        foreach ($st as $peId => $status) {
            $peId = (int)$peId;
            if (!isset($staff[$peId])) continue;
            $name = $staff[$peId]->peName;
            if (!isset(Staff_attendance_model::$status[$status])) return $this->_json(false, 'Select a valid status for ' . $name . '.');
            $type = isset($ty[$peId]) ? (int)$ty[$peId] : 0;
            if (in_array($status, array('L', 'HL'), true)) {
                if (!isset($types[$type])) return $this->_json(false, 'Select the leave type for ' . $name . '.');
            } else {
                $type = 0;
            }
            $ti = $this->_time(isset($in[$peId]) ? $in[$peId] : '');
            $to = $this->_time(isset($out[$peId]) ? $out[$peId] : '');
            if ($ti === false || $to === false) return $this->_json(false, 'Enter valid in / out times for ' . $name . '.');
            if (in_array($status, array('A', 'L'), true)) { $ti = ''; $to = ''; }
            if ($ti !== '' && $to !== '' && $to < $ti) return $this->_json(false, $name . ': out time is before in time.');
            $rows[$peId] = array('status' => $status, 'type' => $type, 'in' => $ti, 'out' => $to, 'remark' => $this->_clean(isset($rm[$peId]) ? $rm[$peId] : '', 100));
        }
        if (!$rows) return $this->_json(false, 'There is no staff to mark.');

        // a leave marked here uses the balance too
        $ys = $this->Satt->year_start($date);
        $sheet = array(); foreach ($this->Satt->day_sheet($date) as $s) $sheet[(int)$s->peId] = $s;
        foreach ($rows as $peId => $r) {
            if (!$r['type'] || $types[$r['type']]->sltQuota === null) continue;
            $prev = $sheet[$peId]->att;
            $prevDays = $prev && (int)$prev->satSltId === $r['type'] ? ($prev->satStatus === 'L' ? 1 : ($prev->satStatus === 'HL' ? 0.5 : 0)) : 0;
            $b = $this->Satt->balances($ys, array($staff[$peId]), array($types[$r['type']]));
            $x = $b[$peId][$r['type']];
            $need = $r['status'] === 'L' ? 1 : 0.5;
            if ($x['entitled'] !== null && $need - ($x['balance'] + $prevDays) > 0.001) {
                return $this->_json(false, $staff[$peId]->peName . ' has only ' . max(0, $x['balance'] + $prevDays) . ' day(s) of ' . $types[$r['type']]->sltName . ' left. Mark Loss of Pay or Absent instead.');
            }
        }
        $n = $this->Satt->save_day($date, $rows, $this->_user());
        return $this->_json($n > 0, $n > 0 ? 'Attendance saved for ' . $n . ' staff on ' . date('d-m-Y', strtotime($date)) . '.' : 'Nothing was saved.');
    }

    /* ================================================================
       LEAVE REQUESTS
       ================================================================ */
    public function staff_leave()
    {
        $perm = $this->_perm('staff_leave');
        if (empty($perm['can_view'])) return $this->_deny();
        $st = (string)$this->input->get('status');
        $f = array('status' => in_array($st, array('PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'ALL'), true) ? $st : 'PENDING',
                   'staff' => (int)$this->input->get('staff'), 'type' => (int)$this->input->get('type'));
        $q = $f; if ($q['status'] === 'ALL') $q['status'] = '';
        $this->_page('staff_leave', array('apps' => $this->Satt->list_apps($q), 'f' => $f, 'perm' => $perm,
            'staff' => $this->Satt->staff_on('1900-01-01', '2999-12-31'), 'types' => $this->Satt->leave_types(true),
            'pending' => $this->Satt->pending_count()), 'Leave Requests', 'STAFF_LEAVE');
    }

    /* reads a leave form; returns [data, error] */
    private function _leave_input($peId)
    {
        $from = $this->_date($this->input->post('from'));
        $to   = $this->_date($this->input->post('to')) ?: $from;
        $half = $this->input->post('half') === '1';
        if (!$from) return array(null, 'Enter the leave date.');
        if ($to < $from) return array(null, 'The end date is before the start date.');
        if ($half && $to !== $from) return array(null, 'A half-day leave is for one day only.');
        if ((strtotime($to) - strtotime($from)) / 86400 > 366) return array(null, 'A leave cannot be longer than a year.');
        return array(array('peId' => $peId, 'sltId' => (int)$this->input->post('type'), 'from' => $from, 'to' => $to, 'half' => $half,
                           'reason' => $this->_clean($this->input->post('reason'), 255), 'user' => $this->_user()), '');
    }

    public function staff_leave_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('staff_leave', 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        list($d, $err) = $this->_leave_input((int)$this->input->post('staff'));
        if ($err) return $this->_json(false, $err);
        $d['self'] = false;
        $approve = $this->input->post('approve') === '1';
        if ($approve && !$this->_can('staff_leave', 'can_edit')) return $this->_json(false, 'You can record the leave, but approving needs edit permission.');
        $r = $this->Satt->apply($d, $approve);
        return $this->_json($r['ok'], $r['msg']);
    }

    /* working days + balance for the leave form */
    public function staff_leave_check()
    {
        $peId = (int)$this->input->get('staff');
        $self = $this->input->get('self') === '1';
        if ($self) {
            $me = $this->Satt->staff_of_user($this->_user());
            if (!$me || !$this->_can('my_attendance', 'can_view')) return $this->_json(false, '');
            $peId = (int)$me->peId;
        } elseif (!$this->_can('staff_leave', 'can_view')) {
            return $this->_json(false, '');
        }
        $from = $this->_date($this->input->get('from'));
        $to = $this->_date($this->input->get('to')) ?: $from;
        if (!$from || $to < $from || (strtotime($to) - strtotime($from)) / 86400 > 366) return $this->_json(false, '');
        $days = $this->Satt->leave_days($from, $to, $this->input->get('half') === '1');
        $pe = $this->Satt->get_staff($peId); $t = $this->Satt->get_type((int)$this->input->get('type'));
        $bal = null;
        if ($pe && $t) {
            $b = $this->Satt->balances($this->Satt->year_start($from), array($pe), array($t));
            $bal = $b[(int)$pe->peId][(int)$t->sltId];
        }
        return $this->_json(true, '', array('days' => $days, 'balance' => $bal));
    }

    public function staff_leave_decide()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $act = (string)$this->input->post('act');
        $id = (int)$this->input->post('id');
        $note = $this->_clean($this->input->post('note'), 255);
        if ($act === 'approve' || $act === 'reject') {
            if (!$this->_can('staff_leave', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');
            if ($act === 'reject' && $note === '') return $this->_json(false, 'Enter the reason for rejecting.');
            $r = $act === 'approve' ? $this->Satt->approve($id, $this->_user(), $note) : $this->Satt->reject($id, $this->_user(), $note);
        } elseif ($act === 'cancel') {
            if (!$this->_can('staff_leave', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
            if ($note === '') return $this->_json(false, 'Enter the reason.');
            $r = $this->Satt->cancel($id, $this->_user(), $note);
        } else {
            return $this->_json(false, 'Invalid request.');
        }
        return $this->_json($r['ok'], $r['msg']);
    }

    /* ================================================================
       LEAVE BALANCES
       ================================================================ */
    public function staff_leave_balance()
    {
        $perm = $this->_perm('staff_leave_balance');
        if (empty($perm['can_view'])) return $this->_deny();
        $cur = $this->Satt->year_start(date('Y-m-d'));
        $y = $this->_date($this->input->get('year'));
        $ys = $y ? $this->Satt->year_start($y) : $cur;
        $cat = $this->_category();
        $staff = $this->Satt->staff_on($ys, $this->Satt->year_end($ys), $cat);
        $types = $this->Satt->leave_types(true);
        $years = array();
        for ($i = -3; $i <= 1; $i++) { $s = date('Y-m-d', strtotime($cur . " $i year")); $years[$s] = $this->Satt->year_label($s); }
        $this->_page('staff_leave_balance', array('year' => $ys, 'years' => $years, 'category' => $cat, 'staff' => $staff, 'types' => $types,
            'bal' => $this->Satt->balances($ys, $staff, $types), 'custom' => $this->Satt->entitlements($ys), 'perm' => $perm),
            'Leave Balances', 'STAFF_LEAVE_BAL');
    }

    public function staff_leave_alloc_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('staff_leave_balance', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');
        $pe = $this->Satt->get_staff((int)$this->input->post('staff'));
        $t = $this->Satt->get_type((int)$this->input->post('type'));
        $y = $this->_date($this->input->post('year'));
        if (!$pe || !$t || !$y) return $this->_json(false, 'Invalid request.');
        $v = trim((string)$this->input->post('days'));
        if ($v !== '' && (!preg_match('/^\d{1,3}(\.[05])?$/', $v) || (float)$v > 366)) return $this->_json(false, 'Enter days from 0 to 366 (half days allowed), or leave it empty for the default.');
        $ok = $this->Satt->set_entitlement($pe->peId, $t->sltId, $this->Satt->year_start($y), $v === '' ? null : (float)$v);
        return $this->_json($ok, $ok ? $t->sltName . ' entitlement of ' . $pe->peName . ' saved.' : 'Could not save.');
    }

    /* ================================================================
       HOLIDAYS, LEAVE TYPES, SETTINGS
       ================================================================ */
    public function staff_att_settings()
    {
        $perm = $this->_perm('staff_att_settings');
        if (empty($perm['can_view'])) return $this->_deny();
        $y = (int)$this->input->get('year');
        if ($y < 2000 || $y > 2100) $y = (int)date('Y');
        $this->_page('staff_att_settings', array('settings' => $this->Satt->settings(), 'types' => $this->Satt->leave_types(),
            'holidays' => $this->Satt->list_holidays($y . '-01-01', $y . '-12-31'), 'year' => $y, 'perm' => $perm),
            'Holidays & Leave Types', 'STAFF_ATT_SETTINGS');
    }

    public function staff_att_settings_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('staff_att_settings', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');
        $off = array();
        foreach ((array)$this->input->post('week_off') as $w) if (ctype_digit((string)$w) && (int)$w <= 6) $off[(int)$w] = (int)$w;
        if (count($off) > 3) return $this->_json(false, 'Select at most 3 weekly off days.');
        $sat = (string)$this->input->post('sat_rule');
        $ys = (int)$this->input->post('year_start');
        $late = $this->_time($this->input->post('late_after'));
        if (!isset(Staff_attendance_model::$sat_rules[$sat])) return $this->_json(false, 'Select the Saturday rule.');
        if ($ys < 1 || $ys > 12) return $this->_json(false, 'Select the month the leave year starts.');
        if (!$late) return $this->_json(false, 'Enter the time after which arrival is late.');
        ksort($off);
        $ok = $this->Satt->save_settings(array('week_off' => implode(',', $off), 'sat_rule' => $sat, 'year_start' => (string)$ys, 'late_after' => $late));
        return $this->_json($ok, $ok ? 'Settings saved.' : 'Could not save.');
    }

    public function staff_leave_type_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id = (int)$this->input->post('id');
        if (!$this->_can('staff_att_settings', $id ? 'can_edit' : 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        if ($id && !$this->Satt->get_type($id)) return $this->_json(false, 'Leave type not found.');
        $name = $this->_clean($this->input->post('name'), 60);
        $code = strtoupper($this->_clean($this->input->post('code'), 6));
        $quota = trim((string)$this->input->post('quota'));
        if ($name === '') return $this->_json(false, 'Enter the leave type name.');
        if (!preg_match('/^[A-Z0-9]{1,6}$/', $code)) return $this->_json(false, 'Enter a short code of up to 6 letters, like CL.');
        if ($quota !== '' && (!preg_match('/^\d{1,3}(\.[05])?$/', $quota) || (float)$quota > 366)) return $this->_json(false, 'Days per year must be 0 to 366, or empty for no limit.');
        if ($this->Satt->type_exists('sltName', $name, $id)) return $this->_json(false, 'A leave type with this name exists.');
        if ($this->Satt->type_exists('sltCode', $code, $id)) return $this->_json(false, 'A leave type with this code exists.');
        $ok = $this->Satt->save_type($id, array('sltName' => $name, 'sltCode' => $code, 'sltPaid' => $this->input->post('paid') ? 1 : 0,
            'sltQuota' => $quota === '' ? null : (float)$quota, 'sltSelf' => $this->input->post('self') ? 1 : 0,
            'sltOrder' => (int)$this->input->post('order'), 'sltActive' => $this->input->post('active') ? 1 : 0));
        return $this->_json($ok, $ok ? 'Leave type saved.' : 'Could not save.');
    }

    public function staff_leave_type_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('staff_att_settings', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
        $id = (int)$this->input->post('id');
        if ($this->Satt->type_in_use($id)) return $this->_json(false, 'This leave type has been used. Make it inactive instead.');
        $ok = $this->Satt->delete_type($id);
        return $this->_json($ok, $ok ? 'Leave type deleted.' : 'Delete failed.');
    }

    public function staff_holiday_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('staff_att_settings', 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        $from = $this->_date($this->input->post('from'));
        $to = $this->_date($this->input->post('to')) ?: $from;
        $name = $this->_clean($this->input->post('name'), 100);
        if (!$from) return $this->_json(false, 'Enter the date.');
        if ($to < $from) return $this->_json(false, 'The end date is before the start date.');
        if ((strtotime($to) - strtotime($from)) / 86400 > 62) return $this->_json(false, 'Add at most 2 months at a time.');
        if ($name === '') return $this->_json(false, 'Enter the holiday name.');
        $n = $this->Satt->save_holidays($from, $to, $name);
        return $this->_json(true, $n . ' holiday date(s) saved.');
    }

    public function staff_holiday_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('staff_att_settings', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
        $ok = $this->Satt->delete_holiday((int)$this->input->post('id'));
        return $this->_json($ok, $ok ? 'Holiday removed.' : 'Delete failed.');
    }

    /* ================================================================
       MY ATTENDANCE & LEAVE (logged-in staff member)
       ================================================================ */
    public function my_attendance()
    {
        $perm = $this->_perm('my_attendance');
        if (empty($perm['can_view'])) return $this->_deny();
        $me = $this->Satt->staff_of_user($this->_user());
        $m = (string)$this->input->get('month');
        $month = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $m) ? $m . '-01' : date('Y-m-01');
        if ($month > date('Y-m-01')) $month = date('Y-m-01');
        $data = array('me' => $me, 'month' => $month, 'perm' => $perm);
        if ($me) {
            $end = date('Y-m-t', strtotime($month));
            $this->Satt->holidays($month, $end);
            $off = array();
            for ($t = strtotime($month); $t <= strtotime($end); $t = strtotime('+1 day', $t)) { $d = date('Y-m-d', $t); $r = $this->Satt->off_reason($d); if ($r !== '') $off[$d] = $r; }
            $types = $this->Satt->leave_types(true);
            $ys = $this->Satt->year_start(date('Y-m-d'));
            $sum = $this->Satt->summary($month, min($end, date('Y-m-d')), array($me));
            $grid = $this->Satt->grid($month, $end, array((int)$me->peId));
            $bal = $this->Satt->balances($ys, array($me), $types);
            $data += array('off' => $off, 'grid' => isset($grid[(int)$me->peId]) ? $grid[(int)$me->peId] : array(), 'sum' => $sum[(int)$me->peId],
                'types' => $types, 'bal' => $bal[(int)$me->peId], 'year_label' => $this->Satt->year_label($ys),
                'apps' => $this->Satt->list_apps(array('staff' => $me->peId)), 'late_after' => $this->Satt->settings()['late_after']);
        }
        $this->_page('my_attendance', $data, 'My Attendance & Leave', 'MY_ATTENDANCE');
    }

    public function my_leave_apply()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('my_attendance', 'can_add')) return $this->_json(false, 'You do not have permission to apply for leave.');
        $me = $this->Satt->staff_of_user($this->_user());
        if (!$me) return $this->_json(false, 'Your login is not linked to a staff record. Please contact the office.');
        list($d, $err) = $this->_leave_input((int)$me->peId);
        if ($err) return $this->_json(false, $err);
        $t = $this->Satt->get_type($d['sltId']);
        if (!$t || !$t->sltSelf || !$t->sltActive) return $this->_json(false, 'Select a leave type you can apply for.');
        if ($d['from'] < date('Y-m-d', strtotime('-30 days'))) return $this->_json(false, 'For leave older than 30 days, please contact the office.');
        $d['self'] = true;
        $r = $this->Satt->apply($d, false);
        return $this->_json($r['ok'], $r['msg']);
    }

    public function my_leave_cancel()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('my_attendance', 'can_view')) return $this->_json(false, 'You do not have permission to do this.');
        $me = $this->Satt->staff_of_user($this->_user());
        $a = $this->Satt->get_app((int)$this->input->post('id'));
        if (!$me || !$a || (int)$a->laPeId !== (int)$me->peId) return $this->_json(false, 'Leave request not found.');
        if ($a->laStatus !== 'PENDING') return $this->_json(false, 'Only a request waiting for approval can be withdrawn. Contact the office to cancel an approved leave.');
        $r = $this->Satt->cancel($a->laId, $this->_user(), 'Withdrawn by staff');
        return $this->_json($r['ok'], $r['msg']);
    }
}
