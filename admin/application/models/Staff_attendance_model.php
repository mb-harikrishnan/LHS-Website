<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Staff attendance & leave. Staff = pay_employee (the payroll staff list).
 *
 * Day status        counts as present   loss of pay (LOP)
 *   P  Present            1                0
 *   OD On duty            1                0
 *   A  Absent             0                1
 *   HD Half day          0.5              0.5
 *   L  Leave              0          1 if the leave type is unpaid
 *   HL Half-day leave    0.5        0.5 if the leave type is unpaid
 * Off days = weekly off (settings) + holidays. Unmarked working days are reported separately.
 * Leave used in a leave year = L (1) + HL (0.5) days of that type; balance = entitlement - used.
 */
class Staff_attendance_model extends CI_Model
{
    public static $status = array('P' => 'Present', 'A' => 'Absent', 'HD' => 'Half day', 'L' => 'Leave', 'HL' => 'Half-day leave', 'OD' => 'On duty');
    public static $sat_rules = array('none' => 'All Saturdays working', 'all' => 'All Saturdays off', '2_4' => '2nd & 4th Saturday off',
                                     '1_3' => '1st & 3rd Saturday off', '2' => '2nd Saturday off');
    public static $categories = array('TEACHING' => 'Teaching', 'NON_TEACHING' => 'Non-Teaching');   // same as Payroll_model
    public static $weekdays = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday');

    private $settings = null;
    private $holidays = array();

    public function require_schema()
    {
        if (!$this->db->table_exists('staff_att_setting') || !$this->db->table_exists('pay_employee')) {
            show_error('The staff attendance tables are not set up on this database yet. Log in as admin and open '
                . site_url('Migration') . ' once to create them.', 500, 'Staff attendance not installed');
        }
    }

    /* ================================================================
       SETTINGS, CALENDAR
       ================================================================ */
    public function settings()
    {
        if ($this->settings === null) {
            $this->settings = array('week_off' => '0', 'sat_rule' => 'none', 'year_start' => '4', 'late_after' => '09:15');
            foreach ($this->db->get('staff_att_setting')->result() as $r) $this->settings[$r->ssKey] = (string)$r->ssValue;
        }
        return $this->settings;
    }

    public function save_settings($data)
    {
        $this->db->trans_start();
        foreach ($data as $k => $v) $this->db->replace('staff_att_setting', array('ssKey' => $k, 'ssValue' => $v));
        $this->db->trans_complete();
        $this->settings = null;
        return $this->db->trans_status();
    }

    public function week_offs()
    {
        $s = $this->settings();
        return $s['week_off'] === '' ? array() : array_map('intval', explode(',', $s['week_off']));
    }

    /* first day of the leave year containing $date */
    public function year_start($date)
    {
        $m = max(1, min(12, (int)$this->settings()['year_start']));
        $y = (int)date('Y', strtotime($date));
        if ((int)date('n', strtotime($date)) < $m) $y--;
        return sprintf('%04d-%02d-01', $y, $m);
    }

    public function year_end($start) { return date('Y-m-d', strtotime($start . ' +1 year -1 day')); }

    public function year_label($start)
    {
        $end = $this->year_end($start);
        return date('M Y', strtotime($start)) . ' – ' . date('M Y', strtotime($end));
    }

    /* loads holidays of a range into the cache; returns [date => name] */
    public function holidays($from, $to)
    {
        $out = array();
        foreach ($this->db->where('shDate >=', $from)->where('shDate <=', $to)->order_by('shDate')->get('staff_holiday')->result() as $h) {
            $out[$h->shDate] = $h->shName;
            $this->holidays[$h->shDate] = $h->shName;
        }
        return $out;
    }

