<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model
{
 /* Academic year id: session first, else the current year (amIsCurrent = 1) */
private function get_am_id()
{
    $amId = (int)$this->session->userdata('selected_am_id');

    if ($amId <= 0) {
        $cur  = $this->db->where('amIsCurrent', 1)
                         ->order_by('amYear', 'DESC')
                         ->limit(1)
                         ->get('academic_master')->row();
        $amId = $cur ? (int)$cur->amId : 0;
    }
    return $amId;
}

/* Exams of the selected academic year (for the dropdown) */
public function get_exams()
{
    return $this->db->where('emAmId', $this->get_am_id())
                    ->order_by('emId', 'ASC')
                    ->get('exam_master')->result();
}

public function get_final_submitted_list($examId = null)
{
    $emAmId = $this->get_am_id();

    $sql = "
        SELECT es.esEmId,
               em.emName,
               es.esCmId,
               cm.cmName,
               es.esDmId,
               dm.dmName,
               COUNT(DISTINCT es.esSmId) AS students,
               MAX(es.esTs)              AS submitted_on
        FROM exam_summary es
        INNER JOIN class_master    cm ON cm.cmId = es.esCmId
        INNER JOIN division_master dm ON dm.dmId = es.esDmId
        INNER JOIN exam_master     em ON em.emId = es.esEmId
        WHERE es.esFinalSubmit = 1
          AND em.emAmId = ?";

    $params = [$emAmId];

    if (!empty($examId)) {
        $sql     .= " AND es.esEmId = ?";
        $params[] = (int)$examId;
    }

    $sql .= "
        GROUP BY es.esEmId, em.emName, es.esCmId, cm.cmName, es.esDmId, dm.dmName
        ORDER BY es.esEmId, es.esCmId, es.esDmId";

    return $this->db->query($sql, $params)->result();
}










}