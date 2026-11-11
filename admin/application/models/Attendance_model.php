<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Daily student attendance.  Status: P present, A absent, H half day, L leave.
 * Working days of a class = days on which attendance was marked for it.
 * Attendance % = (P + 0.5 * H) / days marked.
 */
class Attendance_model extends CI_Model
{
    public static $status = array('P' => 'Present', 'A' => 'Absent', 'H' => 'Half day', 'L' => 'Leave');

    public function require_schema()
    {
        if (!$this->db->table_exists('student_attendance')) {
            show_error('The attendance table is not set up on this database yet. Log in as admin and open '
                . site_url('Migration') . ' once to create it.', 500, 'Attendance not installed');
        }
    }

    public static function percent($p, $h, $days)
    {
        return $days > 0 ? round(($p + 0.5 * $h) * 100 / $days, 1) : null;
    }

    /* students of a class + division for a day, with their status (null = not marked) */
    public function day_sheet($amId, $cmId, $dmId, $date)
    {
        return $this->db->query("SELECT s.smId, s.smAdmissionNo, s.smName, s.smGender, a.saStatus, a.saRemark
                FROM students_master s
                LEFT JOIN student_attendance a ON a.saSmId = s.smId AND a.saDate = ?
                WHERE s.smAmId = ? AND s.smClass = ? AND s.smDiv = ?
                ORDER BY CASE WHEN s.smGender = '0' THEN 0 WHEN s.smGender = '1' THEN 1 ELSE 2 END, TRIM(s.smName)",
            array($date, (int)$amId, (int)$cmId, (int)$dmId))->result();
    }

    /* $marks: [smId => ['status' => P|A|H|L, 'remark' => '']] — only students of this class are saved */
    public function save_day($amId, $cmId, $dmId, $date, $marks, $user)
    {
        $valid = array();
        foreach ($this->db->select('smId')->where(array('smAmId' => (int)$amId, 'smClass' => (int)$cmId, 'smDiv' => (int)$dmId))
                          ->get('students_master')->result() as $r) $valid[(int)$r->smId] = true;
        $n = 0;
        $this->db->trans_start();
        foreach ($marks as $smId => $m) {
            if (!isset($valid[(int)$smId])) continue;
            $this->db->query("INSERT INTO student_attendance (saSmId, saAmId, saCmId, saDmId, saDate, saStatus, saRemark, saUser)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                              ON DUPLICATE KEY UPDATE saAmId = VALUES(saAmId), saCmId = VALUES(saCmId), saDmId = VALUES(saDmId),
                                                      saStatus = VALUES(saStatus), saRemark = VALUES(saRemark), saUser = VALUES(saUser)",
                array((int)$smId, (int)$amId, (int)$cmId, (int)$dmId, $date, $m['status'], $m['remark'] !== '' ? $m['remark'] : null, $user));
            $n++;
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $n : -1;
    }

    public function clear_day($amId, $cmId, $dmId, $date)
    {
        return $this->db->where(array('saAmId' => (int)$amId, 'saCmId' => (int)$cmId, 'saDmId' => (int)$dmId, 'saDate' => $date))
                        ->delete('student_attendance');
    }

    /* days marked in a month for a class: ['Y-m-d' => [P,A,H,L counts]] */
    public function class_month_days($amId, $cmId, $dmId, $ym)
    {
        $rows = $this->db->query("SELECT saDate, saStatus, COUNT(*) n FROM student_attendance
                WHERE saAmId = ? AND saCmId = ? AND saDmId = ? AND saDate BETWEEN ? AND LAST_DAY(?)
                GROUP BY saDate, saStatus", array((int)$amId, (int)$cmId, (int)$dmId, $ym . '-01', $ym . '-01'))->result();
        $out = array();
        foreach ($rows as $r) {
            if (!isset($out[$r->saDate])) $out[$r->saDate] = array('P' => 0, 'A' => 0, 'H' => 0, 'L' => 0);
            $out[$r->saDate][$r->saStatus] = (int)$r->n;
        }
        return $out;
    }

    /* statuses of students of a class for a date range: [smId][Y-m-d] => status */
    public function grid($amId, $cmId, $dmId, $from, $to)
    {
        $where = 'saAmId = ? AND saCmId = ? AND saDate BETWEEN ? AND ?';
        $p = array((int)$amId, (int)$cmId, $from, $to);
        if ($dmId !== '' && $dmId !== null) { $where .= ' AND saDmId = ?'; $p[] = (int)$dmId; }
        $out = array();
        foreach ($this->db->query("SELECT saSmId, saDate, saStatus FROM student_attendance WHERE $where", $p)->result() as $r) {
            $out[(int)$r->saSmId][$r->saDate] = $r->saStatus;
        }
        return $out;
    }

    /* counts per student for a range: [smId => [P,A,H,L,days,pct]] */
    public function summary($smIds, $from, $to)
    {
        $out = array();
        if (!$smIds) return $out;
        $in = implode(',', array_map('intval', $smIds));
        $rows = $this->db->query("SELECT saSmId, SUM(saStatus='P') p, SUM(saStatus='A') a, SUM(saStatus='H') h, SUM(saStatus='L') l, COUNT(*) d
                FROM student_attendance WHERE saSmId IN ($in) AND saDate BETWEEN ? AND ? GROUP BY saSmId", array($from, $to))->result();
        foreach ($rows as $r) {
            $out[(int)$r->saSmId] = array('P' => (int)$r->p, 'A' => (int)$r->a, 'H' => (int)$r->h, 'L' => (int)$r->l, 'days' => (int)$r->d,
                                          'pct' => self::percent((int)$r->p, (int)$r->h, (int)$r->d));
        }
        return $out;
    }

    /* one student's statuses in a range: ['Y-m-d' => [status, remark]] */
    public function student_days($smId, $from, $to)
    {
        $out = array();
        foreach ($this->db->query("SELECT saDate, saStatus, saRemark FROM student_attendance WHERE saSmId = ? AND saDate BETWEEN ? AND ? ORDER BY saDate",
                                  array((int)$smId, $from, $to))->result() as $r) $out[$r->saDate] = array($r->saStatus, $r->saRemark);
        return $out;
    }

    /* academic year date range from attendance of the year (first..last marked day), else the calendar year */
    public function year_range($amId)
    {
        $r = $this->db->query("SELECT MIN(saDate) f, MAX(saDate) t FROM student_attendance WHERE saAmId = ?", array((int)$amId))->row();
        return ($r && $r->f) ? array($r->f, $r->t) : array(date('Y-01-01'), date('Y-m-d'));
    }
}
