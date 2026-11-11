<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Accounts: Vouchers (receipt / payment / contra / journal), Chart of Accounts, Banking, Settings.
 * Fee and store transactions are posted automatically (Accounts_model::sync_sources) when these pages open.
 */
class AccountsController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Accounts_model', 'Acc');
        $this->Acc->require_schema();
        $this->load->model('Subject_Model');
        $this->load->helper('fee');
    }

    /* ================================================================
       helpers
       ================================================================ */
    private function _perm($link)
    {
        $role = (int)$this->session->userdata('user_role_id');
        $perm = $this->Subject_Model->get_permissions($role, $this->Subject_Model->get_menu_id_by_link($link));
        return is_array($perm) ? $perm : array();
    }

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

    private function _user()      { return (int)$this->session->userdata('id'); }
    private function _clean($v)   { return trim(preg_replace('/\s+/', ' ', (string)$v)); }
    private function _post_only() { return $this->input->method() === 'post'; }

    private function _date($v)
    {
        $d = DateTime::createFromFormat('Y-m-d', (string)$v);
        return ($d && $d->format('Y-m-d') === $v) ? $v : '';
    }

    private function _num($v)
    {
        $v = trim((string)$v);
        if ($v === '') return null;
        if (!preg_match('/^\d{1,11}(\.\d{1,2})?$/', $v)) return false;
        return round((float)$v, 2);
    }

    /* ledgers for <select>s, grouped */
    private function _ledger_options($active_only = true)
    {
        $out = array();
        foreach ($this->Acc->get_ledgers($active_only) as $l) {
            $out[] = array('id' => (int)$l->alId, 'name' => $l->alName, 'group' => $l->agName, 'type' => $l->alType, 'nature' => $l->agNature);
        }
        return $out;
    }

    /* ================================================================
       VOUCHERS
       ================================================================ */
    public function acc_vouchers()
    {
        $perm = $this->_perm('acc_vouchers');
        if (empty($perm['can_view'])) return $this->_deny();
        $sync = $this->Acc->sync_sources();

        $f = array(
            'from'   => $this->_date((string)$this->input->get('from')) ?: date('Y-m-01'),
            'to'     => $this->_date((string)$this->input->get('to')) ?: date('Y-m-d'),
            'type'   => isset(Accounts_model::$types[(string)$this->input->get('type')]) ? (string)$this->input->get('type') : '',
            'ledger' => (int)$this->input->get('ledger'),
            'auto'   => in_array((string)$this->input->get('auto'), array('0', '1'), true) ? (string)$this->input->get('auto') : '',
            'q'      => trim((string)$this->input->get('q')),
        );
        $this->_page('acc_vouchers', array(
            'rows' => $this->Acc->list_vouchers($f), 'f' => $f, 'perm' => $perm, 'sync' => $sync,
            'ledgers' => $this->Acc->get_ledgers(), 'types' => Accounts_model::$types,
        ), 'Vouchers', 'ACC_VOUCHERS');
    }

    public function acc_voucher_new()
    {
        $perm = $this->_perm('acc_vouchers');
        if (empty($perm['can_add'])) return $this->_deny();
        $type = strtoupper((string)$this->input->get('type'));
        if (!in_array($type, array('PAYMENT', 'RECEIPT', 'CONTRA', 'JOURNAL'), true)) $type = 'PAYMENT';
        $this->_voucher_form($type, null);
    }

    public function acc_voucher_edit($id = 0)
    {
        $perm = $this->_perm('acc_vouchers');
        if (empty($perm['can_edit'])) return $this->_deny();
        $v = $this->Acc->get_voucher((int)$id);
        if (!$v) show_404();
        if ($v->avSource || $v->avCancelled || !in_array($v->avType, array('PAYMENT', 'RECEIPT', 'CONTRA', 'JOURNAL'), true)) {
            redirect('acc_voucher/' . $v->avId);
            return;
        }
        $this->_voucher_form($v->avType, $v);
    }

    private function _voucher_form($type, $v)
    {
        $this->_page('acc_voucher_form', array(
            'type' => $type, 'v' => $v, 'ledgers' => $this->_ledger_options(), 'types' => Accounts_model::$types,
            'books_start' => $this->Acc->books_start(),
            'default_cash' => (int)$this->Acc->setting('cash_ledger'), 'default_bank' => (int)$this->Acc->setting('bank_ledger'),
        ), ($v ? 'Edit ' : 'New ') . Accounts_model::$types[$type]['label'] . ' Voucher', 'ACC_VOUCHERS');
    }

    /**
     * POST type, id, date, narration, ref
     *   PAYMENT / RECEIPT : account (cash / bank), ledger[], amount[], line_note[]
     *   CONTRA            : account (from), to, amount
     *   JOURNAL           : ledger[], dr[], cr[], line_note[]
     */
    public function acc_voucher_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('acc_vouchers');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');

        $type = (string)$this->input->post('type');
        if (!in_array($type, array('PAYMENT', 'RECEIPT', 'CONTRA', 'JOURNAL'), true)) return $this->_json(false, 'Invalid voucher type.');
        if ($id) {
            $old = $this->Acc->get_voucher($id);
            if (!$old || $old->avSource || $old->avCancelled) return $this->_json(false, 'This voucher cannot be edited.');
            $type = $old->avType;
        }
        $date = $this->_date((string)$this->input->post('date'));
        $narr = trim((string)$this->input->post('narration'));
        $ref  = $this->_clean($this->input->post('ref'));
        if ($date === '')                         return $this->_json(false, 'Enter a valid date.');
        if ($date < $this->Acc->books_start())    return $this->_json(false, 'The date is before the books start date (' . date('d-m-Y', strtotime($this->Acc->books_start())) . ').');
        if ($date > date('Y-m-d', strtotime('+1 year'))) return $this->_json(false, 'The date is too far in the future.');
        if (mb_strlen($narr) > 500 || mb_strlen($ref) > 60) return $this->_json(false, 'Narration or reference is too long.');

        $ledgers = array();
        foreach ($this->Acc->get_ledgers() as $l) $ledgers[(int)$l->alId] = $l;
        $isCB = function ($lid) use ($ledgers) { return isset($ledgers[$lid]) && in_array($ledgers[$lid]->alType, array('CASH', 'BANK'), true); };

        $lines = array();
        if ($type === 'JOURNAL') {
            $ids = (array)$this->input->post('ledger'); $drs = (array)$this->input->post('dr'); $crs = (array)$this->input->post('cr'); $notes = (array)$this->input->post('line_note');
            foreach ($ids as $i => $lid) {
                $lid = (int)$lid;
                $d = $this->_num(isset($drs[$i]) ? $drs[$i] : ''); $c = $this->_num(isset($crs[$i]) ? $crs[$i] : '');
                if (!$lid && !$d && !$c) continue;
                if (!isset($ledgers[$lid]))    return $this->_json(false, 'Select the account on every line.');
                if ($d === false || $c === false) return $this->_json(false, 'Amounts must be valid numbers.');
                if ($d && $c)                  return $this->_json(false, 'A line can have a debit or a credit, not both.');
                if (!$d && !$c)                return $this->_json(false, 'Enter the amount for ' . $ledgers[$lid]->alName . '.');
                $lines[] = array('ledger' => $lid, 'dr' => (float)$d, 'cr' => (float)$c, 'narration' => $this->_clean(isset($notes[$i]) ? $notes[$i] : ''));
            }
            if (count($lines) < 2) return $this->_json(false, 'A journal needs at least one debit and one credit line.');
        } elseif ($type === 'CONTRA') {
            $from = (int)$this->input->post('account'); $to = (int)$this->input->post('to'); $amt = $this->_num($this->input->post('amount'));
            if (!$isCB($from) || !$isCB($to)) return $this->_json(false, 'Contra is between cash and bank accounts only.');
            if ($from === $to)                return $this->_json(false, 'Choose two different accounts.');
            if (!$amt)                        return $this->_json(false, 'Enter the amount.');
            $lines[] = array('ledger' => $to, 'dr' => $amt, 'cr' => 0);
            $lines[] = array('ledger' => $from, 'dr' => 0, 'cr' => $amt);
        } else {
            $acct = (int)$this->input->post('account');
            if (!$isCB($acct)) return $this->_json(false, 'Select the cash / bank account.');
            $ids = (array)$this->input->post('ledger'); $amts = (array)$this->input->post('amount'); $notes = (array)$this->input->post('line_note');
            $total = 0;
            foreach ($ids as $i => $lid) {
                $lid = (int)$lid; $a = $this->_num(isset($amts[$i]) ? $amts[$i] : '');
                if (!$lid && !$a) continue;
                if (!isset($ledgers[$lid])) return $this->_json(false, 'Select the account on every line.');
                if ($lid === $acct)         return $this->_json(false, 'A line cannot use the same account as "' . ($type === 'PAYMENT' ? 'Paid from' : 'Received into') . '".');
                if (!$a)                    return $this->_json(false, 'Enter the amount for ' . $ledgers[$lid]->alName . '.');
                $note = $this->_clean(isset($notes[$i]) ? $notes[$i] : '');
                $lines[] = $type === 'PAYMENT' ? array('ledger' => $lid, 'dr' => $a, 'cr' => 0, 'narration' => $note)
                                               : array('ledger' => $lid, 'dr' => 0, 'cr' => $a, 'narration' => $note);
                $total += $a;
            }
            if (!$lines) return $this->_json(false, 'Add at least one line.');
            $lines[] = $type === 'PAYMENT' ? array('ledger' => $acct, 'dr' => 0, 'cr' => $total) : array('ledger' => $acct, 'dr' => $total, 'cr' => 0);
        }
        foreach ($lines as $l) {
            if (!$ledgers[$l['ledger']]->alActive) return $this->_json(false, $ledgers[$l['ledger']]->alName . ' is inactive.');
        }

        $res = $this->Acc->save_voucher($id, $type, $date, $narr, $ref, $lines, $this->_user());
        return $this->_json($res['ok'], $res['msg'], array('id' => $res['ok'] ? $res['id'] : 0));
    }

    public function acc_voucher($id = 0)
    {
        $perm = $this->_perm('acc_vouchers');
        if (empty($perm['can_view'])) return $this->_deny();
        $v = $this->Acc->get_voucher((int)$id);
        if (!$v) show_404();
        $this->_page('acc_voucher_view', array('v' => $v, 'perm' => $perm, 'types' => Accounts_model::$types,
            'user_name' => $this->_user_name($v->avUser)), 'Voucher ' . $v->avNo, 'ACC_VOUCHERS');
    }

    private function _user_name($id)
    {
        if (!$id) return 'Automatic';
        $e = $this->db->select('emName')->where('emUserId', (int)$id)->get('employee_master')->row();
        if ($e && $e->emName !== '') return $e->emName;
        $u = $this->db->select('name')->where('user_id', (int)$id)->get('user_login')->row();
        return $u ? $u->name : '';
    }

    public function acc_voucher_cancel()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('acc_vouchers');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to cancel vouchers.');
        $v = $this->Acc->get_voucher((int)$this->input->post('id'));
        if (!$v || $v->avCancelled) return $this->_json(false, 'Voucher not found or already cancelled.');
        if ($v->avSource) return $this->_json(false, 'This voucher was posted from ' . Accounts_model::$sources[$v->avSource] . '. Cancel it there instead.');
        $why = $this->_clean($this->input->post('reason'));
        if ($why === '' || mb_strlen($why) > 255) return $this->_json(false, 'Enter the reason for cancelling.');
        $ok = $this->Acc->cancel_voucher($v->avId, $why);
        return $this->_json($ok, $ok ? 'Voucher ' . $v->avNo . ' cancelled.' : 'Could not cancel.');
    }

    /* ================================================================
       CHART OF ACCOUNTS
       ================================================================ */
    public function acc_ledgers()
    {
        $perm = $this->_perm('acc_ledgers');
        if (empty($perm['can_view'])) return $this->_deny();
        $this->Acc->sync_sources();
        $today = date('Y-m-d');
        $this->_page('acc_ledgers', array(
            'groups' => $this->Acc->get_groups(), 'balances' => $this->Acc->balances($today, $today), 'ledgers' => $this->Acc->get_ledgers(),
            'perm' => $perm, 'natures' => Accounts_model::$natures, 'opening_diff' => $this->Acc->opening_difference(),
        ), 'Chart of Accounts', 'ACC_LEDGERS');
    }

    public function acc_ledger_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('acc_ledgers');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        $old = $id ? $this->Acc->get_ledger($id) : null;
        if ($id && !$old) return $this->_json(false, 'Account not found.');

        $name  = $this->_clean($this->input->post('name'));
        $group = $this->Acc->get_group((int)$this->input->post('group'));
        $code  = strtoupper($this->_clean($this->input->post('code')));
        $type  = (string)$this->input->post('type');
        $open  = $this->_num($this->input->post('opening'));
        $otype = (string)$this->input->post('opening_type') === 'Cr' ? 'Cr' : 'Dr';
        if ($name === '' || mb_strlen($name) > 150)       return $this->_json(false, 'Enter the account name.');
        if ($this->Acc->ledger_name_exists($name, $id))   return $this->_json(false, 'An account with this name already exists.');
        if (!$group)                                      return $this->_json(false, 'Select the group.');
        if (!in_array($type, array('GENERAL', 'CASH', 'BANK'), true)) return $this->_json(false, 'Select the account type.');
        if (in_array($type, array('CASH', 'BANK'), true) && $group->agNature !== 'ASSET') return $this->_json(false, 'Cash and bank accounts must be in an Assets group.');
        if ($open === false)                              return $this->_json(false, 'Opening balance must be a valid number.');
        if (mb_strlen($code) > 20)                        return $this->_json(false, 'Code must be 20 characters or fewer.');
        if ($old && $old->alSystemKey && in_array($old->alSystemKey, array('CASH', 'BANK'), true) && $type !== $old->alType) {
            return $this->_json(false, 'The type of this system account cannot change.');
        }

        $row = array(
            'alName' => $name, 'alAgId' => $group->agId, 'alCode' => $code ?: null, 'alType' => $type,
            'alOpening' => (float)$open, 'alOpeningType' => $otype,
            'alBankName' => $type === 'BANK' ? mb_substr($this->_clean($this->input->post('bank_name')), 0, 100) : null,
            'alAccountNo' => $type === 'BANK' ? mb_substr($this->_clean($this->input->post('account_no')), 0, 40) : null,
            'alIfsc' => $type === 'BANK' ? strtoupper(mb_substr($this->_clean($this->input->post('ifsc')), 0, 20)) : null,
            'alPhone' => mb_substr($this->_clean($this->input->post('phone')), 0, 20),
            'alNotes' => mb_substr($this->_clean($this->input->post('notes')), 0, 255),
            'alActive' => $this->input->post('active') ? 1 : 0,
        );
        $ok = $this->Acc->save_ledger($id, $row);
        return $this->_json((bool)$ok, $ok ? 'Account saved.' : 'Could not save.');
    }

    public function acc_ledger_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('acc_ledgers');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to do this.');
        $l = $this->Acc->get_ledger((int)$this->input->post('id'));
        if (!$l) return $this->_json(false, 'Account not found.');
        if ($l->alSystemKey) return $this->_json(false, 'This account is used by automatic posting and cannot be deleted.');
        if ($this->Acc->ledger_in_use($l->alId)) return $this->_json(false, 'This account has vouchers. Mark it inactive instead.');
        $ok = $this->Acc->delete_ledger($l->alId);
        return $this->_json($ok, $ok ? 'Account deleted.' : 'Delete failed.');
    }

    public function acc_group_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('acc_ledgers');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        $name   = $this->_clean($this->input->post('name'));
        $parent = (int)$this->input->post('parent');
        $nature = (string)$this->input->post('nature');
        $old    = $id ? $this->Acc->get_group($id) : null;
        if ($id && !$old)                                 return $this->_json(false, 'Group not found.');
        if ($name === '' || mb_strlen($name) > 100)       return $this->_json(false, 'Enter the group name.');
        if ($this->Acc->group_name_exists($name, $id))    return $this->_json(false, 'A group with this name already exists.');
        if ($parent) {
            $p = $this->Acc->get_group($parent);
            if (!$p || $parent === $id || $p->agParentId) return $this->_json(false, 'Choose a main group as the parent (two levels only).');
            $nature = $p->agNature;
        }
        if (!isset(Accounts_model::$natures[$nature]))    return $this->_json(false, 'Select the nature.');
        if ($old && $old->agKey) { $nature = $old->agNature; $parent = (int)$old->agParentId; }   // system groups keep their place
        $ok = $this->Acc->save_group($id, array('agName' => $name, 'agNature' => $nature, 'agParentId' => $parent ?: null));
        return $this->_json($ok, $ok ? 'Group saved.' : 'Could not save.');
    }

    public function acc_group_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('acc_ledgers');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to do this.');
        $g = $this->Acc->get_group((int)$this->input->post('id'));
        if (!$g) return $this->_json(false, 'Group not found.');
        if ($g->agKey) return $this->_json(false, 'Standard groups cannot be deleted.');
        if ($this->Acc->group_in_use($g->agId)) return $this->_json(false, 'Move or delete the accounts / sub-groups in this group first.');
        $ok = $this->Acc->delete_group($g->agId);
        return $this->_json($ok, $ok ? 'Group deleted.' : 'Delete failed.');
    }

    /* ================================================================
       BANKING
       ================================================================ */
    public function acc_banking()
    {
        $perm = $this->_perm('acc_banking');
        if (empty($perm['can_view'])) return $this->_deny();
        $this->Acc->sync_sources();
        $asOf = $this->_date((string)$this->input->get('as_of')) ?: date('Y-m-d');
        $accounts = array();
        foreach ($this->Acc->get_ledgers(false, array('CASH', 'BANK')) as $l) {
            $l->balance = $this->Acc->ledger_balance($l->alId, $asOf);
            $l->uncleared = $l->alType === 'BANK' ? count($this->Acc->unreconciled($l->alId, $asOf)) : 0;
            $accounts[] = $l;
        }
        $this->_page('acc_banking', array('accounts' => $accounts, 'as_of' => $asOf, 'perm' => $perm,
            'ledgers' => $this->_ledger_options()), 'Banking', 'ACC_BANKING');
    }

    public function acc_reconcile($alId = 0)
    {
        $perm = $this->_perm('acc_banking');
        if (empty($perm['can_view'])) return $this->_deny();
        $l = $this->Acc->get_ledger((int)$alId);
        if (!$l || $l->alType !== 'BANK') show_404();
        $asOf = $this->_date((string)$this->input->get('as_of')) ?: date('Y-m-d');
        $rows = $this->Acc->unreconciled($l->alId, $asOf);
        $this->_page('acc_reconcile', array('l' => $l, 'as_of' => $asOf, 'rows' => $rows, 'perm' => $perm,
            'book' => $this->Acc->ledger_balance($l->alId, $asOf)), 'Reconcile ' . $l->alName, 'ACC_BANKING');
    }

    public function acc_reconcile_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('acc_banking');
        if (empty($perm['can_edit']) && empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        $l = $this->Acc->get_ledger((int)$this->input->post('ledger'));
        if (!$l || $l->alType !== 'BANK') return $this->_json(false, 'Bank account not found.');
        $raw = (array)$this->input->post('clear');
        $dates = array();
        foreach ($raw as $aeId => $d) {
            $d = trim((string)$d);
            if ($d !== '' && $this->_date($d) === '') return $this->_json(false, 'Enter valid bank dates.');
            $dates[(int)$aeId] = $d;
        }
        $n = $this->Acc->save_clear_dates($l->alId, $dates);
        return $this->_json(true, $n ? $n . ' entr' . ($n === 1 ? 'y' : 'ies') . ' updated.' : 'No changes.');
    }

    /* ================================================================
       SETTINGS
       ================================================================ */
    public function acc_settings()
    {
        $perm = $this->_perm('acc_settings');
        if (empty($perm['can_view'])) return $this->_deny();
        $this->_page('acc_settings', array(
            'settings' => $this->Acc->settings(), 'cashbank' => $this->Acc->get_ledgers(true, array('CASH', 'BANK')),
            'pending' => $this->Acc->pending_counts(), 'posted' => $this->Acc->posted_counts(), 'perm' => $perm,
            'sources' => Accounts_model::$sources,
        ), 'Account Settings', 'ACC_SETTINGS');
    }

    public function acc_settings_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('acc_settings');
        if (empty($perm['can_edit']) && empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        $start = $this->_date((string)$this->input->post('books_start'));
        $cash  = $this->Acc->get_ledger((int)$this->input->post('cash_ledger'));
        $bank  = $this->Acc->get_ledger((int)$this->input->post('bank_ledger'));
        if ($start === '')                                       return $this->_json(false, 'Enter the books start date.');
        if (!$cash || !in_array($cash->alType, array('CASH', 'BANK'), true)) return $this->_json(false, 'Select the account for cash collections.');
        if (!$bank || !in_array($bank->alType, array('CASH', 'BANK'), true)) return $this->_json(false, 'Select the account for UPI / card / cheque / bank collections.');
        $this->Acc->set_setting('books_start', $start);
        $this->Acc->set_setting('cash_ledger', $cash->alId);
        $this->Acc->set_setting('bank_ledger', $bank->alId);
        $this->Acc->set_setting('auto_post', $this->input->post('auto_post') ? '1' : '0');
        return $this->_json(true, 'Settings saved.');
    }

    public function acc_sync()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('acc_settings');
        if (empty($perm['can_view'])) return $this->_json(false, 'You do not have permission to do this.');
        $was = $this->Acc->setting('auto_post', '1');
        $this->Acc->set_setting('auto_post', '1');
        $total = 0; $cancel = 0;
        for ($i = 0; $i < 20; $i++) {   // 500 per source per round
            $r = $this->Acc->sync_sources();
            if ($r['skipped']) break;
            $total += $r['posted']; $cancel += $r['cancelled'];
            if (!$r['posted']) break;
        }
        $this->Acc->set_setting('auto_post', $was);
        return $this->_json(true, $total . ' voucher(s) posted, ' . $cancel . ' cancelled.');
    }
}
