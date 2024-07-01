<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Dashboard::index');

$routes->group("users", ["namespace" => "App\Controllers"], function ($routes) {
	$routes->get("/", "Users::index");
	$routes->get("archive", "Users::archive");
	$routes->get("unarchive_user", "Users::unarchive_user");
    $routes->get("users-datatable", "Users::users_datatable");
    $routes->get("archives-datatable", "Users::archives_datatable");
    $routes->get("add_user", "Users::add_user");
    $routes->get("get_user", "Users::get_user");
    $routes->get("delete_user", "Users::delete_user");
});

$routes->group("dashboard", ["namespace" => "App\Controllers"], function ($routes) {
    $routes->get("log", "Dashboard::log");
    $routes->post("update_password", "Dashboard::update_password");
});

$routes->group("timesheet", ["namespace" => "App\Controllers"], function ($routes) {
	$routes->get("/", "Timesheet::index");
	$routes->get("timesheet-datatable", "Timesheet::timesheet_datatable");
    $routes->get("timesheet_pdf", "Timesheet::timesheet_pdf");
    $routes->get("update_log", "Timesheet::update_log");
    $routes->get("add_timesheet", "Timesheet::add_timesheet");
});

$routes->group("login", ["namespace" => "App\Controllers"], function ($routes) {
	$routes->get("/", "Login::index");
	$routes->get("login", "Login::login");
    $routes->get("logout", "Login::logout");
    $routes->get("forgot_password", "Login::forgot_password");
    $routes->get("send_forgot_password", "Login::send_forgot_password");
});

$routes->group("leaves", ["namespace" => "App\Controllers"], function ($routes) {
    $routes->get("/", "Leaves::index");
    $routes->get("leave_requests", "Leaves::leave_requests");
    $routes->get("leave-requests-datatable", "Leaves::leave_requests_datatable");
    $routes->post("apply_leave", "Leaves::apply_leave");
    $routes->get("update-leave-status", "Leaves::update_leave_status");
});

$routes->group("payslip", ["namespace" => "App\Controllers"], function ($routes) {
    $routes->get("/", "Payslip::index");
    $routes->get("payslips-datatable", "Payslip::payslips_datatable");
    $routes->get("payslip-details/(:num)/(:any)", "Payslip::payslip_details/$1/$2");
    $routes->get("payslip-pdf/(:num)", "Payslip::payslip_pdf/$1");
});

$routes->group("cron", ["namespace" => "App\Controllers"], function ($routes) {
    $routes->get("update-payroll", "BackgroundController::update_payroll");
});

$routes->group("overtime", ["namespace" => "App\Controllers"], function($routes) {
    $routes->get("/", "Overtime::index");
    $routes->get("ot_requests", "Overtime::ot_requests");
    $routes->get("overtime-requests-datatable", "Overtime::overtime_requests_datatable");
    $routes->post("apply_ot", "Overtime::apply_ot");
    $routes->post("update-ot-status", "Overtime::update_ot_status");
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
