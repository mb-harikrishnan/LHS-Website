<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->model('Welcome_model');
		$this->load->library('session');

		   $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
	}

	public function index()
	{

		$fetch_details['slider'] =  $this->Welcome_model->fetch_all_sliders();
		$fetch_details['homepage_video'] =  $this->Welcome_model->fetch_all_homepage_video();
		$fetch_details['latest_news'] =  $this->Welcome_model->fetch_all_latest_news();

		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('home_page', $fetch_details);
		$this->load->view('footer');
	}



	public function rules_and_regulations()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('rules_and_regulations');
		$this->load->view('footer');
	}


	// public function gallery()
	// {
	// 	$data['all_images'] = $this->Welcome_model->fetch_all_images();
	// 	$data['all_types'] = $this->Welcome_model->fetch_all_types();

	// 	$this->load->view('topbar');
	// 	$this->load->view('header');
	// 	$this->load->view('gallery', $data);
	// 	$this->load->view('footer');
	// }


	public function gallery()
{
    $data['all_events'] = $this->Welcome_model->fetch_all_events();
 
    $this->load->view('topbar');
    $this->load->view('header');
    $this->load->view('gallery', $data);
    $this->load->view('footer');
}
 
// NEW – details page for one event
public function gallery_detail($id = 0)
{
    $event = $this->Welcome_model->fetch_event((int) $id);
    if (empty($event)) {
        show_404();
    }
 
    $data['event']      = $event;
    $data['all_images'] = $this->Welcome_model->fetch_event_images((int) $id);
 
    $this->load->view('topbar');
    $this->load->view('header');
    $this->load->view('gallery_detail', $data);
    $this->load->view('footer');
}
 



	public function house_system()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('house_system');
		$this->load->view('footer');
	}
