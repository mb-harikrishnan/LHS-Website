<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payroll / employee salary.
 *
 * Salary of a month (one payslip per staff member):
 *   paid days   = days in month - loss-of-pay (LOP) days
 *   basic       = monthly basic x paid days / days in month
 *   earnings    FIXED  : amount (reduced for LOP when "prorate" is on)
 *               BASIC  : % of the earned basic
 *   gross       = basic + earnings + extra earning (arrears, bonus ...)
 *   deductions / employer contributions
 *               FIXED  : amount (reduced for LOP when "prorate" is on; nothing when no day is paid)
 *               BASIC  : % of the earned basic
 *               WAGE   : % of basic + allowances marked "PF wage" (e.g. DA), up to the ceiling (PF on 15,000)
 *               GROSS  : % of gross, only when the full-month gross is within the gross limit (ESI up to 21,000)
 *   advance recovery: instalment of each open advance, never more than what is left
 *   net         = gross - deductions - advance recovery - extra deduction
 * Every amount is rounded to whole rupees.
 *
 * A run is DRAFT (editable, recalculated whenever structures change) until APPROVED.
 * Approval posts a journal to accounts; payments post payment vouchers (see Accounts_model::sync_sources).
 */
class Payroll_model extends CI_Model
{
    public static $types      = array('EARNING' => 'Earning', 'DEDUCTION' => 'Deduction', 'EMPLOYER' => 'Employer contribution');
    public static $calcs      = array('FIXED' => 'Fixed amount', 'BASIC' => '% of Basic', 'WAGE' => '% of PF wage (Basic + DA)', 'GROSS' => '% of Gross');
    public static $categories = array('TEACHING' => 'Teaching', 'NON_TEACHING' => 'Non-Teaching');
    public static $modes      = array('Bank', 'Cash', 'Cheque');
    public static $pay_modes  = array('Bank Transfer', 'Cash', 'Cheque', 'UPI');
    public static $keys       = array('PF' => 'DEDUCTION', 'ESI' => 'DEDUCTION', 'PT' => 'DEDUCTION', 'TDS' => 'DEDUCTION',
                                      'PF_ER' => 'EMPLOYER', 'ESI_ER' => 'EMPLOYER');
    public static $key_labels = array('PF' => 'Provident Fund (employee)', 'ESI' => 'ESI (employee)', 'PT' => 'Professional Tax',
                                      'TDS' => 'Income Tax (TDS)', 'PF_ER' => 'Provident Fund (employer)', 'ESI_ER' => 'ESI (employer)');

    public function require_schema()
    {
        if (!$this->db->table_exists('pay_batch_slip')) {
            show_error('The payroll tables are not set up on this database yet. Log in as admin and open '
                . site_url('Migration') . ' once to create them.', 500, 'Payroll module not installed');
        }
    }

    private static function r2($n) { return round((float)$n, 2); }
    private static function rup($n) { return (float)round((float)$n, 0); }

    public static function month_label($date) { return date('F Y', strtotime($date)); }
    public static function month_end($month)  { return date('Y-m-t', strtotime($month)); }

    /* ================================================================
       COMPONENTS
       ================================================================ */
    public function components($active_only = false)
    {
        if ($active_only) $this->db->where('pcActive', 1);
        return $this->db->order_by("FIELD(pcType,'EARNING','DEDUCTION','EMPLOYER')", '', false)->order_by('pcOrder')->order_by('pcName')
                        ->get('pay_component')->result();
    }

    public function get_component($id)
    {
        return $this->db->where('pcId', (int)$id)->get('pay_component')->row();
    }

    public function component_name_exists($name, $exclude = 0)
    {
        $this->db->where('pcName', $name);
        if ($exclude) $this->db->where('pcId !=', (int)$exclude);
        return $this->db->count_all_results('pay_component') > 0;
    }

    public function component_key_exists($key, $exclude = 0)
    {
        $this->db->where('pcKey', $key);
        if ($exclude) $this->db->where('pcId !=', (int)$exclude);
        return $this->db->count_all_results('pay_component') > 0;
    }

    public function save_component($id, $data)
    {
        if ($id) $ok = $this->db->where('pcId', (int)$id)->update('pay_component', $data);
        else     $ok = $this->db->insert('pay_component', $data);
        if ($ok) $this->recalc_all_drafts();
        return $ok;
    }

    public function component_in_use($id)
    {
        return $this->db->where('pecPcId', (int)$id)->count_all_results('pay_employee_component') > 0
            || $this->db->where('pslPcId', (int)$id)->count_all_results('pay_slip_line') > 0;
    }

    public function delete_component($id)
    {
        return $this->db->where('pcId', (int)$id)->delete('pay_component');
    }

