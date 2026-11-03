
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
    $data['classes']  = $current ? $this->promotion_Model->get_all_class_stats($current->amId) : [];
    $data['pending']  = ($current && $data['next'])
                        ? $this->promotion_Model->count_pending($current->amId, $data['next']->amId) : 0;

    $this->load->view('header');
    $this->load->view('promotion', $data);
    $this->load->view('footer');
}
public function run_all()
{
    $current = $this->promotion_Model->get_current_year();

    if (!$current || (int)$current->amIsCurrent !== 1) {
        return $this->json(['status' => false, 'msg' => 'No current academic year is set.']);
    }
    if ((int)$current->c_status === 1) {
        return $this->json(['status' => false, 'msg' => "Promotion for {$current->amYear} is already done."]);
    }

    $nextName = $this->next_year_name($current->amYear);
    $next     = $nextName ? $this->promotion_Model->get_year_by_name($nextName) : null;

    if (!$next) {
        return $this->json(['status' => false, 'msg' => "Academic year $nextName does not exist. Add it first."]);
    }
    if ((int)$next->amId === (int)$current->amId || (int)$next->amIsCurrent === 1) {
        return $this->json(['status' => false, 'msg' => 'Promotion is allowed only to the next year, not the current one.']);
    }

    $count = $this->promotion_Model->promote_all($current->amId, $next->amId);

    if ($count === -1)    return $this->json(['status' => false, 'msg' => "Promotion for {$current->amYear} is already done."]);
    if ($count === false) return $this->json(['status' => false, 'msg' => 'Promotion failed. Please try again.']);

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

/* AJAX: promote ALL classes */


}