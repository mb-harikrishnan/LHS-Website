
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PromotionController extends CI_Controller
{


  function __construct()
  {
    parent::__construct();

    $this->load->model('promotion_Model');

     if($this->session->userdata(SESSION_VARIABLE))		
		{

        }
		else
		{
		    redirect('login', 'refresh');
		}

  }




public function promotion()
{
    $current = $this->promotion_Model->get_current_year();

    $data['title']    = 'Promotion';
    $data['current']  = $current;
    $data['nextName'] = $current ? $this->next_year_name($current->amYear) : '';
    $data['next']     = $data['nextName'] ? $this->promotion_Model->get_year_by_name($data['nextName']) : null;
    $data['classes']  = $this->promotion_Model->get_classes();

    $this->load->view('header');
    $this->load->view('promotion', $data);
    $this->load->view('footer');
}

/* AJAX: boys / girls / total for the selected class in the current year */
public function stats()
{
    $classId = (int)$this->input->post('class_id');
    $current = $this->promotion_Model->get_current_year();

    if (!$classId || !$current) {
        return $this->json(['status' => false, 'msg' => 'Select a class.']);
    }

    $s = $this->promotion_Model->get_stats($classId, $current->amId);
    $this->json([
        'status' => true,
        'boys'   => (int)$s->boys,
        'girls'  => (int)$s->girls,
        'total'  => (int)$s->total,
    ]);
}

/* AJAX: promote */
public function run()
{
    $classId = (int)$this->input->post('class_id');
    $current = $this->promotion_Model->get_current_year();

    if (!$classId)  return $this->json(['status' => false, 'msg' => 'Select a class.']);
    if (!$current)  return $this->json(['status' => false, 'msg' => 'No current academic year is set.']);

    $nextName = $this->next_year_name($current->amYear);
    $next     = $this->promotion_Model->get_year_by_name($nextName);

    if (!$next) {
        return $this->json(['status' => false, 'msg' => "Academic year $nextName does not exist. Add it first."]);
    }

    $count = $this->promotion_Model->promote($classId, $current->amId, $next->amId);

    if ($count === false) return $this->json(['status' => false, 'msg' => 'Promotion failed. Please try again.']);
    if ($count === 0)     return $this->json(['status' => false, 'msg' => 'No students to promote (already promoted or class is empty).']);

    $this->json(['status' => true, 'msg' => "$count students moved to $nextName."]);
}

/* 2026-27 => 2027-28 */
private function next_year_name($year)
{
    if (!preg_match('/^(\d{4})-(\d{2})$/', $year, $m)) return '';
    $start = (int)$m[1] + 1;
    return sprintf('%d-%02d', $start, ($start + 1) % 100);
}

private function json($arr)
{
    $arr['csrf'] = $this->security->get_csrf_hash();
    $this->output->set_content_type('application/json')->set_output(json_encode($arr));
}




}