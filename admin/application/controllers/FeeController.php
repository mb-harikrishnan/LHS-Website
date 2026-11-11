<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fee module
 *   fee_master      Fee Master (name, amount, frequency, installments, fine ...)
 *   fee_allocation  allot a fee to the students of a class
 *   fee_collection  Fee Register: collect fees, dues, partial payments, fine
 *   fee_management  receipts list (print / cancel)
 */
class FeeController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Fee_model');
        $this->Fee_model->require_schema();
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
        $this->output->set_content_type('application/json')->set_output(json_encode(array_merge(array(
            'status' => (bool)$status,
            'msg'    => $msg,
            'csrf'   => $this->security->get_csrf_hash(),
        ), $extra)));
    }

    private function _deny_page()
    {
        $this->load->view('header');
        $this->load->view('no_permission');
        $this->load->view('footer');
    }

    private function _page($view, $data, $title, $active)
    {
        $this->load->view('header', array('pageTitle' => $title, 'activePage' => $active,
                                          'pageStylesheets' => array(base_url('assets/css/fee.css'))));
        $this->load->view($view, $data);
        $this->load->view('footer');
    }

    private function _user()
    {
        return (int)$this->session->userdata('id');
    }

    private function _valid_date($d)
    {
        $x = DateTime::createFromFormat('Y-m-d', (string)$d);
        return $x && $x->format('Y-m-d') === $d;
    }

    private function _money($v)
    {
        $v = trim((string)$v);
        if ($v === '') return null;
        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/', $v)) return false;
        return round((float)$v, 2);
    }

    /* ================================================================
       FEE MASTER
       ================================================================ */
    public function fee_master()
    {
        $perm = $this->_perm('fee_master');
        if (empty($perm['can_view'])) return $this->_deny_page();

        $amId = $this->Fee_model->get_am_id();
        $fees = $this->Fee_model->get_fees($amId);
        foreach ($fees as $f) $f->rate_usage = Fee_model::is_variable($f) ? $this->Fee_model->rate_usage($f->fmId) : array();
        $data = array(
            'fees'        => $fees,
            'classes'     => $this->Subject_Model->get_all_classes(),
            'years'       => $this->Fee_model->get_years(),
            'year'        => $this->Fee_model->get_year($amId),
            'am_id'       => $amId,
            'frequencies' => Fee_model::$frequencies,
            'fine_types'  => Fee_model::$fine_types,
            'amount_types'=> Fee_model::$amount_types,
            'perm'        => $perm,
        );
        $this->_page('fee_master', $data, 'Fee Master', 'FEE_MASTER');
    }

    public function fee_save()
    {
        if ($this->input->method() !== 'post') return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('fee_master');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');

        $amId = $this->Fee_model->get_am_id();
        if ($id) {
            $old = $this->Fee_model->get_fee($id);
            if (!$old) return $this->_json(false, 'Fee not found.');
            $amId = (int)$old->fmAmId;
        }

        $name   = trim(preg_replace('/\s+/', ' ', (string)$this->input->post('name')));
        $atype  = (string)$this->input->post('amount_type');
        $amount = $this->_money($this->input->post('amount'));
        if ($atype === 'VARIABLE' && $amount === null) $amount = 0.0;   // default amount is optional
        $freq   = (string)$this->input->post('frequency');
        $inst   = (int)$this->input->post('installments');
        $month  = (int)$this->input->post('start_month');
        $day    = (int)$this->input->post('due_day');
        $fType  = (string)$this->input->post('fine_type');
        $fAmt   = $this->_money($this->input->post('fine_amount'));
        $fGrace = (int)$this->input->post('fine_grace');
        $fMax   = $this->_money($this->input->post('fine_max'));
        $desc   = trim((string)$this->input->post('description'));

        if ($name === '' || mb_strlen($name) > 100)          return $this->_json(false, 'Enter a fee name (max 100 characters).');
        if ($this->Fee_model->fee_name_exists($name, $amId, $id)) return $this->_json(false, 'A fee with this name already exists for this academic year.');
        if (!isset(Fee_model::$amount_types[$atype]))        return $this->_json(false, 'Select the amount type.');
        if ($amount === null || $amount === false)           return $this->_json(false, 'Enter a valid amount.');
        if (!isset(Fee_model::$frequencies[$freq]))           return $this->_json(false, 'Select how the fee is collected.');
        $max = Fee_model::$frequencies[$freq]['max'];
        if ($inst < 1 || $inst > $max)                       return $this->_json(false, 'Installments must be between 1 and ' . $max . ' for this frequency.');
        if ($month < 1 || $month > 12)                       return $this->_json(false, 'Select the first installment month.');
        if ($day < 1 || $day > 28)                           return $this->_json(false, 'Due day must be between 1 and 28.');
        if (!isset(Fee_model::$fine_types[$fType]))          return $this->_json(false, 'Select the fine type.');
        if ($fAmt === false || $fMax === false)              return $this->_json(false, 'Enter valid fine amounts.');
        if ($fType !== 'NONE' && !$fAmt)                     return $this->_json(false, 'Enter the fine amount.');
        if ($fGrace < 0 || $fGrace > 365)                    return $this->_json(false, 'Grace days must be between 0 and 365.');
        if (mb_strlen($desc) > 500)                          return $this->_json(false, 'Description must be 500 characters or fewer.');

        // class-wise amounts: keep only valid classes with a value
        $classAmounts = array();
        $raw = $this->input->post('class_amount');
        if (is_array($raw)) {
            foreach ($raw as $cmId => $v) {
                $m = $this->_money($v);
                if ($m === false) return $this->_json(false, 'Class-wise amounts must be valid numbers.');
                if ($m !== null && $this->Subject_Model->class_exists_by_id((int)$cmId)) $classAmounts[(int)$cmId] = $m;
            }
        }

        // rates of a VARIABLE fee (stop / distance slab + amount)
        $rates = array();
        if ($atype === 'VARIABLE') {
            $rid  = (array)$this->input->post('rate_id');
            $rnm  = (array)$this->input->post('rate_name');
            $ramt = (array)$this->input->post('rate_amount');
            $seen = array();
            $own  = array();
            if ($id) foreach ($old->rates as $rt) $own[(int)$rt->id] = $rt->name;
            foreach ($rnm as $i => $rn) {
                $rn = trim(preg_replace('/\s+/', ' ', (string)$rn));
                $ra = $this->_money(isset($ramt[$i]) ? $ramt[$i] : '');
                $ri = (int)(isset($rid[$i]) ? $rid[$i] : 0);
                if ($rn === '' && $ra === null) continue;                 // empty line
                if ($rn === '' || mb_strlen($rn) > 100)  return $this->_json(false, 'Every rate needs a name (stop / distance), max 100 characters.');
                if ($ra === null || $ra === false)       return $this->_json(false, 'Enter a valid amount for ' . $rn . '.');
                if (isset($seen[mb_strtolower($rn)]))    return $this->_json(false, $rn . ' is listed twice.');
                if ($ri && !isset($own[$ri])) $ri = 0;                    // not a rate of this fee
                $seen[mb_strtolower($rn)] = true;
                $rates[] = array('id' => $ri, 'name' => $rn, 'amount' => $ra);
            }
            if (count($rates) > 200) return $this->_json(false, 'A fee can have at most 200 rates.');

            // a rate that students use cannot be removed while the fee stays VARIABLE
            if ($id && Fee_model::is_variable($old)) {
                $kept  = array_map(function ($r) { return (int)$r['id']; }, $rates);
                $usage = $this->Fee_model->rate_usage($id);
                foreach ($own as $rId => $rName) {
                    if (!in_array($rId, $kept, true) && !empty($usage[$rId])) {
                        return $this->_json(false, $rName . ' is used by ' . $usage[$rId] . ' student(s). Move them to another rate before removing it.');
                    }
                }
            }
        }

        $row = array(
            'fmName'          => $name,
            'fmAmount'        => $amount,
            'fmAmountType'    => $atype,
            'fmDescription'   => $desc,
            'fmFrequency'     => $freq,
            'fmInstallments'  => $inst,
            'fmStartMonth'    => $month,
            'fmDueDay'        => $day,
            'fmIsSpecial'     => $this->input->post('is_special') ? 1 : 0,
            'fmAutoAssign'    => $this->input->post('auto_assign') ? 1 : 0,
            'fmFineType'      => $fType,
            'fmFineAmount'    => $fType === 'NONE' ? 0 : (float)$fAmt,
            'fmFineGraceDays' => $fType === 'NONE' ? 0 : $fGrace,
            'fmFineMax'       => $fType === 'PER_DAY' ? (float)$fMax : 0,
            'fmOrder'         => (int)$this->input->post('order'),
            'fmActive'        => $this->input->post('active') ? 1 : 0,
        );
        if (!$id) {
            $row['fmAmId'] = $amId;
            $row['fmUser'] = $this->_user();
        }

        $ok = $this->Fee_model->save_fee($id, $row, $atype === 'VARIABLE' ? array() : $classAmounts,
                                         $id && $this->input->post('apply_existing'), $rates);
        return $this->_json((bool)$ok, $ok ? ($id ? 'Fee updated.' : 'Fee added.') : 'Could not save. Please try again.');
    }

    public function fee_delete()
    {
        if ($this->input->method() !== 'post') return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('fee_master');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to do this.');

        $id = (int)$this->input->post('id');
        if (!$this->Fee_model->get_fee($id)) return $this->_json(false, 'Fee not found.');
        if ($this->Fee_model->fee_has_allocations($id)) {
            return $this->_json(false, 'This fee is allotted to students. Remove it from the students first, or mark it inactive.');
        }
        $ok = $this->Fee_model->delete_fee($id);
        return $this->_json($ok, $ok ? 'Fee deleted.' : 'Delete failed.');
    }

    public function fee_copy()
    {
        if ($this->input->method() !== 'post') return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('fee_master');
        if (empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to do this.');

        $from = (int)$this->input->post('from_year');
        $to   = $this->Fee_model->get_am_id();
        if (!$from || $from === $to || !$this->Fee_model->get_year($from)) return $this->_json(false, 'Select a different academic year to copy from.');
        $n = $this->Fee_model->copy_fees($from, $to, $this->_user());
        return $this->_json(true, $n ? $n . ' fee(s) copied.' : 'Nothing to copy (all fee names already exist).');
    }

    /* ================================================================
       FEE ALLOCATION (class-wise)
       ================================================================ */
    public function fee_allocation()
    {
        $perm = $this->_perm('fee_allocation');
        if (empty($perm['can_view'])) return $this->_deny_page();

        $amId  = $this->Fee_model->get_am_id();
        $class = ctype_digit((string)$this->input->get('class')) ? (string)$this->input->get('class') : '';
        $div   = ctype_digit((string)$this->input->get('division')) ? (string)$this->input->get('division') : '';

        $fees = $this->Fee_model->get_fees($amId, true);   // active fees only
        $students = $class !== '' ? $this->Fee_model->class_students($amId, $class, $div) : array();
        $grid = $this->Fee_model->allocation_grid($amId, array_map(function ($s) { return (int)$s->smId; }, $students));

        // default amount of each fee for this class (FIXED: class-wise / default, VARIABLE: default)
        $defaults = array();
        foreach ($fees as $f) $defaults[(int)$f->fmId] = $class !== '' ? $this->Fee_model->resolve_amount($f, $class) : (float)$f->fmAmount;

        $data = array(
            'fees'     => $fees,
            'classes'  => $this->Subject_Model->get_all_classes(),
            'div_map'  => $this->Subject_Model->get_class_division_map(),
            'filters'  => array('class' => $class, 'division' => $div),
            'students' => $students,
            'grid'     => $grid,
            'defaults' => $defaults,
            'perm'     => $perm,
        );
        $this->_page('fee_allocation', $data, 'Fee Allocation', 'FEE_ALLOCATION');
    }

    /**
     * POST
     *   listed[]                 students shown on screen
     *   fees_listed[]            fees shown on screen (columns)
     *   alloc[smId][fmId][amount] / [rate]   ticked cells only
     * Ticked cells are allotted (or re-priced); unticked cells that were allotted are removed
     * (unless something is already paid).
     */
    public function fee_allocation_save()
    {
        if ($this->input->method() !== 'post') return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('fee_allocation');
        if (empty($perm['can_edit']) && empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to do this.');

        $amId   = $this->Fee_model->get_am_id();
        $listed = array_values(array_unique(array_map('intval', (array)$this->input->post('listed'))));
        $fmIds  = array_values(array_unique(array_map('intval', (array)$this->input->post('fees_listed'))));
        $alloc  = $this->input->post('alloc');
        $alloc  = is_array($alloc) ? $alloc : array();
        if (!$listed || !$fmIds) return $this->_json(false, 'Nothing to save.');

        // fees must belong to the current academic year
        $fees = array();
        foreach ($fmIds as $fmId) {
            $f = $this->Fee_model->get_fee($fmId);
            if (!$f || (int)$f->fmAmId !== $amId) return $this->_json(false, 'A fee on the screen is not from the current academic year. Reload the page.');
            $fees[$fmId] = $f;
        }
        // students must be in the current academic year
        $valid = array();
        foreach ($listed as $smId) if ($this->Fee_model->get_student($smId, $amId)) $valid[$smId] = true;

        // validate every ticked cell before writing
        $plan = array();
        foreach ($alloc as $smId => $cells) {
            $smId = (int)$smId;
            if (!isset($valid[$smId]) || !is_array($cells)) continue;
            foreach ($cells as $fmId => $c) {
                $fmId = (int)$fmId;
                if (!isset($fees[$fmId])) continue;
                $m = $this->_money(is_array($c) && isset($c['amount']) ? $c['amount'] : '');
                if ($m === false || $m === null) return $this->_json(false, 'Enter a valid ' . $fees[$fmId]->fmName . ' amount for every ticked student.');
                $rate = (int)(is_array($c) && isset($c['rate']) ? $c['rate'] : 0);
                if ($rate && (!Fee_model::is_variable($fees[$fmId]) || !$this->Fee_model->find_rate($fees[$fmId], $rate))) {
                    return $this->_json(false, 'A selected ' . $fees[$fmId]->fmName . ' rate is not valid. Reload the page.');
                }
                $plan[$smId][$fmId] = array('amount' => $m, 'rate' => $rate ?: null);
            }
        }

        $current = $this->Fee_model->allocation_grid($amId, array_keys($valid));
        $added = 0; $changed = 0; $removed = 0; $blocked = 0;

        $this->db->trans_start();
        foreach (array_keys($valid) as $smId) {
            foreach ($fees as $fmId => $f) {
                $now = isset($current[$smId][$fmId]) ? $current[$smId][$fmId] : null;
                if (isset($plan[$smId][$fmId])) {
                    $p = $plan[$smId][$fmId];
                    if ($now && abs($now['amount'] - $p['amount']) < 0.001 && (int)$now['rate'] === (int)$p['rate']) continue;
                    $this->Fee_model->allocate($smId, $amId, $fmId, $p['amount'], $this->_user(), $p['rate']);
                    $now ? $changed++ : $added++;
                } elseif ($now) {
                    if ($this->Fee_model->deallocate($smId, $amId, $fmId)) $removed++;
                    else $blocked++;
                }
            }
        }
        $this->db->trans_complete();

        $parts = array();
        if ($added)   $parts[] = $added . ' allotted';
        if ($changed) $parts[] = $changed . ' updated';
        if ($removed) $parts[] = $removed . ' removed';
        $msg = $parts ? 'Saved: ' . implode(', ', $parts) . '.' : 'No changes.';
        if ($blocked) $msg .= ' ' . $blocked . ' fee(s) were not removed because they are already paid.';
        return $this->_json($this->db->trans_status(), $msg);
    }

    /* fees for the Add/Edit Student form */
    public function student_fee_options()
    {
        $perm = $this->_perm('students_list');
        if (empty($perm['can_add']) && empty($perm['can_edit'])) {
            return $this->output->set_content_type('application/json')->set_output('[]');
        }
        $amId  = $this->Fee_model->get_am_id();
        $smId  = (int)$this->input->get('student');
        $class = (int)$this->input->get('class');
        $alloc = $smId ? $this->Fee_model->get_student_allocations($smId, $amId) : array();

        $out = array();
        foreach ($this->Fee_model->get_fees($amId) as $f) {
            $has = isset($alloc[$f->fmId]);
            if (!$f->fmActive && !$has) continue;
            $out[] = array(
                'id'           => (int)$f->fmId,
                'name'         => $f->fmName,
                'frequency'    => Fee_model::$frequencies[$f->fmFrequency]['label'] ?? $f->fmFrequency,
                'installments' => (int)$f->fmInstallments,
                'special'      => (int)$f->fmIsSpecial,
                'auto'         => (int)$f->fmAutoAssign,
                'variable'     => Fee_model::is_variable($f) ? 1 : 0,
                'rates'        => $f->rates,
                'rate'         => $has ? (int)$alloc[$f->fmId]->sfaFmrId : 0,
                'default'      => (float)$f->fmAmount,
                'class_amounts'=> (object)$f->class_amounts,
                'amount'       => $has ? (float)$alloc[$f->fmId]->sfaAmount : $this->Fee_model->resolve_amount($f, $class),
                'allotted'     => $has,
                'paid'         => $has ? (float)$alloc[$f->fmId]->paid : 0,
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    /* ================================================================
       FEE REGISTER (collection)
       ================================================================ */
    public function fee_collection()
    {
        $perm = $this->_perm('fee_collection');
        if (empty($perm['can_view'])) return $this->_deny_page();

        $data = array(
            'classes'  => $this->Subject_Model->get_all_classes(),
            'div_map'  => $this->Subject_Model->get_class_division_map(),
            'modes'    => Fee_model::$pay_modes,
            'student'  => (int)$this->input->get('student'),
            'perm'     => $perm,
            'year'     => $this->Fee_model->get_year($this->Fee_model->get_am_id()),
        );
        $this->_page('fee_register', $data, 'Fee Register', 'FEE_COLLECTION');
    }

    public function fee_students()
    {
        $perm = $this->_perm('fee_collection');
        if (empty($perm['can_view'])) {
            return $this->output->set_content_type('application/json')->set_output('[]');
        }
        $rows = $this->Fee_model->search_students(
            trim((string)$this->input->get('q')),
            $this->Fee_model->get_am_id(),
            (string)$this->input->get('class'),
            (string)$this->input->get('division'),
            50
        );
        $out = array();
        foreach ($rows as $r) {
            $out[] = array('id' => (int)$r->smId, 'adm' => $r->smAdmissionNo, 'name' => $r->smName,
                           'class' => trim($r->cmName . ' ' . $r->dmName));
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    public function fee_student_dues()
    {
        $perm = $this->_perm('fee_collection');
        if (empty($perm['can_view'])) return $this->_json(false, 'You do not have permission to do this.');

        $amId = $this->Fee_model->get_am_id();
        $smId = (int)$this->input->get('student');
        $asOf = (string)$this->input->get('date');
        if (!$this->_valid_date($asOf)) $asOf = date('Y-m-d');

        $st = $this->Fee_model->get_student($smId, $amId);
        if (!$st) return $this->_json(false, 'Student not found in this academic year.');

        $all = $this->Fee_model->get_student_dues($smId, null, false, $asOf);
        $sum = array('total' => 0, 'paid' => 0, 'balance' => 0, 'due_now' => 0, 'old' => 0, 'fine' => 0);
        $dues = array();
        foreach ($all as $d) {
            $current = (int)$d->sfdAmId === $amId;
            if ($current) {
                $sum['total']   += (float)$d->sfdAmount;
                $sum['paid']    += $d->paid;
                $sum['balance'] += max(0, $d->balance);
                if ($d->sfdDueDate <= $asOf) $sum['due_now'] += max(0, $d->balance);
            } else {
                $sum['old'] += max(0, $d->balance);
            }
            $sum['fine'] += $d->fine;
            $dues[] = array(
                'id'       => (int)$d->sfdId,
                'fee'      => $d->fmName,
                'rate'     => (string)$d->fmrName,
                'special'  => (int)$d->fmIsSpecial,
                'year'     => $d->amYear,
                'current'  => $current,
                'period'   => $d->sfdPeriod,
                'due_date' => $d->sfdDueDate,
                'amount'   => (float)$d->sfdAmount,
                'paid'     => round($d->paid, 2),
                'balance'  => max(0, $d->balance),
                'fine'     => $d->fine,
                'overdue'  => $d->balance > 0 && $d->sfdDueDate < $asOf,
            );
        }
        foreach ($sum as $k => $v) $sum[$k] = round($v, 2);

        $receipts = array();
        foreach ($this->Fee_model->list_receipts(array('am_id' => $amId, 'student' => $smId)) as $r) {
            $receipts[] = array('id' => (int)$r->frId, 'no' => $r->frReceiptNo, 'date' => $r->frDate, 'mode' => $r->frMode,
                                'total' => (float)$r->frTotal, 'cancelled' => (int)$r->frCancelled);
        }

        return $this->_json(true, '', array(
            'student'  => array('id' => (int)$st->smId, 'adm' => $st->smAdmissionNo, 'name' => $st->smName,
                                'class' => trim($st->cmName . ' ' . $st->dmName), 'mobile' => $st->smMobile,
                                'father' => $st->smFatherName),
            'dues'     => $dues,
            'summary'  => $sum,
            'receipts' => $receipts,
        ));
    }

    public function fee_collect()
    {
        if ($this->input->method() !== 'post') return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('fee_collection');
        if (empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to collect fees.');

        $amId = $this->Fee_model->get_am_id();
        $smId = (int)$this->input->post('student');
        if (!$this->Fee_model->get_student($smId, $amId)) return $this->_json(false, 'Student not found in this academic year.');

        $date = (string)$this->input->post('date');
        $mode = (string)$this->input->post('mode');
        $ref  = trim((string)$this->input->post('reference'));
        $rem  = trim((string)$this->input->post('remarks'));
        if (!$this->_valid_date($date) || $date > date('Y-m-d')) return $this->_json(false, 'Enter a valid receipt date (not in the future).');
        if (!in_array($mode, Fee_model::$pay_modes, true))        return $this->_json(false, 'Select the payment mode.');
        if ($mode !== 'Cash' && $ref === '')                       return $this->_json(false, 'Enter the reference / transaction / cheque number.');
        if (mb_strlen($ref) > 100 || mb_strlen($rem) > 500)        return $this->_json(false, 'Reference or remarks is too long.');

        $lines = array();
        $amt   = (array)$this->input->post('pay');
        $fine  = (array)$this->input->post('fine');
        foreach ($amt as $sfdId => $v) {
            $a = $this->_money($v);
            $f = $this->_money(isset($fine[$sfdId]) ? $fine[$sfdId] : '');
            if ($a === false || $f === false) return $this->_json(false, 'Amounts must be valid numbers.');
            if ($a || $f) $lines[(int)$sfdId] = array('amount' => (float)$a, 'fine' => (float)$f);
        }

        $res = $this->Fee_model->collect($smId, $amId, $lines, array(
            'date' => $date, 'mode' => $mode, 'reference' => $ref, 'remarks' => $rem, 'user' => $this->_user(),
        ));
        return $this->_json($res['ok'], $res['msg'], array('receipt_id' => $res['ok'] ? $res['id'] : 0,
            'print_url' => $res['ok'] ? site_url('fee_receipt/' . $res['id']) : ''));
    }

    /* printable receipt (no admin layout) */
    public function fee_receipt($frId = 0)
    {
        $perm = $this->_perm('fee_collection');
        $perm2 = $this->_perm('fee_management');
        if (empty($perm['can_view']) && empty($perm2['can_view'])) return $this->_deny_page();

        $r = $this->Fee_model->get_receipt((int)$frId);
        if (!$r) show_404();
        $this->load->view('fee_receipt_print', array('r' => $r));
    }

    /* ================================================================
       RECEIPTS (Fee Management)
       ================================================================ */
    public function fee_management($page = 0)
    {
        $perm = $this->_perm('fee_management');
        if (empty($perm['can_view'])) return $this->_deny_page();
        $this->load->library('pagination');

        $amId = $this->Fee_model->get_am_id();
        $f = array(
            'am_id'  => $amId,
            'from'   => $this->_valid_date((string)$this->input->get('from')) ? $this->input->get('from') : '',
            'to'     => $this->_valid_date((string)$this->input->get('to')) ? $this->input->get('to') : '',
            'q'      => trim((string)$this->input->get('q')),
            'status' => in_array((string)$this->input->get('status'), array('0', '1'), true) ? (string)$this->input->get('status') : '',
        );
        $per   = 25;
        $page  = max(0, (int)$page);
        $total = $this->Fee_model->count_receipts($f);

        $this->pagination->initialize(array(
            'base_url' => site_url('fee_management'), 'total_rows' => $total, 'per_page' => $per,
            'uri_segment' => 2, 'num_links' => 2, 'reuse_query_string' => TRUE,
            'full_tag_open' => '<ul class="pager">', 'full_tag_close' => '</ul>',
            'num_tag_open' => '<li>', 'num_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><span>', 'cur_tag_close' => '</span></li>',
            'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>',
            'first_link' => FALSE, 'last_link' => FALSE, 'prev_link' => '&lsaquo;', 'next_link' => '&rsaquo;',
        ));

        $data = array(
            'rows'    => $this->Fee_model->list_receipts($f, $per, $page),
            'links'   => $this->pagination->create_links(),
            'total'   => $total,
            'start'   => $page,
            'per'     => $per,
            'filters' => $f,
            'perm'    => $perm,
        );
        $this->_page('fee_receipts', $data, 'Fee Receipts', 'FEE_MANAGEMENT');
    }

    public function fee_receipt_cancel()
    {
        if ($this->input->method() !== 'post') return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('fee_management');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to cancel receipts.');

        $id     = (int)$this->input->post('id');
        $reason = trim((string)$this->input->post('reason'));
        if ($reason === '' || mb_strlen($reason) > 255) return $this->_json(false, 'Enter the reason for cancelling (max 255 characters).');
        $r = $this->Fee_model->get_receipt($id);
        if (!$r) return $this->_json(false, 'Receipt not found.');
        if ((int)$r->frCancelled === 1) return $this->_json(false, 'This receipt is already cancelled.');

        $ok = $this->Fee_model->cancel_receipt($id, $reason, $this->_user());
        return $this->_json($ok, $ok ? 'Receipt ' . $r->frReceiptNo . ' cancelled. The amount is back in the student\'s dues.' : 'Could not cancel.');
    }
}
