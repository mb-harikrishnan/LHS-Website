<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


    public function __construct()
	{
		parent::__construct();
		$this->load->model('Welcome_model');
	}
	
	public function index()
	{

	    $fetch_details['slider'] =  $this->Welcome_model->fetch_all_sliders();
	    $fetch_details['homepage_video'] =  $this->Welcome_model->fetch_all_homepage_video();
	    $fetch_details['latest_news'] =  $this->Welcome_model->fetch_all_latest_news();

		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('home_page',$fetch_details);
		$this->load->view('footer');
	}



	public function rules_and_regulations()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('rules_and_regulations');
		$this->load->view('footer');
	}


	public function gallery()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('gallery');
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
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('co_curricular_activities');
		$this->load->view('footer');
	}
	public function sports_and_games()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('sports_and_games');
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
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('news');
		$this->load->view('footer');
	}	
	public function vaccancy()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('vaccancy');
		$this->load->view('footer');
	}	
	public function downloads()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('downloads');
		$this->load->view('footer');
	}	
	public function circulars()
	{
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('circulars');
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
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('transfer_certificates');
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
		
		$this->load->view('topbar');
		$this->load->view('header');
		$this->load->view('mandatory_disclosure',$general_information);
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

}
