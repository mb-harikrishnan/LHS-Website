<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Login/member_login';


////////////////    LOGIN   /////////////////////////

$route['member_login'] = 'Login/member_login';
$route['check_username'] = 'Login/check_username';
$route['check_password'] = 'Login/check_password';
$route['member_login_check'] = 'Login/member_login_check';


////////////////     DASHBOARD //////////////////////

$route['dashboard']  ='members_area/Dashboard';


////////////////     CHANGE PASSWORD   //////////////////////

$route['change_password']  ='members_area/ChangePassword';
$route['check_current_password'] = 'members_area/ChangePassword/check_current_password';
$route['change_old_password'] = 'members_area/ChangePassword/change_old_password';



//////////////   LOGOUT   ////////////////////////////////

$route['logout'] = 'members_area/Logout/member_logout';




/////////////////   NOTIFICATION //////////////////////


$route['notifications'] = 'members_area/Notifications/notifications';




/////////////////////     DOCUMENT UPLOAD //////////////////////////


/////////////    document controller //////////////////////////

$route['general_information']   = 'members_area/Document_Controller/general_information';
$route['delete_general_information']   = 'members_area/Document_Controller/delete_general_information';
$route['upload_document']   = 'members_area/Document_Controller/upload_document';
$route['add_document']   = 'members_area/Document_Controller/add_document';
$route['check_document_exist'] = 'members_area/Document_Controller/check_document_exist';


$route['Result_and_Staff']   = 'members_area/Document_Controller/result_and_staff_list';
$route['delete_details']   = 'members_area/Document_Controller/delete_details';
$route['upload_document_details']   = 'members_area/Document_Controller/upload_document_details';
$route['add_document_details']   = 'members_area/Document_Controller/add_document_details';
$route['check_result_exist']   = 'members_area/Document_Controller/check_result_exist';

$route['infrastructure']   = 'members_area/Document_Controller/infrastructure';
$route['delete_video']   = 'members_area/Document_Controller/delete_video';
$route['upload_video']   = 'members_area/Document_Controller/upload_video';
$route['add_video']   = 'members_area/Document_Controller/add_video';
$route['check_video_exist']   = 'members_area/Document_Controller/check_video_exist';



//////////////////   SCHOOL NEWS   ////////////////////////



$route['school_news']   = 'members_area/NewsController/school_news';
$route['add_news']   = 'members_area/NewsController/add_news';
$route['insert_school_news']   = 'members_area/NewsController/insert_school_news';
$route['delete_news']   = 'members_area/NewsController/delete_news';

$route['edit_news/(:num)'] = 'members_area/NewsController/edit_news/$1';
$route['update_news'] = 'members_area/NewsController/update_news';
$route['change_news_status/(:num)/(:any)'] = 'members_area/NewsController/change_news_status/$1/$2';


///////////////////   galleeryyyy ///////////////////////////////////


$route['gallery']   = 'members_area/GalleryController/gallery';
$route['add_gallery_image']   = 'members_area/GalleryController/add_gallery_image';
$route['insert_school_image']   = 'members_area/GalleryController/insert_school_image';
$route['delete_image']   = 'members_area/GalleryController/delete_image';


/////////////////////// vaccancy_list /////////////////////////////////////////////


$route['vaccancy_list']   = 'members_area/VaccancyController/vaccancy_list';
$route['add_vacancy']   = 'members_area/VaccancyController/add_vacancy';
$route['delete_vacancy']   = 'members_area/VaccancyController/delete_vacancy';
$route['insert_vacancy']   = 'members_area/VaccancyController/insert_vacancy';


$route['apply_members']   = 'members_area/VaccancyController/apply_members';
$route['delete_application']   = 'members_area/VaccancyController/delete_application';


///////////////////  Question Papper  //////////////////////////////


$route['questionpaper_list']   = 'members_area/QuestionPaperController/questionpaper_list';
$route['add_paper']   = 'members_area/QuestionPaperController/add_paper';
$route['delete_paper']   = 'members_area/QuestionPaperController/delete_papper';
$route['insert_paper']   = 'members_area/QuestionPaperController/insert_paper';

/////////////////////////    SLIDER IMAGE VIDEO LINK ///////////////////////////

$route['slider_list']   = 'members_area/SliderController/slider_list';
$route['add_slider']   = 'members_area/SliderController/add_slider';
$route['insert_slider']   = 'members_area/SliderController/insert_slider';
$route['delete_slider']   = 'members_area/SliderController/delete_slider';

$route['edit_slider/(:num)'] = 'members_area/SliderController/edit_slider/$1';
$route['update_slider'] = 'members_area/SliderController/update_slider';


/////////////////////    co curricular activities   //////////////////////

$route['co_curricular_list']   = 'members_area/GalleryController/co_curricular_list';
$route['add_co_curricular_activities']   = 'members_area/GalleryController/add_co_curricular_activities';
$route['insert_activities']   = 'members_area/GalleryController/insert_activities';
$route['delete_activities']   = 'members_area/GalleryController/delete_activities';

$route['activities_list']   = 'members_area/GalleryController/activities_list';
$route['activities_add']   = 'members_area/GalleryController/activities_add';
$route['insert_activities_images']   = 'members_area/GalleryController/insert_activities_images';
$route['delete_activities_image']   = 'members_area/GalleryController/delete_activities_image';
$route['check_news_type']   = 'members_area/GalleryController/check_news_type';



////////////////////////   EMPLOYEE ADDING  //////////////////////////////////////


$route['employee_list']   = 'members_area/EmployeeController/employee_list';
$route['add_employee']   = 'members_area/EmployeeController/add_employee';
$route['insert_employee']   = 'members_area/EmployeeController/insert_employee';
$route['delete_employee']   = 'members_area/EmployeeController/delete_employee';


/////////////////////////   CLASS AND DIVITION /////////////////////////////


$route['class_divition']   = 'members_area/ClassController/class_divition';
$route['insert_class_division']   = 'members_area/ClassController/insert_class_division';
$route['class_divition_list']   = 'members_area/ClassController/class_divition_list';
$route['edit_class_division/(:num)'] = 'members_area/ClassController/edit_class_division/$1';
$route['update_class_division']   = 'members_area/ClassController/update_class_division';
$route['delete_divition']   = 'members_area/ClassController/delete_divition';



//////////////////   SUBJECT MASTER  //////////////////////////


$route['add_subject']   = 'members_area/SubjectController/add_subject';










$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