    /* ================================================================
       STAFF + SALARY STRUCTURE
       ================================================================ */
    public function list_staff($f = array())
    {
        $this->db->select('p.*, e.emActive, r.role_name')->from('pay_employee p')
                 ->join('employee_master e', 'e.emId = p.peEmId', 'left')
                 ->join('user_roles r', 'r.role_id = e.emDesigId', 'left');
        if (!empty($f['q'])) {
            $this->db->group_start()->like('p.peName', $f['q'])->or_like('p.peCode', $f['q'])->or_like('p.pePhone', $f['q'])
                     ->or_like('p.peDesignation', $f['q'])->group_end();
        }
        if (!empty($f['category'])) $this->db->where('p.peCategory', $f['category']);
        if (isset($f['active']) && $f['active'] !== '') $this->db->where('p.peActive', (int)$f['active']);
        return $this->db->order_by('p.peActive', 'DESC')->order_by('p.peName')->get()->result();
    }

    public function get_staff($id)
    {
        return $this->db->where('peId', (int)$id)->get('pay_employee')->row();
    }

    /* [pcId => value] of a staff member's structure */
    public function staff_values($peId)
    {
        $out = array();
        foreach ($this->db->where('pecPeId', (int)$peId)->get('pay_employee_component')->result() as $r) $out[(int)$r->pecPcId] = (float)$r->pecValue;
        return $out;
    }

    /* structure of every staff member: [peId => [pcId => value]] */
    public function all_staff_values()
    {
        $out = array();
        foreach ($this->db->get('pay_employee_component')->result() as $r) $out[(int)$r->pecPeId][(int)$r->pecPcId] = (float)$r->pecValue;
        return $out;
    }

    public function staff_code_exists($code, $exclude = 0)
    {
        $this->db->where('peCode', $code);
        if ($exclude) $this->db->where('peId !=', (int)$exclude);
        return $this->db->count_all_results('pay_employee') > 0;
    }

    public function employee_linked($emId, $exclude = 0)
    {
        $this->db->where('peEmId', (int)$emId);
        if ($exclude) $this->db->where('peId !=', (int)$exclude);
        return $this->db->count_all_results('pay_employee') > 0;
    }

