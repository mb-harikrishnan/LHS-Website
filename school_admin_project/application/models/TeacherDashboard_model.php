<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeacherDashboard_model extends CI_Model
{
    // ---- CHECK THESE: class / subject master tables (guessed from your column names) ----
public function get_teacher($emp_id)
{
    return $this->db->select('e.emId, e.emName, e.emClass, e.emDiv, cm.cmName')
        ->from('employee_master e')
        ->join('class_master cm', 'cm.cmId = e.emClass', 'inner')
        ->where('e.emUserId', (int)$emp_id)
        ->get()->row();
}

// public function student_stats($class, $div)
// {
//     $out = array('total' => 0, 'boys' => 0, 'girls' => 0);
//     if ((int)$class <= 0) { return $out; }

//     $this->db->select('smGender, COUNT(*) AS c')->from('students_master')
//              ->where('smClass', (int)$class);
//     if ((string)$div !== '' && (string)$div !== '0') {
//         $this->db->where('smDiv', $div);
//     }
//     $rows = $this->db->group_by('smGender')->get()->result();

//     foreach ($rows as $r) {
//         $count = (int)$r->c;
//         $out['total'] += $count;
//         if ((int)$r->smGender === 1) {
//             $out['boys'] += $count;      // 1 = boy
//         } elseif ((int)$r->smGender === 0) {
//             $out['girls'] += $count;     // 0 = girl
//         }
//     }
//     return $out;
// }


public function student_stats($class = 0, $div = 0)
{
    $out = array('total' => 0, 'boys' => 0, 'girls' => 0);

    $this->db->select('smGender, COUNT(*) AS c')->from('students_master');

    // 0 = no filter (admin sees everyone)
    if ((int)$class > 0) {
        $this->db->where('smClass', (int)$class);
    }
    if ((string)$div !== '' && (string)$div !== '0') {
        $this->db->where('smDiv', $div);
    }

    $rows = $this->db->group_by('smGender')->get()->result();

    foreach ($rows as $r) {
        $count = (int)$r->c;
        $out['total'] += $count;
        if ((int)$r->smGender === 1) {
            $out['boys'] += $count;      // 1 = boy
        } elseif ((int)$r->smGender === 0) {
            $out['girls'] += $count;     // 0 = girl
        }
    }
    return $out;
}

public function exam_ids_for_class($class)
{
    $rows = $this->db->distinct()->select('emdEmId')
        ->where('emdCmId', (int)$class)->get('exam_master_detail')->result();
    return array_map(function ($r) { return (int)$r->emdEmId; }, $rows);
}

public function latest_news($limit = 5)
{
    return $this->db->order_by('d_date', 'DESC')->limit((int)$limit)
                    ->get('teacher_news')->result();
}
}