<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Finance reports (Reports > Finance Reports). Table reports use libraries/Report_view; statements use views/acc_statement. */
class AccountReports extends CI_Controller
{
    private $f;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Accounts_model', 'Acc');
        $this->Acc->require_schema();
        $this->load->model('Subject_Model');
        $this->load->library('Report_view');
        $this->load->helper('fee');

        list($fyStart) = Accounts_model::fy_range(date('Y-m-d'));
        $start = max($fyStart, $this->Acc->books_start());
        $g = function ($k) { return trim((string)$this->input->get($k)); };
        $d = function ($k, $def) use ($g) {
            $v = $g($k); $x = DateTime::createFromFormat('Y-m-d', $v);
            return ($x && $x->format('Y-m-d') === $v) ? $v : $def;
        };
        $this->f = array(
            'from' => $d('from', $start), 'to' => $d('to', date('Y-m-d')), 'as_of' => $d('as_of', date('Y-m-d')),
            'ledger' => (int)$g('ledger'), 'type' => isset(Accounts_model::$types[$g('type')]) ? $g('type') : '',
            'detail' => $g('detail') === '0' ? '0' : '1', 'zero' => $g('zero') === '1' ? '1' : '0',
            // keys the shared filter view expects
            'q' => '', 'class' => '', 'division' => '', 'student' => 0, 'gender' => '', 'fee' => 0, 'fee_type' => '',
            'mode' => '', 'group' => '', 'only_balance' => '1',
        );
    }

    private function _allowed()
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link('reports/accounts'));
        if (!empty($perm['can_view'])) { $this->Acc->sync_sources(); return true; }
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
        return false;
    }

    private function _dmy($d) { return $d ? date('d-m-Y', strtotime($d)) : ''; }
    private function _drcr($n) { return $n == 0 ? '0.00' : fee_money(abs($n)) . ($n > 0 ? ' Dr' : ' Cr'); }

    private function _render($spec)
    {
        $spec['menu'] = 'ACC_REPORTS';
        $spec['hub']  = 'reports/accounts';
        $spec['year'] = Accounts_model::fy($this->f['to']);
        $this->report_view->output($spec, array('f' => $this->f));
    }

    private function _ledger_select($types = null, $default = 0)
    {
        $o = array();
        foreach ($this->Acc->get_ledgers(false, $types) as $l) $o[$l->alId] = $l->alName . ' — ' . $l->agName;
        if (!$this->f['ledger'] && $default && isset($o[$default])) $this->f['ledger'] = $default;
        return array('name' => 'ledger', 'label' => 'Account', 'options' => $o, 'value' => $this->f['ledger'], 'all' => 'Select account');
    }

    /* ---------------- hub ---------------- */
    public function index()
    {
        if (!$this->_allowed()) return;
        $this->load->view('header', array('pageTitle' => 'Finance Reports', 'activePage' => 'ACC_REPORTS',
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view('report_hub', array('title' => 'Finance Reports', 'groups' => array(
            'Final Accounts' => array(
                array('reports/acc_balance_sheet', 'Balance Sheet', 'Assets, liabilities and capital as on a date'),
                array('reports/acc_profit_loss', 'Profit & Loss (Income & Expenditure)', 'Income and expenses for a period, surplus / deficit'),
                array('reports/acc_trial_balance', 'Trial Balance', 'Opening, debit, credit and closing of every account'),
                array('reports/acc_receipts_payments', 'Receipts & Payments', 'Cash and bank inflow / outflow by account'),
            ),
            'Books' => array(
                array('reports/acc_day_book', 'Day Book', 'All vouchers of a period with their entries'),
                array('reports/acc_cash_book', 'Cash Book', 'Cash account with running balance'),
                array('reports/acc_bank_book', 'Bank Book', 'Bank account with running balance and clearing dates'),
                array('reports/acc_ledger', 'Ledger Statement', 'Any account with running balance'),
            ),
            'Parties' => array(
                array('reports/acc_party_balances', 'Debtors & Creditors', 'Amounts receivable and payable as on a date'),
            ),
        )));
        $this->load->view('footer');
    }

    /* ---------------- books ---------------- */
    public function day_book()
    {
        if (!$this->_allowed()) return;
        $rows = array();
        foreach ($this->Acc->day_book($this->f['from'], $this->f['to'], $this->f['type']) as $e) {
            $rows[] = array('_group' => $this->_dmy($e->avDate), 'no' => $e->avNo,
                'type' => Accounts_model::$types[$e->avType]['label'], 'account' => ($e->aeCr > 0 ? '    To ' : '') . $e->alName,
                'narr' => $e->aeNarration ?: $e->avNarration, 'dr' => $e->aeDr > 0 ? (float)$e->aeDr : '', 'cr' => $e->aeCr > 0 ? (float)$e->aeCr : '');
        }
        $types = array();
        foreach (Accounts_model::$types as $k => $t) $types[$k] = $t['label'];
        $this->_render(array(
            'title' => 'Day Book', 'route' => 'reports/acc_day_book', 'filters' => array('from', 'to'), 'group' => true,
            'selects' => array(array('name' => 'type', 'label' => 'Voucher Type', 'options' => $types, 'value' => $this->f['type'])),
            'note' => 'Vouchers from ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . '. Cancelled vouchers are excluded.',
            'columns' => array(
                array('key' => 'no', 'label' => 'Voucher No'), array('key' => 'type', 'label' => 'Type'),
                array('key' => 'account', 'label' => 'Account'), array('key' => 'narr', 'label' => 'Narration'),
                array('key' => 'dr', 'label' => 'Debit', 'type' => 'money', 'total' => true),
                array('key' => 'cr', 'label' => 'Credit', 'type' => 'money', 'total' => true),
            ),
            'rows' => $rows, 'count_label' => 'entries',
        ));
    }

    public function ledger()        { $this->_statement(null, 'Ledger Statement', 'reports/acc_ledger', 0); }
    public function cash_book()     { $this->_statement('CASH', 'Cash Book', 'reports/acc_cash_book', (int)$this->Acc->setting('cash_ledger')); }
    public function bank_book()     { $this->_statement('BANK', 'Bank Book', 'reports/acc_bank_book', (int)$this->Acc->setting('bank_ledger')); }

    private function _statement($types, $title, $route, $default)
    {
        if (!$this->_allowed()) return;
        $sel = $this->_ledger_select($types, $default);
        if (!$this->f['ledger'] && $sel['options']) { $this->f['ledger'] = (int)key($sel['options']); $sel['value'] = $this->f['ledger']; }
        $rows = array(); $need = '';
        $st = $this->f['ledger'] ? $this->Acc->statement($this->f['ledger'], $this->f['from'], $this->f['to']) : null;
        if (!$st) {
            $need = 'Select an account.';
        } else {
            $bal = $st['open'];
            $rows[] = array('date' => $this->_dmy($this->f['from']), 'no' => '', 'particulars' => 'Opening balance', 'dr' => '', 'cr' => '', 'bal' => $this->_drcr($bal));
            foreach ($st['rows'] as $r) {
                $bal = round($bal + $r->aeDr - $r->aeCr, 2);
                $rows[] = array(
                    'date' => $this->_dmy($r->avDate), 'no' => $r->avNo,
                    'particulars' => ($r->other ?: '') . (($r->aeNarration ?: $r->avNarration) ? ' — ' . ($r->aeNarration ?: $r->avNarration) : ''),
                    'ref' => $r->avRef, 'dr' => $r->aeDr > 0 ? (float)$r->aeDr : '', 'cr' => $r->aeCr > 0 ? (float)$r->aeCr : '',
                    'bal' => $this->_drcr($bal), 'clear' => $r->aeClearDate ? $this->_dmy($r->aeClearDate) : '',
                );
            }
            $title .= ' — ' . $st['ledger']->alName;
        }
        $cols = array(
            array('key' => 'date', 'label' => 'Date'), array('key' => 'no', 'label' => 'Voucher No'),
            array('key' => 'particulars', 'label' => 'Particulars'), array('key' => 'ref', 'label' => 'Ref / Cheque'),
            array('key' => 'dr', 'label' => 'Debit', 'type' => 'money', 'total' => true),
            array('key' => 'cr', 'label' => 'Credit', 'type' => 'money', 'total' => true),
            array('key' => 'bal', 'label' => 'Balance'),
        );
        if ($types === 'BANK') $cols[] = array('key' => 'clear', 'label' => 'Bank Date');
        $this->_render(array(
            'title' => $title, 'route' => $route, 'filters' => array('from', 'to'), 'selects' => array($sel), 'need' => $need,
            'note' => 'From ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . ($st ? '. Closing balance ' . $this->_drcr(isset($bal) ? $bal : 0) . '.' : ''),
            'columns' => $cols, 'rows' => $rows, 'count_label' => 'lines',
        ));
    }

    /* ---------------- trial balance ---------------- */
    public function trial_balance()
    {
        if (!$this->_allowed()) return;
        $rows = array();
        foreach ($this->Acc->balances($this->f['from'], $this->f['to']) as $b) {
            if ($this->f['zero'] !== '1' && $b->open == 0 && $b->dr == 0 && $b->cr == 0) continue;
            $rows[] = array('_group' => Accounts_model::$natures[$b->agNature] . ' — ' . $b->agName, 'account' => $b->alName,
                'odr' => $b->open > 0 ? $b->open : '', 'ocr' => $b->open < 0 ? -$b->open : '',
                'dr' => $b->dr ?: '', 'cr' => $b->cr ?: '',
                'cdr' => $b->close > 0 ? $b->close : '', 'ccr' => $b->close < 0 ? -$b->close : '');
        }
        $diff = $this->Acc->opening_difference();
        $this->_render(array(
            'title' => 'Trial Balance', 'route' => 'reports/acc_trial_balance', 'filters' => array('from', 'to'), 'group' => true,
            'selects' => array(array('name' => 'zero', 'label' => 'Accounts', 'options' => array('1' => 'Include zero balances'), 'value' => $this->f['zero'], 'all' => 'With balance / activity')),
            'note' => 'From ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']) . '.'
                    . ($diff != 0 ? ' Opening balances differ by ' . $this->_drcr($diff) . ' — correct the opening balances in Chart of Accounts.' : ' Opening balances agree.'),
            'columns' => array(
                array('key' => 'account', 'label' => 'Account'),
                array('key' => 'odr', 'label' => 'Opening Dr', 'type' => 'money', 'total' => true),
                array('key' => 'ocr', 'label' => 'Opening Cr', 'type' => 'money', 'total' => true),
                array('key' => 'dr', 'label' => 'Debit', 'type' => 'money', 'total' => true),
                array('key' => 'cr', 'label' => 'Credit', 'type' => 'money', 'total' => true),
                array('key' => 'cdr', 'label' => 'Closing Dr', 'type' => 'money', 'total' => true),
                array('key' => 'ccr', 'label' => 'Closing Cr', 'type' => 'money', 'total' => true),
            ),
            'rows' => $rows, 'count_label' => 'accounts',
        ));
    }

    /* ---------------- statements ---------------- */
    /* sections of a statement from ledger balances: [ [label, amount, level, link], ... ] */
    private function _tree($balances, $natures, $amountFn)
    {
        $groups = $this->Acc->get_groups();
        $byGroup = array();
        foreach ($balances as $b) {
            if (!in_array($b->agNature, $natures, true)) continue;
            $a = round($amountFn($b), 2);
            if ($a == 0) continue;
            $byGroup[$b->agId][] = array($b->alName, $a, $b->alId);
        }
        $children = array();
        foreach ($groups as $g) if ($g->agParentId) $children[$g->agParentId][] = $g;
        $sum = function ($gid) use (&$sum, $byGroup, $children) {
            $t = 0;
            if (isset($byGroup[$gid])) foreach ($byGroup[$gid] as $l) $t += $l[1];
            if (isset($children[$gid])) foreach ($children[$gid] as $c) $t += $sum($c->agId);
            return $t;
        };
        $rows = array(); $total = 0;
        foreach ($groups as $g) {
            if ($g->agParentId || !in_array($g->agNature, $natures, true)) continue;
            $gt = round($sum($g->agId), 2);
            if ($gt == 0) continue;
            $total += $gt;
            $rows[] = array($g->agName, $gt, 0, 0);
            if ($this->f['detail'] !== '1') continue;
            if (isset($children[$g->agId])) foreach ($children[$g->agId] as $c) {
                $ct = round($sum($c->agId), 2);
                if ($ct == 0) continue;
                $rows[] = array($c->agName, $ct, 1, 0);
                if (isset($byGroup[$c->agId])) foreach ($byGroup[$c->agId] as $l) $rows[] = array($l[0], $l[1], 2, $l[2]);
            }
            if (isset($byGroup[$g->agId])) foreach ($byGroup[$g->agId] as $l) $rows[] = array($l[0], $l[1], 1, $l[2]);
        }
        return array('rows' => $rows, 'total' => round($total, 2));
    }

    private function _statement_page($s)
    {
        $s += array('from' => $this->f['from'], 'to' => $this->f['to'], 'as_of' => $this->f['as_of'], 'detail' => $this->f['detail']);
        if ($this->input->get('export') === 'csv') {
            $rows = array();
            foreach ($s['sections'] as $sec) {
                $rows[] = array('_group' => $sec['title'], 'label' => '', 'amount' => '');
                foreach ($sec['rows'] as $r) $rows[] = array('_group' => $sec['title'], 'label' => str_repeat('  ', $r[2]) . $r[0], 'amount' => $r[1]);
                $rows[] = array('_group' => $sec['title'], 'label' => $sec['total_label'], 'amount' => $sec['total']);
            }
            foreach ($s['summary'] as $x) $rows[] = array('_group' => '', 'label' => $x[0], 'amount' => $x[1]);
            $this->report_view->csv(array('title' => $s['title'], 'group' => true, 'rows' => $rows,
                'columns' => array(array('key' => 'label', 'label' => 'Particulars'), array('key' => 'amount', 'label' => 'Amount', 'type' => 'money'))));
            exit;
        }
        $this->load->view('header', array('pageTitle' => $s['title'], 'activePage' => 'ACC_REPORTS',
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view('acc_statement', array('s' => $s));
        $this->load->view('footer');
    }

    public function profit_loss()
    {
        if (!$this->_allowed()) return;
        $bal = $this->Acc->balances($this->f['from'], $this->f['to']);
        $inc = $this->_tree($bal, array('INCOME'), function ($b) { return $b->cr - $b->dr; });
        $exp = $this->_tree($bal, array('EXPENSE'), function ($b) { return $b->dr - $b->cr; });
        $net = round($inc['total'] - $exp['total'], 2);
        $this->_statement_page(array(
            'title' => 'Profit & Loss (Income & Expenditure)', 'route' => 'reports/acc_profit_loss', 'mode' => 'period',
            'subtitle' => 'For the period ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']),
            'sections' => array(
                array('title' => 'Income', 'rows' => $inc['rows'], 'total_label' => 'Total Income', 'total' => $inc['total']),
                array('title' => 'Expenditure', 'rows' => $exp['rows'], 'total_label' => 'Total Expenditure', 'total' => $exp['total']),
            ),
            'summary' => array(array($net >= 0 ? 'Surplus (excess of income over expenditure)' : 'Deficit (excess of expenditure over income)', abs($net), $net >= 0 ? 'ok' : 'bad')),
        ));
    }

    public function balance_sheet()
    {
        if (!$this->_allowed()) return;
        $d = $this->f['as_of'];
        $bal = $this->Acc->balances($d, $d);
        $assets = $this->_tree($bal, array('ASSET'), function ($b) { return $b->close; });
        $liabs  = $this->_tree($bal, array('EQUITY', 'LIABILITY'), function ($b) { return -$b->close; });
        $surplus = 0;
        foreach ($bal as $b) {
            if ($b->agNature === 'INCOME')  $surplus -= $b->close;
            if ($b->agNature === 'EXPENSE') $surplus -= $b->close;
        }
        $surplus = round($surplus, 2);
        $diff = $this->Acc->opening_difference();
        $liabs['rows'][] = array('Income & Expenditure Account (accumulated ' . ($surplus >= 0 ? 'surplus' : 'deficit') . ')', $surplus, 0, 0);
        $liabs['total'] = round($liabs['total'] + $surplus, 2);
        if ($diff > 0)  { $liabs['rows'][] = array('Difference in opening balances', $diff, 0, 0);  $liabs['total'] = round($liabs['total'] + $diff, 2); }
        if ($diff < 0)  { $assets['rows'][] = array('Difference in opening balances', -$diff, 0, 0); $assets['total'] = round($assets['total'] - $diff, 2); }
        $this->_statement_page(array(
            'title' => 'Balance Sheet', 'route' => 'reports/acc_balance_sheet', 'mode' => 'asof',
            'subtitle' => 'As on ' . $this->_dmy($d),
            'sections' => array(
                array('title' => 'Capital & Liabilities', 'rows' => $liabs['rows'], 'total_label' => 'Total', 'total' => $liabs['total']),
                array('title' => 'Assets', 'rows' => $assets['rows'], 'total_label' => 'Total', 'total' => $assets['total']),
            ),
            'summary' => abs($liabs['total'] - $assets['total']) > 0.009
                ? array(array('Not balanced by', abs($liabs['total'] - $assets['total']), 'bad'))
                : array(array('Balanced', $assets['total'], 'ok')),
        ));
    }

    public function receipts_payments()
    {
        if (!$this->_allowed()) return;
        $open = 0; $close = 0;
        foreach ($this->Acc->balances($this->f['from'], $this->f['to']) as $b) {
            if (in_array($b->alType, array('CASH', 'BANK'), true)) { $open += $b->open; $close += $b->close; }
        }
        $byR = array(); $byP = array();
        foreach ($this->Acc->receipts_payments($this->f['from'], $this->f['to']) as $r) {
            if ($r->rec > 0) $byR[$r->agName][] = array($r->alName, (float)$r->rec, 1, $r->alId);
            if ($r->pay > 0) $byP[$r->agName][] = array($r->alName, (float)$r->pay, 1, $r->alId);
        }
        $build = function ($byGroup) {
            $rows = array(); $total = 0;
            foreach ($byGroup as $gname => $ls) {
                $gt = 0;
                foreach ($ls as $l) $gt += $l[1];
                $rows[] = array($gname, round($gt, 2), 0, 0);
                if ($this->f['detail'] === '1') foreach ($ls as $l) $rows[] = $l;
                $total += $gt;
            }
            return array($rows, $total);
        };
        list($recRows, $recTotal) = $build($byR);
        list($payRows, $payTotal) = $build($byP);
        $rec = array_merge(array(array('Opening cash & bank balance', round($open, 2), 0, 0)), $recRows);
        $tr  = $open + $recTotal;
        $pay = $payRows;
        $tp  = $payTotal;
        $pay[] = array('Closing cash & bank balance', round($close, 2), 0, 0);
        $tp += $close;
        $this->_statement_page(array(
            'title' => 'Receipts & Payments', 'route' => 'reports/acc_receipts_payments', 'mode' => 'period',
            'subtitle' => 'For the period ' . $this->_dmy($this->f['from']) . ' to ' . $this->_dmy($this->f['to']),
            'sections' => array(
                array('title' => 'Receipts', 'rows' => $rec, 'total_label' => 'Total', 'total' => round($tr, 2)),
                array('title' => 'Payments', 'rows' => $pay, 'total_label' => 'Total', 'total' => round($tp, 2)),
            ),
            'summary' => abs($tr - $tp) > 0.009 ? array(array('Difference', abs($tr - $tp), 'bad')) : array(),
        ));
    }

    public function party_balances()
    {
        if (!$this->_allowed()) return;
        $d = $this->f['as_of'];
        $keys = array();
        foreach ($this->Acc->get_groups() as $g) if (in_array($g->agKey, array('DEBTORS', 'CREDITORS'), true)) $keys[$g->agId] = $g->agKey;
        $rows = array();
        foreach ($this->Acc->balances($d, $d) as $b) {
            if (!isset($keys[$b->agId]) || $b->close == 0) continue;
            $rows[] = array('_group' => $b->agName, 'account' => $b->alName,
                'recv' => $b->close > 0 ? $b->close : '', 'pay' => $b->close < 0 ? -$b->close : '');
        }
        usort($rows, function ($a, $b) { return strcmp($a['_group'] . $a['account'], $b['_group'] . $b['account']); });
        $this->_render(array(
            'title' => 'Debtors & Creditors', 'route' => 'reports/acc_party_balances', 'filters' => array('as_of'), 'group' => true,
            'note' => 'Balances as on ' . $this->_dmy($d) . '. Receivable = owed to the school; Payable = the school owes.',
            'columns' => array(
                array('key' => 'account', 'label' => 'Party'),
                array('key' => 'recv', 'label' => 'Receivable', 'type' => 'money', 'total' => true),
                array('key' => 'pay', 'label' => 'Payable', 'type' => 'money', 'total' => true),
            ),
            'rows' => $rows, 'count_label' => 'parties',
        ));
    }
}
