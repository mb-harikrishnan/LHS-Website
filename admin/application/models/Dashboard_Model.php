<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_Model extends CI_Model
{



  public function fetch_academic_yaar()
  {
      $sql   = "SELECT amYear FROM academic_master WHERE amIsCurrent =1";
      $query = $this->db->query($sql);
      $res = $query->row()->amYear ?? '';

      return $res;
  }


  /* Selected academic year: session value if valid, otherwise the current year */
public function get_selected_am_id()
{
    $id = (int)$this->session->userdata('selected_am_id');

    if ($id > 0) {
        $exists = $this->db->where('amId', $id)->count_all_results('academic_master');
        if ($exists) return $id;
    }

    // fall back to the current year (latest one if several are marked current)
    $cur = $this->db->select('amId')
                    ->where('amIsCurrent', 1)
                    ->order_by('amYear', 'DESC')
                    ->limit(1)
                    ->get('academic_master')->row();

    $id = $cur ? (int)$cur->amId : 0;
    $this->session->set_userdata('selected_am_id', $id);

    return $id;
}

public function all_students_count()
{
    $smAmId = $this->get_selected_am_id();

    $row = $this->db->query(
        "SELECT COUNT(smId) AS totalCount
         FROM students_master
         WHERE smActive = 1 AND smAmId = ?",
        [$smAmId]
    )->row();

    return (int)($row->totalCount ?? 0);
}


//   public function all_students_count()
//   {
//     $smAmId = $this->session->userdata('selected_am_id');

//       $sql   = "SELECT COUNT(smId) as totalCount FROM students_master WHERE smActive =1 AND smAmId = $smAmId";
//       $query = $this->db->query($sql);
//       $res = $query->row()->totalCount ?? '';

//       return $res;
//   }


  public function all_employee_count()
  {
      $sql   = "SELECT COUNT(emId) as totalCount FROM employee_master WHERE emActive =1";
      $query = $this->db->query($sql);
      $res = $query->row()->totalCount ?? '';

      return $res;
  }


  public function school_news()
  {
      $sql   = "SELECT COUNT(n_slno) as totalCount FROM school_news WHERE c_status = 'Y'";
      $query = $this->db->query($sql);
      $res = $query->row()->totalCount ?? '';

      return $res;
  }

  public function exam_master()
  {
    $smAmId = $this->session->userdata('selected_am_id');

      $sql   = "SELECT COUNT(emId) as totalCount FROM exam_master WHERE emActive = 1 AND emAmId = $smAmId";
      $query = $this->db->query($sql);
      $res = $query->row()->totalCount ?? '';

      return $res;
  }
public function active_exam_list()
{
    $smAmId = $this->session->userdata('selected_am_id');

    $sql = "SELECT DISTINCT
                em.emId,
                em.emDisplayName,
                cm.cmName AS class_name,
                dm.dmName AS division_name
            FROM exam_master em
            INNER JOIN exam_summary es ON es.esEmId = em.emId
            LEFT JOIN class_master cm   ON cm.cmId = es.esCmId
            LEFT JOIN division_master dm ON dm.dmId = es.esDmId
            WHERE em.emActive = 1 AND emAmid = $smAmId
            ORDER BY em.emId, cm.cmId, dm.dmId";

    return $this->db->query($sql)->result();
}


public function all_school_news()
{
    $sql = "SELECT n_slno, c_title, c_news, d_date
            FROM school_news
            WHERE c_status = 'Y'
            ORDER BY d_date DESC, n_slno DESC
            LIMIT 10";

    return $this->db->query($sql)->result();
}








}