public function co_curricular_activities()
{
    $rows = $this->Welcome_model->fetch_all_co_images();

    // map: c_type => image file name
    $images = array();
    foreach ($rows as $r) {
        $images[$r->c_type] = $r->c_images;
    }
    $data['images'] = $images;

    $this->load->view('topbar');
    $this->load->view('header');
    $this->load->view('co_curricular_activities', $data);
    $this->load->view('footer');
}

	public function list_all_activities($type = '')
	{
		$data['type'] = $type;

		$sql = "SELECT * 
				FROM all_activities 
				WHERE c_type = '$type' 
				AND c_status = 'Y'";

		$query = $this->db->query($sql);
		$data['gallery'] = $query->result();
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('list_all_activities', $data);
		$this->load->view('footer');

	}
	public function sports_and_games()
	{
		$data['all_images'] = $this->Welcome_model->fetch_all_co_images();

		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('sports_and_games',$data);
		$this->load->view('footer');
	}
	public function clubs()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('clubs');
		$this->load->view('footer');
	}
	public function band_page()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('band_page');
		$this->load->view('footer');
	}

	public function study_tour()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('study_tour');
		$this->load->view('footer');
	}
	public function annual_day()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('anual_day');
		$this->load->view('footer');
	}
	public function news()
	{

		$data['news_list'] = $this->Welcome_model->fetch_all_news();
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('news', $data);
		$this->load->view('footer');
	}
	public function vaccancy()
	{

		$data['all_vacancy'] = $this->Welcome_model->fetch_all_vacancy();

		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('vaccancy', $data);
		$this->load->view('footer');
	}
	public function downloads()
	{
		$data['Q_paper']  = $this->Welcome_model->fetch_all_Q_paper();
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('downloads', $data);
		$this->load->view('footer');
	}


	public function about_us()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('about');
		$this->load->view('footer');
	}
	public function directors_message()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('directors_message');
		$this->load->view('footer');
	}
	public function principals_message()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('principals_message');
		$this->load->view('footer');
	}
	public function mission_vision()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('mission_vision');
		$this->load->view('footer');
	}
	public function transfer_certificates()
	{
		$data['transfer_details']  = $this->Welcome_model->fetch_all_certificates();
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('transfer_certificates', $data);
		$this->load->view('footer');
	}

	public function mandatory_disclosure()
	{

		$general_information['general_information'] 	= $this->Welcome_model->fetch_general_information();
		$general_information['copy_of_affiliation'] 	= $this->Welcome_model->copy_of_affiliation();
		$general_information['copy_of_societies']   	= $this->Welcome_model->copy_of_societies();
		$general_information['NOC']                 	= $this->Welcome_model->NOC();
		$general_information['copy_of_recognition'] 	= $this->Welcome_model->copy_of_recognition();
		$general_information['copy_of_safty']       	= $this->Welcome_model->copy_of_safty();
		$general_information['copy_of_fire_and_safety'] = $this->Welcome_model->copy_of_fire_and_safety();
		$general_information['DEO']                     = $this->Welcome_model->DEO();
		$general_information['sanitation']              = $this->Welcome_model->sanitation();
		$general_information['land']                    = $this->Welcome_model->land();
		$general_information['fee_structure']                    = $this->Welcome_model->fee_structure();
		$general_information['anual_academic_calendar']                    = $this->Welcome_model->anual_academic_calendar();
		$general_information['school_managment_comitte']                    = $this->Welcome_model->school_managment_comitte();
		$general_information['pta_members']                    = $this->Welcome_model->pta_members();
		$general_information['_yers_board_exam']                    = $this->Welcome_model->three_yers_board_exam();
		$general_information['staff_details']                    = $this->Welcome_model->staff_details();
		$general_information['video']                    = $this->Welcome_model->video();

		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('mandatory_disclosure', $general_information);
		$this->load->view('footer');
	}
	public function fun_n_learn()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('fun_n_learn');
		$this->load->view('footer');
	}
	public function curriculum()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('curriculum');
		$this->load->view('footer');
	}
	public function scheme_of_studies()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('scheme_of_studies');
		$this->load->view('footer');
	}
	public function discipline()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('discipline');
		$this->load->view('footer');
	}
	public function fee_regulations()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('fee_regulations');
		$this->load->view('footer');
	}
	public function admissions()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('admissions');
		$this->load->view('footer');
	}
	public function school_uniform()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('school_uniform');
		$this->load->view('footer');
	}
	public function parental_support()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('parental_support');
		$this->load->view('footer');
	}





	public function contact()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('contact');
		$this->load->view('footer');
	}


	public function submit_contact()
	{
		$data = array(
			'c_name'       => $this->input->post('name'),
			'c_email'      => $this->input->post('email'),
			'n_mobile'     => $this->input->post('mobile'),
			'c_comment'    => $this->input->post('message'),
			'd_date'   => date('Y-m-d'),
			'c_status' =>'Y'
		);

		$insert = $this->db->insert('contact_us', $data);

		if($insert)
		{
			echo json_encode(array('status' => 1));
		}
		else
		{
			echo json_encode(array('status' => 0));
		}
		exit;
	}

	public function testimonials()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('testimonials');
		$this->load->view('footer');
	}

	public function teachers()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('teachers');
		$this->load->view('footer');
	}
	public function pricing()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('pricing');
		$this->load->view('footer');
	}

	public function home_page()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('home_page');
		$this->load->view('footer');
	}
	public function apply_job($id)
	{
		$data['job'] = $this->db
		->where('n_slno', $id)
		->get('school_vacancy')
		->row();

		if(empty($data['job']))
		{
			show_404();
		}
         $this->load->view('topbar');
		 $this->load->view('header');
	     $this->load->view('apply_job', $data);
	   	 $this->load->view('footer');

	

	}





