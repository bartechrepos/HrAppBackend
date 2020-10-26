<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Protected route
Route::get('employees_protected','EmployeeCtrl@index')->middleware('auth:api');

Route::post('company','CompanyCtrl@addCompany');

Route::get('request_types','RequestTypeCtrl@index');
Route::post('request_type','RequestTypeCtrl@add');
Route::put('request_type/{id}','RequestTypeCtrl@update');
Route::delete('request_type/{id}','RequestTypeCtrl@delete');

//* Task 2020-06-24 api endpoints *//
Route::get('emp_requests','EmpRequestCtrl@index');
Route::put('emp_request/{id}','EmpRequestCtrl@update');

Route::post('emp_request','EmpRequestCtrl@add');
Route::delete('emp_request/{id}','EmpRequestCtrl@delete');

Route::get('employees','EmployeeCtrl@index');
Route::get('employee/{id}','EmployeeCtrl@get');
Route::post('employee','EmployeeCtrl@add');
Route::delete('employee/{id}','EmployeeCtrl@delete');
Route::put('employee/{id}','EmployeeCtrl@update');

Route::get('branches','CompanyCtrl@getBranches');
Route::post('branch','CompanyCtrl@addBranch');
Route::delete('branch/{id}','CompanyCtrl@deleteBranch');
Route::put('branch/{id}','CompanyCtrl@updateBranch');
Route::get('branch/autodetect','CompanyCtrl@autoDetectBranch');

Route::get('departments','DepartmentCtrl@index');
Route::post('department','DepartmentCtrl@add');
Route::delete('department/{id}','DepartmentCtrl@delete');
Route::put('department/{id}','DepartmentCtrl@update');

Route::get('settings','SettingCtrl@index');

Route::post('attend','HRCtrl@logAttend');
Route::post('request_vacation','HRCtrl@requestVacation');
Route::get('vacation_types','HRCtrl@getVacationsTypes');

Route::post('pushin','InoutCtrl@pushin');
Route::post('pushout','InoutCtrl@pushout');
Route::get('get_emp_inouts','InoutCtrl@getEmpInouts');

Route::post('push_clear_today','InoutCtrl@pushClearToday');
Route::get('push_get_today','InoutCtrl@getEmpTodayInouts');

// IMIS Scanner
Route::post('Scanner/login','ImisScannerCtrl@login');
Route::post('imis/login','ImisScannerCtrl@login');
Route::post('Scanner/header_guid','ImisScannerCtrl@transHeaderGUID');
Route::get('Scanner/store_trans_types','ImisScannerCtrl@getStoreTransactionsTypes');
Route::get('Scanner/sdelivery_types','ImisScannerCtrl@getSalesDeliveryTypes');
Route::get('Scanner/stock_count_types','ImisScannerCtrl@getStockCountTpes');
Route::get('Scanner/good_receipt_types','ImisScannerCtrl@getGoodReceiptTypes');
Route::post('Scanner/post_codes','ImisScannerCtrl@postCodes');

Route::get('Scanner/RFIDLog','ImisScannerCtrl@getRFIDLogNotAcknowledge');
Route::get('Scanner/RFIDLogAll','ImisScannerCtrl@getRFIDLogAll');
Route::post('Scanner/update_acknowledge','ImisScannerCtrl@updateAcknowledge');
Route::post('Scanner/get_otp','ImisScannerCtrl@getOTP');
Route::post('Scanner/login_otp','ImisScannerCtrl@loginOTP');
Route::post('Scanner/random_theft_log','ImisScannerCtrl@generateRandomTheftLog');
Route::get('Scanner/item_image/{ItemId}.png','ImisScannerCtrl@getItemImage');

// Imis's
Route::get('imis_branches','CompanyCtrl@getImisBranches');
Route::get('imis_warehouses','ImisWarehouseCtrl@index');

// Sales Order Work
Route::get('imis/items','ImisItemsCtrl@getItems');
Route::get('imis/item/specs','ImisItemsCtrl@getItemSpecification');
Route::get('imis/item/spec/list','ImisItemsCtrl@getSingleSpecificationList');
Route::get('imis/customers','ImisCustomersCtrl@getCustomers');
Route::get('imis/salesorders/types','ImisSalesOrderCtrl@getSalesOrderTypes');
Route::get('imis/salesorders/currencies','ImisSalesOrderCtrl@getCurrencies');
Route::post('imis/salesorders','ImisSalesOrderCtrl@insertSalesOrder');
Route::post('imis/salesorders/detail','ImisSalesOrderCtrl@insertSalesOrderDet');
Route::post('imis/salesorders/spec','ImisSalesOrderCtrl@insertSalesOrderDetSpec');
// Panasonic Proxy
// Route::post('ManageAccount/Login','PanaProxyCtrl@login');
Route::post('Proxy/SendSMS','PanaProxyCtrl@SendSMS');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Login
Route::post('login', 'UserCtrl@login');
Route::post('users', 'UserCtrl@register');
Route::get('checkauth', 'UserCtrl@checkUser')->middleware('auth:api');
