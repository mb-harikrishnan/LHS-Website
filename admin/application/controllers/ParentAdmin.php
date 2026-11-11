<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Admin side of the parent portal: Parent Accounts, Parent Notices, Publish Results */
class ParentAdmin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata(SESSION_VARIABLE)) {
            redirect('login', 'refresh');
        }
        $this->load->model('Parent_model', 'Par');
        $this->Par->require_schema();
        $this->load->model('Subject_Model');
    }

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

    private function _post_only() { return $this->input->method() === 'post'; }
    private function _clean($v)   { return trim(preg_replace('/\s+/', ' ', (string)$v)); }

    /* ================================================================
       PARENT ACCOUNTS
       ================================================================ */
    public function parent_accounts()
    {
        $perm = $this->_perm('parent_accounts');
        if (empty($perm['can_view'])) return $this->_deny();
        $q = trim((string)$this->input->get('q'));
        $am = $this->Par->current_am_id();
        $this->_page('parent_accounts', array(
            'parents' => $this->Par->list_parents($q), 'q' => $q, 'perm' => $perm,
            'counts' => $this->Par->unlinked_counts($am), 'classes' => $this->Subject_Model->get_all_classes(),
            'div_map' => $this->Subject_Model->get_class_division_map(),
            'portal_url' => site_url('parent'),
        ), 'Parent Accounts', 'PARENT_ACCOUNTS');
    }

    /* students (current year) for the child picker: by name / admission no, and / or by class + division */
    public function parent_student_search()
    {
        $perm = $this->_perm('parent_accounts');
        if (empty($perm['can_add']) && empty($perm['can_edit'])) return $this->_json(false, 'You do not have permission to link students.', array('rows' => array()));
        $q     = trim((string)$this->input->get('q'));
        $class = (int)$this->input->get('class');
        $div   = (int)$this->input->get('division');
        if (mb_strlen($q) < 2 && !$class) return $this->_json(true, '', array('rows' => array()));

        // look the year up BEFORE building the query: it runs its own query, which would reset a half-built one
        $amId = $this->Par->current_am_id();
        $this->db->select('s.smId, s.smName, s.smAdmissionNo, c.cmName, d.dmName,
                (SELECT GROUP_CONCAT(p.pmName SEPARATOR ", ") FROM parent_student ps JOIN parent_master p ON p.pmId = ps.psPmId WHERE ps.psSmId = s.smId) AS parents', false)
            ->from('students_master s')
            ->join('class_master c', 'c.cmId = s.smClass', 'left')->join('division_master d', 'd.dmId = s.smDiv', 'left')
            ->where('s.smAmId', $amId);
        if ($class) $this->db->where('s.smClass', $class);
        if ($class && $div) $this->db->where('s.smDiv', $div);
        if (mb_strlen($q) >= 2) $this->db->group_start()->like('s.smName', $q)->or_like('s.smAdmissionNo', $q)->group_end();
        $rows = $this->db->order_by('s.smName')->limit($class ? 200 : 30)->get()->result();

        $out = array();
        foreach ($rows as $r) {
            $out[] = array('id' => (int)$r->smId, 'name' => $r->smName, 'adm' => $r->smAdmissionNo,
                           'class' => trim($r->cmName . ' ' . $r->dmName), 'parents' => (string)$r->parents);
        }
        return $this->_json(true, '', array('rows' => $out));
    }

    public function parent_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('parent_accounts');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        if ($id && !$this->Par->get_parent($id)) return $this->_json(false, 'Parent not found.');

        $name   = $this->_clean($this->input->post('name'));
        $mobile = Parent_model::norm_mobile($this->input->post('mobile'));
        $pw     = (string)$this->input->post('password');
        $kids   = array_filter(array_map('intval', (array)$this->input->post('children')));
        if ($name === '' || mb_strlen($name) > 150)       return $this->_json(false, 'Enter the parent name.');
        if ($mobile === '')                               return $this->_json(false, 'Enter a valid 10-digit mobile number. It is the login ID.');
        if ($this->Par->mobile_exists($mobile, $id))      return $this->_json(false, 'Another parent account already uses this mobile number.');
        if (!$id && $pw === '')                           return $this->_json(false, 'Set a password for the new account.');
        if ($pw !== '' && (strlen($pw) < 6 || strlen($pw) > 64)) return $this->_json(false, 'Password must be 6 to 64 characters.');
        if (!$kids)                                       return $this->_json(false, 'Link at least one child.');
        foreach ($kids as $k) if (!$this->Par->student_exists($k)) return $this->_json(false, 'A selected child was not found.');

        $ok = $this->Par->save_parent($id, $name, $mobile, $pw !== '' ? $pw : null, (bool)$this->input->post('active'), $kids);
        return $this->_json((bool)$ok, $ok ? 'Parent account saved.' : 'Could not save.');
    }

    public function parent_reset_password()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('parent_accounts');
        if (empty($perm['can_edit'])) return $this->_json(false, 'You do not have permission to do this.');
        $p = $this->Par->get_parent((int)$this->input->post('id'));
        if (!$p) return $this->_json(false, 'Parent not found.');
        $pw = Parent_model::random_password();
        $ok = $this->Par->set_password($p->pmId, $pw);
        return $this->_json($ok, $ok ? 'New password created.' : 'Could not reset.', array('password' => $ok ? $pw : '', 'mobile' => $p->pmMobile, 'name' => $p->pmName));
    }

    public function parent_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('parent_accounts');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to do this.');
        $ok = $this->Par->delete_parent((int)$this->input->post('id'));
        return $this->_json($ok, $ok ? 'Parent account deleted.' : 'Delete failed.');
    }

    public function parent_bulk_create()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('parent_accounts');
        if (empty($perm['can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        $rows = $this->Par->bulk_create($this->Par->current_am_id(), (int)$this->input->post('class'));
        $new = 0; foreach ($rows as $r) if ($r['password'] !== '') $new++;
        return $this->_json(true, $rows ? $new . ' new account(s) created, ' . (count($rows) - $new) . ' existing account(s) got children linked.'
                                         : 'No students with a valid phone number are waiting for an account.', array('rows' => $rows));
    }

    /* ================================================================
       NOTICES
       ================================================================ */
    public function parent_notices()
    {
        $perm = $this->_perm('parent_notices');
        if (empty($perm['can_view'])) return $this->_deny();
        $this->_page('parent_notices', array(
            'notices' => $this->Par->list_notices(), 'perm' => $perm,
            'classes' => $this->Subject_Model->get_all_classes(), 'div_map' => $this->Subject_Model->get_class_division_map(),
        ), 'Parent Notices', 'PARENT_NOTICES');
    }

    public function parent_notice_save()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $id   = (int)$this->input->post('id');
        $perm = $this->_perm('parent_notices');
        if (empty($perm[$id ? 'can_edit' : 'can_add'])) return $this->_json(false, 'You do not have permission to do this.');
        if ($id && !$this->Par->get_notice($id)) return $this->_json(false, 'Notice not found.');
        $title = $this->_clean($this->input->post('title'));
        $body  = trim((string)$this->input->post('body'));
        $date  = (string)$this->input->post('date');
        $exp   = (string)$this->input->post('expiry');
        $cls   = (int)$this->input->post('class');
        $div   = (int)$this->input->post('division');
        $valid = function ($d) { $x = DateTime::createFromFormat('Y-m-d', $d); return $x && $x->format('Y-m-d') === $d; };
        if ($title === '' || mb_strlen($title) > 200)  return $this->_json(false, 'Enter a title (max 200 characters).');
        if ($body === '' || mb_strlen($body) > 5000)   return $this->_json(false, 'Enter the message (max 5000 characters).');
        if (!$valid($date))                            return $this->_json(false, 'Enter the publish date.');
        if ($exp !== '' && (!$valid($exp) || $exp < $date)) return $this->_json(false, 'Show-until date must be on or after the publish date.');
        if ($cls && !$this->Subject_Model->class_exists_by_id($cls)) return $this->_json(false, 'Select a valid class.');
        if ($div && (!$cls || !$this->Subject_Model->division_exists_by_id($div))) return $this->_json(false, 'Select a valid division.');
        $ok = $this->Par->save_notice($id, array('pnTitle' => $title, 'pnBody' => $body, 'pnDate' => $date, 'pnExpiry' => $exp ?: null,
            'pnCmId' => $cls ?: null, 'pnDmId' => $div ?: null, 'pnActive' => $this->input->post('active') ? 1 : 0,
            'pnUser' => (int)$this->session->userdata('id')));
        return $this->_json($ok, $ok ? 'Notice saved.' : 'Could not save.');
    }

    public function parent_notice_delete()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('parent_notices');
        if (empty($perm['can_delete'])) return $this->_json(false, 'You do not have permission to do this.');
        $ok = $this->Par->delete_notice((int)$this->input->post('id'));
        return $this->_json($ok, $ok ? 'Notice deleted.' : 'Delete failed.');
    }

    /* ================================================================
       PUBLISH RESULTS
       ================================================================ */
    public function parent_publish()
    {
        $perm = $this->_perm('parent_publish');
        if (empty($perm['can_view'])) return $this->_deny();
        $am = $this->Par->current_am_id();
        $this->_page('parent_publish', array('exams' => $this->Par->exams_for_publish($am), 'perm' => $perm,
            'year' => $this->Par->year_label($am)), 'Publish Results', 'PARENT_PUBLISH');
    }

    public function parent_publish_toggle()
    {
        if (!$this->_post_only()) return $this->_json(false, 'Invalid request.');
        $perm = $this->_perm('parent_publish');
        if (empty($perm['can_edit'])) return $this->_json(false, 'You do not have permission to do this.');
        $on = $this->input->post('on') === '1';
        $ok = $this->Par->set_publish((int)$this->input->post('id'), $on);
        return $this->_json($ok, $ok ? ($on ? 'Results are now visible to parents.' : 'Results hidden from parents.') : 'Could not update.');
    }
}
