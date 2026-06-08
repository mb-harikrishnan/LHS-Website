<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['login_check'] = 'welcome/login_check';
$route['check_username'] = 'welcome/check_username';


// /////////////   HOME PAGE   //////////////////

$route['dashboard']   = 'HomepageController/dashboard';


/////////////    document controller //////////////////////////

$route['general_information']   = 'DocumentController/general_information';
$route['delete_general_information']   = 'DocumentController/delete_general_information';
$route['upload_document']   = 'DocumentController/upload_document';
$route['add_document']   = 'DocumentController/add_document';


$route['Result_and_Staff']   = 'DocumentController/result_and_staff_list';
$route['delete_details']   = 'DocumentController/delete_details';
$route['upload_document_details']   = 'DocumentController/upload_document_details';
$route['add_document_details']   = 'DocumentController/add_document_details';

$route['infrastructure']   = 'DocumentController/infrastructure';
$route['delete_video']   = 'DocumentController/delete_video';
$route['upload_video']   = 'DocumentController/upload_video';
$route['add_video']   = 'DocumentController/add_video';


$route['school_news']   = 'NewsController/school_news';
$route['add_news']   = 'NewsController/add_news';
$route['insert_school_news']   = 'NewsController/insert_school_news';
$route['delete_news']   = 'NewsController/delete_news';


$route['gallery']   = 'GalleryController/gallery';
$route['add_gallery_image']   = 'GalleryController/add_gallery_image';
$route['insert_school_image']   = 'GalleryController/insert_school_image';
$route['delete_image']   = 'GalleryController/delete_image';


$route['vaccancy_list']   = 'VaccancyController/vaccancy_list';
$route['add_vacancy']   = 'VaccancyController/add_vacancy';
$route['delete_vacancy']   = 'VaccancyController/delete_vacancy';
$route['insert_vacancy']   = 'VaccancyController/insert_vacancy';


$route['questionpaper_list']   = 'QuestionPaperController/questionpaper_list';
$route['add_paper']   = 'QuestionPaperController/add_paper';
$route['delete_paper']   = 'QuestionPaperController/delete_paper';
$route['insert_paper']   = 'QuestionPaperController/insert_paper';

$route['slider_list']   = 'SliderController/slider_list';
$route['add_slider']   = 'SliderController/add_slider';
$route['insert_slider']   = 'SliderController/insert_slider';
$route['delete_slider']   = 'SliderController/delete_slider';







$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

