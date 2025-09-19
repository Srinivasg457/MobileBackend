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
|	https://codeigniter.com/user_guide/general/routing.html
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


$route['default_controller'] = 'home/index';
$route['404_override'] = 'home/error_404';
$route['translate_uri_dashes'] = FALSE;
$route['index'] = 'home/index';
$route['error-404'] = 'home/error_404';


//site routes
$route['features'] = 'home/features';
$route['faqs'] = 'home/faqs';
$route['pricing'] = 'home/pricing';
$route['contact'] = 'home/contact';
$route['send-message'] = 'home/send_message';
$route['terms-of-service'] = 'home/terms';
$route['blog'] = 'home/blogs';
$route['category/(:any)'] = 'home/category/$1';
$route['post/(:any)'] = 'home/post_details/$1';
$route['page/(:any)'] = 'home/page/$1';


//auth routes
$route['login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['verify'] = 'auth/verify_email';
$route['register_user'] = 'auth/register_user';
$route['create-business'] = 'auth/register_business';
$route['package/(:any)/(:any)'] = 'auth/add_package/$1/$2';
$route['purchase-plan'] = 'auth/purchase';
$route['payment-success/(:any)'] = 'auth/payment_success/$1';
$route['payment-cancel/(:any)'] = 'auth/payment_cancel/$1';
$route['setup'] = 'auth/setup';
$route['change_password'] = 'admin/dashboard/change_password';

//organization sites
$route['admin/view_screenshots'] = 'admin/ScreenshotController';
$route['admin/webcam_screenshots'] = 'admin/ScreenshotController/webcam';
$route['admin/organization_settings'] = 'admin/Organization_settings/Organization_settings_edit';
$route['admin/employee_settings/assigned'] = 'admin/Organization_settings/org_exception_settings';
$route['admin/employee_settings/unassigned'] = 'admin/Organization_settings/no_org_exception_settings';
$route['admin/activity_logs'] = 'admin/Activity_logs';
$route['admin/time_cards'] = 'admin/Activity_logs/Time_Cards';
$route['admin/application_tracker'] = 'admin/Application_Tracker';
$route['admin/live_monitoring'] = 'admin/Monitoring_room';
$route['admin/notification/webcam'] = 'admin/Notification';
$route['admin/notification/desktop'] = 'admin/Notification/desktop';
$route['admin/roles_permissions'] = 'employee/EmployeeRoles';
$route['admin/roles_permissions/role_management'] = 'employee/EmployeeRoles/role';
$route['admin/subscription/current_plan'] = 'admin/subscription/currentPlan';
$route['admin/subscription/upgrade_plan'] = 'admin/subscription';


//employee sites
$route['accept-invitation'] = 'employee/Employee/accept_invitation';
$route['complete-registration'] = 'employee/Employee/complete_registration';
$route['employee/application_tracker'] = 'admin/Application_Tracker/employee_application_tracker';
$route['employee/dashboard'] = 'employee/EmployeeDashboard';
$route['employee/view_screenshots'] = 'employee/Employee/screenshot';
$route['employee/webcam_screenshots'] = 'admin/ScreenshotController/Employeewebcam';
$route['employee/activity_log'] = 'employee/Timecards_manual';
$route['organization'] = 'admin/Organization_settings';
// $route['employee/report'] = 'employee/Report';
$route['employee/time_request'] = 'employee/TimeRequest';



// Chatbot main page
$route['chatbot'] = 'Chatbot';
$route['chatbot/get_response'] = 'chatbot/get_response';
