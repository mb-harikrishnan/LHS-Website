<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Payroll: Monthly Payroll, Salary Structure, Salary Advances, Salary Components, payslips */
class PayrollController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Payroll_model', 'Pay');
        $this->Pay->require_schema();
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

    /* '' -> $empty, valid amount -> float, otherwise false */
    private function _money($v, $empty = 0)
    {
        $v = trim((string)$v);
        if ($v === '') return $empty;
        return preg_match('/^\d{1,9}(\.\d{1,2})?$/', $v) ? (float)$v : false;
    }

    private function _date($v)
    {
        $v = (string)$v; $d = DateTime::createFromFormat('Y-m-d', $v);
        return ($d && $d->format('Y-m-d') === $v) ? $v : '';
    }

    /* 'YYYY-MM' -> 'YYYY-MM-01' */
    private function _month($v)
    {
        $v = (string)$v;
        if (!preg_match('/^(\d{4})-(\d{2})$/', $v, $m) || (int)$m[2] < 1 || (int)$m[2] > 12 || (int)$m[1] < 2000) return '';
        return $v . '-01';
    }

    /* ================================================================
       SALARY COMPONENTS
       ================================================================ */
    public function pay_components()
    {
        $perm = $this->_perm('pay_components');
        if (empty($perm['can_view'])) return $this->_deny();
        $this->_page('pay_components', array('components' => $this->Pay->components(), 'perm' => $perm),
                     'Salary Components', 'PAY_COMPONENTS');
    }

    public function pay_component_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id = (int)$this->input->post('id');
        if (!$this->_can('pay_components', $id ? 'can_edit' : 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        if ($id && !$this->Pay->get_component($id)) return $this->_json(false, 'Component not found.');

        $name  = $this->_clean($this->input->post('name'), 80);
        $short = strtoupper($this->_clean($this->input->post('short'), 12));
        $type  = (string)$this->input->post('type');
        $calc  = (string)$this->input->post('calc');
        $value = $this->_money($this->input->post('value'));
        $ceil  = $this->_money($this->input->post('ceiling'), null);
        $limit = $this->_money($this->input->post('gross_limit'), null);
        $key   = (string)$this->input->post('key');

        if ($name === '')  return $this->_json(false, 'Enter the component name.');
        if ($short === '') return $this->_json(false, 'Enter a short name (shown as the column heading in the salary register).');
        if (!isset(Payroll_model::$types[$type])) return $this->_json(false, 'Select the type.');
        $allowed = $type === 'EARNING' ? array('FIXED', 'BASIC') : array_keys(Payroll_model::$calcs);
        if (!in_array($calc, $allowed, true)) return $this->_json(false, 'Select how it is calculated.');
        if ($value === false) return $this->_json(false, 'Enter a valid default value.');
        if ($calc !== 'FIXED' && $value > 100) return $this->_json(false, 'A percentage cannot be more than 100.');
        if ($ceil === false || $limit === false) return $this->_json(false, 'Enter valid amounts for the wage ceiling and gross limit.');
        if ($key !== '') {
            if (!isset(Payroll_model::$keys[$key]))         return $this->_json(false, 'Select a valid statutory type.');
            if (Payroll_model::$keys[$key] !== $type)        return $this->_json(false, Payroll_model::$key_labels[$key] . ' must be of type ' . Payroll_model::$types[Payroll_model::$keys[$key]] . '.');
            if ($this->Pay->component_key_exists($key, $id)) return $this->_json(false, 'Another component is already marked as ' . Payroll_model::$key_labels[$key] . '.');
        }
        if ($this->Pay->component_name_exists($name, $id)) return $this->_json(false, 'A component with this name already exists.');

        $ok = $this->Pay->save_component($id, array('pcName' => $name, 'pcShort' => $short, 'pcType' => $type, 'pcCalc' => $calc,
            'pcValue' => $value, 'pcCeiling' => $calc === 'FIXED' ? null : $ceil, 'pcGrossLimit' => $type === 'EARNING' ? null : $limit,
            'pcProrate' => $this->input->post('prorate') ? 1 : 0, 'pcWage' => $type === 'EARNING' && $this->input->post('wage') ? 1 : 0,
            'pcDefault' => $this->input->post('default') ? 1 : 0, 'pcKey' => $key !== '' ? $key : null,
            'pcOrder' => (int)$this->input->post('order'), 'pcActive' => $this->input->post('active') ? 1 : 0));
        return $this->_json($ok, $ok ? 'Component saved. Draft payrolls were recalculated.' : 'Could not save.');
    }

    public function pay_component_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('pay_components', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
        $id = (int)$this->input->post('id');
        if ($this->Pay->component_in_use($id)) return $this->_json(false, 'This component is used in salary structures or payslips. Make it inactive instead.');
        $ok = $this->Pay->delete_component($id);
        return $this->_json($ok, $ok ? 'Component deleted.' : 'Delete failed.');
    }

    /* ================================================================
       SALARY STRUCTURE
       ================================================================ */
    public function pay_staff()
    {
        $perm = $this->_perm('pay_staff');
        if (empty($perm['can_view'])) return $this->_deny();
        $f = array('q' => mb_substr(trim((string)$this->input->get('q')), 0, 60),
                   'category' => isset(Payroll_model::$categories[$this->input->get('category')]) ? $this->input->get('category') : '',
                   'active' => in_array($this->input->get('active'), array('0', '1'), true) ? $this->input->get('active') : '');
        $comps = $this->Pay->components(true);
        $all = $this->Pay->all_staff_values();
        $staff = $this->Pay->list_staff($f);
        foreach ($staff as $s) {
            $c = $this->Pay->calculate($s->peBasic, $comps, isset($all[$s->peId]) ? $all[$s->peId] : array(), 30, 0);
            $s->gross = $c['gross']; $s->deduction = $c['deduction']; $s->net = $c['net']; $s->employer = $c['employer'];
        }
        $this->_page('pay_staff', array('staff' => $staff, 'f' => $f, 'perm' => $perm, 'components' => $comps,
            'unlinked' => $this->Pay->unlinked_employees()), 'Salary Structure', 'PAY_STAFF');
    }

    public function pay_staff_get()
    {
        if (!$this->_can('pay_staff', 'can_view')) return $this->_json(false, 'You do not have permission to do this.');
        $s = $this->Pay->get_staff((int)$this->input->get('id'));
        if (!$s) return $this->_json(false, 'Staff not found.');
        return $this->_json(true, '', array('staff' => $s, 'values' => (object)$this->Pay->staff_values($s->peId)));
    }

    /* reads basic + component values from the post; returns [basic, values] or [false, message] */
    private function _structure_input()
    {
        $basic = $this->_money($this->input->post('basic'));
        if ($basic === false) return array(false, 'Enter a valid basic pay.');
        $comps = array();
        foreach ($this->Pay->components(true) as $c) $comps[(int)$c->pcId] = $c;
        $values = array();
        $ids = (array)$this->input->post('comp'); $vals = (array)$this->input->post('val');
        foreach ($ids as $i => $pcId) {
            $pcId = (int)$pcId;
            if (!isset($comps[$pcId])) continue;
            $v = $this->_money(isset($vals[$i]) ? $vals[$i] : '');
            if ($v === false) return array(false, 'Enter a valid value for ' . $comps[$pcId]->pcName . '.');
            if ($comps[$pcId]->pcCalc !== 'FIXED' && $v > 100) return array(false, $comps[$pcId]->pcName . ': a percentage cannot be more than 100.');
            $values[$pcId] = $v;
        }
        return array($basic, $values);
    }

    public function pay_staff_preview()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        list($basic, $values) = $this->_structure_input();
        if ($basic === false) return $this->_json(false, $values);
        $c = $this->Pay->calculate($basic, $this->Pay->components(true), $values, 30, 0);
        return $this->_json(true, '', array('calc' => $c));
    }

    public function pay_staff_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id = (int)$this->input->post('id');
        if (!$this->_can('pay_staff', $id ? 'can_edit' : 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        $old = $id ? $this->Pay->get_staff($id) : null;
        if ($id && !$old) return $this->_json(false, 'Staff not found.');

        $name  = $this->_clean($this->input->post('name'), 100);
        $code  = strtoupper($this->_clean($this->input->post('code'), 20));
        $phone = preg_replace('/\D/', '', (string)$this->input->post('phone'));
        $cat   = (string)$this->input->post('category');
        $join  = trim((string)$this->input->post('join_date'));
        $leave = trim((string)$this->input->post('leave_date'));
        $mode  = (string)$this->input->post('pay_mode');
        $emId  = (int)$this->input->post('em_id');
        $ifsc  = strtoupper($this->_clean($this->input->post('ifsc'), 15));
        $pan   = strtoupper($this->_clean($this->input->post('pan'), 10));
        $acct  = preg_replace('/\s+/', '', (string)$this->input->post('account_no'));
        $active = $this->input->post('active') ? 1 : 0;

        if ($name === '') return $this->_json(false, 'Enter the staff name.');
        if ($phone !== '' && !preg_match('/^\d{10}$/', $phone)) return $this->_json(false, 'Phone must be 10 digits.');
        if (!isset(Payroll_model::$categories[$cat])) return $this->_json(false, 'Select the staff category.');
        if (!in_array($mode, Payroll_model::$modes, true)) return $this->_json(false, 'Select how the salary is paid.');
        if ($join !== '' && !$this->_date($join))   return $this->_json(false, 'Enter a valid joining date.');
        if ($leave !== '' && !$this->_date($leave)) return $this->_json(false, 'Enter a valid relieving date.');
        if ($join !== '' && $leave !== '' && $leave < $join) return $this->_json(false, 'Relieving date cannot be before the joining date.');
        if ($code !== '' && $this->Pay->staff_code_exists($code, $id)) return $this->_json(false, 'Another staff member has this employee code.');
        if ($emId && $this->Pay->employee_linked($emId, $id)) return $this->_json(false, 'This login is already linked to another staff member.');
        if ($mode === 'Bank' && $active && ($acct === '' || $ifsc === '')) return $this->_json(false, 'Enter the bank account number and IFSC for bank payment.');
        if ($acct !== '' && !preg_match('/^[0-9A-Za-z]{6,30}$/', $acct)) return $this->_json(false, 'Enter a valid bank account number.');
        if ($ifsc !== '' && !preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifsc)) return $this->_json(false, 'IFSC must be 11 characters, like SBIN0001234.');
        if ($pan !== '' && !preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]$/', $pan)) return $this->_json(false, 'PAN must be like ABCDE1234F.');

        list($basic, $values) = $this->_structure_input();
        if ($basic === false) return $this->_json(false, $values);
        if ($active && $basic <= 0) return $this->_json(false, 'Enter the basic pay, or untick "On payroll".');

        $data = array('peName' => $name, 'peCode' => $code !== '' ? $code : null, 'pePhone' => $phone ?: null, 'peCategory' => $cat,
            'peDesignation' => $this->_clean($this->input->post('designation'), 80) ?: null,
            'peJoinDate' => $join ?: null, 'peLeaveDate' => $leave ?: null, 'peBasic' => $basic, 'pePayMode' => $mode,
            'peBankName' => $this->_clean($this->input->post('bank_name'), 100) ?: null, 'peAccountNo' => $acct ?: null, 'peIfsc' => $ifsc ?: null,
            'pePan' => $pan ?: null, 'peUan' => $this->_clean($this->input->post('uan'), 20) ?: null,
            'peEsiNo' => $this->_clean($this->input->post('esi_no'), 20) ?: null, 'peActive' => $active);
        if (!$id) $data['peEmId'] = $emId ?: null;
        $saved = $this->Pay->save_staff($id, $data, $values);
        return $this->_json((bool)$saved, $saved ? 'Salary structure of ' . $name . ' saved.' : 'Could not save.');
    }

    public function pay_staff_import()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('pay_staff', 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        $n = $this->Pay->import_employees();
        return $this->_json(true, $n ? $n . ' employee(s) added. Set the salary of each and tick "On payroll".' : 'Every employee is already on the list.');
    }

    public function pay_staff_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('pay_staff', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
        $id = (int)$this->input->post('id');
        if (!$this->Pay->get_staff($id)) return $this->_json(false, 'Staff not found.');
        if ($this->Pay->staff_has_history($id)) return $this->_json(false, 'This staff member has payslips or advances. Set a relieving date and untick "On payroll" instead.');
        $ok = $this->Pay->delete_staff($id);
        return $this->_json($ok, $ok ? 'Removed from payroll.' : 'Delete failed.');
    }

    /* ================================================================
       MONTHLY PAYROLL
       ================================================================ */
    public function payroll()
    {
        $perm = $this->_perm('payroll');
        if (empty($perm['can_view'])) return $this->_deny();
        $this->_page('payroll_runs', array('runs' => $this->Pay->list_runs(), 'perm' => $perm,
            'next' => date('Y-m'), 'on_payroll' => count($this->Pay->eligible_staff(date('Y-m-01')))), 'Monthly Payroll', 'PAY_RUNS');
    }

    public function payroll_create()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        $month = $this->_month($this->input->post('month'));
        if (!$month) return $this->_json(false, 'Select the month.');
        if ($month > date('Y-m-01', strtotime('first day of next month'))) return $this->_json(false, 'You can prepare payroll up to next month only.');
        $r = $this->Pay->create_run($month, $this->_user());
        return $this->_json($r['ok'], $r['msg'], array('url' => $r['ok'] ? site_url('payroll/' . $r['id']) : ''));
    }

    public function payroll_run($id)
    {
        $perm = $this->_perm('payroll');
        if (empty($perm['can_view'])) return $this->_deny();
        $run = $this->Pay->get_run($id);
        if (!$run) show_404();
        $missing = 0;
        if ($run->prStatus === 'DRAFT') {
            $have = array(); foreach ($this->Pay->slips($id) as $s) $have[(int)$s->psPeId] = 1;
            foreach ($this->Pay->eligible_staff($run->prMonth) as $pe) if (!isset($have[(int)$pe->peId])) $missing++;
        }
        $this->_page('payroll_run', array('run' => $run, 'slips' => $this->Pay->slips($id), 'batches' => $this->Pay->batches($id),
            'perm' => $perm, 'missing' => $missing, 'modes' => Payroll_model::$pay_modes), 'Payroll ' . Payroll_model::month_label($run->prMonth), 'PAY_RUNS');
    }

    public function payroll_slip()
    {
        if (!$this->_can('payroll', 'can_view')) return $this->_json(false, 'You do not have permission to do this.');
        $s = $this->Pay->get_slip((int)$this->input->get('id'));
        if (!$s) return $this->_json(false, 'Payslip not found.');
        return $this->_json(true, '', array('slip' => $s));
    }

    public function payroll_slip_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');
        $psId = (int)$this->input->post('id');
        $s = $this->Pay->get_slip($psId);
        if (!$s) return $this->_json(false, 'Payslip not found.');
        $lop = trim((string)$this->input->post('lop'));
        if ($lop === '') $lop = '0';
        if (!preg_match('/^\d{1,2}(\.[05])?$/', $lop) || (float)$lop > (int)$s->psDays) return $this->_json(false, 'Loss-of-pay days must be 0 to ' . (int)$s->psDays . ' (half days allowed).');
        $ee = $this->_money($this->input->post('extra_earn'));
        $ed = $this->_money($this->input->post('extra_ded'));
        if ($ee === false || $ed === false) return $this->_json(false, 'Enter valid amounts.');
        $r = $this->Pay->save_slip($psId, (float)$lop, $ee, $this->_clean($this->input->post('extra_earn_note'), 100), $ed,
                                   $this->_clean($this->input->post('extra_ded_note'), 100), $this->_clean($this->input->post('remarks'), 255));
        return $this->_json($r['ok'], $r['msg']);
    }

    public function payroll_slip_remove()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
        $ok = $this->Pay->remove_slip((int)$this->input->post('id'));
        return $this->_json($ok, $ok ? 'Payslip removed from this month.' : 'Only payslips of a draft payroll can be removed.');
    }

    public function payroll_recalc()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');
        $id = (int)$this->input->post('id');
        $added = $this->input->post('add_missing') ? $this->Pay->add_missing($id) : 0;
        $ok = $this->Pay->recalc_run($id);
        return $this->_json($ok, $ok ? 'Recalculated from the current salary structures' . ($added ? '; ' . $added . ' payslip(s) added' : '') . '.'
                                     : 'Only a draft payroll can be recalculated.');
    }

    public function payroll_load_attendance()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');
        $r = $this->Pay->load_attendance_lop((int)$this->input->post('id'), $this->input->post('unmarked') === '1');
        return $this->_json($r['ok'], $r['msg']);
    }

    public function payroll_approve()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');
        $r = $this->Pay->approve_run((int)$this->input->post('id'), $this->_user());
        return $this->_json($r['ok'], $r['msg']);
    }

    public function payroll_reopen()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_edit')) return $this->_json(false, 'You do not have permission to do this.');
        $r = $this->Pay->reopen_run((int)$this->input->post('id'));
        return $this->_json($r['ok'], $r['msg']);
    }

    public function payroll_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
        $ok = $this->Pay->delete_run((int)$this->input->post('id'));
        return $this->_json($ok, $ok ? 'Payroll deleted.' : 'Only a draft payroll that was never approved can be deleted.');
    }

    public function payroll_pay()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        $date = $this->_date($this->input->post('date'));
        $mode = (string)$this->input->post('mode');
        if (!$date) return $this->_json(false, 'Enter the payment date.');
        if ($date > date('Y-m-d')) return $this->_json(false, 'Payment date cannot be in the future.');
        if (!in_array($mode, Payroll_model::$pay_modes, true)) return $this->_json(false, 'Select the payment mode.');
        $amounts = array();
        $ids = (array)$this->input->post('slip'); $amts = (array)$this->input->post('amount');
        foreach ($ids as $i => $psId) {
            $a = $this->_money(isset($amts[$i]) ? $amts[$i] : '', false);
            if ($a === false) return $this->_json(false, 'Enter a valid amount for every selected staff member.');
            $amounts[(int)$psId] = $a;
        }
        $r = $this->Pay->pay((int)$this->input->post('id'), $amounts, $date, $mode, $this->_clean($this->input->post('ref'), 60),
                             $this->_clean($this->input->post('remarks'), 255), $this->_user());
        return $this->_json($r['ok'], $r['msg']);
    }

    public function payroll_payment_cancel()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('payroll', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
        $reason = $this->_clean($this->input->post('reason'), 255);
        if ($reason === '') return $this->_json(false, 'Enter the reason.');
        $ok = $this->Pay->cancel_batch((int)$this->input->post('id'), $reason);
        return $this->_json($ok, $ok ? 'Payment cancelled.' : 'Could not cancel.');
    }

    /* printable payslip(s): payslip/<psId> or payslips/<prId> */
    public function payslip($psId)
    {
        if (!$this->_can('payroll', 'can_view')) return $this->_deny();
        $s = $this->Pay->get_slip($psId);
        if (!$s) show_404();
        $this->load->view('payslip_print', array('slips' => $this->Pay->slips_full($s->psPrId, $psId)));
    }

    public function payslips($prId)
    {
        if (!$this->_can('payroll', 'can_view')) return $this->_deny();
        if (!$this->Pay->get_run($prId)) show_404();
        $this->load->view('payslip_print', array('slips' => $this->Pay->slips_full($prId)));
    }

    /* ================================================================
       SALARY ADVANCES
       ================================================================ */
    public function pay_advances()
    {
        $perm = $this->_perm('pay_advances');
        if (empty($perm['can_view'])) return $this->_deny();
        $f = array('staff' => (int)$this->input->get('staff'), 'open' => $this->input->get('show') === 'all' ? 0 : 1,
                   'cancelled' => $this->input->get('show') === 'all' ? 1 : 0);
        $this->_page('pay_advances', array('advances' => $this->Pay->list_advances($f), 'f' => $f, 'perm' => $perm,
            'staff' => $this->Pay->list_staff(array('active' => '1')), 'all_staff' => $this->Pay->list_staff(),
            'modes' => Payroll_model::$pay_modes), 'Salary Advances', 'PAY_ADVANCES');
    }

    public function pay_advance_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('pay_advances', 'can_add')) return $this->_json(false, 'You do not have permission to do this.');
        $pe   = $this->Pay->get_staff((int)$this->input->post('staff'));
        $date = $this->_date($this->input->post('date'));
        $amt  = $this->_money($this->input->post('amount'), false);
        $inst = $this->_money($this->input->post('installment'), false);
        $from = $this->_month($this->input->post('from_month'));
        $mode = (string)$this->input->post('mode');
        if (!$pe || !$pe->peActive) return $this->_json(false, 'Select a staff member who is on payroll.');
        if (!$date) return $this->_json(false, 'Enter the date the advance was given.');
        if ($date > date('Y-m-d')) return $this->_json(false, 'Date cannot be in the future.');
        if ($amt === false || $amt <= 0) return $this->_json(false, 'Enter the advance amount.');
        if ($inst === false || $inst <= 0 || $inst > $amt) return $this->_json(false, 'Monthly recovery must be more than 0 and not more than the advance.');
        if (!$from) return $this->_json(false, 'Select the month recovery starts from.');
        if ($from < date('Y-m-01', strtotime($date))) return $this->_json(false, 'Recovery cannot start before the month the advance is given.');
        if (!in_array($mode, Payroll_model::$pay_modes, true)) return $this->_json(false, 'Select the payment mode.');
        $ok = $this->Pay->save_advance(array('padPeId' => (int)$pe->peId, 'padDate' => $date, 'padAmount' => $amt, 'padInstallment' => $inst,
            'padFromMonth' => $from, 'padMode' => $mode, 'padRef' => $this->_clean($this->input->post('ref'), 60) ?: null,
            'padRemarks' => $this->_clean($this->input->post('remarks'), 255) ?: null, 'padUser' => $this->_user()));
        return $this->_json($ok, $ok ? 'Advance of ' . number_format($amt, 2) . ' to ' . $pe->peName . ' saved. It will be recovered through payroll.' : 'Could not save.');
    }

    public function pay_advance_cancel()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        if (!$this->_can('pay_advances', 'can_delete')) return $this->_json(false, 'You do not have permission to do this.');
        $reason = $this->_clean($this->input->post('reason'), 255);
        if ($reason === '') return $this->_json(false, 'Enter the reason.');
        $r = $this->Pay->cancel_advance((int)$this->input->post('id'), $reason);
        return $this->_json($r['ok'], $r['msg']);
    }
}