    /* '' for a working day, else why it is off (weekly off / holiday name). Call holidays() for the range first. */
    public function off_reason($date)
    {
        if (isset($this->holidays[$date])) return $this->holidays[$date];
        $t = strtotime($date);
        $w = (int)date('w', $t);
        if (in_array($w, $this->week_offs(), true)) return self::$weekdays[$w] . ' (weekly off)';
        if ($w === 6) {
            $nth = (int)ceil((int)date('j', $t) / 7);
            $rule = $this->settings()['sat_rule'];
            $off = $rule === 'all' || ($rule === '2_4' && in_array($nth, array(2, 4), true)) || ($rule === '1_3' && in_array($nth, array(1, 3), true))
                || ($rule === '2' && $nth === 2);
            if ($off) return array(1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th', 5 => '5th')[$nth] . ' Saturday (off)';
        }
        return '';
    }

    /* working days between two dates (inclusive) */
    public function working_days($from, $to)
    {
        $this->holidays($from, $to);
        $out = array();
        for ($t = strtotime($from), $e = strtotime($to); $t <= $e; $t = strtotime('+1 day', $t)) {
            $d = date('Y-m-d', $t);
            if ($this->off_reason($d) === '') $out[] = $d;
        }
        return $out;
    }

    public function save_holidays($from, $to, $name)
    {
        $n = 0;
        for ($t = strtotime($from), $e = strtotime($to); $t <= $e; $t = strtotime('+1 day', $t)) {
            $d = date('Y-m-d', $t);
            $this->db->query('INSERT INTO staff_holiday (shDate, shName) VALUES (?, ?) ON DUPLICATE KEY UPDATE shName = VALUES(shName)', array($d, $name));
            $n++;
        }
        return $n;
    }

    public function delete_holiday($id)
    {
        return $this->db->where('shId', (int)$id)->delete('staff_holiday');
    }

    public function list_holidays($from, $to)
    {
        return $this->db->where('shDate >=', $from)->where('shDate <=', $to)->order_by('shDate')->get('staff_holiday')->result();
    }

    /* ================================================================
       LEAVE TYPES
       ================================================================ */
    public function leave_types($active_only = false)
    {
        if ($active_only) $this->db->where('sltActive', 1);
        return $this->db->order_by('sltOrder')->order_by('sltName')->get('staff_leave_type')->result();
    }

    public function get_type($id)
    {
        return $this->db->where('sltId', (int)$id)->get('staff_leave_type')->row();
    }

    public function type_exists($field, $value, $exclude = 0)
    {
        $this->db->where($field, $value);
        if ($exclude) $this->db->where('sltId !=', (int)$exclude);
        return $this->db->count_all_results('staff_leave_type') > 0;
    }

    public function save_type($id, $data)
    {
        return $id ? $this->db->where('sltId', (int)$id)->update('staff_leave_type', $data) : $this->db->insert('staff_leave_type', $data);
    }

    public function type_in_use($id)
    {
        return $this->db->where('satSltId', (int)$id)->count_all_results('staff_attendance') > 0
            || $this->db->where('laSltId', (int)$id)->count_all_results('staff_leave_app') > 0;
    }

    public function delete_type($id)
    {
        $this->db->where('slaSltId', (int)$id)->delete('staff_leave_alloc');
        return $this->db->where('sltId', (int)$id)->delete('staff_leave_type');
    }

    /* ================================================================
       STAFF
       ================================================================ */
    /* staff on payroll and employed on $date (or any day of a range) */
    public function staff_on($from, $to = null, $category = '', $peId = 0)
    {
        $to = $to ?: $from;
        $this->db->select('peId, peName, peCode, peCategory, peDesignation, peJoinDate, peLeaveDate, peEmId')->from('pay_employee')
                 ->where('peActive', 1)
                 ->group_start()->where('peJoinDate IS NULL', null, false)->or_where('peJoinDate <=', $to)->group_end()
                 ->group_start()->where('peLeaveDate IS NULL', null, false)->or_where('peLeaveDate >=', $from)->group_end();
        if ($category) $this->db->where('peCategory', $category);
        if ($peId) $this->db->where('peId', (int)$peId);
        return $this->db->order_by('peCategory')->order_by('peName')->get()->result();
    }

    public function get_staff($id)
    {
        return $this->db->where('peId', (int)$id)->get('pay_employee')->row();
    }

    /* the payroll staff record of a login (user_login.user_id) */
    public function staff_of_user($userId)
    {
        return $this->db->query('SELECT p.* FROM pay_employee p JOIN employee_master e ON e.emId = p.peEmId
                                 WHERE e.emUserId = ? AND e.emActive = 1 LIMIT 1', array((int)$userId))->row();
    }

    private static function employed($pe, $date)
    {
        return (!$pe->peJoinDate || $pe->peJoinDate <= $date) && (!$pe->peLeaveDate || $pe->peLeaveDate >= $date);
    }

    /* ================================================================
       DAILY ATTENDANCE
       ================================================================ */
    public function day_sheet($date, $category = '')
    {
        $staff = $this->staff_on($date, $date, $category);
        if (!$staff) return array();
        $ids = array(); foreach ($staff as $s) $ids[] = (int)$s->peId;
        $att = array();
        foreach ($this->db->select('a.*, t.sltCode, t.sltName')->from('staff_attendance a')->join('staff_leave_type t', 't.sltId = a.satSltId', 'left')
                          ->where('a.satDate', $date)->where_in('a.satPeId', $ids)->get()->result() as $a) $att[(int)$a->satPeId] = $a;
        foreach ($staff as $s) $s->att = isset($att[(int)$s->peId]) ? $att[(int)$s->peId] : null;
        return $staff;
    }

    /**
     * $rows [peId => ['status', 'type', 'in', 'out', 'remark']] (already validated). Days set by an approved leave application are kept.
     * returns number saved
     */
    public function save_day($date, $rows, $user)
    {
        $locked = array();
        foreach ($this->db->select('satPeId')->where('satDate', $date)->where('satLaId IS NOT NULL', null, false)->get('staff_attendance')->result() as $r) $locked[(int)$r->satPeId] = 1;
        $n = 0;
        $this->db->trans_start();
        foreach ($rows as $peId => $r) {
            if (isset($locked[(int)$peId])) continue;
            $this->db->query('INSERT INTO staff_attendance (satPeId, satDate, satStatus, satSltId, satIn, satOut, satRemark, satUser) VALUES (?,?,?,?,?,?,?,?)
                              ON DUPLICATE KEY UPDATE satStatus = VALUES(satStatus), satSltId = VALUES(satSltId), satIn = VALUES(satIn),
                              satOut = VALUES(satOut), satRemark = VALUES(satRemark), satUser = VALUES(satUser)',
                array((int)$peId, $date, $r['status'], $r['type'] ?: null, $r['in'] ?: null, $r['out'] ?: null, $r['remark'] !== '' ? $r['remark'] : null, $user));
            $n++;
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $n : 0;
    }

    /* removes the day's marks (not leave from applications) */
    public function clear_day($date)
    {
        $this->db->where('satDate', $date)->where('satLaId IS NULL', null, false)->delete('staff_attendance');
        return $this->db->affected_rows();
    }

    /* [date => marked count] of a month, for the calendar */
    public function month_marks($month)
    {
        $out = array();
        foreach ($this->db->query('SELECT satDate d, COUNT(*) n, SUM(satStatus = "A") a FROM staff_attendance WHERE satDate BETWEEN ? AND ? GROUP BY satDate',
                                  array($month, date('Y-m-t', strtotime($month))))->result() as $r) $out[$r->d] = array('n' => (int)$r->n, 'a' => (int)$r->a);
        return $out;
    }

    /* attendance rows of a range: [peId => [date => row]] */
    public function grid($from, $to, $peIds)
    {
        $out = array();
        if (!$peIds) return $out;
        foreach ($this->db->select('a.*, t.sltCode, t.sltPaid')->from('staff_attendance a')->join('staff_leave_type t', 't.sltId = a.satSltId', 'left')
                          ->where('a.satDate >=', $from)->where('a.satDate <=', $to)->where_in('a.satPeId', $peIds)->get()->result() as $a) {
            $out[(int)$a->satPeId][$a->satDate] = $a;
        }
        return $out;
    }

    /**
     * Counts per staff member for a range (only days they were employed).
     * returns [peId => ['working', 'present', 'absent', 'half', 'leave_paid', 'leave_unpaid', 'od', 'unmarked', 'late', 'lop', 'off_marked']]
     */
    public function summary($from, $to, $staff)
    {
        $ids = array(); foreach ($staff as $s) $ids[] = (int)$s->peId;
        $grid = $this->grid($from, $to, $ids);
        $work = $this->working_days($from, $to);
        $late = $this->settings()['late_after'];
        $out = array();
        foreach ($staff as $s) {
            $c = array('working' => 0, 'present' => 0, 'absent' => 0, 'half' => 0, 'leave_paid' => 0, 'leave_unpaid' => 0, 'od' => 0,
                       'unmarked' => 0, 'late' => 0, 'lop' => 0);
            $rows = isset($grid[(int)$s->peId]) ? $grid[(int)$s->peId] : array();
            foreach ($work as $d) {
                if (!self::employed($s, $d)) continue;
                $c['working']++;
                if (!isset($rows[$d])) $c['unmarked']++;
            }
            foreach ($rows as $d => $a) {
                if (!self::employed($s, $d)) continue;
                $unpaid = $a->satSltId && !(int)$a->sltPaid;
                switch ($a->satStatus) {
                    case 'P':  $c['present'] += 1; break;
                    case 'OD': $c['present'] += 1; $c['od']++; break;
                    case 'A':  $c['absent'] += 1; $c['lop'] += 1; break;
                    case 'HD': $c['present'] += 0.5; $c['half']++; $c['lop'] += 0.5; break;
                    case 'L':  $c[$unpaid ? 'leave_unpaid' : 'leave_paid'] += 1; if ($unpaid) $c['lop'] += 1; break;
                    case 'HL': $c['present'] += 0.5; $c[$unpaid ? 'leave_unpaid' : 'leave_paid'] += 0.5; if ($unpaid) $c['lop'] += 0.5; break;
                }
                if ($a->satIn && substr($a->satIn, 0, 5) > $late && in_array($a->satStatus, array('P', 'OD'), true)) $c['late']++;   // half days arrive late by design
            }
            $out[(int)$s->peId] = $c;
        }
        return $out;
    }

    /* loss-of-pay days of one staff member in a range (unmarked working days optional) */
    public function lop_days($pe, $from, $to, $count_unmarked = false)
    {
        $s = $this->summary($from, $to, array($pe));
        $c = $s[(int)$pe->peId];
        return $c['lop'] + ($count_unmarked ? $c['unmarked'] : 0);
    }

    /* ================================================================
       LEAVE: ENTITLEMENT, BALANCE
       ================================================================ */
    public function entitlements($yearStart)
    {
        $out = array();
        foreach ($this->db->where('slaYear', $yearStart)->get('staff_leave_alloc')->result() as $r) $out[(int)$r->slaPeId][(int)$r->slaSltId] = (float)$r->slaDays;
        return $out;
    }

    public function set_entitlement($peId, $sltId, $yearStart, $days)
    {
        if ($days === null) {
            return $this->db->where('slaPeId', (int)$peId)->where('slaSltId', (int)$sltId)->where('slaYear', $yearStart)->delete('staff_leave_alloc');
        }
        return $this->db->replace('staff_leave_alloc', array('slaPeId' => (int)$peId, 'slaSltId' => (int)$sltId, 'slaYear' => $yearStart, 'slaDays' => $days));
    }

    /* leave taken per staff per type in a leave year: [peId => [sltId => days]] */
    public function used($yearStart, $peId = 0)
    {
        $sql = "SELECT satPeId, satSltId, SUM(CASE satStatus WHEN 'L' THEN 1 WHEN 'HL' THEN 0.5 ELSE 0 END) d FROM staff_attendance
                WHERE satSltId IS NOT NULL AND satDate BETWEEN ? AND ?" . ($peId ? ' AND satPeId = ' . (int)$peId : '') . ' GROUP BY satPeId, satSltId';
        $out = array();
        foreach ($this->db->query($sql, array($yearStart, $this->year_end($yearStart)))->result() as $r) $out[(int)$r->satPeId][(int)$r->satSltId] = (float)$r->d;
        return $out;
    }

    /* pending applications per staff per type in a leave year */
    public function pending($yearStart, $peId = 0)
    {
        $sql = "SELECT laPeId, laSltId, SUM(laDays) d FROM staff_leave_app WHERE laStatus = 'PENDING' AND laFrom BETWEEN ? AND ?"
             . ($peId ? ' AND laPeId = ' . (int)$peId : '') . ' GROUP BY laPeId, laSltId';
        $out = array();
        foreach ($this->db->query($sql, array($yearStart, $this->year_end($yearStart)))->result() as $r) $out[(int)$r->laPeId][(int)$r->laSltId] = (float)$r->d;
        return $out;
    }

    /**
     * balance of every leave type for staff members: [peId => [sltId => ['entitled' (null = no limit), 'used', 'pending', 'balance']]]
     */
    public function balances($yearStart, $staff, $types)
    {
        $ent = $this->entitlements($yearStart);
        $one = count($staff) === 1 ? (int)$staff[0]->peId : 0;
        $used = $this->used($yearStart, $one); $pend = $this->pending($yearStart, $one);
        $out = array();
        foreach ($staff as $s) {
            $p = (int)$s->peId;
            foreach ($types as $t) {
                $id = (int)$t->sltId;
                $e = isset($ent[$p][$id]) ? $ent[$p][$id] : ($t->sltQuota === null ? null : (float)$t->sltQuota);
                $u = isset($used[$p][$id]) ? $used[$p][$id] : 0;
                $out[$p][$id] = array('entitled' => $e, 'used' => $u, 'pending' => isset($pend[$p][$id]) ? $pend[$p][$id] : 0,
                                      'balance' => $e === null ? null : round($e - $u, 1));
            }
        }
        return $out;
    }

    /* ================================================================
       LEAVE APPLICATIONS
       ================================================================ */
    public function list_apps($f = array())
    {
        $this->db->select('a.*, p.peName, p.peCode, p.peDesignation, t.sltName, t.sltCode, t.sltPaid')->from('staff_leave_app a')
                 ->join('pay_employee p', 'p.peId = a.laPeId')->join('staff_leave_type t', 't.sltId = a.laSltId');
        if (!empty($f['status'])) $this->db->where('a.laStatus', $f['status']);
        if (!empty($f['staff']))  $this->db->where('a.laPeId', (int)$f['staff']);
        if (!empty($f['type']))   $this->db->where('a.laSltId', (int)$f['type']);
        if (!empty($f['from']))   $this->db->where('a.laTo >=', $f['from']);
        if (!empty($f['to']))     $this->db->where('a.laFrom <=', $f['to']);
        return $this->db->order_by("FIELD(a.laStatus,'PENDING','APPROVED','REJECTED','CANCELLED')", '', false)
                        ->order_by('a.laFrom', 'DESC')->order_by('a.laId', 'DESC')->limit(500)->get()->result();
    }

    public function get_app($id)
    {
        return $this->db->select('a.*, p.peName, t.sltName, t.sltCode, t.sltPaid, t.sltQuota')->from('staff_leave_app a')
                        ->join('pay_employee p', 'p.peId = a.laPeId')->join('staff_leave_type t', 't.sltId = a.laSltId')
                        ->where('a.laId', (int)$id)->get()->row();
    }

    /* working days the leave covers */
    public function leave_days($from, $to, $half)
    {
        $n = count($this->working_days($from, $to));
        return $half ? ($n ? 0.5 : 0) : (float)$n;
    }

    /* another pending / approved application overlapping these dates */
    public function overlap($peId, $from, $to, $exclude = 0)
    {
        $this->db->where('laPeId', (int)$peId)->where_in('laStatus', array('PENDING', 'APPROVED'))->where('laFrom <=', $to)->where('laTo >=', $from);
        if ($exclude) $this->db->where('laId !=', (int)$exclude);
        return $this->db->get('staff_leave_app')->row();
    }

    /**
     * $d: peId, sltId, from, to, half, reason, self, user. $approve: approve at once (office entry)
     * returns ['ok', 'msg', 'id']
     */
    public function apply($d, $approve = false, $note = '')
    {
        $pe = $this->get_staff($d['peId']);
        $t = $this->get_type($d['sltId']);
        if (!$pe || !$pe->peActive) return array('ok' => false, 'msg' => 'Select a staff member on payroll.');
        if (!$t || !$t->sltActive)  return array('ok' => false, 'msg' => 'Select the leave type.');
        if (!self::employed($pe, $d['from']) || !self::employed($pe, $d['to'])) return array('ok' => false, 'msg' => 'The dates are outside ' . $pe->peName . "'s joining / relieving dates.");
        $days = $this->leave_days($d['from'], $d['to'], $d['half']);
        if ($days <= 0) return array('ok' => false, 'msg' => 'These dates are all holidays or weekly offs.');
        $o = $this->overlap($d['peId'], $d['from'], $d['to']);
        if ($o) return array('ok' => false, 'msg' => 'There is already a ' . strtolower($o->laStatus) . ' leave from ' . date('d-m-Y', strtotime($o->laFrom)) . ' to ' . date('d-m-Y', strtotime($o->laTo)) . '.');
        $err = $this->_balance_error($pe, $t, $d['from'], $d['to'], $days, 0, !$approve);
        if ($err) return array('ok' => false, 'msg' => $err);

        $this->db->insert('staff_leave_app', array('laPeId' => (int)$d['peId'], 'laSltId' => (int)$d['sltId'], 'laFrom' => $d['from'], 'laTo' => $d['to'],
            'laHalf' => $d['half'] ? 1 : 0, 'laDays' => $days, 'laReason' => $d['reason'] ?: null, 'laStatus' => 'PENDING',
            'laSelf' => $d['self'] ? 1 : 0, 'laUser' => $d['user']));
        $id = (int)$this->db->insert_id();
        if (!$id) return array('ok' => false, 'msg' => 'Could not save.');
        if ($approve) {
            $r = $this->approve($id, $d['user'], $note);
            if (!$r['ok']) return $r;
            return array('ok' => true, 'msg' => $t->sltName . ' of ' . $days . ' day(s) for ' . $pe->peName . ' recorded and approved.', 'id' => $id);
        }
        return array('ok' => true, 'msg' => 'Leave application for ' . $days . ' day(s) sent for approval.', 'id' => $id);
    }

    /* '' or why the leave is more than the balance; $with_pending also counts other pending applications */
    private function _balance_error($pe, $t, $from, $to, $days, $exclude, $with_pending)
    {
        if ($t->sltQuota === null && !$this->db->where('slaPeId', $pe->peId)->where('slaSltId', $t->sltId)->count_all_results('staff_leave_alloc')) return '';
        $ys = $this->year_start($from);
        if ($this->year_start($to) !== $ys) return 'A leave cannot run across two leave years. Split it at ' . date('d-m-Y', strtotime($this->year_end($ys))) . '.';
        $b = $this->balances($ys, array($pe), array($t));
        $x = $b[(int)$pe->peId][(int)$t->sltId];
        if ($x['entitled'] === null) return '';
        $left = $x['balance'] - ($with_pending ? $x['pending'] : 0);
        if ($exclude) {
            $own = $this->db->select('laDays, laStatus')->where('laId', (int)$exclude)->get('staff_leave_app')->row();
            if ($with_pending && $own && $own->laStatus === 'PENDING') $left += (float)$own->laDays;
        }
        if ($days - $left > 0.001) {
            return $t->sltName . ' balance is ' . max(0, $left) . ' day(s)' . ($with_pending && $x['pending'] ? ' after pending requests' : '')
                 . '; this leave needs ' . $days . '. Take the extra days as Loss of Pay or ask the office to raise the entitlement.';
        }
        return '';
    }

    public function approve($id, $user, $note = '')
    {
        $a = $this->get_app($id);
        if (!$a || $a->laStatus !== 'PENDING') return array('ok' => false, 'msg' => 'Only a pending application can be approved.');
        $pe = $this->get_staff($a->laPeId);
        $err = $this->_balance_error($pe, $this->get_type($a->laSltId), $a->laFrom, $a->laTo, (float)$a->laDays, $id, false);
        if ($err) return array('ok' => false, 'msg' => $err);
        $status = $a->laHalf ? 'HL' : 'L';
        $this->db->trans_start();
        foreach ($this->working_days($a->laFrom, $a->laTo) as $d) {
            $this->db->query('INSERT INTO staff_attendance (satPeId, satDate, satStatus, satSltId, satLaId, satIn, satOut, satRemark, satUser) VALUES (?,?,?,?,?,NULL,NULL,?,?)
                              ON DUPLICATE KEY UPDATE satStatus = VALUES(satStatus), satSltId = VALUES(satSltId), satLaId = VALUES(satLaId),
                              satIn = IF(VALUES(satStatus) = "HL", satIn, NULL), satOut = IF(VALUES(satStatus) = "HL", satOut, NULL),
                              satRemark = VALUES(satRemark), satUser = VALUES(satUser)',
                array((int)$a->laPeId, $d, $status, (int)$a->laSltId, (int)$id, mb_substr($a->sltCode . ($a->laReason ? ': ' . $a->laReason : ''), 0, 100), $user));
        }
        $this->db->where('laId', (int)$id)->update('staff_leave_app', array('laStatus' => 'APPROVED', 'laDecidedBy' => $user,
            'laDecidedAt' => date('Y-m-d H:i:s'), 'laNote' => $note !== '' ? $note : null));
        $this->db->trans_complete();
        return $this->db->trans_status() ? array('ok' => true, 'msg' => 'Leave approved and marked in attendance.') : array('ok' => false, 'msg' => 'Could not approve.');
    }

    public function reject($id, $user, $note)
    {
        $a = $this->get_app($id);
        if (!$a || $a->laStatus !== 'PENDING') return array('ok' => false, 'msg' => 'Only a pending application can be rejected.');
        $this->db->where('laId', (int)$id)->update('staff_leave_app', array('laStatus' => 'REJECTED', 'laDecidedBy' => $user,
            'laDecidedAt' => date('Y-m-d H:i:s'), 'laNote' => $note));
        return array('ok' => true, 'msg' => 'Leave rejected.');
    }

    /* pending -> cancelled; approved -> cancelled and its attendance marks removed */
    public function cancel($id, $user, $note)
    {
        $a = $this->get_app($id);
        if (!$a || !in_array($a->laStatus, array('PENDING', 'APPROVED'), true)) return array('ok' => false, 'msg' => 'This leave cannot be cancelled.');
        $this->db->trans_start();
        $this->db->where('satLaId', (int)$id)->delete('staff_attendance');
        $this->db->where('laId', (int)$id)->update('staff_leave_app', array('laStatus' => 'CANCELLED', 'laDecidedBy' => $user,
            'laDecidedAt' => date('Y-m-d H:i:s'), 'laNote' => $note !== '' ? $note : $a->laNote));
        $this->db->trans_complete();
        return $this->db->trans_status() ? array('ok' => true, 'msg' => $a->laStatus === 'APPROVED' ? 'Leave cancelled; those days are now unmarked.' : 'Leave request withdrawn.')
                                         : array('ok' => false, 'msg' => 'Could not cancel.');
    }

    public function pending_count()
    {
        return $this->db->where('laStatus', 'PENDING')->count_all_results('staff_leave_app');
    }
}
