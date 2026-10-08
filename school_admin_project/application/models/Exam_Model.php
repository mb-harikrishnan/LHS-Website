<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam_Model extends CI_Model
{
    /* =====================  EXAMS  ===================== */

    public function fetch_all_exams()
    {
        return $this->db->order_by('emDisplayOrder', 'ASC')->order_by('emId', 'ASC')
                        ->get('exam_master')->result();
    }

    public function fetch_open_exams()
    {
        return $this->db->where('emActive', 1)->where('emIsOpened', 1)
                        ->order_by('emDisplayOrder', 'ASC')
                        ->get('exam_master')->result();
    }

    public function get_exam($id)
    {
        return $this->db->where('emId', (int)$id)->get('exam_master')->row();
    }

    public function fetch_term()
    {
        return $this->db->order_by('tmId', 'ASC')->get('term_master')->result();
    }

    public function term_exists($id)
    {
        return $this->db->where('tmId', (int)$id)->count_all_results('term_master') > 0;
    }

    public function abbr_exists($abbr, $exclude_id = 0)
    {
        $row = $this->db->query(
            'SELECT COUNT(*) AS c FROM exam_master WHERE LOWER(emName) = LOWER(?) AND emId != ?',
            array($abbr, (int)$exclude_id)
        )->row();
        return ((int)$row->c) > 0;
    }

    public function next_order()
    {
        $row = $this->db->select_max('emDisplayOrder', 'm')->get('exam_master')->row();
        return ((int)$row->m) + 1;
    }

    public function insert_exam($data)       { return $this->db->insert('exam_master', $data); }
    public function update_exam($id, $data)  { return $this->db->where('emId', (int)$id)->update('exam_master', $data); }
    public function delete_exam($id)         { return $this->db->where('emId', (int)$id)->delete('exam_master'); }


    /* =====================  CLASS / DIVISION / SUBJECT  ===================== */

    public function fetch_all_class()
    {
        return $this->db->order_by('cmId', 'ASC')->get('class_master')->result();
    }

    public function fetch_all_subjects()
    {
        return $this->db->order_by('smDisplayOrder', 'ASC')->get('subject_master')->result();
    }

    public function get_all_divisions()
    {
        return $this->db->order_by('dmId', 'ASC')->get('division_master')->result();
    }

    /* class id => [ {id, name} ] of the divisions allocated to that class */
    public function get_class_division_map()
    {
        $sql = "SELECT a.cdaCmId AS cid, d.dmId, d.dmName
                FROM class_division_allocation a
                JOIN division_master d ON d.dmId = a.cdaDmId
                ORDER BY a.cdaCmId ASC, d.dmName ASC";
        $map = array(); $seen = array();
        foreach ($this->db->query($sql)->result() as $r) {
            $key = $r->cid . '-' . $r->dmId;
            if (isset($seen[$key])) { continue; }
            $seen[$key] = true;
            $map[(string)$r->cid][] = array('id' => (string)$r->dmId, 'name' => $r->dmName);
        }
        return $map;
    }

    public function class_exists($id)
    {
        return $this->db->where('cmId', (int)$id)->count_all_results('class_master') > 0;
    }

    public function class_exists_by_id($id)
    {
        return $this->db->where('cmId', (int)$id)->count_all_results('class_master') > 0;
    }

    public function division_exists_by_id($id)
    {
        return $this->db->where('dmId', (int)$id)->count_all_results('division_master') > 0;
    }

    public function subjects_exist($ids)
    {
        if (empty($ids)) { return false; }
        $ids = array_map('intval', $ids);
        return $this->db->where_in('smId', $ids)->count_all_results('subject_master') === count(array_unique($ids));
    }


    /* =====================  PERMISSIONS + USER SCOPE  ===================== */

    public function get_menu_id_by_link($link)
    {
        $link = trim($link, '/');
        $row = $this->db->select('menu_id')
                        ->where_in('menu_link', array($link, '/' . $link, $link . '/', '/' . $link . '/'))
                        ->get('menus')->row();
        return $row ? (int)$row->menu_id : 0;
    }

    public function get_permissions($role_id, $menu_id)
    {
        if ((int)$role_id === 1) {
            return array('can_view' => 1, 'can_add' => 1, 'can_edit' => 1, 'can_delete' => 1);
        }
        $none = array('can_view' => 0, 'can_add' => 0, 'can_edit' => 0, 'can_delete' => 0);
        if (!$menu_id) { return $none; }

        $row = $this->db->select('can_view, can_add, can_edit, can_delete')
                        ->where('role_id', (int)$role_id)
                        ->where('menu_id', (int)$menu_id)
                        ->get('user_roles_menu_permissions')->row_array();
        return $row ? array_map('intval', $row) : $none;
    }

    public function get_employee_class_div($user_id)
    {
        return $this->db->select('emClass, emDiv')
                        ->where('user_id', (int)$user_id)
                        ->get('employee_master')->row();
    }


    /* =====================  MARK ALLOCATION  ===================== */

    public function fetch_all_allocation_list()
    {
        $sql = "SELECT emd.emdEmId, emd.emdCmId, emd.emdSmId, emd.emdMaxMark,
                       cm.cmName, em.emDisplayName, sm.smName
                FROM exam_master_detail emd
                JOIN class_master   cm ON cm.cmId = emd.emdCmId
                JOIN exam_master    em ON em.emId = emd.emdEmId
                JOIN subject_master sm ON sm.smId = emd.emdSmId
                ORDER BY emd.emdEmId DESC, emd.emdCmId ASC, sm.smDisplayOrder ASC";
        $out = array();
        foreach ($this->db->query($sql)->result() as $r) {
            $key = $r->emdEmId . '-' . $r->emdCmId;
            if (!isset($out[$key])) {
                $out[$key] = array(
                    'exam_id' => (int)$r->emdEmId, 'class_id' => (int)$r->emdCmId,
                    'exam_name' => $r->emDisplayName, 'class_name' => $r->cmName, 'subjects' => array()
                );
            }
            $out[$key]['subjects'][] = array('id' => (int)$r->emdSmId, 'name' => $r->smName, 'marks' => (int)$r->emdMaxMark);
        }
        return array_values($out);
    }

    public function allocation_exists($exam_id, $class_id)
    {
        return $this->db->where('emdEmId', (int)$exam_id)->where('emdCmId', (int)$class_id)
                        ->count_all_results('exam_master_detail') > 0;
    }

    public function save_allocation($exam_id, $class_id, $subjects, $old_exam = 0, $old_class = 0)
    {
        $this->db->trans_start();
        if ($old_exam > 0 && $old_class > 0) {
            $this->db->where('emdEmId', (int)$old_exam)->where('emdCmId', (int)$old_class)->delete('exam_master_detail');
        }
        $batch = array();
        foreach ($subjects as $s) {
            $batch[] = array(
                'emdEmId' => (int)$exam_id, 'emdCmId' => (int)$class_id,
                'emdSmId' => (int)$s['id'], 'emdMaxMark' => (int)$s['marks']
            );
        }
        $this->db->insert_batch('exam_master_detail', $batch);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete_allocation($exam_id, $class_id)
    {
        return $this->db->where('emdEmId', (int)$exam_id)->where('emdCmId', (int)$class_id)
                        ->delete('exam_master_detail');
    }


    /* =====================  MARK ENTRY  ===================== */

    /* list rows: one per exam + class + division, with completeness + final flag */
    public function fetch_marklists($class = '', $div = '')
    {
        $this->db->select('es.esEmId AS mkEmId, es.esCmId AS mkCmId, es.esDmId AS mkDmId, em.emDisplayName, em.emIsGrade, cm.cmName, dm.dmName');
        $this->db->from('exam_summary es');
        $this->db->join('exam_detail ed', 'ed.edEsId = es.esId');          // only lists that really have marks
        $this->db->join('exam_master em', 'em.emId = es.esEmId');
        $this->db->join('class_master cm', 'cm.cmId = es.esCmId');
        $this->db->join('division_master dm', 'dm.dmId = es.esDmId');
        if ($class !== '') { $this->db->where('es.esCmId', (int)$class); }
        if ($div !== '')   { $this->db->where('es.esDmId', (int)$div); }
        $this->db->group_by(array('es.esEmId', 'es.esCmId', 'es.esDmId', 'em.emDisplayName', 'em.emIsGrade', 'cm.cmName', 'dm.dmName'));
        $this->db->order_by('es.esEmId', 'DESC');
        $this->db->order_by('es.esCmId', 'ASC');
        $this->db->order_by('es.esDmId', 'ASC');
        $rows = $this->db->get()->result();

        foreach ($rows as $r) {
            $p = $this->marks_progress($r->mkEmId, $r->mkCmId, $r->mkDmId);
            $r->filled   = $p['filled'];
            $r->expected = $p['expected'];
            $r->complete = ($p['expected'] > 0 && $p['filled'] >= $p['expected']);
            $r->final    = $this->is_final($r->mkEmId, $r->mkCmId, $r->mkDmId);
        }
        return $rows;
    }

    /* filled marks vs (students x subjects) expected */
    public function marks_progress($exam_id, $class_id, $div_id)
    {
        $exam_id = (int)$exam_id; $class_id = (int)$class_id; $div_id = (int)$div_id;

        $students = $this->db->where('smClass', $class_id)->where('smDiv', $div_id)->count_all_results('students_master');
        $subjects = $this->db->where('emdEmId', $exam_id)->where('emdCmId', $class_id)->count_all_results('exam_master_detail');

        $sql = "SELECT COUNT(*) AS c
                FROM exam_detail ed
                JOIN exam_summary es ON es.esId = ed.edEsId
                JOIN students_master st ON st.smId = ed.edSmId AND st.smClass = ? AND st.smDiv = ?
                JOIN exam_master_detail emd ON emd.emdEmId = es.esEmId AND emd.emdCmId = es.esCmId AND emd.emdSmId = es.esSmId
                WHERE es.esEmId = ? AND es.esCmId = ? AND es.esDmId = ?
                  AND ed.edMark IS NOT NULL AND ed.edMark <> ''";
        $row = $this->db->query($sql, array($class_id, $div_id, $exam_id, $class_id, $div_id))->row();

        return array('filled' => (int)$row->c, 'expected' => $students * $subjects);
    }

    /* final submitted? (at least one summary row and none still open) */
    public function is_final($exam_id, $class_id, $div_id)
    {
        $base  = array('esEmId' => (int)$exam_id, 'esCmId' => (int)$class_id, 'esDmId' => (int)$div_id);
        $total = $this->db->where($base)->count_all_results('exam_summary');
        if ($total === 0) { return false; }
        $open = $this->db->where($base)->where('esFinalSubmit', 0)->count_all_results('exam_summary');
        return $open === 0;
    }

    public function final_submit($exam_id, $class_id, $div_id, $user_id = 0)
    {
        $data = array('esFinalSubmit' => 1, 'esTs' => date('Y-m-d H:i:s'));
        if ($user_id > 0) { $data['esUserId'] = (int)$user_id; }
        return $this->db->where('esEmId', (int)$exam_id)
                        ->where('esCmId', (int)$class_id)
                        ->where('esDmId', (int)$div_id)
                        ->update('exam_summary', $data);
    }

    /* subjects + max marks set in Mark Allocation, for an exam + class */
    public function get_allocation_subjects($exam_id, $class_id)
    {
        return $this->db->select('sm.smId AS id, sm.smName AS name, emd.emdMaxMark AS max')
                        ->from('exam_master_detail emd')
                        ->join('subject_master sm', 'sm.smId = emd.emdSmId')
                        ->where('emd.emdEmId', (int)$exam_id)
                        ->where('emd.emdCmId', (int)$class_id)
                        ->order_by('sm.smDisplayOrder', 'ASC')
                        ->get()->result();
    }

    public function get_class_students($class_id, $div_id)
    {
        return $this->db->select('smId AS id, smAdmissionNo AS adm_no, smName AS name')
                        ->where('smClass', (int)$class_id)
                        ->where('smDiv', (int)$div_id)
                        ->order_by('smName', 'ASC')
                        ->get('students_master')->result();
    }

    /* marks as [studentId][subjectId] => value */
    public function get_marks_map($exam_id, $class_id, $div_id)
    {
        $rows = $this->db->select('es.esSmId, ed.edSmId, ed.edMark')
                         ->from('exam_summary es')
                         ->join('exam_detail ed', 'ed.edEsId = es.esId')
                         ->where('es.esEmId', (int)$exam_id)
                         ->where('es.esCmId', (int)$class_id)
                         ->where('es.esDmId', (int)$div_id)
                         ->get()->result();
        $map = array();
        foreach ($rows as $r) {
            $v = $r->edMark;
            if (is_numeric($v) && strpos((string)$v, '.') !== false) {
                $v = rtrim(rtrim((string)$v, '0'), '.');                  // 42.00 -> 42
            }
            $map[(int)$r->edSmId][(int)$r->esSmId] = $v;
        }
        return $map;
    }

    public function marks_exist($exam_id, $class_id, $div_id)
    {
        return $this->db->from('exam_summary es')
                        ->join('exam_detail ed', 'ed.edEsId = es.esId')
                        ->where('es.esEmId', (int)$exam_id)
                        ->where('es.esCmId', (int)$class_id)
                        ->where('es.esDmId', (int)$div_id)
                        ->count_all_results() > 0;
    }

    /* replace every mark of an exam + class + division.
       $batch = array of array('st' => studentId, 'sub' => subjectId, 'mark' => value) */
    public function save_marks($exam_id, $class_id, $div_id, $batch, $user_id = 0)
    {
        $exam_id = (int)$exam_id; $class_id = (int)$class_id; $div_id = (int)$div_id;
        $now = date('Y-m-d H:i:s');

        $this->db->trans_start();

        $existing = $this->db->select('esId, esSmId')
                             ->where('esEmId', $exam_id)->where('esCmId', $class_id)->where('esDmId', $div_id)
                             ->order_by('esId', 'ASC')
                             ->get('exam_summary')->result();
        $by_sub = array(); $old_ids = array();
        foreach ($existing as $r) {
            $old_ids[] = (int)$r->esId;
            if (!isset($by_sub[(int)$r->esSmId])) { $by_sub[(int)$r->esSmId] = (int)$r->esId; }
        }

        if (!empty($old_ids)) {
            $this->db->where_in('edEsId', $old_ids)->delete('exam_detail');
        }

        $keep = array(); $rows = array();
        foreach ($batch as $b) {
            $sub = (int)$b['sub'];
            if (!isset($keep[$sub])) {
                if (isset($by_sub[$sub])) {
                    $keep[$sub] = $by_sub[$sub];
                    $this->db->where('esId', $keep[$sub])->update('exam_summary', array(
                        'esUserId' => $user_id > 0 ? (int)$user_id : NULL,
                        'esTs'     => $now
                    ));
                } else {
                    $this->db->insert('exam_summary', array(
                        'esEmId' => $exam_id, 'esCmId' => $class_id, 'esDmId' => $div_id, 'esSmId' => $sub,
                        'esUserId' => $user_id > 0 ? (int)$user_id : NULL,
                        'esTs' => $now, 'esFinalSubmit' => 0
                    ));
                    $keep[$sub] = (int)$this->db->insert_id();
                }
            }
            $rows[] = array('edEsId' => $keep[$sub], 'edSmId' => (int)$b['st'], 'edMark' => $b['mark']);
        }
        if (!empty($rows)) {
            $this->db->insert_batch('exam_detail', $rows);
        }

        $stale = array_diff($old_ids, array_values($keep));
        if (!empty($stale)) {
            $this->db->where_in('esId', $stale)->delete('exam_summary');
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete_marks($exam_id, $class_id, $div_id)
    {
        $ids = array();
        $rows = $this->db->select('esId')
                         ->where('esEmId', (int)$exam_id)->where('esCmId', (int)$class_id)->where('esDmId', (int)$div_id)
                         ->get('exam_summary')->result();
        foreach ($rows as $r) { $ids[] = (int)$r->esId; }
        if (empty($ids)) { return true; }

        $this->db->trans_start();
        $this->db->where_in('edEsId', $ids)->delete('exam_detail');
        $this->db->where_in('esId', $ids)->delete('exam_summary');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}