    /* active employees (logins) that are not on payroll yet */
    public function unlinked_employees()
    {
        return $this->db->query("SELECT e.emId, e.emName, e.emPhoneNo, r.role_name FROM employee_master e
                                 LEFT JOIN user_roles r ON r.role_id = e.emDesigId
                                 LEFT JOIN pay_employee p ON p.peEmId = e.emId
                                 WHERE e.emActive = 1 AND p.peId IS NULL ORDER BY e.emName")->result();
    }

    /** $values = [pcId => value] */
    public function save_staff($id, $data, $values)
    {
        $this->db->trans_start();
        if ($id) {
            $this->db->where('peId', (int)$id)->update('pay_employee', $data);
        } else {
            $this->db->insert('pay_employee', $data);
            $id = (int)$this->db->insert_id();
        }
        $this->db->where('pecPeId', (int)$id)->delete('pay_employee_component');
        foreach ($values as $pcId => $v) {
            $this->db->insert('pay_employee_component', array('pecPeId' => (int)$id, 'pecPcId' => (int)$pcId, 'pecValue' => self::r2($v)));
        }
        $this->db->trans_complete();
        if (!$this->db->trans_status()) return 0;
        $this->recalc_staff_drafts($id);
        return (int)$id;
    }

    /* adds every active employee (login) that is not on payroll yet; not on payroll until a salary is set */
    public function import_employees()
    {
        $n = 0;
        foreach ($this->unlinked_employees() as $e) {
            $cat = stripos((string)$e->role_name, 'teacher') !== false ? 'TEACHING' : 'NON_TEACHING';
            $this->db->insert('pay_employee', array('peEmId' => (int)$e->emId, 'peName' => mb_substr(trim((string)$e->emName), 0, 100) ?: 'Employee ' . $e->emId,
                'pePhone' => $e->emPhoneNo ? (string)$e->emPhoneNo : null, 'peCategory' => $cat, 'peDesignation' => $e->role_name ?: null,
                'peActive' => 0, 'peBasic' => 0, 'pePayMode' => 'Bank'));
            $n++;
        }
        return $n;
    }

    public function staff_has_history($id)
    {
        return $this->db->where('psPeId', (int)$id)->count_all_results('pay_slip') > 0
            || $this->db->where('padPeId', (int)$id)->count_all_results('pay_advance') > 0;
    }

    public function delete_staff($id)
    {
        $this->db->trans_start();
        $this->db->where('pecPeId', (int)$id)->delete('pay_employee_component');
        $this->db->where('peId', (int)$id)->delete('pay_employee');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* ================================================================
       CALCULATION
       ================================================================ */
    /**
     * $basicRate monthly basic; $comps active components; $values [pcId => value] of the staff member
     * $advances [ ['id', 'name', 'installment', 'outstanding'], ... ]
     * returns ['lines' => [...], 'gross', 'deduction', 'employer', 'net', 'paid_days', 'std_gross']
     */
    public function calculate($basicRate, $comps, $values, $days, $lop, $extraEarn = 0, $extraDed = 0, $extraEarnNote = '', $extraDedNote = '', $advances = array())
    {
        $days = max(1, (int)$days);
        $lop  = min(max(0, (float)$lop), $days);
        $paid = $days - $lop;
        $f    = $paid / $days;
        $lines = array(); $ord = 0;
        $line = function ($kind, $type, $name, $amount, $pcId = null, $key = null, $base = null, $padId = null) use (&$lines, &$ord) {
            $lines[] = array('kind' => $kind, 'type' => $type, 'name' => $name, 'amount' => $amount, 'pcId' => $pcId,
                             'key' => $key, 'base' => $base, 'padId' => $padId, 'order' => ++$ord);
        };

        $basicRate = (float)$basicRate;
        $basic = self::rup($basicRate * $f);
        $line('BASIC', 'EARNING', 'Basic Pay', $basic);
        $gross = $basic; $wage = $basic; $stdGross = $basicRate;

        foreach ($comps as $c) {
            if ($c->pcType !== 'EARNING' || !array_key_exists((int)$c->pcId, $values)) continue;
            $v = (float)$values[(int)$c->pcId];
            if ($c->pcCalc === 'BASIC') { $full = $basicRate * $v / 100; $amt = self::rup($basic * $v / 100); }
            else                        { $full = $v; $amt = self::rup($c->pcProrate ? $v * $f : $v); }
            $stdGross += $full;
            if ($amt <= 0) continue;
            $gross += $amt;
            if ($c->pcWage) $wage += $amt;
            $line('COMP', 'EARNING', $c->pcName, $amt, (int)$c->pcId, $c->pcKey);
        }
        $extraEarn = self::rup($extraEarn);
        if ($extraEarn > 0) { $gross += $extraEarn; $line('EXTRA', 'EARNING', $extraEarnNote !== '' ? $extraEarnNote : 'Other Earnings', $extraEarn); }

        $ded = 0; $emp = 0;
        foreach ($comps as $c) {
            if ($c->pcType === 'EARNING' || !array_key_exists((int)$c->pcId, $values)) continue;
            if ((float)$c->pcGrossLimit > 0 && $stdGross > (float)$c->pcGrossLimit) continue;   // not applicable at this salary
            $v = (float)$values[(int)$c->pcId];
            $base = null;
            if ($c->pcCalc === 'BASIC') $base = $basic;
            if ($c->pcCalc === 'WAGE')  $base = $wage;
            if ($c->pcCalc === 'GROSS') $base = $gross;
            if ($base !== null) {
                if ((float)$c->pcCeiling > 0) $base = min($base, (float)$c->pcCeiling);
                $amt = self::rup($base * $v / 100);
            } else {
                $amt = $paid <= 0 ? 0 : self::rup($c->pcProrate ? $v * $f : $v);
            }
            if ($amt <= 0) continue;
            if ($c->pcType === 'DEDUCTION') $ded += $amt; else $emp += $amt;
            $line('COMP', $c->pcType, $c->pcName, $amt, (int)$c->pcId, $c->pcKey, $base !== null ? self::r2($base) : null);
        }
        $extraDed = self::rup($extraDed);
        if ($extraDed > 0) { $ded += $extraDed; $line('EXTRA', 'DEDUCTION', $extraDedNote !== '' ? $extraDedNote : 'Other Deductions', $extraDed); }

        $room = $gross - $ded;
        foreach ($advances as $a) {
            $rec = self::rup(min((float)$a['installment'], (float)$a['outstanding'], max(0, $room)));
            if ($rec <= 0) continue;
            $ded += $rec; $room -= $rec;
            $line('ADV', 'DEDUCTION', $a['name'], $rec, null, null, null, (int)$a['id']);
        }

        return array('lines' => $lines, 'gross' => self::r2($gross), 'deduction' => self::r2($ded), 'employer' => self::r2($emp),
                     'net' => self::r2($gross - $ded), 'paid_days' => $paid, 'std_gross' => self::r2($stdGross));
    }

    /* ================================================================
       PAYROLL RUNS
       ================================================================ */
    public function list_runs()
    {
        return $this->db->query("SELECT r.*, COUNT(s.psId) staff, COALESCE(SUM(s.psGross),0) gross, COALESCE(SUM(s.psDeduction),0) deduction,
                COALESCE(SUM(s.psEmployer),0) employer, COALESCE(SUM(s.psNet),0) net, COALESCE(SUM(s.psPaid),0) paid
                FROM pay_run r LEFT JOIN pay_slip s ON s.psPrId = r.prId GROUP BY r.prId ORDER BY r.prMonth DESC")->result();
    }

    public function get_run($id)
    {
        return $this->db->where('prId', (int)$id)->get('pay_run')->row();
    }

    public function run_exists($month)
    {
        return $this->db->where('prMonth', $month)->count_all_results('pay_run') > 0;
    }

    /* staff on payroll during a month */
    public function eligible_staff($month)
    {
        $end = self::month_end($month);
        return $this->db->query("SELECT * FROM pay_employee WHERE peActive = 1
                                 AND (peJoinDate IS NULL OR peJoinDate <= ?) AND (peLeaveDate IS NULL OR peLeaveDate >= ?)
                                 ORDER BY peCategory, peName", array($end, $month))->result();
    }

    /* days of the month before joining / after leaving */
    private function _auto_lop($pe, $month, $days)
    {
        $lop = 0;
        $start = strtotime($month); $end = strtotime(self::month_end($month));
        if ($pe->peJoinDate && strtotime($pe->peJoinDate) > $start) $lop += (int)round((strtotime($pe->peJoinDate) - $start) / 86400);
        if ($pe->peLeaveDate && strtotime($pe->peLeaveDate) < $end) $lop += (int)round(($end - strtotime($pe->peLeaveDate)) / 86400);
        return min($days, $lop);
    }

    /* open advances of a staff member for a month; recovery already made in other payslips is taken off */
    private function _advances_for($peId, $month, $psId)
    {
        $rows = $this->db->query("SELECT a.padId, a.padDate, a.padAmount, a.padInstallment,
                    (SELECT COALESCE(SUM(l.pslAmount),0) FROM pay_slip_line l WHERE l.pslPadId = a.padId AND l.pslPsId <> ?) done
                FROM pay_advance a WHERE a.padPeId = ? AND a.padCancelled = 0 AND a.padFromMonth <= ?
                ORDER BY a.padDate, a.padId", array((int)$psId, (int)$peId, $month))->result();
        $out = array();
        foreach ($rows as $a) {
            $left = self::r2($a->padAmount - $a->done);
            if ($left <= 0) continue;
            $out[] = array('id' => (int)$a->padId, 'installment' => (float)$a->padInstallment, 'outstanding' => $left,
                           'name' => 'Advance Recovery (' . date('d-m-Y', strtotime($a->padDate)) . ')');
        }
        return $out;
    }

    /**
     * Creates or recalculates the payslip of one staff member in a draft run.
     * $keep: existing slip row (its LOP and extras are kept); null = new slip with automatic LOP.
     */
    private function _build_slip($run, $pe, $keep = null, $comps = null, $values = null)
    {
        if ($comps === null)  $comps  = $this->components(true);
        if ($values === null) $values = $this->staff_values($pe->peId);
        $days = (int)$run->prDays;
        $lop  = $keep ? (float)$keep->psLop : $this->_auto_lop($pe, $run->prMonth, $days);
        $ee   = $keep ? (float)$keep->psExtraEarn : 0;  $een = $keep ? (string)$keep->psExtraEarnNote : '';
        $ed   = $keep ? (float)$keep->psExtraDed : 0;   $edn = $keep ? (string)$keep->psExtraDedNote : '';
        $psId = $keep ? (int)$keep->psId : 0;
        $c = $this->calculate($pe->peBasic, $comps, $values, $days, $lop, $ee, $ed, $een, $edn, $this->_advances_for($pe->peId, $run->prMonth, $psId));

        $row = array('psName' => $pe->peName, 'psCode' => $pe->peCode, 'psDesignation' => $pe->peDesignation, 'psCategory' => $pe->peCategory,
            'psPayMode' => $pe->pePayMode, 'psBankName' => $pe->peBankName, 'psAccountNo' => $pe->peAccountNo, 'psIfsc' => $pe->peIfsc,
            'psPan' => $pe->pePan, 'psUan' => $pe->peUan, 'psEsiNo' => $pe->peEsiNo,
            'psDays' => $days, 'psLop' => $lop, 'psPaidDays' => $c['paid_days'], 'psBasicRate' => self::r2($pe->peBasic),
            'psGross' => $c['gross'], 'psDeduction' => $c['deduction'], 'psEmployer' => $c['employer'], 'psNet' => $c['net']);
        if ($psId) {
            $this->db->where('psId', $psId)->update('pay_slip', $row);
            $this->db->where('pslPsId', $psId)->delete('pay_slip_line');
        } else {
            $row['psPrId'] = (int)$run->prId; $row['psPeId'] = (int)$pe->peId;
            $this->db->insert('pay_slip', $row);
            $psId = (int)$this->db->insert_id();
        }
        foreach ($c['lines'] as $l) {
            $this->db->insert('pay_slip_line', array('pslPsId' => $psId, 'pslKind' => $l['kind'], 'pslPcId' => $l['pcId'], 'pslPadId' => $l['padId'],
                'pslName' => mb_substr($l['name'], 0, 80), 'pslType' => $l['type'], 'pslKey' => $l['key'], 'pslBase' => $l['base'],
                'pslAmount' => $l['amount'], 'pslOrder' => $l['order']));
        }
        return $psId;
    }

    /** returns ['ok', 'msg', 'id'] */
    public function create_run($month, $user)
    {
        if ($this->run_exists($month)) return array('ok' => false, 'msg' => 'Payroll for ' . self::month_label($month) . ' already exists.');
        $staff = $this->eligible_staff($month);
        if (!$staff) return array('ok' => false, 'msg' => 'No staff is on payroll for ' . self::month_label($month) . '. Set salaries in Salary Structure first.');
        $this->db->trans_begin();
        $ok = $this->db->insert('pay_run', array('prMonth' => $month, 'prDays' => (int)date('t', strtotime($month)), 'prStatus' => 'DRAFT', 'prUser' => $user));
        $id = (int)$this->db->insert_id();
        if (!$ok || !$id) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Payroll for ' . self::month_label($month) . ' already exists.'); }
        $run = $this->get_run($id);
        $comps = $this->components(true); $all = $this->all_staff_values();
        foreach ($staff as $pe) $this->_build_slip($run, $pe, null, $comps, isset($all[$pe->peId]) ? $all[$pe->peId] : array());
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Could not create the payroll.'); }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Payroll for ' . self::month_label($month) . ' created with ' . count($staff) . ' payslip(s).', 'id' => $id);
    }

    /* adds payslips for staff on payroll who are missing from a draft run */
    public function add_missing($prId)
    {
        $run = $this->get_run($prId);
        if (!$run || $run->prStatus !== 'DRAFT') return 0;
        $have = array();
        foreach ($this->db->select('psPeId')->where('psPrId', (int)$prId)->get('pay_slip')->result() as $r) $have[(int)$r->psPeId] = true;
        $n = 0;
        $this->db->trans_start();
        $comps = $this->components(true); $all = $this->all_staff_values();
        foreach ($this->eligible_staff($run->prMonth) as $pe) {
            if (isset($have[(int)$pe->peId])) continue;
            $this->_build_slip($run, $pe, null, $comps, isset($all[$pe->peId]) ? $all[$pe->peId] : array());
            $n++;
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $n : 0;
    }

    public function recalc_run($prId)
    {
        $run = $this->get_run($prId);
        if (!$run || $run->prStatus !== 'DRAFT') return false;
        $comps = $this->components(true); $all = $this->all_staff_values();
        $this->db->trans_start();
        foreach ($this->db->where('psPrId', (int)$prId)->order_by('psId')->get('pay_slip')->result() as $s) {
            $pe = $this->get_staff($s->psPeId);
            if ($pe) $this->_build_slip($run, $pe, $s, $comps, isset($all[$pe->peId]) ? $all[$pe->peId] : array());
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Sets loss-of-pay days of every payslip in a draft run from staff attendance:
     * days before joining / after relieving + absent + half days (0.5) + unpaid leave (+ unmarked working days when asked).
     * returns ['ok', 'msg']
     */
    public function load_attendance_lop($prId, $count_unmarked)
    {
        $run = $this->get_run($prId);
        if (!$run || $run->prStatus !== 'DRAFT') return array('ok' => false, 'msg' => 'Only a draft payroll can be changed.');
        if (!$this->db->table_exists('staff_attendance')) return array('ok' => false, 'msg' => 'Staff attendance is not set up.');
        $this->load->model('Staff_attendance_model', 'Satt');
        $from = $run->prMonth; $to = self::month_end($run->prMonth);
        $marked = $this->db->where('satDate >=', $from)->where('satDate <=', $to)->count_all_results('staff_attendance');
        if (!$marked) return array('ok' => false, 'msg' => 'No staff attendance is marked for ' . self::month_label($run->prMonth) . '.');

        $comps = $this->components(true); $all = $this->all_staff_values();
        $changed = 0; $unmarked = 0;
        $this->db->trans_start();
        foreach ($this->slips($prId) as $s) {
            $pe = $this->get_staff($s->psPeId);
            if (!$pe) continue;
            $sum = $this->Satt->summary($from, $to, array($pe));
            $c = $sum[(int)$pe->peId];
            $unmarked += $c['unmarked'];
            $lop = min((int)$run->prDays, $this->_auto_lop($pe, $run->prMonth, (int)$run->prDays) + $c['lop'] + ($count_unmarked ? $c['unmarked'] : 0));
            if (abs($lop - (float)$s->psLop) < 0.01) continue;
            $s->psLop = $lop;
            $this->db->where('psId', (int)$s->psId)->update('pay_slip', array('psLop' => $lop));
            $this->_build_slip($run, $pe, $s, $comps, isset($all[$pe->peId]) ? $all[$pe->peId] : array());
            $changed++;
        }
        $this->db->trans_complete();
        if (!$this->db->trans_status()) return array('ok' => false, 'msg' => 'Could not update the payslips.');
        $msg = 'Loss-of-pay days loaded from attendance: ' . $changed . ' payslip(s) changed.';
        if ($unmarked && !$count_unmarked) $msg .= ' Note: ' . $unmarked . ' working day(s) are not marked and were not counted.';
        return array('ok' => true, 'msg' => $msg);
    }

    public function recalc_all_drafts()
    {
        foreach ($this->db->select('prId')->where('prStatus', 'DRAFT')->order_by('prMonth')->get('pay_run')->result() as $r) $this->recalc_run($r->prId);
    }

    /* recalculates a staff member's payslips in draft runs (oldest first, so advance recovery flows month to month) */
    public function recalc_staff_drafts($peId)
    {
        $pe = $this->get_staff($peId);
        if (!$pe) return;
        $rows = $this->db->query("SELECT s.*, r.prId FROM pay_slip s JOIN pay_run r ON r.prId = s.psPrId
                                  WHERE s.psPeId = ? AND r.prStatus = 'DRAFT' ORDER BY r.prMonth", array((int)$peId))->result();
        foreach ($rows as $s) $this->_build_slip($this->get_run($s->prId), $pe, $s);
    }

    public function slips($prId)
    {
        return $this->db->where('psPrId', (int)$prId)->order_by('psCategory')->order_by('psName')->get('pay_slip')->result();
    }

    public function get_slip($psId)
    {
        $s = $this->db->select('s.*, r.prMonth, r.prStatus')->from('pay_slip s')->join('pay_run r', 'r.prId = s.psPrId')
                      ->where('s.psId', (int)$psId)->get()->row();
        if ($s) $s->lines = $this->db->where('pslPsId', (int)$psId)->order_by('pslOrder')->get('pay_slip_line')->result();
        return $s;
    }

    /* payslips with their lines, for printing */
    public function slips_full($prId, $psId = 0)
    {
        $this->db->select('s.*, r.prMonth, r.prStatus')->from('pay_slip s')->join('pay_run r', 'r.prId = s.psPrId')->where('s.psPrId', (int)$prId);
        if ($psId) $this->db->where('s.psId', (int)$psId);
        $slips = $this->db->order_by('s.psCategory')->order_by('s.psName')->get()->result();
        if (!$slips) return array();
        $ids = array(); foreach ($slips as $s) { $s->lines = array(); $ids[(int)$s->psId] = $s; }
        foreach ($this->db->where_in('pslPsId', array_keys($ids))->order_by('pslOrder')->get('pay_slip_line')->result() as $l) $ids[(int)$l->pslPsId]->lines[] = $l;
        return $slips;
    }

    public function save_slip($psId, $lop, $ee, $een, $ed, $edn, $remarks)
    {
        $s = $this->db->where('psId', (int)$psId)->get('pay_slip')->row();
        if (!$s) return array('ok' => false, 'msg' => 'Payslip not found.');
        $run = $this->get_run($s->psPrId);
        if ($run->prStatus !== 'DRAFT') return array('ok' => false, 'msg' => 'This payroll is approved. Reopen it to make changes.');
        $pe = $this->get_staff($s->psPeId);
        if (!$pe) return array('ok' => false, 'msg' => 'The staff member was removed.');
        // check first, write only when the result is valid
        $c = $this->calculate($pe->peBasic, $this->components(true), $this->staff_values($pe->peId), $run->prDays, $lop, $ee, $ed, $een, $edn,
                              $this->_advances_for($pe->peId, $run->prMonth, $psId));
        if ($c['net'] < 0) return array('ok' => false, 'msg' => 'Deductions are more than the earnings: net pay would be ' . number_format($c['net'], 2) . '.');
        $s->psLop = $lop; $s->psExtraEarn = $ee; $s->psExtraEarnNote = $een; $s->psExtraDed = $ed; $s->psExtraDedNote = $edn;

        $this->db->trans_start();
        $this->db->where('psId', (int)$psId)->update('pay_slip', array('psLop' => $lop, 'psExtraEarn' => $ee, 'psExtraEarnNote' => $een ?: null,
            'psExtraDed' => $ed, 'psExtraDedNote' => $edn ?: null, 'psRemarks' => $remarks ?: null));
        $this->_build_slip($run, $pe, $s);
        $this->db->trans_complete();
        if (!$this->db->trans_status()) return array('ok' => false, 'msg' => 'Could not save.');
        // later draft months may recover the same advance
        $this->recalc_staff_drafts($pe->peId);
        return array('ok' => true, 'msg' => 'Payslip of ' . $pe->peName . ' updated.');
    }

    public function remove_slip($psId)
    {
        $s = $this->db->where('psId', (int)$psId)->get('pay_slip')->row();
        if (!$s) return false;
        $run = $this->get_run($s->psPrId);
        if ($run->prStatus !== 'DRAFT') return false;
        $this->db->trans_start();
        $this->db->where('pslPsId', (int)$psId)->delete('pay_slip_line');
        $this->db->where('psId', (int)$psId)->delete('pay_slip');
        $this->db->trans_complete();
        if ($this->db->trans_status()) $this->recalc_staff_drafts($s->psPeId);
        return $this->db->trans_status();
    }

    public function delete_run($prId)
    {
        $run = $this->get_run($prId);
        if (!$run || $run->prStatus !== 'DRAFT') return false;
        if ($this->db->where('paPrId', (int)$prId)->count_all_results('pay_approval')) return false;   // was approved once: keep for the audit trail
        $peIds = array();
        foreach ($this->db->select('psPeId')->where('psPrId', (int)$prId)->get('pay_slip')->result() as $r) $peIds[] = (int)$r->psPeId;
        $this->db->trans_start();
        $this->db->query('DELETE l FROM pay_slip_line l JOIN pay_slip s ON s.psId = l.pslPsId WHERE s.psPrId = ?', array((int)$prId));
        $this->db->where('psPrId', (int)$prId)->delete('pay_slip');
        $this->db->where('prId', (int)$prId)->delete('pay_run');
        $this->db->trans_complete();
        if ($this->db->trans_status()) foreach ($peIds as $p) $this->recalc_staff_drafts($p);
        return $this->db->trans_status();
    }

    public function approve_run($prId, $user)
    {
        $this->db->trans_begin();
        $run = $this->db->query('SELECT * FROM pay_run WHERE prId = ? FOR UPDATE', array((int)$prId))->row();
        if (!$run || $run->prStatus !== 'DRAFT') { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'This payroll is not a draft.'); }
        if (!$this->db->where('psPrId', (int)$prId)->count_all_results('pay_slip')) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'There are no payslips to approve.'); }
        $neg = $this->db->where('psPrId', (int)$prId)->where('psNet <', 0)->count_all_results('pay_slip');
        if ($neg) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => $neg . ' payslip(s) have a negative net pay.'); }
        $this->db->where('prId', (int)$prId)->update('pay_run', array('prStatus' => 'APPROVED', 'prApprovedAt' => date('Y-m-d H:i:s'), 'prApprovedBy' => $user));
        $this->db->insert('pay_approval', array('paPrId' => (int)$prId, 'paDate' => self::month_end($run->prMonth), 'paUser' => $user));
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Could not approve.'); }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Payroll for ' . self::month_label($run->prMonth) . ' approved. You can now pay salaries.');
    }

    public function reopen_run($prId)
    {
        $this->db->trans_begin();
        $run = $this->db->query('SELECT * FROM pay_run WHERE prId = ? FOR UPDATE', array((int)$prId))->row();
        if (!$run || $run->prStatus !== 'APPROVED') { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'This payroll is not approved.'); }
        if ($this->db->where('pbPrId', (int)$prId)->where('pbCancelled', 0)->count_all_results('pay_batch')) {
            $this->db->trans_rollback();
            return array('ok' => false, 'msg' => 'Salaries of this month have been paid. Cancel those payments first.');
        }
        $this->db->where('prId', (int)$prId)->update('pay_run', array('prStatus' => 'DRAFT', 'prApprovedAt' => null, 'prApprovedBy' => null));
        $this->db->where('paPrId', (int)$prId)->where('paCancelled', 0)->update('pay_approval', array('paCancelled' => 1));
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Could not reopen.'); }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Payroll reopened. Its accounts entry is cancelled and will be posted again when you approve.');
    }

