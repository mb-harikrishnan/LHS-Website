<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['home_page'] = 'welcome';

$route['rules_and_regulations'] = 'welcome/rules_and_regulations';
$route['gallery'] = 'welcome/gallery';
$route['house_system'] = 'welcome/house_system';
$route['co_curricular_activities'] = 'welcome/co_curricular_activities';
$route['sports_and_games'] = 'welcome/sports_and_games';
$route['clubs'] = 'welcome/clubs';
$route['band_page'] = 'welcome/band_page';
$route['study_tour'] = 'welcome/study_tour';
$route['annual_day'] = 'welcome/annual_day';
$route['news'] = 'welcome/news';
$route['vaccancy'] = 'welcome/vaccancy';
$route['downloads'] = 'welcome/downloads';
$route['circulars'] = 'welcome/circulars';
$route['about_us'] = 'welcome/about_us';
$route['principals_message'] = 'welcome/principals_message';
$route['directors_message'] = 'welcome/directors_message';
$route['mission_vision'] = 'welcome/mission_vision';
$route['transfer_certificates'] = 'welcome/transfer_certificates';
$route['mandatory_disclosure'] = 'welcome/mandatory_disclosure';
$route['fun_n_learn'] = 'welcome/fun_n_learn';
$route['curriculum'] = 'welcome/curriculum';
$route['scheme_of_studies'] = 'welcome/scheme_of_studies';
$route['rules_and_regulations'] = 'welcome/rules_and_regulations';
$route['discipline'] = 'welcome/discipline';
$route['fee_regulations'] = 'welcome/fee_regulations';
$route['admissions'] = 'welcome/admissions';
$route['school_uniform'] = 'welcome/school_uniform';
$route['parental_support'] = 'welcome/parental_support';









$route['contact'] = 'welcome/contact';
$route['testimonials'] = 'welcome/testimonials';
$route['teachers'] = 'welcome/teachers';
$route['pricing'] = 'welcome/pricing';
// $route['home_page'] = 'welcome/home_page';






$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
