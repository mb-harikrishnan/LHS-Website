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


$route['Result_and_Staff']   = 'members_area/Document_Controller/result_and_staff_list';
$route['delete_details']   = 'members_area/Document_Controller/delete_details';
$route['upload_document_details']   = 'members_area/Document_Controller/upload_document_details';
$route['add_document_details']   = 'members_area/Document_Controller/add_document_details';

$route['infrastructure']   = 'members_area/Document_Controller/infrastructure';
$route['delete_video']   = 'members_area/Document_Controller/delete_video';
$route['upload_video']   = 'members_area/Document_Controller/upload_video';
$route['add_video']   = 'members_area/Document_Controller/add_video';





$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
