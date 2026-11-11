<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Parent accounts (parent_master), their children (parent_student) and the data the parent portal shows.
 * A child is identified by smId; its class / division for a year comes from students_master (smId + smAmId).
 */
class Parent_model extends CI_Model
{
    const MAX_FAILS = 5;
    const LOCK_MINUTES = 15;

    public function require_schema()
    {
        if (!$this->db->table_exists('parent_student') || !$this->db->field_exists('emPublishParents', 'exam_master')) {
            show_error('The parent portal tables are not set up on this database yet. Log in as admin and open '
                . site_url('Migration') . ' once to create them.', 500, 'Parent portal not installed');
        }
    }

    public function current_am_id()
    {
        $r = $this->db->select('amId')->where('amIsCurrent', 1)->get('academic_master')->row();
        return $r ? (int)$r->amId : 0;
    }

    public function year_label($amId)
    {
        $r = $this->db->select('amYear')->where('amId', (int)$amId)->get('academic_master')->row();
        return $r ? $r->amYear : '';
    }

    /* "+91 98765-43210" -> "9876543210"; '' when it is not a 10-digit Indian mobile */
    public static function norm_mobile($m)
    {
        $d = preg_replace('/\D/', '', (string)$m);
        if (strlen($d) > 10) $d = substr($d, -10);
        return preg_match('/^[6-9]\d{9}$/', $d) ? $d : '';
    }

    /* ================================================================
       ACCOUNTS (admin)
       ================================================================ */
    public function list_parents($q = '')
    {
        $this->db->select('p.pmId, p.pmName, p.pmMobile, p.pmActive, p.pmLastLogin, p.pmLockUntil')->from('parent_master p');
        if ($q !== '') {
            $this->db->group_start()->like('p.pmName', $q)->or_like('p.pmMobile', $q)
                ->or_where("p.pmId IN (SELECT ps.psPmId FROM parent_student ps JOIN students_master s ON s.smId = ps.psSmId
                            WHERE s.smName LIKE " . $this->db->escape('%' . $q . '%') . " OR s.smAdmissionNo LIKE " . $this->db->escape('%' . $q . '%') . ")", null, false)
                ->group_end();
        }
        $parents = $this->db->order_by('p.pmName')->get()->result();
        $kids = $this->children_map(array_map(function ($p) { return (int)$p->pmId; }, $parents));
        foreach ($parents as $p) $p->children = isset($kids[$p->pmId]) ? $kids[$p->pmId] : array();
        return $parents;
    }

