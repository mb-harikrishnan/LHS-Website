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


