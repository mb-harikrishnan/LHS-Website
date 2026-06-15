<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Login/member_login';

////////////////     REGISTRION   ////////////////////////

$route['registration'] = 'welcome/registration';
$route['check_sponsor_id'] = 'welcome/check_sponsor_id';
$route['registration_confirm'] = 'welcome/registration_confirm';
$route['check_pan'] = 'welcome/check_pan';
$route['check_email'] = 'welcome/check_email';
$route['check_mobile'] = 'welcome/check_mobile';
$route['registration_insert_data'] = 'Registration_insert_data';


////////////////    LOGIN   /////////////////////////

$route['member_login'] = 'Login/member_login';
$route['check_username'] = 'Login/check_username';
$route['check_password'] = 'Login/check_password';
$route['member_login_check'] = 'Login/member_login_check';


////////////////     DASHBOARD //////////////////////

$route['dashboard']  ='members_area/Dashboard';


////////////////     PROFILE   //////////////////////

$route['profile']  ='members_area/Profile_controller';
$route['update_profile_photo']  ='members_area/Profile_controller/update_profile_photo';
$route['edit_profile']  ='members_area/Profile_controller/edit_profile';
$route['update_bank_details']  ='members_area/Profile_controller/update_bank_details';
$route['update_pan']  ='members_area/Profile_controller/update_pan';



////////////////     CHANGE PASSWORD   //////////////////////

$route['change_password']  ='members_area/ChangePassword';
$route['check_current_password'] = 'members_area/ChangePassword/check_current_password';
$route['change_old_password'] = 'members_area/ChangePassword/change_old_password';



//////////////   LOGOUT   ////////////////////////////////

$route['logout'] = 'members_area/Logout/member_logout';




/////////////////   NOTIFICATION //////////////////////


$route['notifications'] = 'members_area/Notifications/notifications';




$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
