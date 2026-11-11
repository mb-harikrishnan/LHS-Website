<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Login';


$route['login']        = 'login';
$route['login_submit'] = 'login/login_submit';


$route['dashboard'] = 'DashboardController/dashboard';
$route['switch_year'] = 'DashboardController/switch_year';

$route['general_information'] = 'MandatoryController/general_information';
$route['delete_document'] = 'MandatoryController/delete_document';
$route['upload_document'] = 'MandatoryController/upload_document';


$route['Result_and_Staff'] = 'MandatoryController/Result_and_Staff';
$route['delete_Result_and_Staff'] = 'MandatoryController/delete_Result_and_Staff';
$route['upload_Result_and_Staff'] = 'MandatoryController/upload_Result_and_Staff';

$route['infrastructure']        = 'MandatoryController/infrastructure';
$route['upload_infrastructure'] = 'MandatoryController/upload_infrastructure';
$route['delete_infrastructure'] = 'MandatoryController/delete_infrastructure';

$route['school_news'] = 'NewsController/school_news';
$route['save_news']   = 'NewsController/save_news';
$route['delete_news'] = 'NewsController/delete_news';


$route['teacher_news'] = 'NewsController/teacher_news';
$route['save_te_news']   = 'NewsController/save_te_news';
$route['delete_te_news'] = 'NewsController/delete_te_news';



$route['gallery']                = 'GalleryController/gallery';
$route['gallery_album/(:num)']   = 'GalleryController/gallery_album/$1';
$route['save_gallery_images']    = 'GalleryController/save_gallery_images';
$route['delete_gallery_image']   = 'GalleryController/delete_gallery_image';

$route['save_album'] = 'GalleryController/save_album';
$route['delete_album'] = 'GalleryController/delete_album';

$route['delete_album_images'] = 'GalleryController/delete_album_images';

$route['co_curricular_list'] = 'CurricularController/co_curricular_list';
$route['save_co_curricular'] = 'CurricularController/save_co_curricular';
$route['delete_co_curricular'] = 'CurricularController/delete_co_curricular';




$route['activities_list'] = 'CurricularController/activities_list';
$route['save_activities_list'] = 'CurricularController/save_activities_list';
$route['delete_activities_list'] = 'CurricularController/delete_activities_list';




$route['vaccancy_list'] = 'VaccancyController/vaccancy_list';
$route['delete_vaccancy_list'] = 'VaccancyController/delete_vaccancy_list';
$route['save_vaccancy'] = 'VaccancyController/save_vaccancy';


  $route['apply_members']       = 'VaccancyController/apply_members';
 $route['delete_application']  = 'VaccancyController/delete_application';


 
$route['questionpaper_list'] = 'QuestionPaperController/questionpaper_list';
$route['insert_paper']       = 'QuestionPaperController/insert_paper';   // add + edit
$route['delete_paper']       = 'QuestionPaperController/delete_papper';



$route['slider_list'] = 'QuestionpaperController/slider_list';
$route['save_slider']   = 'QuestionpaperController/save_slider';
$route['delete_slider'] = 'QuestionpaperController/delete_slider';




$route['accademic_list']      = 'QuestionpaperController/accademic_list';
$route['save_academic']       = 'QuestionpaperController/save_academic';     // add + edit
$route['delete_accademic']    = 'QuestionpaperController/delete_accademic';
$route['set_current_year']    = 'QuestionpaperController/set_current_year';  // NEW
$route['toggle_academic_active'] = 'QuestionpaperController/toggle_academic_active'; // NEW

$route['term_list']   = 'QuestionpaperController/term_list';
$route['save_term']   = 'QuestionpaperController/save_term';     // add + edit
$route['delete_term'] = 'QuestionpaperController/delete_term';

$route['teacherdashboard'] = 'TeacherController/teacherdashboard';


$route['divition_list']         = 'StudentController/divition_list';
$route['insert_divition']       = 'StudentController/insert_divition';        // NEW
$route['update_divition']       = 'StudentController/update_divition';
$route['delete_divition_table'] = 'StudentController/delete_divition_table';



