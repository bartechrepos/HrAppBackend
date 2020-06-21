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

//* Task 2020-06-21 api endpoints *//

Route::get('employees','EmployeeCtrl@index');
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
Route::post('push_clear_today','InoutCtrl@pushClearToday');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Login
Route::post('login', 'UserCtrl@login');
