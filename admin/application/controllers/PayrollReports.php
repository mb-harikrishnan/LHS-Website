<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Payroll reports (menu: Reports > Payroll Reports). Rendered by libraries/Report_view. */
class PayrollReports extends CI_Controller
{
    private $f;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Payroll_model', 'Pay');
        $this->Pay->require_schema();
        $this->load->model('Subject_Model');
        $this->load->library('Report_view');
        $this->load->helper('fee');

        $g = function ($k) { return trim((string)$this->input->get($k)); };
        $d = function ($k, $def) use ($g) {
            $v = $g($k); $x = DateTime::createFromFormat('Y-m-d', $v);
            return ($x && $x->format('Y-m-d') === $v) ? $v : $def;
        };
        $m = function ($k, $def) use ($g) { $v = $g($k); return preg_match('/^(\d{4})-(0[1-9]|1[0-2])$/', $v) ? $v : $def; };
        $latest = $this->db->select_max('prMonth')->get('pay_run')->row();
        $fyStart = (int)date('n') >= 4 ? date('Y') . '-04' : (date('Y') - 1) . '-04';
        $this->f = array(
            'month'      => $m('month', $latest && $latest->prMonth ? date('Y-m', strtotime($latest->prMonth)) : date('Y-m')),
            'from_month' => $m('from_month', $fyStart),
            'to_month'   => $m('to_month', date('Y-m')),
            'from'       => $d('from', date('Y-m-01')),
            'to'         => $d('to', date('Y-m-d')),
            'mode'       => in_array($g('mode'), Payroll_model::$pay_modes, true) ? $g('mode') : '',
            'staff'      => (int)$g('staff'),
            'category'   => isset(Payroll_model::$categories[$g('category')]) ? $g('category') : '',
            'component'  => (int)$g('component'),
            'paid_by'    => in_array($g('paid_by'), Payroll_model::$modes, true) ? $g('paid_by') : '',
            'show'       => $g('show') === 'all' ? 'all' : '',
            // expected by the shared view
            'q' => '', 'class' => '', 'division' => '', 'student' => 0, 'gender' => '', 'fee' => 0, 'fee_type' => '',
            'as_of' => date('Y-m-d'), 'only_balance' => '1', 'group' => '',
        );
        if ($this->f['from_month'] > $this->f['to_month']) $this->f['to_month'] = $this->f['from_month'];
    }

    private function _allowed()
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link('reports/payroll'));
        if (!empty($perm['can_view'])) return true;
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
        return false;
    }

    private function _dmy($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; }
    private function _mlabel($ym) { return date('F Y', strtotime($ym . '-01')); }
    private function _range_label() { return $this->_mlabel($this->f['from_month']) . ' to ' . $this->_mlabel($this->f['to_month']); }

    private function _render($spec)
    {
        $spec['menu'] = 'PAY_REPORTS';
        $spec['hub']  = 'reports/payroll';
        $this->report_view->output($spec, array('f' => $this->f, 'modes' => Payroll_model::$pay_modes));
    }

    private function _sel_staff()
    {
        $o = array();
        foreach ($this->Pay->list_staff() as $s) $o[$s->peId] = $s->peName . ($s->peCode ? ' (' . $s->peCode . ')' : '');
        return array('name' => 'staff', 'label' => 'Staff', 'options' => $o, 'value' => $this->f['staff'], 'all' => 'All staff');
    }

    private function _sel_category()
    {
        return array('name' => 'category', 'label' => 'Category', 'options' => Payroll_model::$categories, 'value' => $this->f['category']);
    }

    private function _month_run()
    {
        return $this->Pay->run_for_month($this->f['month'] . '-01');
    }

    private function _no_run_msg()
    {
        return 'There is no payroll for ' . $this->_mlabel($this->f['month']) . '. Prepare it in Payroll > Monthly Payroll.';
    }

    private function _status_note($run)
    {
        return $run && $run->prStatus === 'DRAFT' ? 'DRAFT — figures can still change' : '';
    }

    /* slips of a run, filtered by category */
    private function _slips($run)
    {
        $out = array();
        foreach ($this->Pay->slips($run->prId) as $s) {
            if ($this->f['category'] && $s->psCategory !== $this->f['category']) continue;
            $out[(int)$s->psId] = $s;
        }
        return $out;
    }

    /* ---------------- hub ---------------- */
    public function index()
    {
        if (!$this->_allowed()) return;
        $this->load->view('header', array('pageTitle' => 'Payroll Reports', 'activePage' => 'PAY_REPORTS',
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view('report_hub', array('title' => 'Payroll Reports', 'sub' => 'Payroll reports are by month.', 'groups' => array(
            'Monthly' => array(
                array('reports/pay_salary_register', 'Salary Register', 'Every staff member\'s earnings, deductions and net pay for a month'),
                array('reports/pay_bank_statement', 'Bank Transfer Statement', 'Net pay with bank account and IFSC, to send to the bank'),
                array('reports/pay_pf_statement', 'PF Statement', 'PF wage, employee and employer PF with UAN'),
                array('reports/pay_esi_statement', 'ESI Statement', 'ESI wage, employee and employer ESI'),
                array('reports/pay_deduction_register', 'Deduction Register', 'Each deduction (PT, TDS, PF ...) staff-wise'),
            ),
            'Payments & dues' => array(
                array('reports/pay_payment_register', 'Salary Payment Register', 'Salary payments between two dates'),
                array('reports/pay_salary_due', 'Salary Due', 'Approved salary not yet paid'),
                array('reports/pay_advance_register', 'Advance Register', 'Advances given, recovered and outstanding'),
            ),
            'Period' => array(
                array('reports/pay_monthly_summary', 'Monthly Summary', 'Month-wise gross, deductions, net and paid'),
                array('reports/pay_annual_statement', 'Annual Salary Statement', 'Staff-wise totals for a period: gross, PF, PT, TDS, net (for Form 16)'),
                array('reports/pay_staff_history', 'Staff Salary History', 'Month-by-month salary of one staff member'),
            ),
        )));
        $this->load->view('footer');
    }

    /* ---------------- monthly ---------------- */
    public function salary_register()
    {
        if (!$this->_allowed()) return;
        $run = $this->_month_run();
        $spec = array('title' => 'Salary Register', 'route' => 'reports/pay_salary_register', 'filters' => array('month'),
            'selects' => array($this->_sel_category()), 'period' => $this->_mlabel($this->f['month']), 'group' => true, 'count_label' => 'staff',
            'columns' => array(), 'rows' => array());
        if (!$run) { $spec['need'] = $this->_no_run_msg(); return $this->_render($spec); }
        $spec['note'] = $this->_status_note($run);

        $short = array();
        foreach ($this->Pay->components() as $c) $short[(int)$c->pcId] = $c->pcShort;
        $slips = $this->_slips($run);
        $cols = array('EARNING' => array(), 'DEDUCTION' => array(), 'EMPLOYER' => array());
        $vals = array();
        foreach ($this->Pay->run_lines($run->prId) as $l) {
            if (!isset($slips[(int)$l->pslPsId])) continue;
            if ($l->pslKind === 'BASIC')     { $k = 'basic'; $lab = 'Basic'; }
            elseif ($l->pslKind === 'ADV')   { $k = 'adv'; $lab = 'Advance'; }
            elseif ($l->pslKind === 'EXTRA') { $k = $l->pslType === 'EARNING' ? 'xe' : 'xd'; $lab = $l->pslType === 'EARNING' ? 'Other Earn.' : 'Other Ded.'; }
            else { $k = 'c' . (int)$l->pslPcId; $lab = isset($short[(int)$l->pslPcId]) ? $short[(int)$l->pslPcId] : $l->pslName; }
            if (!isset($cols[$l->pslType][$k])) $cols[$l->pslType][$k] = $lab;
            $vals[(int)$l->pslPsId][$k] = (isset($vals[(int)$l->pslPsId][$k]) ? $vals[(int)$l->pslPsId][$k] : 0) + (float)$l->pslAmount;
        }
        $money = function ($k, $l) { return array('key' => $k, 'label' => $l, 'type' => 'money', 'total' => true); };
        $c = array(array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'), array('key' => 'desig', 'label' => 'Designation'),
                   array('key' => 'days', 'label' => 'Paid Days'), array('key' => 'lop', 'label' => 'LOP'));
        foreach ($cols['EARNING'] as $k => $l) $c[] = $money($k, $l);
        $c[] = $money('gross', 'Gross');
        foreach ($cols['DEDUCTION'] as $k => $l) $c[] = $money($k, $l);
        $c[] = $money('ded', 'Total Ded.');
        $c[] = $money('net', 'Net Pay');
        foreach ($cols['EMPLOYER'] as $k => $l) $c[] = $money($k, $l);
        $c[] = array('key' => 'mode', 'label' => 'Paid by');
        $spec['columns'] = $c;

        foreach ($slips as $s) {
            $row = array('_group' => Payroll_model::$categories[$s->psCategory] . ' staff', 'code' => $s->psCode, 'name' => $s->psName,
                'desig' => $s->psDesignation, 'days' => (float)$s->psPaidDays, 'lop' => (float)$s->psLop ?: '',
                'gross' => (float)$s->psGross, 'ded' => (float)$s->psDeduction, 'net' => (float)$s->psNet, 'mode' => $s->psPayMode);
            foreach ($cols as $type => $list) foreach ($list as $k => $l) $row[$k] = isset($vals[(int)$s->psId][$k]) ? $vals[(int)$s->psId][$k] : 0;
            $spec['rows'][] = $row;
        }
        $this->_render($spec);
    }

    public function bank_statement()
    {
        if (!$this->_allowed()) return;
        $paidBy = $this->f['paid_by'] ?: 'Bank';
        $run = $this->_month_run();
        $spec = array('title' => 'Bank Transfer Statement', 'route' => 'reports/pay_bank_statement', 'filters' => array('month'),
            'selects' => array(array('name' => 'paid_by', 'label' => 'Salary paid by', 'options' => array('Cash' => 'Cash', 'Cheque' => 'Cheque'),
                                     'value' => $paidBy === 'Bank' ? '' : $paidBy, 'all' => 'Bank transfer')),
            'period' => $this->_mlabel($this->f['month']), 'count_label' => 'staff', 'rows' => array(),
            'columns' => array(
                array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'),
                array('key' => 'bank', 'label' => 'Bank'), array('key' => 'acct', 'label' => 'Account No'), array('key' => 'ifsc', 'label' => 'IFSC'),
                array('key' => 'net', 'label' => 'Net Pay', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'bal', 'label' => 'To Pay', 'type' => 'money', 'total' => true),
            ));
        if (!$run) { $spec['need'] = $this->_no_run_msg(); return $this->_render($spec); }
        $spec['note'] = trim($this->_status_note($run) . ($paidBy === 'Bank' ? ' Staff paid by bank transfer.' : ' Staff paid by ' . strtolower($paidBy) . '.'));
        foreach ($this->_slips($run) as $s) {
            if ($s->psPayMode !== $paidBy) continue;
            $spec['rows'][] = array('code' => $s->psCode, 'name' => $s->psName, 'bank' => $s->psBankName, 'acct' => $s->psAccountNo, 'ifsc' => $s->psIfsc,
                                    'net' => (float)$s->psNet, 'paid' => (float)$s->psPaid, 'bal' => round($s->psNet - $s->psPaid, 2));
        }
        $this->_render($spec);
    }

    /* PF / ESI statement from the lines marked with the statutory keys */
    private function _statutory($title, $route, $eeKey, $erKey, $noLabel, $noField, $wageLabel)
    {
        if (!$this->_allowed()) return;
        $run = $this->_month_run();
        $spec = array('title' => $title, 'route' => $route, 'filters' => array('month'), 'selects' => array($this->_sel_category()),
            'period' => $this->_mlabel($this->f['month']), 'count_label' => 'staff', 'rows' => array(),
            'columns' => array(
                array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'), array('key' => 'no', 'label' => $noLabel),
                array('key' => 'days', 'label' => 'Paid Days'),
                array('key' => 'wage', 'label' => $wageLabel, 'type' => 'money', 'total' => true),
                array('key' => 'ee', 'label' => 'Employee', 'type' => 'money', 'total' => true),
                array('key' => 'er', 'label' => 'Employer', 'type' => 'money', 'total' => true),
                array('key' => 'tot', 'label' => 'Total', 'type' => 'money', 'total' => true),
            ));
        if (!$run) { $spec['need'] = $this->_no_run_msg(); return $this->_render($spec); }
        $spec['note'] = $this->_status_note($run);
        $slips = $this->_slips($run); $v = array();
        foreach ($this->Pay->run_lines($run->prId) as $l) {
            if (!isset($slips[(int)$l->pslPsId]) || !in_array($l->pslKey, array($eeKey, $erKey), true)) continue;
            $p = (int)$l->pslPsId;
            if (!isset($v[$p])) $v[$p] = array('wage' => 0, 'ee' => 0, 'er' => 0);
            if ($l->pslKey === $eeKey) { $v[$p]['ee'] += (float)$l->pslAmount; $v[$p]['wage'] = max($v[$p]['wage'], (float)$l->pslBase); }
            else                       { $v[$p]['er'] += (float)$l->pslAmount; $v[$p]['wage'] = max($v[$p]['wage'], (float)$l->pslBase); }
        }
        foreach ($slips as $s) {
            if (!isset($v[(int)$s->psId])) continue;
            $x = $v[(int)$s->psId];
            $spec['rows'][] = array('code' => $s->psCode, 'name' => $s->psName, 'no' => $s->$noField, 'days' => (float)$s->psPaidDays,
                                    'wage' => $x['wage'], 'ee' => $x['ee'], 'er' => $x['er'], 'tot' => $x['ee'] + $x['er']);
        }
        $this->_render($spec);
    }

    public function pf_statement()
    {
        $this->_statutory('PF Statement', 'reports/pay_pf_statement', 'PF', 'PF_ER', 'UAN', 'psUan', 'PF Wage');
    }

    public function esi_statement()
    {
        $this->_statutory('ESI Statement', 'reports/pay_esi_statement', 'ESI', 'ESI_ER', 'ESI No', 'psEsiNo', 'ESI Wage');
    }

    public function deduction_register()
    {
        if (!$this->_allowed()) return;
        $o = array();
        foreach ($this->Pay->components() as $c) if ($c->pcType !== 'EARNING') $o[$c->pcId] = $c->pcName;
        $o['-1'] = 'Advance recovery'; $o['-2'] = 'Other deductions';
        $run = $this->_month_run();
        $spec = array('title' => 'Deduction Register', 'route' => 'reports/pay_deduction_register', 'filters' => array('month'),
            'selects' => array(array('name' => 'component', 'label' => 'Deduction', 'options' => $o, 'value' => $this->f['component'] ?: '', 'all' => 'All deductions'),
                               $this->_sel_category()),
            'period' => $this->_mlabel($this->f['month']), 'group' => true, 'count_label' => 'entries', 'rows' => array(),
            'columns' => array(
                array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'), array('key' => 'desig', 'label' => 'Designation'),
                array('key' => 'base', 'label' => 'On wage', 'type' => 'money'),
                array('key' => 'amt', 'label' => 'Amount', 'type' => 'money', 'total' => true),
            ));
        if (!$run) { $spec['need'] = $this->_no_run_msg(); return $this->_render($spec); }
        $spec['note'] = $this->_status_note($run);
        $slips = $this->_slips($run);
        $fc = $this->f['component'];
        $rows = array();
        foreach ($this->Pay->run_lines($run->prId) as $l) {
            if ($l->pslType === 'EARNING' || !isset($slips[(int)$l->pslPsId])) continue;
            $id = $l->pslKind === 'ADV' ? -1 : ($l->pslKind === 'EXTRA' ? -2 : (int)$l->pslPcId);
            if ($fc && $fc !== $id) continue;
            if (!$fc && $l->pslType === 'EMPLOYER') continue;   // school's share only when that component is chosen
            $g = $id === -1 ? 'Advance recovery' : ($id === -2 ? 'Other deductions' : $l->pslName . ($l->pslType === 'EMPLOYER' ? ' (paid by school)' : ''));
            $s = $slips[(int)$l->pslPsId];
            $rows[] = array('_group' => $g, 'code' => $s->psCode, 'name' => $s->psName, 'desig' => $s->psDesignation,
                            'base' => $l->pslBase === null ? '' : (float)$l->pslBase, 'amt' => (float)$l->pslAmount);
        }
        usort($rows, function ($a, $b) { return strcmp($a['_group'], $b['_group']) ?: strcmp($a['name'], $b['name']); });
        $spec['rows'] = $rows;
        $this->_render($spec);
    }

    /* ---------------- payments & dues ---------------- */
    public function payment_register()
    {
        if (!$this->_allowed()) return;
        $sql = "SELECT b.pbDate, b.pbMode, b.pbRef, r.prMonth, s.psName, s.psCode, x.pbsAmount FROM pay_batch b
                JOIN pay_batch_slip x ON x.pbsPbId = b.pbId JOIN pay_slip s ON s.psId = x.pbsPsId JOIN pay_run r ON r.prId = b.pbPrId
                WHERE b.pbCancelled = 0 AND b.pbDate BETWEEN ? AND ?";
        $par = array($this->f['from'], $this->f['to']);
        if ($this->f['mode'])  { $sql .= ' AND b.pbMode = ?'; $par[] = $this->f['mode']; }
        if ($this->f['staff']) { $sql .= ' AND s.psPeId = ?'; $par[] = $this->f['staff']; }
        $rows = array();
        foreach ($this->db->query($sql . ' ORDER BY b.pbDate, b.pbId, s.psName', $par)->result() as $r) {
            $rows[] = array('_group' => $this->_dmy($r->pbDate), 'month' => date('M Y', strtotime($r->prMonth)), 'code' => $r->psCode, 'name' => $r->psName,
                            'mode' => $r->pbMode, 'ref' => $r->pbRef, 'amt' => (float)$r->pbsAmount);
        }
        $this->_render(array('title' => 'Salary Payment Register', 'route' => 'reports/pay_payment_register', 'filters' => array('from', 'mode'),
            'selects' => array($this->_sel_staff()), 'period' => $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']),
            'group' => true, 'count_label' => 'payments', 'rows' => $rows,
            'columns' => array(
                array('key' => 'month', 'label' => 'Salary of'), array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'),
                array('key' => 'mode', 'label' => 'Mode'), array('key' => 'ref', 'label' => 'Reference'),
                array('key' => 'amt', 'label' => 'Amount', 'type' => 'money', 'total' => true),
            )));
    }

    public function salary_due()
    {
        if (!$this->_allowed()) return;
        $sql = "SELECT r.prMonth, s.* FROM pay_slip s JOIN pay_run r ON r.prId = s.psPrId
                WHERE r.prStatus = 'APPROVED' AND s.psNet - s.psPaid > 0.004";
        $par = array();
        if ($this->f['staff'])    { $sql .= ' AND s.psPeId = ?'; $par[] = $this->f['staff']; }
        if ($this->f['category']) { $sql .= ' AND s.psCategory = ?'; $par[] = $this->f['category']; }
        $rows = array();
        foreach ($this->db->query($sql . ' ORDER BY r.prMonth, s.psName', $par)->result() as $s) {
            $rows[] = array('_group' => Payroll_model::month_label($s->prMonth), 'code' => $s->psCode, 'name' => $s->psName, 'mode' => $s->psPayMode,
                            'net' => (float)$s->psNet, 'paid' => (float)$s->psPaid, 'bal' => round($s->psNet - $s->psPaid, 2));
        }
        $this->_render(array('title' => 'Salary Due', 'route' => 'reports/pay_salary_due', 'selects' => array($this->_sel_staff(), $this->_sel_category()),
            'period' => 'As on ' . date('d-m-Y'), 'note' => 'Approved payroll not fully paid', 'group' => true, 'count_label' => 'staff', 'rows' => $rows,
            'columns' => array(
                array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'), array('key' => 'mode', 'label' => 'Paid by'),
                array('key' => 'net', 'label' => 'Net Pay', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'bal', 'label' => 'Due', 'type' => 'money', 'total' => true),
            )));
    }

    public function advance_register()
    {
        if (!$this->_allowed()) return;
        $all = $this->f['show'] === 'all';
        $rows = array();
        foreach ($this->Pay->list_advances(array('staff' => $this->f['staff'], 'open' => !$all, 'cancelled' => 0)) as $a) {
            $rows[] = array('date' => $this->_dmy($a->padDate), 'code' => $a->peCode, 'name' => $a->peName, 'mode' => $a->padMode,
                'amt' => (float)$a->padAmount, 'inst' => (float)$a->padInstallment, 'from' => date('M Y', strtotime($a->padFromMonth)),
                'rec' => (float)$a->recovered, 'out' => (float)$a->outstanding, 'status' => $a->outstanding <= 0 ? 'Recovered' : 'Open');
        }
        $this->_render(array('title' => 'Advance Register', 'route' => 'reports/pay_advance_register',
            'selects' => array($this->_sel_staff(), array('name' => 'show', 'label' => 'Show', 'options' => array('all' => 'All advances'), 'value' => $this->f['show'], 'all' => 'Outstanding only')),
            'period' => 'As on ' . date('d-m-Y'), 'note' => 'Recovered = deducted in approved payrolls', 'count_label' => 'advances', 'rows' => $rows,
            'columns' => array(
                array('key' => 'date', 'label' => 'Date'), array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'),
                array('key' => 'mode', 'label' => 'Paid by'),
                array('key' => 'amt', 'label' => 'Advance', 'type' => 'money', 'total' => true),
                array('key' => 'inst', 'label' => 'Per Month', 'type' => 'money'), array('key' => 'from', 'label' => 'From'),
                array('key' => 'rec', 'label' => 'Recovered', 'type' => 'money', 'total' => true),
                array('key' => 'out', 'label' => 'Outstanding', 'type' => 'money', 'total' => true),
                array('key' => 'status', 'label' => 'Status'),
            )));
    }

    /* ---------------- period ---------------- */
    public function monthly_summary()
    {
        if (!$this->_allowed()) return;
        $sql = "SELECT r.prMonth, r.prStatus, COUNT(s.psId) staff, SUM(s.psGross) gross, SUM(s.psDeduction) ded, SUM(s.psEmployer) er,
                       SUM(s.psNet) net, SUM(s.psPaid) paid
                FROM pay_run r JOIN pay_slip s ON s.psPrId = r.prId WHERE r.prMonth BETWEEN ? AND ?";
        $par = array($this->f['from_month'] . '-01', $this->f['to_month'] . '-01');
        if ($this->f['category']) { $sql .= ' AND s.psCategory = ?'; $par[] = $this->f['category']; }
        $rows = array();
        foreach ($this->db->query($sql . ' GROUP BY r.prId, r.prMonth, r.prStatus ORDER BY r.prMonth', $par)->result() as $r) {
            $rows[] = array('month' => Payroll_model::month_label($r->prMonth), 'status' => $r->prStatus === 'DRAFT' ? 'Draft' : 'Approved',
                'staff' => (int)$r->staff, 'gross' => (float)$r->gross, 'ded' => (float)$r->ded, 'er' => (float)$r->er,
                'cost' => (float)$r->gross + (float)$r->er, 'net' => (float)$r->net, 'paid' => (float)$r->paid, 'bal' => round($r->net - $r->paid, 2));
        }
        $this->_render(array('title' => 'Monthly Payroll Summary', 'route' => 'reports/pay_monthly_summary', 'filters' => array('months'),
            'selects' => array($this->_sel_category()), 'period' => $this->_range_label(), 'count_label' => 'months', 'rows' => $rows,
            'columns' => array(
                array('key' => 'month', 'label' => 'Month'), array('key' => 'status', 'label' => 'Status'), array('key' => 'staff', 'label' => 'Staff', 'type' => 'int'),
                array('key' => 'gross', 'label' => 'Gross', 'type' => 'money', 'total' => true),
                array('key' => 'ded', 'label' => 'Deductions', 'type' => 'money', 'total' => true),
                array('key' => 'net', 'label' => 'Net Pay', 'type' => 'money', 'total' => true),
                array('key' => 'er', 'label' => 'Employer PF/ESI', 'type' => 'money', 'total' => true),
                array('key' => 'cost', 'label' => 'Cost to School', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'bal', 'label' => 'Balance', 'type' => 'money', 'total' => true),
            )));
    }

    public function annual_statement()
    {
        if (!$this->_allowed()) return;
        $par = array($this->f['from_month'] . '-01', $this->f['to_month'] . '-01');
        $where = "r.prStatus = 'APPROVED' AND r.prMonth BETWEEN ? AND ?";
        if ($this->f['staff'])    { $where .= ' AND s.psPeId = ' . (int)$this->f['staff']; }
        if ($this->f['category']) { $where .= ' AND s.psCategory = ' . $this->db->escape($this->f['category']); }
        $staff = $this->db->query("SELECT s.psPeId, MAX(s.psName) name, MAX(s.psCode) code, MAX(s.psPan) pan, COUNT(*) months,
                    SUM(s.psGross) gross, SUM(s.psDeduction) ded, SUM(s.psNet) net, SUM(s.psPaid) paid
                FROM pay_slip s JOIN pay_run r ON r.prId = s.psPrId WHERE $where GROUP BY s.psPeId ORDER BY name", $par)->result();
        $keys = array();
        foreach ($this->db->query("SELECT s.psPeId, l.pslKey, SUM(l.pslAmount) amt FROM pay_slip_line l JOIN pay_slip s ON s.psId = l.pslPsId
                    JOIN pay_run r ON r.prId = s.psPrId WHERE $where AND l.pslKey IN ('PF','ESI','PT','TDS') GROUP BY s.psPeId, l.pslKey", $par)->result() as $k) {
            $keys[(int)$k->psPeId][$k->pslKey] = (float)$k->amt;
        }
        $rows = array();
        foreach ($staff as $s) {
            $k = isset($keys[(int)$s->psPeId]) ? $keys[(int)$s->psPeId] : array();
            $g = function ($x) use ($k) { return isset($k[$x]) ? $k[$x] : 0; };
            $rows[] = array('code' => $s->code, 'name' => $s->name, 'pan' => $s->pan, 'months' => (int)$s->months, 'gross' => (float)$s->gross,
                'pf' => $g('PF'), 'esi' => $g('ESI'), 'pt' => $g('PT'), 'tds' => $g('TDS'),
                'other' => round($s->ded - $g('PF') - $g('ESI') - $g('PT') - $g('TDS'), 2), 'net' => (float)$s->net, 'paid' => (float)$s->paid);
        }
        $this->_render(array('title' => 'Annual Salary Statement', 'route' => 'reports/pay_annual_statement', 'filters' => array('months'),
            'selects' => array($this->_sel_staff(), $this->_sel_category()), 'period' => $this->_range_label(),
            'note' => 'Approved payrolls only', 'count_label' => 'staff', 'rows' => $rows,
            'columns' => array(
                array('key' => 'code', 'label' => 'Code'), array('key' => 'name', 'label' => 'Name'), array('key' => 'pan', 'label' => 'PAN'),
                array('key' => 'months', 'label' => 'Months', 'type' => 'int'),
                array('key' => 'gross', 'label' => 'Gross', 'type' => 'money', 'total' => true),
                array('key' => 'pf', 'label' => 'PF', 'type' => 'money', 'total' => true),
                array('key' => 'esi', 'label' => 'ESI', 'type' => 'money', 'total' => true),
                array('key' => 'pt', 'label' => 'Prof. Tax', 'type' => 'money', 'total' => true),
                array('key' => 'tds', 'label' => 'TDS', 'type' => 'money', 'total' => true),
                array('key' => 'other', 'label' => 'Other Ded.', 'type' => 'money', 'total' => true),
                array('key' => 'net', 'label' => 'Net Pay', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
            )));
    }

    public function staff_history()
    {
        if (!$this->_allowed()) return;
        $spec = array('title' => 'Staff Salary History', 'route' => 'reports/pay_staff_history', 'filters' => array('months'),
            'selects' => array($this->_sel_staff()), 'period' => $this->_range_label(), 'count_label' => 'months', 'rows' => array(),
            'columns' => array(
                array('key' => 'month', 'label' => 'Month'), array('key' => 'status', 'label' => 'Status'),
                array('key' => 'days', 'label' => 'Paid Days'), array('key' => 'lop', 'label' => 'LOP'),
                array('key' => 'basic', 'label' => 'Basic Rate', 'type' => 'money'),
                array('key' => 'gross', 'label' => 'Gross', 'type' => 'money', 'total' => true),
                array('key' => 'ded', 'label' => 'Deductions', 'type' => 'money', 'total' => true),
                array('key' => 'net', 'label' => 'Net Pay', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'bal', 'label' => 'Balance', 'type' => 'money', 'total' => true),
            ));
        $spec['selects'][0]['all'] = 'Select staff';
        if (!$this->f['staff']) { $spec['need'] = 'Select a staff member and click Show.'; return $this->_render($spec); }
        $pe = $this->Pay->get_staff($this->f['staff']);
        if ($pe) $spec['note'] = $pe->peName . ($pe->peDesignation ? ', ' . $pe->peDesignation : '');
        $rows = $this->db->query("SELECT r.prMonth, r.prStatus, s.* FROM pay_slip s JOIN pay_run r ON r.prId = s.psPrId
                                  WHERE s.psPeId = ? AND r.prMonth BETWEEN ? AND ? ORDER BY r.prMonth",
                                  array($this->f['staff'], $this->f['from_month'] . '-01', $this->f['to_month'] . '-01'))->result();
        foreach ($rows as $s) {
            $spec['rows'][] = array('month' => Payroll_model::month_label($s->prMonth), 'status' => $s->prStatus === 'DRAFT' ? 'Draft' : 'Approved',
                'days' => (float)$s->psPaidDays . ' / ' . (int)$s->psDays, 'lop' => (float)$s->psLop ?: '', 'basic' => (float)$s->psBasicRate,
                'gross' => (float)$s->psGross, 'ded' => (float)$s->psDeduction, 'net' => (float)$s->psNet, 'paid' => (float)$s->psPaid,
                'bal' => round($s->psNet - $s->psPaid, 2));
        }
        $this->_render($spec);
    }
}