    /* [pmId => [ {smId, name, adm, class} ]] using each child's latest academic-year record */
    public function children_map($pmIds)
    {
        $out = array();
        if (!$pmIds) return $out;
        $rows = $this->db->query("SELECT ps.psPmId, s.smId, s.smName, s.smAdmissionNo, c.cmName, d.dmName, y.amYear
                FROM parent_student ps
                JOIN students_master s ON s.smId = ps.psSmId
                 AND s.smAmId = (SELECT s2.smAmId FROM students_master s2 JOIN academic_master y2 ON y2.amId = s2.smAmId
                                 WHERE s2.smId = ps.psSmId ORDER BY y2.amYear DESC LIMIT 1)
                LEFT JOIN class_master c ON c.cmId = s.smClass
                LEFT JOIN division_master d ON d.dmId = s.smDiv
                LEFT JOIN academic_master y ON y.amId = s.smAmId
                WHERE ps.psPmId IN (" . implode(',', array_map('intval', $pmIds)) . ")
                ORDER BY s.smName")->result();
        foreach ($rows as $r) {
            $out[$r->psPmId][] = array('id' => (int)$r->smId, 'name' => $r->smName, 'adm' => $r->smAdmissionNo,
                                       'class' => trim($r->cmName . ' ' . $r->dmName), 'year' => $r->amYear);
        }
        return $out;
    }

    public function get_parent($id)
    {
        return $this->db->where('pmId', (int)$id)->get('parent_master')->row();
    }

    public function mobile_exists($mobile, $exclude = 0)
    {
        $this->db->where('pmMobile', $mobile);
        if ($exclude) $this->db->where('pmId !=', (int)$exclude);
        return $this->db->count_all_results('parent_master') > 0;
    }

    public function student_exists($smId)
    {
        return $this->db->where('smId', (int)$smId)->count_all_results('students_master') > 0;
    }

    /* $password null = keep; $children = [smId, ...] replaces the links */
    public function save_parent($id, $name, $mobile, $password, $active, $children)
    {
        $row = array('pmName' => $name, 'pmMobile' => $mobile, 'pmActive' => $active ? 1 : 0);
        if ($password !== null) { $row['pmPassword'] = password_hash($password, PASSWORD_DEFAULT); $row['pmFailCount'] = 0; $row['pmLockUntil'] = null; }
        $this->db->trans_start();
        if ($id) {
            $this->db->where('pmId', (int)$id)->update('parent_master', $row);
        } else {
            $this->db->insert('parent_master', $row);
            $id = (int)$this->db->insert_id();
        }
        $this->db->where('psPmId', (int)$id)->delete('parent_student');
        foreach (array_unique(array_map('intval', $children)) as $smId) {
            $this->db->insert('parent_student', array('psPmId' => (int)$id, 'psSmId' => $smId));
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? (int)$id : 0;
    }

    public function set_password($id, $password)
    {
        return $this->db->where('pmId', (int)$id)->update('parent_master', array(
            'pmPassword' => password_hash($password, PASSWORD_DEFAULT), 'pmFailCount' => 0, 'pmLockUntil' => null));
    }

    public function delete_parent($id)
    {
        $this->db->trans_start();
        $this->db->where('psPmId', (int)$id)->delete('parent_student');
        $this->db->where('pmId', (int)$id)->delete('parent_master');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public static function random_password()
    {
        $c = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $p = '';
        for ($i = 0; $i < 8; $i++) $p .= $c[random_int(0, strlen($c) - 1)];
        return $p;
    }

    /**
     * Create parent accounts from the phone numbers in student records of a year
     * (father phone, else mother phone, else student mobile). Students with the same number become one parent.
     * Existing parents with that mobile only get the missing children linked.
     * Returns rows: [name, mobile, children, password ('' when the account already existed)]
     */
    public function bulk_create($amId, $classId = 0)
    {
        $this->db->select('smId, smName, smAdmissionNo, smFatherName, smMotherName, smFatherPhoneNo, smMotherPhoneNo, smMobile')
                 ->where('smAmId', (int)$amId);
        if ($classId) $this->db->where('smClass', (int)$classId);
        $students = $this->db->order_by('smName')->get('students_master')->result();

        $linked = array();
        foreach ($this->db->select('psSmId')->get('parent_student')->result() as $r) $linked[(int)$r->psSmId] = true;

        $groups = array();
        foreach ($students as $s) {
            if (isset($linked[(int)$s->smId])) continue;
            $m = self::norm_mobile($s->smFatherPhoneNo) ?: (self::norm_mobile($s->smMotherPhoneNo) ?: self::norm_mobile($s->smMobile));
            if ($m === '') continue;
            $groups[$m][] = $s;
        }

        $out = array();
        foreach ($groups as $mobile => $kids) {
            $mobile = (string)$mobile;   // numeric array keys become integers in PHP
            $existing = $this->db->where('pmMobile', $mobile)->get('parent_master')->row();
            $names = array_map(function ($k) { return $k->smName; }, $kids);
            $this->db->trans_start();
            if ($existing) {
                foreach ($kids as $k) {
                    $this->db->query('INSERT IGNORE INTO parent_student (psPmId, psSmId) VALUES (?, ?)', array($existing->pmId, $k->smId));
                }
                $pw = '';
                $name = $existing->pmName;
            } else {
                $name = trim($kids[0]->smFatherName) ?: (trim($kids[0]->smMotherName) ?: 'Parent of ' . $kids[0]->smName);
                $pw = self::random_password();
                $this->db->insert('parent_master', array('pmName' => mb_substr($name, 0, 150), 'pmMobile' => $mobile,
                    'pmPassword' => password_hash($pw, PASSWORD_DEFAULT), 'pmActive' => 1));
                $pmId = (int)$this->db->insert_id();
                foreach ($kids as $k) $this->db->insert('parent_student', array('psPmId' => $pmId, 'psSmId' => $k->smId));
            }
            $this->db->trans_complete();
            if ($this->db->trans_status()) $out[] = array('name' => $name, 'mobile' => $mobile, 'children' => implode(', ', $names), 'password' => $pw);
        }
        return $out;
    }

    /* students with no parent account, and how many of them have no usable phone number */
    public function unlinked_counts($amId)
    {
        $r = $this->db->query("SELECT COUNT(*) n,
                    SUM(COALESCE(NULLIF(smFatherPhoneNo,''), NULLIF(smMotherPhoneNo,''), NULLIF(smMobile,'')) IS NULL) nophone
                FROM students_master s WHERE s.smAmId = ? AND NOT EXISTS (SELECT 1 FROM parent_student ps WHERE ps.psSmId = s.smId)",
            array((int)$amId))->row();
        return array('unlinked' => (int)$r->n, 'no_phone' => (int)$r->nophone);
    }

    /* ================================================================
       LOGIN
       ================================================================ */
    /** returns ['ok' => bool, 'msg' => '', 'parent' => row] */
    public function authenticate($mobile, $password)
    {
        $generic = array('ok' => false, 'msg' => 'Mobile number or password is incorrect.');
        $p = $this->db->where('pmMobile', $mobile)->get('parent_master')->row();
        if (!$p) return $generic;
        if ($p->pmLockUntil && strtotime($p->pmLockUntil) > time()) {
            return array('ok' => false, 'msg' => 'Too many wrong attempts. Try again after ' . date('h:i A', strtotime($p->pmLockUntil)) . '.');
        }
        $hash = (string)$p->pmPassword;
        $ok = (strlen($hash) === 32 && ctype_xdigit($hash)) ? hash_equals(strtolower($hash), md5($password)) : password_verify($password, $hash);
        if (!$ok) {
            $fails = (int)$p->pmFailCount + 1;
            $upd = array('pmFailCount' => $fails >= self::MAX_FAILS ? 0 : $fails);
            if ($fails >= self::MAX_FAILS) $upd['pmLockUntil'] = date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60);
            $this->db->where('pmId', $p->pmId)->update('parent_master', $upd);
            return $generic;
        }
        if (!(int)$p->pmActive) return array('ok' => false, 'msg' => 'This account is disabled. Please contact the school office.');

        $upd = array('pmFailCount' => 0, 'pmLockUntil' => null, 'pmLastLogin' => date('Y-m-d H:i:s'));
        if (strlen($hash) === 32 || password_needs_rehash($hash, PASSWORD_DEFAULT)) $upd['pmPassword'] = password_hash($password, PASSWORD_DEFAULT);   // upgrade old md5
        $this->db->where('pmId', $p->pmId)->update('parent_master', $upd);
        return array('ok' => true, 'msg' => '', 'parent' => $p);
    }

    public function check_password($pmId, $password)
    {
        $p = $this->get_parent($pmId);
        if (!$p) return false;
        $h = (string)$p->pmPassword;
        return (strlen($h) === 32 && ctype_xdigit($h)) ? hash_equals(strtolower($h), md5($password)) : password_verify($password, $h);
    }

    /* ================================================================
       CHILDREN (portal)
       ================================================================ */
    /**
     * Children of a parent with their record for the current academic year
     * (or their latest year when they are not in the current one).
     */
    public function children($pmId)
    {
        $cur = $this->current_am_id();
        $rows = $this->db->query("SELECT s.*, c.cmName, d.dmName, y.amYear, y.amId AS yearId
                FROM parent_student ps
                JOIN students_master s ON s.smId = ps.psSmId
                 AND s.smAmId = COALESCE(
                       (SELECT s1.smAmId FROM students_master s1 WHERE s1.smId = ps.psSmId AND s1.smAmId = ? LIMIT 1),
                       (SELECT s2.smAmId FROM students_master s2 JOIN academic_master y2 ON y2.amId = s2.smAmId
                         WHERE s2.smId = ps.psSmId ORDER BY y2.amYear DESC LIMIT 1))
                LEFT JOIN class_master c ON c.cmId = s.smClass
                LEFT JOIN division_master d ON d.dmId = s.smDiv
                LEFT JOIN academic_master y ON y.amId = s.smAmId
                WHERE ps.psPmId = ?
                ORDER BY s.smDOB, s.smName", array($cur, (int)$pmId))->result();
        foreach ($rows as $r) {
            $r->class = trim($r->cmName . ' ' . $r->dmName);
            $r->is_current = (int)$r->smAmId === $cur;
        }
        return $rows;
    }

    /* ================================================================
       RESULTS
       ================================================================ */
    public static function grade($pct)
    {
        if ($pct === null) return '';
        foreach (array(90 => 'A+', 80 => 'A', 70 => 'B+', 60 => 'B', 50 => 'C+', 40 => 'C', 30 => 'D') as $min => $g) if ($pct >= $min) return $g;
        return 'E';
    }

    /**
     * Published exams of a year with the child's marks (only class / divisions whose marks are final-submitted).
     * returns [ {exam, term, is_grade, subjects: [ {name, mark, max} ], total, max, pct, grade} ]
     */
    public function results($smId, $amId, $cmId, $dmId)
    {
        $rows = $this->db->query("SELECT em.emId, COALESCE(NULLIF(em.emDisplayName,''), em.emName) AS exam, em.emIsGrade, em.emDisplayOrder,
                       tm.tmName, sub.smName AS subject, sub.smDisplayOrder, ed.edMark, emd.emdMaxMark
                FROM exam_master em
                JOIN exam_summary es ON es.esEmId = em.emId AND es.esCmId = ? AND es.esDmId = ? AND es.esFinalSubmit = 1
                JOIN subject_master sub ON sub.smId = es.esSmId
                LEFT JOIN exam_detail ed ON ed.edEsId = es.esId AND ed.edSmId = ?
                LEFT JOIN exam_master_detail emd ON emd.emdEmId = em.emId AND emd.emdCmId = es.esCmId AND emd.emdSmId = es.esSmId
                LEFT JOIN term_master tm ON tm.tmId = em.emTmId
                WHERE em.emAmId = ? AND em.emPublishParents = 1
                ORDER BY em.emDisplayOrder, em.emId, sub.smDisplayOrder + 0, sub.smName",
            array((int)$cmId, (int)$dmId, (int)$smId, (int)$amId))->result();
        $exams = array();
        foreach ($rows as $r) {
            $k = (int)$r->emId;
            if (!isset($exams[$k])) {
                $exams[$k] = (object)array('id' => $k, 'exam' => $r->exam, 'term' => $r->tmName, 'is_grade' => (int)$r->emIsGrade === 1,
                                           'subjects' => array(), 'total' => 0, 'max' => 0);
            }
            $mark = $r->edMark;
            if (is_numeric($mark) && strpos((string)$mark, '.') !== false) $mark = rtrim(rtrim((string)$mark, '0'), '.');
            $exams[$k]->subjects[] = (object)array('name' => $r->subject, 'mark' => $mark, 'max' => $r->emdMaxMark !== null ? (int)$r->emdMaxMark : null);
            if (!$exams[$k]->is_grade && $mark !== null && $mark !== '' && $r->emdMaxMark) {
                $exams[$k]->max += (int)$r->emdMaxMark;
                if (is_numeric($mark)) $exams[$k]->total += (float)$mark;   // AB / absent counts as 0
            }
        }
        foreach ($exams as $e) {
            $e->pct = (!$e->is_grade && $e->max > 0) ? round($e->total * 100 / $e->max, 1) : null;
            $e->grade = self::grade($e->pct);
            foreach ($e->subjects as $s) {
                $s->grade = (!$e->is_grade && is_numeric($s->mark) && $s->max) ? self::grade($s->mark * 100 / $s->max) : '';
            }
        }
        return array_values($exams);
    }

    /* ================================================================
       NOTICES
       ================================================================ */
    public function list_notices()
    {
        return $this->db->select('n.*, c.cmName, d.dmName')->from('parent_notice n')
            ->join('class_master c', 'c.cmId = n.pnCmId', 'left')->join('division_master d', 'd.dmId = n.pnDmId', 'left')
            ->order_by('n.pnDate', 'DESC')->order_by('n.pnId', 'DESC')->get()->result();
    }

    public function get_notice($id)
    {
        return $this->db->where('pnId', (int)$id)->get('parent_notice')->row();
    }

    public function save_notice($id, $data)
    {
        if ($id) return $this->db->where('pnId', (int)$id)->update('parent_notice', $data);
        return $this->db->insert('parent_notice', $data);
    }

    public function delete_notice($id)
    {
        return $this->db->where('pnId', (int)$id)->delete('parent_notice');
    }

    /* notices for a set of children (their class / division) + active school news, newest first */
    public function notices_for($children, $limit = 50)
    {
        $today = date('Y-m-d');
        $conds = array('(n.pnCmId IS NULL)');
        foreach ($children as $c) {
            $conds[] = '(n.pnCmId = ' . (int)$c->smClass . ' AND (n.pnDmId IS NULL OR n.pnDmId = ' . (int)$c->smDiv . '))';
        }
        $rows = $this->db->query("SELECT n.pnId AS id, n.pnTitle AS title, n.pnBody AS body, n.pnDate AS d, c.cmName, dv.dmName, 'notice' AS kind
                FROM parent_notice n LEFT JOIN class_master c ON c.cmId = n.pnCmId LEFT JOIN division_master dv ON dv.dmId = n.pnDmId
                WHERE n.pnActive = 1 AND n.pnDate <= ? AND (n.pnExpiry IS NULL OR n.pnExpiry >= ?) AND (" . implode(' OR ', $conds) . ")
                ORDER BY n.pnDate DESC, n.pnId DESC LIMIT " . (int)$limit, array($today, $today))->result();
        if ($this->db->table_exists('school_news')) {
            foreach ($this->db->query("SELECT n_slno AS id, c_title AS title, c_news AS body, d_date AS d, NULL AS cmName, NULL AS dmName, 'news' AS kind
                    FROM school_news WHERE c_status = 'Y' ORDER BY d_date DESC LIMIT " . (int)$limit)->result() as $r) $rows[] = $r;
        }
        usort($rows, function ($a, $b) { return strcmp($b->d, $a->d); });
        return array_slice($rows, 0, $limit);
    }

    /* ================================================================
       PUBLISH RESULTS (admin)
       ================================================================ */
    public function exams_for_publish($amId)
    {
        return $this->db->query("SELECT em.emId, COALESCE(NULLIF(em.emDisplayName,''), em.emName) AS exam, em.emIsGrade, em.emPublishParents, tm.tmName,
                       (SELECT COUNT(DISTINCT CONCAT(es.esCmId,'-',es.esDmId)) FROM exam_summary es WHERE es.esEmId = em.emId AND es.esFinalSubmit = 1) AS final_cd,
                       (SELECT COUNT(DISTINCT CONCAT(es.esCmId,'-',es.esDmId)) FROM exam_summary es WHERE es.esEmId = em.emId) AS any_cd
                FROM exam_master em LEFT JOIN term_master tm ON tm.tmId = em.emTmId
                WHERE em.emAmId = ? ORDER BY em.emDisplayOrder, em.emId", array((int)$amId))->result();
    }

    public function set_publish($emId, $on)
    {
        return $this->db->where('emId', (int)$emId)->update('exam_master', array('emPublishParents' => $on ? 1 : 0));
    }
}
