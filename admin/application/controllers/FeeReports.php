<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Student + Fee reports (Reports menu)
 *
 * Every report builds a "spec" (title, filters, columns, rows, grouping) that is
 * rendered by views/report_table.php, or downloaded as CSV with ?export=csv.
 */
class FeeReports extends CI_Controller
{
    private $amId;
    private $f;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Fee_model');
        $this->Fee_model->require_schema();
        $this->load->model('Fee_report_model', 'Rpt');
        $this->load->model('Subject_Model');
        $this->load->helper('fee');
        $this->amId = $this->Fee_model->get_am_id();
        $this->f    = $this->_filters();
    }

    /* ================================================================
       helpers
       ================================================================ */
    private function _allowed($link)
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link($link));
        if (!empty($perm['can_view'])) return true;
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
        return false;
    }

    private function _date($k, $default = '')
    {
        $v = trim((string)$this->input->get($k));
        $d = DateTime::createFromFormat('Y-m-d', $v);
        return ($d && $d->format('Y-m-d') === $v) ? $v : $default;
    }

    private function _filters()
    {
        $g = function ($k) { return trim((string)$this->input->get($k)); };
        $mode = $g('mode');
        return array(
            'class'        => ctype_digit($g('class')) ? $g('class') : '',
            'division'     => ctype_digit($g('division')) ? $g('division') : '',
            'gender'       => in_array($g('gender'), array('0', '1'), true) ? $g('gender') : '',
            'q'            => mb_substr($g('q'), 0, 100),
            'student'      => (int)$g('student'),
            'fee'          => (int)$g('fee'),
            'special'      => in_array($g('fee_type'), array('0', '1'), true) ? $g('fee_type') : '',
            'fee_type'     => in_array($g('fee_type'), array('0', '1'), true) ? $g('fee_type') : '',
            'mode'         => in_array($mode, Fee_model::$pay_modes, true) ? $mode : '',
            'from'         => $this->_date('from', date('Y-m-01')),
            'to'           => $this->_date('to', date('Y-m-d')),
            'as_of'        => $this->_date('as_of', date('Y-m-d')),
            'group'        => in_array($g('group'), array('date', 'class', 'mode'), true) ? $g('group') : '',
            'only_balance' => $this->input->get('only_balance') === null ? '1' : ($g('only_balance') === '1' ? '1' : '0'),
        );
    }

    private function _cls($r)
    {
        return trim((string)$r->cmName . ((string)$r->dmName !== '' ? ' - ' . $r->dmName : ''));
    }

    private function _dmy($d)
    {
        return ($d && $d !== '0000-00-00' && $d !== '0000-00-00 00:00:00') ? date('d-m-Y', strtotime($d)) : '';
    }

    /* fee columns (fee_<id>) for the fees that occur in $ids, in display order */
    private function _fee_columns($ids, $prefix = '')
    {
        $cols = array();
        foreach ($this->Fee_model->get_fees($this->amId) as $f) {
            if (isset($ids[(int)$f->fmId])) {
                $cols[] = array('key' => 'fee_' . $f->fmId, 'label' => $prefix . $f->fmName, 'type' => 'money', 'total' => true);
            }
        }
        return $cols;
    }

    private function _render($spec)
    {
        $year = $this->Fee_model->get_year($this->amId);
        $spec += array('filters' => array(), 'group' => false, 'note' => '', 'need' => '', 'count_label' => 'rows');
        $spec['year'] = $year ? $year->amYear : '';

        if ($this->input->get('export') === 'csv') return $this->_csv($spec);

        $data = array(
            'r'       => $spec,
            'f'       => $this->f,
            'classes' => $this->Subject_Model->get_all_classes(),
            'div_map' => $this->Subject_Model->get_class_division_map(),
            'fees'    => $this->Fee_model->get_fees($this->amId),
            'modes'   => Fee_model::$pay_modes,
        );
        $this->load->view('header', array('pageTitle' => $spec['title'], 'activePage' => $spec['menu'],
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view('report_table', $data);
        $this->load->view('footer');
    }

    private function _csv($spec)
    {
        $name = preg_replace('/[^a-z0-9]+/i', '_', strtolower($spec['title'])) . '_' . date('Ymd') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $name . '"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");   // UTF-8 BOM for Excel

        $head = array();
        if ($spec['group']) $head[] = 'Group';
        foreach ($spec['columns'] as $c) $head[] = $c['label'];
        fputcsv($out, $head);

        $tot = array();
        foreach ($spec['rows'] as $row) {
            $line = array();
            if ($spec['group']) $line[] = $row['_group'];
            foreach ($spec['columns'] as $c) {
                $v = isset($row[$c['key']]) ? $row[$c['key']] : '';
                if (($c['type'] ?? '') === 'money') $v = $v === '' ? '' : number_format((float)$v, 2, '.', '');
                $line[] = $v;
                if (!empty($c['total'])) $tot[$c['key']] = ($tot[$c['key']] ?? 0) + (float)(isset($row[$c['key']]) ? $row[$c['key']] : 0);
            }
            fputcsv($out, $line);
        }
        if ($tot) {
            $line = $spec['group'] ? array('') : array();
            foreach ($spec['columns'] as $i => $c) {
                $line[] = isset($tot[$c['key']]) ? number_format($tot[$c['key']], 2, '.', '') : ($i === 0 ? 'TOTAL' : '');
            }
            fputcsv($out, $line);
        }
        fclose($out);
        exit;
    }

    /* ================================================================
       HUBS
       ================================================================ */
    public function students()
    {
        if (!$this->_allowed('reports/students')) return;
        $this->_hub('Student Reports', 'STUDENT_REPORTS', array(
            'Student Details' => array(
                array('reports/student_list', 'Student List', 'All students with personal and contact details'),
                array('reports/student_class_wise', 'Student Class Wise List', 'Students grouped by class and division with boys / girls count'),
                array('reports/student_fee_list', 'Student Fee List', 'Fees allotted to each student and the yearly total'),
            ),
        ));
    }

    public function fees()
    {
        if (!$this->_allowed('reports/fees')) return;
        $this->_hub('Fee Reports', 'FEE_REPORTS', array(
            'Collection' => array(
                array('reports/fee_collection_register', 'Fee Collection Register', 'Receipts between two dates with fee-wise split'),
                array('reports/student_fee_register', 'Student Wise Fee Register', 'Installment ledger of a student: amount, paid, balance, receipts'),
                array('reports/fee_paid_class_wise', 'Fee Paid List (Class Wise)', 'Fees paid by each student of a class in a period'),
            ),
            'Dues' => array(
                array('reports/fee_due_register', 'Fee Due Register', 'Every unpaid installment due till a date, with fine'),
                array('reports/fee_due_amount_register', 'Fee Due Amount Register', 'Due amount per student (fee-wise) till a date'),
                array('reports/balance_due_register', 'Balance Due Register', 'Year total, paid, balance and old balance per student'),
                array('reports/fee_collection_mismatch', 'Fee Collection Mismatch Register', 'Part-paid / excess-paid installments and receipt total mismatches'),
            ),
            'Special Fees' => array(
                array('reports/special_fee_collection', 'Special Fee Collection Register', 'Collections of fees marked as Special'),
                array('reports/special_fee_due', 'Special Fee Due Register', 'Unpaid installments of Special fees'),
            ),
        ));
    }

    private function _hub($title, $menu, $groups)
    {
        $this->load->view('header', array('pageTitle' => $title, 'activePage' => $menu,
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view('report_hub', array('title' => $title, 'groups' => $groups));
        $this->load->view('footer');
    }

    /* ================================================================
       STUDENT REPORTS
       ================================================================ */
    private $genders = array('0' => 'Female', '1' => 'Male');

    private function _student_row($s, $i)
    {
        return array(
            'sl'     => $i,
            'adm'    => $s->smAdmissionNo,
            'name'   => $s->smName,
            'gender' => isset($this->genders[(string)$s->smGender]) ? $this->genders[(string)$s->smGender] : '',
            'dob'    => $this->_dmy($s->smDOB),
            'class'  => $this->_cls($s),
            'mobile' => $s->smMobile,
            'parent' => $s->smFatherName ?: $s->smMotherName,
            'address'=> $s->smAddress,
        );
    }

    public function student_list()
    {
        if (!$this->_allowed('reports/students')) return;
        $rows = array();
        foreach ($this->Rpt->students($this->amId, $this->f) as $i => $s) $rows[] = $this->_student_row($s, $i + 1);

        $this->_render(array(
            'title' => 'Student List', 'menu' => 'STUDENT_REPORTS', 'route' => 'reports/student_list',
            'filters' => array('q', 'class', 'division', 'gender'),
            'columns' => array(
                array('key' => 'sl', 'label' => '#'), array('key' => 'adm', 'label' => 'Adm No'),
                array('key' => 'name', 'label' => 'Name'), array('key' => 'gender', 'label' => 'Gender'),
                array('key' => 'dob', 'label' => 'DOB'), array('key' => 'class', 'label' => 'Class'),
                array('key' => 'mobile', 'label' => 'Mobile'), array('key' => 'parent', 'label' => 'Parent'),
                array('key' => 'address', 'label' => 'Address'),
            ),
            'rows' => $rows, 'count_label' => 'students',
        ));
    }

    public function student_class_wise()
    {
        if (!$this->_allowed('reports/students')) return;
        $list = $this->Rpt->students($this->amId, $this->f);

        $cnt = array();
        foreach ($list as $s) {
            $k = $this->_cls($s) ?: 'No class';
            if (!isset($cnt[$k])) $cnt[$k] = array(0, 0, 0);
            $cnt[$k][0]++;
            if ((string)$s->smGender === '1') $cnt[$k][1]++;
            if ((string)$s->smGender === '0') $cnt[$k][2]++;
        }
        $rows = array(); $n = 0; $last = null;
        foreach ($list as $s) {
            $k = $this->_cls($s) ?: 'No class';
            if ($k !== $last) { $n = 0; $last = $k; }
            $row = $this->_student_row($s, ++$n);
            $row['_group'] = $k . '  —  ' . $cnt[$k][0] . ' students (' . $cnt[$k][1] . ' boys, ' . $cnt[$k][2] . ' girls)';
            $rows[] = $row;
        }

        $this->_render(array(
            'title' => 'Student Class Wise List', 'menu' => 'STUDENT_REPORTS', 'route' => 'reports/student_class_wise',
            'filters' => array('q', 'class', 'division', 'gender'), 'group' => true,
            'columns' => array(
                array('key' => 'sl', 'label' => '#'), array('key' => 'adm', 'label' => 'Adm No'),
                array('key' => 'name', 'label' => 'Name'), array('key' => 'gender', 'label' => 'Gender'),
                array('key' => 'dob', 'label' => 'DOB'), array('key' => 'mobile', 'label' => 'Mobile'),
                array('key' => 'parent', 'label' => 'Parent'),
            ),
            'rows' => $rows, 'count_label' => 'students',
        ));
    }

    public function student_fee_list()
    {
        if (!$this->_allowed('reports/students')) return;
        $by = array(); $fees = array();
        foreach ($this->Rpt->dues($this->amId, $this->f) as $d) {
            $id = (int)$d->sfdSmId;
            if (!isset($by[$id])) {
                $by[$id] = array('_group' => $this->_cls($d), 'adm' => $d->smAdmissionNo, 'name' => $d->smName,
                                 'mobile' => $d->smMobile, 'total' => 0);
            }
            $k = 'fee_' . $d->sfdFmId;
            $by[$id][$k] = ($by[$id][$k] ?? 0) + (float)$d->sfdAmount;
            $by[$id]['total'] += (float)$d->sfdAmount;
            $fees[(int)$d->sfdFmId] = true;
        }
        $rows = array(); $i = 0;
        foreach ($by as $r) { $r['sl'] = ++$i; $rows[] = $r; }

        $this->_render(array(
            'title' => 'Student Fee List', 'menu' => 'STUDENT_REPORTS', 'route' => 'reports/student_fee_list',
            'filters' => array('q', 'class', 'division', 'fee', 'fee_type'), 'group' => true,
            'note' => 'Yearly amount of each fee allotted to the student (all installments).',
            'columns' => array_merge(
                array(array('key' => 'sl', 'label' => '#'), array('key' => 'adm', 'label' => 'Adm No'),
                      array('key' => 'name', 'label' => 'Name'), array('key' => 'mobile', 'label' => 'Mobile')),
                $this->_fee_columns($fees),
                array(array('key' => 'total', 'label' => 'Total', 'type' => 'money', 'total' => true))
            ),
            'rows' => $rows, 'count_label' => 'students',
        ));
    }

    /* ================================================================
       COLLECTION REPORTS
       ================================================================ */
    public function fee_collection_register()
    {
        $this->_collection_register(null);
    }

    public function special_fee_collection()
    {
        $this->_collection_register(1);
    }

    private function _collection_register($special)
    {
        if (!$this->_allowed('reports/fees')) return;
        $f = $this->f;
        if ($special !== null) $f['special'] = (string)$special;
        $lines = $this->Rpt->collection_lines($this->amId, $f, $this->f['from'], $this->f['to']);

        $by = array(); $fees = array(); $modes = array();
        foreach ($lines as $l) {
            $id = (int)$l->frId;
            if (!isset($by[$id])) {
                $by[$id] = array('no' => $l->frReceiptNo, 'date' => $this->_dmy($l->frDate), '_d' => $l->frDate,
                                 'adm' => $l->smAdmissionNo, 'name' => $l->smName, 'class' => $this->_cls($l),
                                 '_c' => sprintf('%05d', (int)$l->smClass) . $l->dmName,
                                 'mode' => $l->frMode . ($l->frReference ? ' ' . $l->frReference : ''), '_m' => $l->frMode,
                                 'fine' => 0, 'total' => 0);
            }
            $k = 'fee_' . $l->frdFmId;
            $by[$id][$k] = ($by[$id][$k] ?? 0) + (float)$l->frdAmount;
            $by[$id]['fine']  += (float)$l->frdFine;
            $by[$id]['total'] += (float)$l->frdAmount + (float)$l->frdFine;
            $fees[(int)$l->frdFmId] = true;
            $modes[$l->frMode] = ($modes[$l->frMode] ?? 0) + (float)$l->frdAmount + (float)$l->frdFine;
        }
        $rows = array_values($by);

        $grp = $this->f['group'];
        if ($grp) {
            $key = array('date' => '_d', 'class' => '_c', 'mode' => '_m')[$grp];
            usort($rows, function ($a, $b) use ($key) { return strcmp($a[$key] . $a['_d'] . $a['no'], $b[$key] . $b['_d'] . $b['no']); });
            foreach ($rows as &$r) {
                $r['_group'] = $grp === 'date' ? $r['date'] : ($grp === 'class' ? $r['class'] : $r['_m']);
            }
            unset($r);
        }

        $note = 'Period ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . '. Cancelled receipts are excluded.';
        if ($modes) {
            $p = array();
            foreach ($modes as $m => $v) $p[] = $m . ': ₹' . fee_money($v);
            $note .= ' Mode-wise: ' . implode(' · ', $p);
        }

        $this->_render(array(
            'title' => $special ? 'Special Fee Collection Register' : 'Fee Collection Register',
            'menu' => 'FEE_REPORTS',
            'route' => $special ? 'reports/special_fee_collection' : 'reports/fee_collection_register',
            'filters' => array_merge(array('from', 'to', 'q', 'class', 'division', 'fee', 'mode'), $special ? array() : array('fee_type'), array('group')),
            'group' => (bool)$grp, 'note' => $note,
            'columns' => array_merge(
                array(array('key' => 'no', 'label' => 'Receipt No'), array('key' => 'date', 'label' => 'Date'),
                      array('key' => 'adm', 'label' => 'Adm No'), array('key' => 'name', 'label' => 'Name'),
                      array('key' => 'class', 'label' => 'Class'), array('key' => 'mode', 'label' => 'Mode')),
                $this->_fee_columns($fees),
                array(array('key' => 'fine', 'label' => 'Fine', 'type' => 'money', 'total' => true),
                      array('key' => 'total', 'label' => 'Total', 'type' => 'money', 'total' => true))
            ),
            'rows' => $rows, 'count_label' => 'receipts',
        ));
    }

    public function student_fee_register()
    {
        if (!$this->_allowed('reports/fees')) return;
        $f = $this->f;
        $need = '';
        $rows = array();
        if (!$f['student'] && $f['class'] === '' && $f['q'] === '') {
            $need = 'Choose a class, or search for a student, to show the register.';
        } else {
            $dues = $this->Rpt->dues($this->amId, $f);
            $rc   = $this->Rpt->receipts_by_due(array_map(function ($d) { return (int)$d->sfdId; }, $dues));
            foreach ($dues as $d) {
                $fine = $this->Fee_model->compute_fine($d, $f['as_of']);
                $status = $d->balance <= 0 ? 'Paid' : ($d->paid > 0 ? 'Part paid' : ($d->sfdDueDate < $f['as_of'] ? 'Overdue' : 'Not due'));
                $rows[] = array(
                    '_group'  => $d->smAdmissionNo . '  ' . $d->smName . '  (' . $this->_cls($d) . ')',
                    'fee'     => $d->fmName, 'period' => $d->sfdPeriod, 'due' => $this->_dmy($d->sfdDueDate),
                    'amount'  => (float)$d->sfdAmount, 'paid' => $d->paid, 'balance' => max(0, $d->balance),
                    'fine'    => $fine, 'status' => $status, 'receipts' => isset($rc[$d->sfdId]) ? $rc[$d->sfdId] : '',
                );
            }
        }
        $title = 'Student Wise Fee Register';
        if ($f['student'] && ($st = $this->Fee_model->get_student($f['student'], $this->amId))) {
            $title .= ' — ' . $st->smName;
        }
        $this->_render(array(
            'title' => $title, 'menu' => 'FEE_REPORTS', 'route' => 'reports/student_fee_register',
            'filters' => array('q', 'class', 'division', 'fee', 'fee_type', 'as_of', 'student'), 'group' => true, 'need' => $need,
            'note' => 'Fine is calculated as of ' . $this->_dmy($f['as_of']) . '.',
            'columns' => array(
                array('key' => 'fee', 'label' => 'Fee'), array('key' => 'period', 'label' => 'Period'),
                array('key' => 'due', 'label' => 'Due Date'),
                array('key' => 'amount', 'label' => 'Amount', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'balance', 'label' => 'Balance', 'type' => 'money', 'total' => true),
                array('key' => 'fine', 'label' => 'Fine', 'type' => 'money', 'total' => true),
                array('key' => 'status', 'label' => 'Status'), array('key' => 'receipts', 'label' => 'Receipts'),
            ),
            'rows' => $rows, 'count_label' => 'installments',
        ));
    }

    public function fee_paid_class_wise()
    {
        if (!$this->_allowed('reports/fees')) return;
        $lines = $this->Rpt->collection_lines($this->amId, $this->f, $this->f['from'], $this->f['to']);
        $by = array(); $fees = array();
        foreach ($lines as $l) {
            $id = (int)$l->frSmId;
            if (!isset($by[$id])) {
                $by[$id] = array('_sort' => sprintf('%05d', (int)$l->smClass) . '|' . $l->dmName . '|' . $l->smName,
                                 '_group' => $this->_cls($l), 'adm' => $l->smAdmissionNo, 'name' => $l->smName,
                                 'fine' => 0, 'total' => 0, '_r' => array());
            }
            $k = 'fee_' . $l->frdFmId;
            $by[$id][$k] = ($by[$id][$k] ?? 0) + (float)$l->frdAmount;
            $by[$id]['fine']  += (float)$l->frdFine;
            $by[$id]['total'] += (float)$l->frdAmount + (float)$l->frdFine;
            $by[$id]['_r'][$l->frId] = true;
            $fees[(int)$l->frdFmId] = true;
        }
        $rows = array_values($by);
        usort($rows, function ($a, $b) { return strcmp($a['_sort'], $b['_sort']); });
        $last = null; $n = 0;
        foreach ($rows as &$r) {
            if ($r['_group'] !== $last) { $n = 0; $last = $r['_group']; }
            $r['sl'] = ++$n;
            $r['receipts'] = count($r['_r']);
        }
        unset($r);

        $this->_render(array(
            'title' => 'Fee Paid List (Class Wise)', 'menu' => 'FEE_REPORTS', 'route' => 'reports/fee_paid_class_wise',
            'filters' => array('from', 'to', 'q', 'class', 'division', 'fee', 'fee_type'), 'group' => true,
            'note' => 'Payments from ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . '.',
            'columns' => array_merge(
                array(array('key' => 'sl', 'label' => '#'), array('key' => 'adm', 'label' => 'Adm No'), array('key' => 'name', 'label' => 'Name')),
                $this->_fee_columns($fees),
                array(array('key' => 'fine', 'label' => 'Fine', 'type' => 'money', 'total' => true),
                      array('key' => 'total', 'label' => 'Total Paid', 'type' => 'money', 'total' => true),
                      array('key' => 'receipts', 'label' => 'Receipts', 'type' => 'int'))
            ),
            'rows' => $rows, 'count_label' => 'students',
        ));
    }

    /* ================================================================
       DUE REPORTS
       ================================================================ */
    public function fee_due_register()
    {
        $this->_due_register(null);
    }

    public function special_fee_due()
    {
        $this->_due_register(1);
    }

    private function _due_register($special)
    {
        if (!$this->_allowed('reports/fees')) return;
        $f = $this->f;
        if ($special !== null) $f['special'] = (string)$special;
        $asOf = $f['as_of'];
        $rows = array();
        $today = new DateTime($asOf);
        foreach ($this->Rpt->dues($this->amId, $f, $asOf, true) as $d) {
            $late = (new DateTime($d->sfdDueDate))->diff($today);
            $rows[] = array(
                '_group' => $this->_cls($d), 'adm' => $d->smAdmissionNo, 'name' => $d->smName, 'mobile' => $d->smMobile,
                'fee' => $d->fmName, 'period' => $d->sfdPeriod, 'due' => $this->_dmy($d->sfdDueDate),
                'days' => $late->invert ? 0 : $late->days,
                'amount' => (float)$d->sfdAmount, 'paid' => $d->paid, 'balance' => $d->balance,
                'fine' => $this->Fee_model->compute_fine($d, $asOf),
            );
        }
        $this->_render(array(
            'title' => $special ? 'Special Fee Due Register' : 'Fee Due Register',
            'menu' => 'FEE_REPORTS',
            'route' => $special ? 'reports/special_fee_due' : 'reports/fee_due_register',
            'filters' => array_merge(array('as_of', 'q', 'class', 'division', 'fee'), $special ? array() : array('fee_type')),
            'group' => true,
            'note' => 'Unpaid installments due on or before ' . $this->_dmy($asOf) . '.',
            'columns' => array(
                array('key' => 'adm', 'label' => 'Adm No'), array('key' => 'name', 'label' => 'Name'),
                array('key' => 'mobile', 'label' => 'Mobile'), array('key' => 'fee', 'label' => 'Fee'),
                array('key' => 'period', 'label' => 'Period'), array('key' => 'due', 'label' => 'Due Date'),
                array('key' => 'days', 'label' => 'Days Late', 'type' => 'int'),
                array('key' => 'amount', 'label' => 'Amount', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'balance', 'label' => 'Balance', 'type' => 'money', 'total' => true),
                array('key' => 'fine', 'label' => 'Fine', 'type' => 'money', 'total' => true),
            ),
            'rows' => $rows, 'count_label' => 'installments',
        ));
    }

    public function fee_due_amount_register()
    {
        if (!$this->_allowed('reports/fees')) return;
        $asOf = $this->f['as_of'];
        $by = array(); $fees = array();
        foreach ($this->Rpt->dues($this->amId, $this->f, $asOf, true) as $d) {
            $id = (int)$d->sfdSmId;
            if (!isset($by[$id])) {
                $by[$id] = array('_group' => $this->_cls($d), 'adm' => $d->smAdmissionNo, 'name' => $d->smName,
                                 'mobile' => $d->smMobile, 'fine' => 0, 'total' => 0, 'inst' => 0);
            }
            $k = 'fee_' . $d->sfdFmId;
            $fine = $this->Fee_model->compute_fine($d, $asOf);
            $by[$id][$k] = ($by[$id][$k] ?? 0) + $d->balance;
            $by[$id]['fine']  += $fine;
            $by[$id]['total'] += $d->balance + $fine;
            $by[$id]['inst']++;
            $fees[(int)$d->sfdFmId] = true;
        }
        $rows = array(); $last = null; $n = 0;
        foreach ($by as $r) {
            if ($r['_group'] !== $last) { $n = 0; $last = $r['_group']; }
            $r['sl'] = ++$n;
            $rows[] = $r;
        }
        $this->_render(array(
            'title' => 'Fee Due Amount Register', 'menu' => 'FEE_REPORTS', 'route' => 'reports/fee_due_amount_register',
            'filters' => array('as_of', 'q', 'class', 'division', 'fee', 'fee_type'), 'group' => true,
            'note' => 'Balance of installments due on or before ' . $this->_dmy($asOf) . ', fee-wise.',
            'columns' => array_merge(
                array(array('key' => 'sl', 'label' => '#'), array('key' => 'adm', 'label' => 'Adm No'),
                      array('key' => 'name', 'label' => 'Name'), array('key' => 'mobile', 'label' => 'Mobile'),
                      array('key' => 'inst', 'label' => 'Inst.', 'type' => 'int')),
                $this->_fee_columns($fees),
                array(array('key' => 'fine', 'label' => 'Fine', 'type' => 'money', 'total' => true),
                      array('key' => 'total', 'label' => 'Total Due', 'type' => 'money', 'total' => true))
            ),
            'rows' => $rows, 'count_label' => 'students',
        ));
    }

    public function balance_due_register()
    {
        if (!$this->_allowed('reports/fees')) return;
        $asOf = $this->f['as_of'];
        $by = array();
        foreach ($this->Rpt->dues($this->amId, $this->f) as $d) {
            $id = (int)$d->sfdSmId;
            if (!isset($by[$id])) {
                $by[$id] = array('_group' => $this->_cls($d), 'adm' => $d->smAdmissionNo, 'name' => $d->smName,
                                 'mobile' => $d->smMobile, 'total' => 0, 'paid' => 0, 'balance' => 0, 'due_now' => 0);
            }
            $by[$id]['total']   += (float)$d->sfdAmount;
            $by[$id]['paid']    += $d->paid;
            $by[$id]['balance'] += max(0, $d->balance);
            if ($d->sfdDueDate <= $asOf) $by[$id]['due_now'] += max(0, $d->balance);
        }
        $old = $this->Rpt->old_balance_map($this->amId, array_keys($by));
        $rows = array(); $last = null; $n = 0;
        foreach ($by as $id => $r) {
            $r['old'] = isset($old[$id]) ? $old[$id] : 0;
            $r['net'] = $r['balance'] + $r['old'];
            if ($this->f['only_balance'] === '1' && $r['net'] <= 0) continue;
            if ($r['_group'] !== $last) { $n = 0; $last = $r['_group']; }
            $r['sl'] = ++$n;
            $rows[] = $r;
        }
        $this->_render(array(
            'title' => 'Balance Due Register', 'menu' => 'FEE_REPORTS', 'route' => 'reports/balance_due_register',
            'filters' => array('as_of', 'q', 'class', 'division', 'fee', 'fee_type', 'only_balance'), 'group' => true,
            'note' => '"Due till date" counts installments due on or before ' . $this->_dmy($asOf) . '. Old balance is the unpaid amount of earlier academic years.',
            'columns' => array(
                array('key' => 'sl', 'label' => '#'), array('key' => 'adm', 'label' => 'Adm No'),
                array('key' => 'name', 'label' => 'Name'), array('key' => 'mobile', 'label' => 'Mobile'),
                array('key' => 'total', 'label' => 'Year Total', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Paid', 'type' => 'money', 'total' => true),
                array('key' => 'due_now', 'label' => 'Due till date', 'type' => 'money', 'total' => true),
                array('key' => 'balance', 'label' => 'Year Balance', 'type' => 'money', 'total' => true),
                array('key' => 'old', 'label' => 'Old Balance', 'type' => 'money', 'total' => true),
                array('key' => 'net', 'label' => 'Net Balance', 'type' => 'money', 'total' => true),
            ),
            'rows' => $rows, 'count_label' => 'students',
        ));
    }

    public function fee_collection_mismatch()
    {
        if (!$this->_allowed('reports/fees')) return;
        $rows = array();
        $dues = array_filter($this->Rpt->dues($this->amId, $this->f), function ($d) {
            return ($d->paid > 0 && $d->balance > 0) || $d->balance < 0;
        });
        $rc = $this->Rpt->receipts_by_due(array_map(function ($d) { return (int)$d->sfdId; }, $dues));
        foreach ($dues as $d) {
            $rows[] = array(
                'type' => $d->balance < 0 ? 'Excess paid' : 'Part paid',
                'adm' => $d->smAdmissionNo, 'name' => $d->smName, 'class' => $this->_cls($d),
                'fee' => $d->fmName, 'period' => $d->sfdPeriod, 'due' => $this->_dmy($d->sfdDueDate),
                'expected' => (float)$d->sfdAmount, 'paid' => $d->paid, 'diff' => round($d->paid - (float)$d->sfdAmount, 2),
                'receipts' => isset($rc[$d->sfdId]) ? $rc[$d->sfdId] : '',
            );
        }
        foreach ($this->Rpt->receipt_total_mismatch($this->amId) as $r) {
            $lines = (float)$r->line_amt + (float)$r->line_fine;
            $rows[] = array(
                'type' => 'Receipt total', 'adm' => $r->smAdmissionNo, 'name' => $r->smName,
                'class' => trim($r->cmName . ' - ' . $r->dmName, ' -'), 'fee' => '', 'period' => '',
                'due' => $this->_dmy($r->frDate), 'expected' => $lines, 'paid' => (float)$r->frTotal,
                'diff' => round((float)$r->frTotal - $lines, 2), 'receipts' => $r->frReceiptNo,
            );
        }
        $this->_render(array(
            'title' => 'Fee Collection Mismatch Register', 'menu' => 'FEE_REPORTS', 'route' => 'reports/fee_collection_mismatch',
            'filters' => array('q', 'class', 'division', 'fee', 'fee_type'),
            'note' => 'Part paid: an installment collected for less than its amount. Excess paid: collected more than the amount (the fee was reduced after payment). Receipt total: receipt header does not match its lines.',
            'columns' => array(
                array('key' => 'type', 'label' => 'Type'), array('key' => 'adm', 'label' => 'Adm No'),
                array('key' => 'name', 'label' => 'Name'), array('key' => 'class', 'label' => 'Class'),
                array('key' => 'fee', 'label' => 'Fee'), array('key' => 'period', 'label' => 'Period'),
                array('key' => 'due', 'label' => 'Due / Rcpt Date'),
                array('key' => 'expected', 'label' => 'Expected', 'type' => 'money', 'total' => true),
                array('key' => 'paid', 'label' => 'Collected', 'type' => 'money', 'total' => true),
                array('key' => 'diff', 'label' => 'Difference', 'type' => 'money', 'total' => true),
                array('key' => 'receipts', 'label' => 'Receipts'),
            ),
            'rows' => $rows, 'count_label' => 'rows',
        ));
    }
}
