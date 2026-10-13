<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome_model extends CI_Model
{


   public function fetch_all_sliders()
   {
      $sql = "SELECT * FROM school_sliders WHERE c_status='A'";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }


   // public function fetch_all_homepage_video()
   // {
   //    $sql = "SELECT * 
   //             FROM infrastructure_videos 
   //             WHERE c_status = 'Y' 
   //             AND c_type = 'infrastructure'
   //             ORDER BY 
   //                CASE 
   //                   WHEN links IS NOT NULL 
   //                   AND links != '' 
   //                   THEN 0 
   //                   ELSE 1 
   //                END
   //             LIMIT 1";

   //    $query = $this->db->query($sql);
   //    return $query->result();
   // }
public function fetch_all_homepage_video()
{
    $sql = "SELECT *
            FROM infrastructure_videos
            WHERE c_status = 'Y'
              AND c_type = 'infrastructure'
              AND (
                    (c_videos IS NOT NULL AND c_videos != '')
                 OR (links    IS NOT NULL AND links    != '')
              )
            ORDER BY
                CASE
                    WHEN c_videos IS NOT NULL AND c_videos != '' THEN 0   -- uploaded video first
                    ELSE 1                                                -- then link
                END,
                n_slno DESC
            LIMIT 1";

    return $this->db->query($sql)->result();
}


  public function fetch_all_latest_news()
   {
      $sql = "SELECT * FROM school_news WHERE c_status='Y'";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }


  public function fetch_general_information()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='general_information' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_affiliation()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_affiliation' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_societies()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_societies' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function NOC()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='NOC' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_recognition()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_recognition' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_safty()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_safty' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function copy_of_fire_and_safety()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='copy_of_fire_and_safety' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function DEO()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='DEO' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function sanitation()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='sanitation' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function land()
   {
      $sql = "SELECT * FROM document_master WHERE c_status='Y' AND c_type ='land' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fee_structure()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='fee_structure' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function anual_academic_calendar()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='anual_academic_calendar' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function school_managment_comitte()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='school_managment_comitte' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function pta_members()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='pta_members' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function three_yers_board_exam()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='3_yers_board_exam' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function staff_details()
   {
      $sql = "SELECT * FROM result_and_staff_list WHERE c_status='Y' AND c_type ='staff_details' LIMIT 1";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
public function video()
{
    $row = $this->db->where('c_status', 'Y')
                    ->where('c_type', 'infrastructure')
                    ->group_start()
                        ->where('links !=', '')
                        ->or_where('c_videos !=', '')
                    ->group_end()
                    ->order_by('n_slno', 'DESC')   // latest one
                    ->limit(1)
                    ->get('infrastructure_videos')
                    ->row();

    if (!$row) {
        return array();
    }

    if (!empty($row->links)) {
        // Link chosen
        $row->video_kind = 'link';
        $row->video_url  = $row->links;

        // YouTube: add embed URL for an iframe
        $row->embed_url = '';
        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~', $row->links, $m)) {
            $row->video_kind = 'youtube';
            $row->embed_url  = 'https://www.youtube.com/embed/' . $m[1];
        }
    } else {
        // Uploaded video file
        $row->video_kind = 'file';
        $row->video_url  = base_url('assets/uploads/videos/' . $row->c_videos); // adjust to your public path
        $row->embed_url  = '';
    }

    return array($row);   // still an array, so your existing code keeps working
}
  public function fetch_all_news()
   {
      $sql = "SELECT * FROM school_news WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_Q_paper()
   {
      $sql = "SELECT * FROM question_paper WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
  public function fetch_all_certificates()
   {
      $sql = "SELECT * FROM transfer_certificate WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
//   public function fetch_all_images()
//    {
//       $sql = "SELECT * FROM school_event_images WHERE c_status='Y' ";
//       $query = $this->db->query($sql);
//       $result = $query->result();
//       return $result;
//    }
//   public function fetch_all_types()
//    {
//       $sql = "SELECT distinct(c_type) as c_type FROM school_event_images WHERE c_status='Y' ";
//       $query = $this->db->query($sql);
//       $result = $query->result();
//       return $result;
//    }





// All events with cover image + photo count
public function fetch_all_events()
{
    $sql = "SELECT e.slno, e.name, e.c_cover,
                   (SELECT COUNT(*) FROM school_event_images i
                     WHERE i.c_type = e.slno AND i.c_status = 'Y') AS photo_count
            FROM events e
            WHERE e.c_status = 1
            ORDER BY e.slno DESC";
    return $this->db->query($sql)->result();
}
 
// Single event
public function fetch_event($id)
{
    $sql = "SELECT slno, name, c_cover FROM events WHERE slno = ? AND c_status = 1";
    return $this->db->query($sql, array($id))->row();
}
 
// Images of one event
public function fetch_event_images($id)
{
$sql = "SELECT * FROM school_event_images WHERE c_type = ? AND c_status = 'Y'";
    return $this->db->query($sql, array($id))->result();
}
 










  public function fetch_all_vacancy()
   {
      $sql = "SELECT * FROM school_vacancy WHERE c_status='Y' ";
      $query = $this->db->query($sql);
      $result = $query->result();
      return $result;
   }
public function fetch_all_co_images()
{
    $sql = "SELECT c_type, MAX(c_images) AS c_images
            FROM co_curricular_activities
            WHERE c_status = 'Y'
              AND c_images IS NOT NULL
              AND c_images <> ''
            GROUP BY c_type";
    return $this->db->query($sql)->result();
}

   



}