<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Parentdashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
        // $this->load->model('das');
    }


public function index()
{
    $data['school_name']    = 'Little Hearts School';
    $data['avg_attendance'] = 95;
    $data['performance']    = 'Good';
    $data['fee_due']        = 12500;
    $data['children'] = [
        ['id' => 1, 'name' => 'Aarya Ramesh',   'class' => '5 - A', 'photo' => 'assets/images/girl.png'],
        ['id' => 2, 'name' => 'Adithya Ramesh', 'class' => '2 - B', 'photo' => 'assets/images/boy.png'],
    ];
    $data['notices'] = [
        ['day' => '10', 'month' => 'Nov', 'title' => 'Annual Day Celebration – Save the Date!', 'text' => 'Our Annual Day will be held on 20th December 2025...'],
    ];

    $this->load->view('header');
    $this->load->view('parentdashboardView', $data);
    $this->load->view('footer');
}


public function mychilld()
{
    $this->load->view('header');
    $this->load->view('mychilld');
    $this->load->view('footer');
}
public function attendence($child_id = 1)
{
    $month = (int) ($this->input->get('month') ?: date('n'));
    $year  = (int) ($this->input->get('year')  ?: date('Y'));

    // Replace these with your database queries.
    $data['child'] = ['id' => $child_id, 'name' => 'Aarya Ramesh', 'class' => '5', 'division' => 'A', 'roll_no' => 12, 'gender' => 'F', 'photo' => ''];
    $data['month'] = $month;
    $data['year']  = $year;
    $data['saturday_off'] = true;

    // Attendance for the month: date => P (Present), A (Absent), L (Late), H (Half day)
    $data['attendance'] = [
        '2026-11-02' => 'P',
        '2026-11-03' => 'A',
        '2026-11-04' => 'L',
        '2026-11-05' => 'H',
    ];

    // Holidays: date => name
    $data['holidays'] = [
        '2026-11-01' => 'Kerala Piravi',
        '2026-11-14' => "Children's Day",
    ];

    $this->load->view('header');
    $this->load->view('attendence', $data);
    $this->load->view('footer');
}





public function fees($child_id = 1)
{
    // Replace these with your database queries.
    $data['children'] = [
        ['id' => 1, 'name' => 'Aarya Ramesh',   'class' => '5', 'division' => 'A'],
        ['id' => 2, 'name' => 'Adithya Ramesh', 'class' => '2', 'division' => 'B'],
    ];
    $data['child'] = ['id' => $child_id, 'name' => 'Aarya Ramesh', 'class' => '5', 'division' => 'A', 'roll_no' => 12];
    $data['academic_year'] = '2026 - 27';

    $data['fees'] = [
        ['id' => 5, 'title' => 'Tuition Fee - Term 3', 'due_date' => '2026-11-15', 'amount' => 12000, 'paid' => 6000],
    ];

    $data['payments'] = [
        ['receipt_no' => 'RCP-10452', 'date' => '2026-11-02', 'title' => 'Tuition Fee - Term 3', 'mode' => 'UPI', 'amount' => 6000, 'status' => 'Success'],
    ];

    $this->load->view('header');
    $this->load->view('parentfeesView', $data);
    $this->load->view('footer');
}






public function receipt($receipt_no)
{
    // Fetch payment details from your database
    // Replace this sample with your actual model query.

    $data['school_name'] = 'Little Hearts Public School';
    $data['school_address'] = 'North Paravur, Ernakulam, Kerala';
    $data['school_phone'] = '+91 98765 43210';
    $data['school_logo'] = base_url('assets/images/school-logo.png');

    // $data['receipt'] = $this->Parent_model
    //     ->get_receipt_details($receipt_no);

    // if (empty($data['receipt'])) {
    //     show_404();
    // }
    $this->load->view('header');
    $this->load->view('receipt_view', $data);
    $this->load->view('footer');
}

public function results($student_id = null)
{
    $term_id = $this->input->get('term') ?: 3;

    // Important: Verify this student belongs to logged-in parent.
    // $data['children'] = $this->Parent_model->get_parent_children();

    // $data['child'] = $this->Parent_model
    //     ->get_student_details($student_id);

    // if (empty($data['child'])) {
    //     show_404();
    // }

    $data['academic_year'] = '2026 - 27';

    $data['terms'] = array(
        '1' => 'First Term Examination',
        '2' => 'Second Term Examination',
        '3' => 'Third Term Examination',
        '4' => 'Annual Examination'
    );

    $data['selected_term'] = $term_id;

    // Fetch marks for selected student and term
    // $data['results'] = $this->Parent_model
    //     ->get_student_exam_results($student_id, $term_id);

    // $data['remarks'] = 'Very good performance. Keep improving!';
    $this->load->view('header');

    $this->load->view('result', $data);
    $this->load->view('footer');

}

public function notice()
{
    $this->load->view('header');

    $this->load->view('notice');
    $this->load->view('footer');

}





}