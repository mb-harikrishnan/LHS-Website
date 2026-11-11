<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Parent portal (/parent). Separate login (parent_master: mobile + password) and session key.
 * A parent can have several children; the selected child is kept in the session and every
 * child-specific page checks that the student belongs to the logged-in parent.
 */
class ParentPortal extends CI_Controller
{
    const SESSION = 'parent_portal';

    private $parent = null;     // ['id', 'name', 'mobile']
    private $children = array();
    private $child = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Parent_model', 'Par');
        $this->Par->require_schema();
        $this->load->model('Attendance_model', 'Att');
        $this->load->helper('fee');
    }

    /* ================================================================
       helpers
       ================================================================ */
    private function _require_login()
    {
        $s = $this->session->userdata(self::SESSION);
        if (!$s || empty($s['id'])) { redirect('parent/login'); exit; }
        $p = $this->Par->get_parent($s['id']);
        if (!$p || !(int)$p->pmActive) { $this->session->unset_userdata(self::SESSION); redirect('parent/login'); exit; }
        $this->parent = array('id' => (int)$p->pmId, 'name' => $p->pmName, 'mobile' => $p->pmMobile);
        $this->children = $this->Par->children($p->pmId);

        $sel = (int)$this->session->userdata('parent_child');
        foreach ($this->children as $c) if ((int)$c->smId === $sel) $this->child = $c;
        if (!$this->child && $this->children) {
            $this->child = $this->children[0];
            $this->session->set_userdata('parent_child', (int)$this->child->smId);
        }
    }

    private function _owns($smId)
    {
        foreach ($this->children as $c) if ((int)$c->smId === (int)$smId) return $c;
        return null;
    }

    private function _page($view, $data, $title, $tab)
    {
        $data += array('parent' => $this->parent, 'children' => $this->children, 'child' => $this->child, 'tab' => $tab, 'title' => $title);
        $this->load->view('parent/_head', $data);
        if (!$this->children) $this->load->view('parent/no_children', $data);
        else $this->load->view('parent/' . $view, $data);
        $this->load->view('parent/_foot', $data);
    }

    private function _fees_on()  { return $this->db->table_exists('student_fee_due'); }
    private function _store_on() { return $this->db->table_exists('store_invoice'); }

    /* fee position of a child: due now, balance, old balance, next due */
    private function _fee_summary($smId, $amId)
    {
        $out = array('due_now' => 0, 'balance' => 0, 'old' => 0, 'fine' => 0, 'paid' => 0, 'total' => 0, 'next' => null, 'dues' => array());
        if (!$this->_fees_on()) return $out;
        $this->load->model('Fee_model');
        $today = date('Y-m-d');
        foreach ($this->Fee_model->get_student_dues($smId, null, false, $today) as $d) {
            $cur = (int)$d->sfdAmId === (int)$amId;
            $bal = max(0, $d->balance);
            if ($cur) {
                $out['total'] += (float)$d->sfdAmount; $out['paid'] += $d->paid; $out['balance'] += $bal;
                if ($d->sfdDueDate <= $today) $out['due_now'] += $bal;
                elseif ($bal > 0 && (!$out['next'] || $d->sfdDueDate < $out['next']->sfdDueDate)) $out['next'] = $d;
            } else {
                $out['old'] += $bal;
            }
            $out['fine'] += $d->fine;
            $out['dues'][] = $d;
        }
        return $out;
    }

    private function _attendance_summary($child)
    {
        list($yf) = $this->Att->year_range($child->smAmId);
        $today = date('Y-m-d');
        $y = $this->Att->summary(array((int)$child->smId), $yf, $today);
        $m = $this->Att->summary(array((int)$child->smId), date('Y-m-01'), $today);
        $z = array('P' => 0, 'A' => 0, 'H' => 0, 'L' => 0, 'days' => 0, 'pct' => null);
        return array('year' => isset($y[$child->smId]) ? $y[$child->smId] : $z, 'month' => isset($m[$child->smId]) ? $m[$child->smId] : $z);
    }

    /* ================================================================
       LOGIN
       ================================================================ */
    public function login()
    {
        if ($this->session->userdata(self::SESSION)) redirect('parent');
        $data = array('error' => '', 'mobile' => '');
        if ($this->input->method() === 'post') {
            $mobile = Parent_model::norm_mobile($this->input->post('mobile'));
            $pw = (string)$this->input->post('password');
            $data['mobile'] = $mobile;
            if ($mobile === '' || $pw === '') {
                $data['error'] = 'Enter your registered mobile number and password.';
            } else {
                $res = $this->Par->authenticate($mobile, $pw);
                if ($res['ok']) {
                    $this->session->sess_regenerate(TRUE);
                    $this->session->set_userdata(self::SESSION, array('id' => (int)$res['parent']->pmId));
                    $this->session->unset_userdata('parent_child');
                    redirect('parent');
                    return;
                }
                usleep(400000);   // slow down guessing
                $data['error'] = $res['msg'];
            }
        }
        $this->load->view('parent/login', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata(array(self::SESSION, 'parent_child'));
        redirect('parent/login');
    }

    public function child($smId = 0)
    {
        $this->_require_login();
        if ($this->_owns($smId)) $this->session->set_userdata('parent_child', (int)$smId);
        $back = (string)$this->input->get('to');
        redirect(preg_match('#^parent(/[a-z_]+)?$#', $back) ? $back : 'parent');
    }

    /* ================================================================
       DASHBOARD
       ================================================================ */
    public function index()
    {
        $this->_require_login();
        $cards = array();
        foreach ($this->children as $c) {
            $fee = $this->_fee_summary($c->smId, $c->smAmId);
            $res = $this->Par->results($c->smId, $c->smAmId, $c->smClass, $c->smDiv);
            $cards[] = array(
                'child' => $c,
                'att' => $this->_attendance_summary($c),
                'fee' => $fee,
                'store' => $this->_store_on() ? $this->_store_balance($c->smId) : 0,
                'result' => $res ? end($res) : null,
            );
        }
        $this->_page('dashboard', array('cards' => $cards, 'notices' => $this->Par->notices_for($this->children, 5)), 'Home', 'home');
    }

    private function _store_balance($smId)
    {
        $this->load->model('Store_model');
        return $this->Store_model->student_balance($smId);
    }

    /* ================================================================
       CHILD PAGES
       ================================================================ */
    public function attendance()
    {
        $this->_require_login();
        $data = array();
        if ($this->child) {
            $ym = preg_match('/^\d{4}-\d{2}$/', (string)$this->input->get('month')) ? (string)$this->input->get('month') : date('Y-m');
            if ($ym > date('Y-m')) $ym = date('Y-m');
            $from = $ym . '-01'; $to = date('Y-m-t', strtotime($from));
            $data = array('ym' => $ym, 'days' => $this->Att->student_days($this->child->smId, $from, $to),
                          'sum' => $this->_attendance_summary($this->child), 'month_sum' => $this->Att->summary(array((int)$this->child->smId), $from, $to),
                          'status' => Attendance_model::$status);
        }
        $this->_page('attendance', $data, 'Attendance', 'attendance');
    }

    public function results()
    {
        $this->_require_login();
        $data = array('exams' => $this->child ? $this->Par->results($this->child->smId, $this->child->smAmId, $this->child->smClass, $this->child->smDiv) : array());
        $this->_page('results', $data, 'Exam Results', 'results');
    }

    public function fees()
    {
        $this->_require_login();
        $data = array('fee' => null, 'receipts' => array(), 'fees_on' => $this->_fees_on());
        if ($this->child && $this->_fees_on()) {
            $data['fee'] = $this->_fee_summary($this->child->smId, $this->child->smAmId);
            $this->load->model('Fee_model');
            $data['receipts'] = $this->Fee_model->list_receipts(array('am_id' => $this->child->smAmId, 'student' => $this->child->smId, 'status' => '0'));
        }
        $this->_page('fees', $data, 'Fees', 'fees');
    }

    public function store()
    {
        $this->_require_login();
        $data = array('invoices' => array(), 'balance' => 0, 'store_on' => $this->_store_on());
        if ($this->child && $this->_store_on()) {
            $this->load->model('Store_model');
            $data['invoices'] = $this->Store_model->list_invoices($this->child->smAmId, array('student' => $this->child->smId));
            $data['balance'] = $this->Store_model->student_balance($this->child->smId);
        }
        $this->_page('store', $data, 'Store', 'store');
    }

    public function notices()
    {
        $this->_require_login();
        $this->_page('notices', array('notices' => $this->Par->notices_for($this->children, 100)), 'Notices', 'notices');
    }

    public function profile()
    {
        $this->_require_login();
        $this->_page('profile', array('msg' => $this->session->flashdata('pp_msg'), 'err' => $this->session->flashdata('pp_err')), 'Profile', 'profile');
    }

    public function password()
    {
        $this->_require_login();
        if ($this->input->method() !== 'post') redirect('parent/profile');
        $cur = (string)$this->input->post('current');
        $new = (string)$this->input->post('new');
        $rep = (string)$this->input->post('repeat');
        if (!$this->Par->check_password($this->parent['id'], $cur)) $this->session->set_flashdata('pp_err', 'Current password is incorrect.');
        elseif (strlen($new) < 6 || strlen($new) > 64)              $this->session->set_flashdata('pp_err', 'New password must be 6 to 64 characters.');
        elseif ($new !== $rep)                                       $this->session->set_flashdata('pp_err', 'The new passwords do not match.');
        else { $this->Par->set_password($this->parent['id'], $new); $this->session->set_flashdata('pp_msg', 'Password changed.'); }
        redirect('parent/profile');
    }

    /* printable receipt / invoice, only for the parent's own children */
    public function receipt($id = 0)
    {
        $this->_require_login();
        if (!$this->_fees_on()) show_404();
        $this->load->model('Fee_model');
        $r = $this->Fee_model->get_receipt((int)$id);
        if (!$r || !$this->_owns($r->frSmId) || (int)$r->frCancelled === 1) show_404();
        $this->load->view('fee_receipt_print', array('r' => $r));
    }

    public function invoice($id = 0)
    {
        $this->_require_login();
        if (!$this->_store_on()) show_404();
        $this->load->model('Store_model');
        $v = $this->Store_model->get_invoice((int)$id);
        if (!$v || !$v->sinSmId || !$this->_owns($v->sinSmId)) show_404();
        $this->load->view('store_invoice_print', array('v' => $v, 'user_name' => 'School Store'));
    }
}
