<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Login';


$route['login']        = 'login';
$route['login_submit'] = 'login/login_submit';


$route['dashboard'] = 'DashboardController/dashboard';

$route['general_information'] = 'MandatoryController/general_information';
$route['Result_and_Staff'] = 'MandatoryController/Result_and_Staff';
$route['infrastructure'] = 'MandatoryController/infrastructure';



$route['school_news'] = 'NewsController/school_news';


$route['gallery'] = 'GalleryController/gallery';


$route['co_curricular_list'] = 'CurricularController/co_curricular_list';
$route['activities_list'] = 'CurricularController/activities_list';


$route['vaccancy_list'] = 'VaccancyController/vaccancy_list';
$route['apply_members'] = 'VaccancyController/apply_members';


$route['questionpaper_list'] = 'QuestionpaperController/questionpaper_list';

$route['slider_list'] = 'QuestionpaperController/slider_list';

$route['accademic_list'] = 'QuestionpaperController/accademic_list';

$route['term_list'] = 'QuestionpaperController/term_list';


$route['teacherdashboard'] = 'TeacherController/teacherdashboard';


$route['divition_list'] = 'StudentController/divition_list';
$route['class_divition_list'] = 'StudentController/class_divition_list';
$route['students_list'] = 'StudentController/students_list';



$route['exam_list'] = 'ExamController/exam_list';
$route['allocation_list'] = 'ExamController/allocation_list';
$route['Marksentry_list'] = 'ExamController/Marksentry_list';


$route['change_password'] = 'ChangePasswordController/change_password';


$route['user_role_list'] = 'QuestionpaperController/user_role_list';

$route['menu_list'] = 'QuestionpaperController/menu_list';
$route['add_menu_permission'] = 'QuestionpaperController/add_menu_permission';



$route['employee_list'] = 'EmployeeController/employee_list';