$route['class_divition_list']   = 'StudentController/class_divition_list';
$route['save_class_division']   = 'StudentController/save_class_division';
$route['delete_class_division'] = 'StudentController/delete_class_division';



 $route['students_list']        = 'StudentController/students_list';
 $route['students_list/(:num)'] = 'StudentController/students_list/$1';
 $route['save_student']         = 'StudentController/save_student';     // add + edit
 $route['delete_student']       = 'StudentController/delete_student';
 $route['get_divisions/(:num)'] = 'StudentController/get_divisions/$1';


$route['exam_list']   = 'ExamController/exam_list';
$route['save_exam']   = 'ExamController/save_exam';     // add + edit
$route['delete_exam'] = 'ExamController/delete_exam';


$route['allocation_list']   = 'ExamController/allocation_list';
$route['save_allocation']   = 'ExamController/save_allocation';     // add + edit
$route['delete_allocation'] = 'ExamController/delete_allocation';

$route['Marksentry_list'] = 'ExamController/Marksentry_list';
$route['marks_sheet']     = 'ExamController/marks_sheet';
$route['save_marks']      = 'ExamController/save_marks';
$route['delete_marks']    = 'ExamController/delete_marks';
$route['final_submit']    = 'ExamController/final_submit';

$route['change_password'] = 'ChangePasswordController/change_password';
$route['update_account'] = 'ChangePasswordController/update_account';

$route['user_role_list']   = 'QuestionpaperController/user_role_list';
$route['user_role_save']   = 'QuestionpaperController/user_role_save';
$route['user_role_update'] = 'QuestionpaperController/user_role_update';
$route['user_role_delete'] = 'QuestionpaperController/user_role_delete';
$route['user_role_status'] = 'QuestionpaperController/user_role_status';


$route['menu_list']   = 'PermissionController/menu_list';
$route['menu_save']   = 'PermissionController/menu_save';
$route['menu_update'] = 'PermissionController/menu_update';
$route['menu_delete'] = 'PermissionController/menu_delete';
$route['menu_status'] = 'PermissionController/menu_status';

$route['add_menu_permission']   = 'PermissionController/add_menu_permission';
$route['get_role_permissions']  = 'PermissionController/get_role_permissions';
$route['save_menu_permissions'] = 'PermissionController/save_menu_permissions';


$route['employee_list']     = 'EmployeeController/employee_list';
$route['employee_save']     = 'EmployeeController/employee_save';
$route['employee_update']   = 'EmployeeController/employee_update';
$route['employee_delete']   = 'EmployeeController/employee_delete';


$route['employee_divisions'] = 'EmployeeController/employee_divisions';


$route['contact']   = 'EnquiryController/contact';
$route['read_contact'] = 'EnquiryController/read_contact';
$route['admission']      = 'EnquiryController/admission';
$route['read_admission'] = 'EnquiryController/read_admission';

$route['promotion']         = 'PromotionController/promotion';
$route['promotion_run_all'] = 'PromotionController/run_all';


$route['division_allocation']      = 'DivisionAllocationController/division_allocation';
$route['division_students']        = 'DivisionAllocationController/students';
$route['division_allocation_save'] = 'DivisionAllocationController/save';



$route['mark_entry_list'] = 'Reports/mark_entry_list';


/* ---------- Fee management ---------- */
$route['fee_master']             = 'FeeController/fee_master';
$route['fee_save']               = 'FeeController/fee_save';
$route['fee_delete']             = 'FeeController/fee_delete';
$route['fee_copy']               = 'FeeController/fee_copy';

$route['fee_allocation']         = 'FeeController/fee_allocation';
$route['fee_allocation_save']    = 'FeeController/fee_allocation_save';
$route['student_fee_options']    = 'FeeController/student_fee_options';

$route['fee_collection']         = 'FeeController/fee_collection';      // Fee Register
$route['fee_students']           = 'FeeController/fee_students';
$route['fee_student_dues']       = 'FeeController/fee_student_dues';
$route['fee_collect']            = 'FeeController/fee_collect';
$route['fee_receipt/(:num)']     = 'FeeController/fee_receipt/$1';

