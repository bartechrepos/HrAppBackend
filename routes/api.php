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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('Employees','EmployeeCtrl@index');

Route::post('Company/AddCompany','CompanyCtrl@addCompany');
Route::post('Company/AddBranch','CompanyCtrl@addBranch');
Route::get('Company/Branches','CompanyCtrl@getBranches');
Route::delete('Company/Branch/{id}','CompanyCtrl@deleteBranch');
Route::get('Company/AutodetectBranch','CompanyCtrl@autoDetectBranch');

Route::get('APP/Settings','SettingCtrl@index');

Route::post('HR/Attend','HRCtrl@logAttend');
Route::post('HR/RequestVacation','HRCtrl@requestVacation');
Route::get('HR/VacationTypes','HRCtrl@getVacationsTypes');