    /* ================================================================
       SALARY PAYMENTS
       ================================================================ */
    /** $amounts [psId => amount]; returns ['ok', 'msg'] */
    public function pay($prId, $amounts, $date, $mode, $ref, $remarks, $user)
    {
        $this->db->trans_begin();
        $run = $this->db->query('SELECT * FROM pay_run WHERE prId = ? FOR UPDATE', array((int)$prId))->row();
        if (!$run || $run->prStatus !== 'APPROVED') { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Approve the payroll before paying salaries.'); }
        $total = 0; $rows = array();
        foreach ($amounts as $psId => $amt) {
            $s = $this->db->query('SELECT psId, psName, psNet, psPaid FROM pay_slip WHERE psId = ? AND psPrId = ? FOR UPDATE', array((int)$psId, (int)$prId))->row();
            if (!$s) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'A selected payslip was not found.'); }
            $bal = self::r2($s->psNet - $s->psPaid);
            if ($amt <= 0 || $amt - $bal > 0.005) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => $s->psName . ': pay between 0.01 and the balance ' . number_format($bal, 2) . '.'); }
            $rows[] = array((int)$psId, self::r2($amt));
            $total += $amt;
        }
        if (!$rows) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Select the staff to pay.'); }
        $this->db->insert('pay_batch', array('pbPrId' => (int)$prId, 'pbDate' => $date, 'pbMode' => $mode, 'pbRef' => $ref ?: null,
            'pbAmount' => self::r2($total), 'pbRemarks' => $remarks ?: null, 'pbUser' => $user));
        $pbId = (int)$this->db->insert_id();
        foreach ($rows as $r) $this->db->insert('pay_batch_slip', array('pbsPbId' => $pbId, 'pbsPsId' => $r[0], 'pbsAmount' => $r[1]));
        $this->_refresh_paid($prId);
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return array('ok' => false, 'msg' => 'Could not save the payment.'); }
        $this->db->trans_commit();
        return array('ok' => true, 'msg' => 'Paid ' . number_format($total, 2) . ' to ' . count($rows) . ' staff.');
    }

    private function _refresh_paid($prId)
    {
        $this->db->query("UPDATE pay_slip s SET s.psPaid = (SELECT COALESCE(SUM(b.pbsAmount),0) FROM pay_batch_slip b
                          JOIN pay_batch p ON p.pbId = b.pbsPbId AND p.pbCancelled = 0 WHERE b.pbsPsId = s.psId) WHERE s.psPrId = ?", array((int)$prId));
    }

    public function batches($prId)
    {
        return $this->db->query("SELECT p.*, COUNT(b.pbsId) staff FROM pay_batch p LEFT JOIN pay_batch_slip b ON b.pbsPbId = p.pbId
                                 WHERE p.pbPrId = ? GROUP BY p.pbId ORDER BY p.pbDate DESC, p.pbId DESC", array((int)$prId))->result();
    }

    public function cancel_batch($pbId, $reason)
    {
        $b = $this->db->where('pbId', (int)$pbId)->get('pay_batch')->row();
        if (!$b || $b->pbCancelled) return false;
        $this->db->trans_start();
        $this->db->where('pbId', (int)$pbId)->update('pay_batch', array('pbCancelled' => 1, 'pbCancelReason' => $reason));
        $this->_refresh_paid($b->pbPrId);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /* ================================================================
       ADVANCES
       ================================================================ */
    /* recovered = recovery in approved payrolls; pending = in draft payrolls */
    public function list_advances($f = array())
    {
        $sql = "SELECT a.*, p.peName, p.peCode,
                  (SELECT COALESCE(SUM(l.pslAmount),0) FROM pay_slip_line l JOIN pay_slip s ON s.psId = l.pslPsId JOIN pay_run r ON r.prId = s.psPrId
                    WHERE l.pslPadId = a.padId AND r.prStatus = 'APPROVED') recovered,
                  (SELECT COALESCE(SUM(l.pslAmount),0) FROM pay_slip_line l JOIN pay_slip s ON s.psId = l.pslPsId JOIN pay_run r ON r.prId = s.psPrId
                    WHERE l.pslPadId = a.padId AND r.prStatus = 'DRAFT') pending
                FROM pay_advance a JOIN pay_employee p ON p.peId = a.padPeId WHERE 1=1";
        $par = array();
        if (!empty($f['staff'])) { $sql .= ' AND a.padPeId = ?'; $par[] = (int)$f['staff']; }
        if (empty($f['cancelled'])) $sql .= ' AND a.padCancelled = 0';
        $rows = $this->db->query($sql . ' ORDER BY a.padDate DESC, a.padId DESC', $par)->result();
        foreach ($rows as $r) $r->outstanding = self::r2($r->padAmount - $r->recovered);
        if (!empty($f['open'])) $rows = array_values(array_filter($rows, function ($r) { return !$r->padCancelled && $r->outstanding > 0; }));
        return $rows;
    }

    public function save_advance($data)
    {
        if (!$this->db->insert('pay_advance', $data)) return 0;
        $id = (int)$this->db->insert_id();
        $this->recalc_staff_drafts($data['padPeId']);
        return $id;
    }

    public function cancel_advance($id, $reason)
    {
        $a = $this->db->where('padId', (int)$id)->get('pay_advance')->row();
        if (!$a || $a->padCancelled) return array('ok' => false, 'msg' => 'Advance not found.');
        $done = $this->db->query("SELECT COUNT(*) n FROM pay_slip_line l JOIN pay_slip s ON s.psId = l.pslPsId JOIN pay_run r ON r.prId = s.psPrId
                                  WHERE l.pslPadId = ? AND r.prStatus = 'APPROVED'", array((int)$id))->row()->n;
        if ($done) return array('ok' => false, 'msg' => 'Part of this advance was already recovered in an approved payroll, so it cannot be cancelled.');
        $this->db->where('padId', (int)$id)->update('pay_advance', array('padCancelled' => 1, 'padCancelReason' => $reason));
        $this->recalc_staff_drafts($a->padPeId);
        return array('ok' => true, 'msg' => 'Advance cancelled.');
    }

    /* ================================================================
       REPORT HELPERS
       ================================================================ */
    public function run_for_month($month)
    {
        return $this->db->where('prMonth', $month)->get('pay_run')->row();
    }

    /* lines of a run: [psId => [pslName/pcId key => amount]] plus the ordered list of columns */
    public function run_lines($prId)
    {
        return $this->db->query("SELECT l.* FROM pay_slip_line l JOIN pay_slip s ON s.psId = l.pslPsId WHERE s.psPrId = ? ORDER BY l.pslOrder",
                                array((int)$prId))->result();
    }
}