$route['fee_management']         = 'FeeController/fee_management';      // Receipts
$route['fee_management/(:num)']  = 'FeeController/fee_management/$1';
$route['fee_receipt_cancel']     = 'FeeController/fee_receipt_cancel';

/* ---------- Reports ---------- */
$route['reports/students']                 = 'FeeReports/students';
$route['reports/fees']                     = 'FeeReports/fees';
$route['reports/student_list']             = 'FeeReports/student_list';
$route['reports/student_class_wise']       = 'FeeReports/student_class_wise';
$route['reports/student_fee_list']         = 'FeeReports/student_fee_list';
$route['reports/fee_collection_register']  = 'FeeReports/fee_collection_register';
$route['reports/student_fee_register']     = 'FeeReports/student_fee_register';
$route['reports/fee_paid_class_wise']      = 'FeeReports/fee_paid_class_wise';
$route['reports/fee_due_register']         = 'FeeReports/fee_due_register';
$route['reports/fee_due_amount_register']  = 'FeeReports/fee_due_amount_register';
$route['reports/balance_due_register']     = 'FeeReports/balance_due_register';
$route['reports/fee_collection_mismatch']  = 'FeeReports/fee_collection_mismatch';
$route['reports/special_fee_collection']   = 'FeeReports/special_fee_collection';
$route['reports/special_fee_due']          = 'FeeReports/special_fee_due';


$route['Migration'] = 'Migration';
$route['logout'] = 'Login/logout';


/* ---------- School store ---------- */
$route['store_items']                 = 'StoreController/store_items';
$route['store_item_code']             = 'StoreController/store_item_code';
$route['store_item_save']             = 'StoreController/store_item_save';
$route['store_item_delete']           = 'StoreController/store_item_delete';
$route['store_category_save']         = 'StoreController/store_category_save';
$route['store_suppliers']             = 'StoreController/store_suppliers';
$route['store_supplier_save']         = 'StoreController/store_supplier_save';
$route['store_supplier_delete']       = 'StoreController/store_supplier_delete';
$route['store_kits']                  = 'StoreController/store_kits';
$route['store_kit_save']              = 'StoreController/store_kit_save';
$route['store_kit_delete']            = 'StoreController/store_kit_delete';
$route['store_stock']                 = 'StoreController/store_stock';
$route['store_adjust']                = 'StoreController/store_adjust';
$route['store_purchases']             = 'StoreController/store_purchases';
$route['store_purchase_new']          = 'StoreController/store_purchase_new';
$route['store_purchase_save']         = 'StoreController/store_purchase_save';
$route['store_purchase/(:num)']       = 'StoreController/store_purchase_view/$1';
$route['store_purchase_cancel']       = 'StoreController/store_purchase_cancel';
$route['store_invoices']              = 'StoreController/store_invoices';
$route['store_invoice_new']           = 'StoreController/store_invoice_new';
$route['store_invoice_save']          = 'StoreController/store_invoice_save';
$route['store_invoice/(:num)']        = 'StoreController/store_invoice/$1';
$route['store_payment_save']          = 'StoreController/store_payment_save';
$route['store_invoice_cancel']        = 'StoreController/store_invoice_cancel';
$route['store_students']              = 'StoreController/store_students';

$route['reports/store']                   = 'StoreReports/index';
$route['reports/store_stock_summary']     = 'StoreReports/stock_summary';
$route['reports/store_low_stock']         = 'StoreReports/low_stock';
$route['reports/store_stock_ledger']      = 'StoreReports/stock_ledger';
$route['reports/store_sales_register']    = 'StoreReports/sales_register';
$route['reports/store_item_sales']        = 'StoreReports/item_sales';
$route['reports/store_collections']       = 'StoreReports/collections';
$route['reports/store_outstanding']       = 'StoreReports/outstanding';
$route['reports/store_purchase_register'] = 'StoreReports/purchase_register';

