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

// MEMBERS AREA

$route['dashboard'] = 'members_area/HomeController';

// Reports
$route['records'] = 'members_area/ReportController/records';
$route['filter_roi_payout'] = 'members_area/ReportController/filter_roi_payout';

$route['records_details'] = 'members_area/ReportController/records_details';
$route['filter_roi_payout_details'] = 'members_area/ReportController/filter_roi_payout_details';


$route['binary_roi'] = 'members_area/ReportController/level_roi';
$route['filter_level_roi_payout'] = 'members_area/ReportController/filter_level_roi_payout';

$route['binary_roi_details'] = 'members_area/ReportController/binary_roi_details';
$route['binary_roi_details_filter'] = 'members_area/ReportController/binary_roi_details_filter';

$route['binary_roi_details'] = 'members_area/ReportController/binary_roi_details';
$route['binary_roi_details_filter'] = 'members_area/ReportController/binary_roi_details_filter';



$route['tire_income'] = 'members_area/ReportController/tire_income';
$route['filter_tire_income'] = 'members_area/ReportController/filter_tire_income';

$route['tire_income_details'] = 'members_area/ReportController/tire_income_details';
$route['filter_tire_income_details'] = 'members_area/ReportController/filter_tire_income_details';



$route['referral_team'] = 'members_area/ReportController/referral_team';
$route['referral_team/(:any)/(:any)'] = 'members_area/ReportController/referral_team/$1/$2';

$route['level_income'] = 'members_area/ReportController/level_income';


// Investment
$route['make_invest'] = 'members_area/InvestmentController/make_invest';
$route['submit_transaction'] = 'members_area/InvestmentController/submit_transaction';
$route['my_investments'] = 'members_area/InvestmentController/my_investments';
$route['filter_investments'] = 'members_area/InvestmentController/filter_investments';

$route['check_sponsor'] = 'members_area/InvestmentController/check_sponsor';
$route['get_sponsor_name'] = 'members_area/InvestmentController/get_sponsor_name';
$route['add_sponsor'] = 'members_area/InvestmentController/add_sponsor';


//compliants
$route['complaints'] = 'members_area/ComplaintsController/complaints';
$route['inquiry_submit'] = 'members_area/ComplaintsController/submit_inquiry';
$route['complaints_list'] = 'members_area/ComplaintsController/complaints_list';
$route['filter_complaints'] = 'members_area/ComplaintsController/filter_complaints';
$route['complaints_replay_list'] = 'members_area/ComplaintsController/complaints_replay_list';
$route['filter_admin_complaints_replay'] = 'members_area/ComplaintsController/filter_admin_complaints_replay';


// withdrawal
$route['withdrawal'] = 'members_area/WithdrawalController/withdrawal';
$route['submit_withdrawal'] = 'members_area/WithdrawalController/submit_withdrawal';
$route['withdrawal_confirmation'] = 'members_area/WithdrawalController/withdrawal_confirmation';
$route['withdraw_confirm_insertion'] = 'members_area/WithdrawalController/withdraw_confirm_insertion';
$route['check_daily_limit'] = 'members_area/WithdrawalController/check_daily_limit';
$route['check_security_password_user'] = 'members_area/WithdrawalController/check_security_password_user';


$route['roi_withdrawal_history'] = 'members_area/WithdrawalController/roi_withdrawal_history';
$route['filter_roi_withdrawals'] = 'members_area/WithdrawalController/filter_roi_withdrawals';

$route['level_withdrawal_history'] = 'members_area/WithdrawalController/level_withdrawal_history';
$route['filter_level_withdrawals'] = 'members_area/WithdrawalController/filter_level_withdrawals';

$route['tire_withdrawal_history'] = 'members_area/WithdrawalController/tire_withdrawal_history';
$route['filter_tire_withdrawals'] = 'members_area/WithdrawalController/filter_tire_withdrawals';


// wallet


$route['all_wallet'] = 'members_area/WalletController/all_wallet';


// gift wallet withdrwaal

$route['gift_withdrawal'] = 'members_area/GiftWalletController/gift_withdrawal';
$route['gift_submit_withdrawal'] = 'members_area/GiftWalletController/gift_submit_withdrawal';
$route['gift_withdrawal_confirmation'] = 'members_area/GiftWalletController/gift_withdrawal_confirmation';
$route['gift_withdraw_confirm_insertion'] = 'members_area/GiftWalletController/gift_withdraw_confirm_insertion';
$route['check_withdrawal_amount'] = 'members_area/GiftWalletController/check_withdrawal_amount';
$route['check_security_password'] = 'members_area/GiftWalletController/check_security_password';



$route['gift_withdrawal_history'] = 'members_area/GiftWalletController/gift_withdrawal_history';
$route['filter_gift_withdrawals'] = 'members_area/GiftWalletController/filter_gift_withdrawals';





//notification

$route['notifications'] = 'members_area/NotificationController/notifications';


/////   chatboat



$route['send_message'] = 'members_area/ChatBoxController/send_message';
$route['get_messages'] = 'members_area/ChatBoxController/get_messages';










$route['settings'] = 'members_area/ProfileController/profile_settings';
$route['edit_profile'] = 'members_area/ProfileController/edit_profile';


$route['logout'] = 'members_area/ReportController/logout';




///calculation

$route['investment_level_calculation'] = 'members_area/Calculation/daily_investment_level_income_calculation_do';
$route['investment_roi_calculation'] = 'members_area/Calculation/daily_investment_roi_income_calculation_do';
$route['investment_tire_calculation'] = 'members_area/Calculation/daily_investment_investment_tire_calculation_do';
$route['investment_rank_finding_calculation'] = 'members_area/Calculation/daily_investment_rank_finding_calculation_calculation_do';

$route['investment_rankincome_calculation'] = 'members_area/Calculation/daily_investment_rank_income_calculation_do';
$route['investment_reward_calculation'] = 'members_area/Calculation/daily_investment_reward_calculation_do';







$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
