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

$route['class_list']   = 'members_area/ClassController/class_list';
$route['delete_class']   = 'members_area/ClassController/delete_class';

$route['edit_class/(:num)'] = 'members_area/ClassController/edit_class/$1';
$route['update_class'] = 'members_area/ClassController/update_class';

$route['divition_list']   = 'members_area/ClassController/divition_list';
$route['delete_divition_table']   = 'members_area/ClassController/delete_divition_table';

$route['edit_division/(:num)'] = 'members_area/ClassController/edit_division/$1';
$route['update_divition'] = 'members_area/ClassController/update_divition';




//////////////////   SUBJECT MASTER  //////////////////////////


$route['add_subject']   = 'members_area/SubjectController/add_subject';


$route['add_class']   = 'members_area/SubjectController/add_class';
$route['check_class_name']   = 'members_area/SubjectController/check_class_name';
$route['insert_class']   = 'members_area/SubjectController/insert_class';




$route['add_division']   = 'members_area/SubjectController/add_division';
$route['check_divition']   = 'members_area/SubjectController/check_divition';
$route['insert_divition']   = 'members_area/SubjectController/insert_divition';



$route['add_student']   = 'members_area/SubjectController/add_student';
$route['check_admission_number']   = 'members_area/SubjectController/check_admission_number';
$route['insert_student']   = 'members_area/SubjectController/insert_student';


$route['students_list']   = 'members_area/SubjectController/students_list';


$route['add_exam']   = 'members_area/SubjectController/add_exam';
$route['check_exam_name']   = 'members_area/SubjectController/check_exam_name';
$route['check_abbreviation']   = 'members_area/SubjectController/check_abbreviation';
$route['insert_exam']   = 'members_area/SubjectController/insert_exam';
$route['update_exam_order']   = 'members_area/SubjectController/update_exam_order';



$route['save_exam_mark_details']   = 'members_area/SubjectController/save_exam_mark_details';


$route['add_mark_entry']   = 'members_area/SubjectController/add_mark_entry';
$route['getMarksEntry']   = 'members_area/SubjectController/getMarksEntry';
$route['saveMarksEntry']   = 'members_area/SubjectController/saveMarksEntry';
$route['getExistingMarks']   = 'members_area/SubjectController/getExistingMarks';

$route['Marksentry_list']   = 'members_area/SubjectController/Marksentry_list';
$route['edit_marks/(:num)/(:num)'] = 'members_area/SubjectController/edit_marks/$1/$2';
$route['update_marks']             = 'members_area/SubjectController/update_marks';



$route['edit_students/(:num)'] = 'members_area/SubjectController/edit_students/$1';


$route['update_student'] = 'members_area/SubjectController/update_student';
$route['check_admission_number_edit'] = 'members_area/SubjectController/check_admission_number_edit';
$route['delete_students'] = 'members_area/SubjectController/delete_students';



$route['exam_list'] = 'members_area/SubjectController/exam_list';
$route['delete_exam'] = 'members_area/SubjectController/delete_exam';

$route['edit_exam/(:num)'] = 'members_area/SubjectController/edit_exam/$1';
$route['update_exam'] = 'members_area/SubjectController/update_exam';


$route['check_abbreviation_edit'] = 'members_area/SubjectController/check_abbreviation_edit';
$route['check_exam_name_edit'] = 'members_area/SubjectController/check_exam_name_edit';



$route['allocation_list'] = 'members_area/SubjectController/allocation_list';
$route['delete_allocation_list'] = 'members_area/SubjectController/delete_allocation_list';


$route['edit_allocation/(:num)/(:num)'] = 'members_area/SubjectController/edit_allocation/$1/$2';

$route['update_allocation'] = 'members_area/SubjectController/update_allocation';
$route['delete_allocation'] = 'members_area/SubjectController/delete_allocation';


$route['updateMarks'] = 'members_area/SubjectController/updateMarks';


$route['view_marks_students/(:num)/(:num)/(:num)']='members_area/SubjectController/view_marks_students/$1/$2/$3';



/////////////////    accademics ////////////////

$route['accademic_list'] = 'members_area/PermissionsController/accademic_list';
$route['delete_accademic'] = 'members_area/PermissionsController/delete_accademic';
$route['add_academic'] = 'members_area/PermissionsController/add_academic';
$route['check_academic_year'] = 'members_area/PermissionsController/check_academic_year';
$route['insert_academic'] = 'members_area/PermissionsController/insert_academic';

/////////////////    TERM ////////////////

$route['term_list'] = 'members_area/PermissionsController/term_list';
$route['delete_term'] = 'members_area/PermissionsController/delete_term';
$route['add_term'] = 'members_area/PermissionsController/add_term';
$route['check_term'] = 'members_area/PermissionsController/check_term';
$route['insert_term'] = 'members_area/PermissionsController/insert_term';

/////////////////    User role List ////////////////

$route['user_role_list'] = 'members_area/PermissionsController/user_role_list';
$route['update_role_status'] = 'members_area/PermissionsController/update_role_status';
$route['add_user_role'] = 'members_area/PermissionsController/add_user_role';
$route['check_role'] = 'members_area/PermissionsController/check_role';
$route['insert_role'] = 'members_area/PermissionsController/insert_role';



$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