/* ---------- Accounts ---------- */
$route['acc_vouchers']            = 'AccountsController/acc_vouchers';
$route['acc_voucher_new']         = 'AccountsController/acc_voucher_new';
$route['acc_voucher_edit/(:num)'] = 'AccountsController/acc_voucher_edit/$1';
$route['acc_voucher_save']        = 'AccountsController/acc_voucher_save';
$route['acc_voucher/(:num)']      = 'AccountsController/acc_voucher/$1';
$route['acc_voucher_cancel']      = 'AccountsController/acc_voucher_cancel';
$route['acc_ledgers']             = 'AccountsController/acc_ledgers';
$route['acc_ledger_save']         = 'AccountsController/acc_ledger_save';
$route['acc_ledger_delete']       = 'AccountsController/acc_ledger_delete';
$route['acc_group_save']          = 'AccountsController/acc_group_save';
$route['acc_group_delete']        = 'AccountsController/acc_group_delete';
$route['acc_banking']             = 'AccountsController/acc_banking';
$route['acc_reconcile/(:num)']    = 'AccountsController/acc_reconcile/$1';
$route['acc_reconcile_save']      = 'AccountsController/acc_reconcile_save';
$route['acc_settings']            = 'AccountsController/acc_settings';
$route['acc_settings_save']       = 'AccountsController/acc_settings_save';
$route['acc_sync']                = 'AccountsController/acc_sync';

$route['reports/accounts']              = 'AccountReports/index';
$route['reports/acc_day_book']          = 'AccountReports/day_book';
$route['reports/acc_ledger']            = 'AccountReports/ledger';
$route['reports/acc_cash_book']         = 'AccountReports/cash_book';
$route['reports/acc_bank_book']         = 'AccountReports/bank_book';
$route['reports/acc_trial_balance']     = 'AccountReports/trial_balance';
$route['reports/acc_profit_loss']       = 'AccountReports/profit_loss';
$route['reports/acc_balance_sheet']     = 'AccountReports/balance_sheet';
$route['reports/acc_receipts_payments'] = 'AccountReports/receipts_payments';
$route['reports/acc_party_balances']    = 'AccountReports/party_balances';

/* ---------- Attendance ---------- */
$route['attendance']                  = 'AttendanceController/attendance';
$route['attendance_save']             = 'AttendanceController/attendance_save';
$route['reports/attendance_register'] = 'AttendanceController/attendance_register';
$route['reports/attendance_summary']  = 'AttendanceController/attendance_summary';

/* ---------- Parent portal: admin side ---------- */
$route['parent_accounts']        = 'ParentAdmin/parent_accounts';
$route['parent_student_search']  = 'ParentAdmin/parent_student_search';
$route['parent_save']            = 'ParentAdmin/parent_save';
$route['parent_reset_password']  = 'ParentAdmin/parent_reset_password';
$route['parent_delete']          = 'ParentAdmin/parent_delete';
$route['parent_bulk_create']     = 'ParentAdmin/parent_bulk_create';
$route['parent_notices']         = 'ParentAdmin/parent_notices';
$route['parent_notice_save']     = 'ParentAdmin/parent_notice_save';
$route['parent_notice_delete']   = 'ParentAdmin/parent_notice_delete';
$route['parent_publish']         = 'ParentAdmin/parent_publish';
$route['parent_publish_toggle']  = 'ParentAdmin/parent_publish_toggle';

/* ---------- Parent portal: parents ---------- */
$route['parent']                 = 'ParentPortal/index';
$route['parent/login']           = 'ParentPortal/login';
$route['parent/logout']          = 'ParentPortal/logout';
$route['parent/child/(:num)']    = 'ParentPortal/child/$1';
$route['parent/attendance']      = 'ParentPortal/attendance';
$route['parent/results']         = 'ParentPortal/results';
$route['parent/fees']            = 'ParentPortal/fees';
$route['parent/store']           = 'ParentPortal/store';
$route['parent/notices']         = 'ParentPortal/notices';
$route['parent/profile']         = 'ParentPortal/profile';
$route['parent/password']        = 'ParentPortal/password';
$route['parent/receipt/(:num)']  = 'ParentPortal/receipt/$1';
$route['parent/invoice/(:num)']  = 'ParentPortal/invoice/$1';