public function submit_job_application()
{

    header('Content-Type: application/json');

    $name       = $this->input->post('name');
    $mobile     = $this->input->post('mobile');
    $email      = $this->input->post('email');
    $vacancy_id = $this->input->post('vacancy_id');

    $resume = '';

    // FILE UPLOAD
    if(isset($_FILES['resume']) && $_FILES['resume']['name'] != '')
    {

        $config['upload_path']   = './assets/images/resumes';
        $config['allowed_types'] = 'pdf|doc|docx';
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if($this->upload->do_upload('resume'))
        {

            $uploadData = $this->upload->data();

            $resume = $uploadData['file_name'];

        }
        else
        {

            echo json_encode([
                'status'  => 'error',
                'message' => strip_tags($this->upload->display_errors())
            ]);

            exit;
        }
    }

	  $insertData = [

        'n_job_id' => $vacancy_id,
        'c_name'       => $name,
        'n_mobile'     => $mobile,
        'c_email'      => $email,
        'c_resume'     => $resume,
		'd_date'      => date('Y-m-d'),
		'c_status'   => 'Y'

    ];

    $insert = $this->db->insert('job_applications', $insertData);

    if($insert)
    {

        echo json_encode([
            'status'  => 'success',
            'message' => 'Application submitted successfully'
        ]);

    }
    else
    {

        echo json_encode([
            'status'  => 'error',
            'message' => 'Database insertion failed'
        ]);

    }

    exit;
}







    private $admin_email = 'info.littleheartsschool@gmail.com';
 

    /* =====================================================
     *  PAGE + FORM HANDLER  (GET shows form, POST saves it)
     * ===================================================== */
    public function admissions_enqry()
    {
        if ($this->input->method() === 'post') {
 
            $this->_admission_rules();
 
            if ($this->form_validation->run() === TRUE) {
 
                $data = [
                    'student_name'  => $this->input->post('student_name', TRUE),
                    'dob'           => $this->input->post('dob', TRUE),
                    'gender'        => $this->input->post('gender', TRUE),
                    'class_applied' => $this->input->post('class', TRUE),
                    'prev_school'   => $this->input->post('prev_school', TRUE),
                    'parent_name'   => $this->input->post('parent_name', TRUE),
                    'phone'         => $this->input->post('phone', TRUE),
                    'email'         => $this->input->post('email', TRUE),
                    'address'       => $this->input->post('address', TRUE),
                ];
 
                $id = $this->Welcome_model->insert($data);
 
                if ($id) {
                    $data['ref'] = 'LHS-' . str_pad($id, 5, '0', STR_PAD_LEFT);
 
                    // PHP mail() does not work on XAMPP/localhost, so skip it there
                    $is_local = in_array($this->input->server('SERVER_NAME'), ['localhost', '127.0.0.1', '::1']);
                    if ($is_local) {
                        log_message('debug', 'Local server: admission emails skipped for ' . $data['ref']);
                    } else {
                        $this->_send_admission_mails($data);   // parent + admin
                    }
 
                    $this->session->set_flashdata('ae_success',
                        'Thank you! Your application is received. Reference no: ' . $data['ref'] .
                        '. We will contact you soon.');
                } else {
                    $this->session->set_flashdata('ae_error',
                        'Sorry, we could not save your application. Please try again.');
                }
                redirect(current_url());   // stops double submit on refresh
            }
            // validation failed: fall through, the view shows errors and old values
        }
 
        $this->load->view('topbar');
        $this->load->view('header');
        $this->load->view('admissions_enqry');
        $this->load->view('footer');
    }
 
    /* =====================================================
     *  VALIDATION RULES + MESSAGES
     * ===================================================== */
    private function _admission_rules()
    {
        $name_rule = 'required|trim|min_length[3]|max_length[100]|regex_match[/^[A-Za-z][A-Za-z .\']+$/]';
        $classes   = 'PRE KG,LKG,UKG,Class 1,Class 2,Class 3,Class 4,Class 5,Class 6,Class 7,Class 8,Class 9,Class 10';
 
        $this->form_validation->set_error_delimiters('', '');
 
        $this->form_validation->set_rules('student_name', 'Student name', $name_rule, [
            'required'    => 'Please enter the student name.',
            'min_length'  => 'Student name must be at least 3 characters.',
            'max_length'  => 'Student name is too long.',
            'regex_match' => 'Student name can contain only letters, spaces and dots.',
        ]);
 
        $this->form_validation->set_rules('dob', 'Date of birth', 'required|trim|callback_valid_dob', [
            'required' => 'Please select the date of birth.',
        ]);
 
        $this->form_validation->set_rules('gender', 'Gender', 'required|in_list[Girl,Boy,Other]', [
            'required' => 'Please choose a gender.',
            'in_list'  => 'Please choose a valid gender.',
        ]);
 
        $this->form_validation->set_rules('class', 'Class', 'required|in_list[' . $classes . ']', [
            'required' => 'Please select the class.',
            'in_list'  => 'Please select a valid class.',
        ]);
 
        $this->form_validation->set_rules('prev_school', 'Previous school', 'trim|max_length[150]', [
            'max_length' => 'Previous school name is too long.',
        ]);
 
        $this->form_validation->set_rules('parent_name', 'Parent name', $name_rule, [
            'required'    => 'Please enter the parent name.',
            'min_length'  => 'Parent name must be at least 3 characters.',
            'max_length'  => 'Parent name is too long.',
            'regex_match' => 'Parent name can contain only letters, spaces and dots.',
        ]);
 
        $this->form_validation->set_rules('phone', 'Phone number', 'required|trim|regex_match[/^[6-9][0-9]{9}$/]', [
            'required'    => 'Please enter the phone number.',
            'regex_match' => 'Enter a valid 10-digit mobile number.',
        ]);
 
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[120]', [
            'required'    => 'Please enter the email address.',
            'valid_email' => 'Enter a valid email address.',
            'max_length'  => 'Email address is too long.',
        ]);
 
        $this->form_validation->set_rules('address', 'Address', 'trim|max_length[300]', [
            'max_length' => 'Address must be under 300 characters.',
        ]);
    }
 
    /* Custom rule used by callback_valid_dob (must stay public) */
    public function valid_dob($dob)
    {
        $d = DateTime::createFromFormat('Y-m-d', $dob);
        if (!$d || $d->format('Y-m-d') !== $dob) {
            $this->form_validation->set_message('valid_dob', 'Enter a valid date of birth.');
            return FALSE;
        }
        $today = new DateTime('today');
        if ($d > $today) {
            $this->form_validation->set_message('valid_dob', 'Date of birth cannot be in the future.');
            return FALSE;
        }
        $age = $d->diff($today)->y;
        if ($age < 2 || $age > 18) {
            $this->form_validation->set_message('valid_dob', 'Student age must be between 2 and 18 years.');
            return FALSE;
        }
        return TRUE;
    }
 
    /* =====================================================
     *  EMAILS (PHP mail): thank-you to parent, details to admin
     *  Templates: application/views/admission_user.php
     *             application/views/admission_admin.php
     * ===================================================== */
    private function _send_admission_mails($d)
{
    $this->load->library('email');
    $school = 'Little Hearts School';

    // Option A: Gmail SMTP (needs 2-Step Verification + an App Password)
    $smtp_user = 'info.littleheartsschool@gmail.com';
    $smtp_pass = 'info@123';   // 16-char Google App Password, NOT your normal password

    $this->email->initialize([
        'protocol'    => 'smtp',
        'smtp_host'   => 'smtp.gmail.com',
        'smtp_port'   => 587,
        'smtp_crypto' => 'tls',
        'smtp_user'   => $smtp_user,
        'smtp_pass'   => $smtp_pass,
        'smtp_timeout'=> 20,
        'mailtype'    => 'html',
        'charset'     => 'utf-8',
        'newline'     => "\r\n",
        'crlf'        => "\r\n",
        'wordwrap'    => TRUE,
    ]);

    // 1) Parent / entered email
    $this->email->clear(TRUE);
    $this->email->from($smtp_user, $school);      // must be the SMTP account
    $this->email->reply_to($this->admin_email, $school);
    $this->email->to($d['email']);
    $this->email->subject('Admission enquiry received - ' . $school . ' (' . $d['ref'] . ')');
    $this->email->message($this->load->view('admission_user', $d, TRUE));
    if (!$this->email->send(FALSE)) {
        log_message('error', 'Parent mail failed: ' . $this->email->print_debugger(['headers']));
    }

    // 2) Admin (yourself)
    $this->email->clear(TRUE);
    $this->email->from($smtp_user, $school . ' Website');
    $this->email->reply_to($d['email'], $d['parent_name']);
    $this->email->to($this->admin_email);
    $this->email->subject('New admission enquiry: ' . $d['student_name'] . ' - ' . $d['class_applied'] . ' (' . $d['ref'] . ')');
    $this->email->message($this->load->view('admission_admin', $d, TRUE));
    if (!$this->email->send(FALSE)) {
        log_message('error', 'Admin mail failed: ' . $this->email->print_debugger(['headers']));
    }
}
 
    /* ---------- Validation rules and messages ---------- */
   










}
