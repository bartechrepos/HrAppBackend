<?php

namespace App\Http\Controllers;

use App\Standalone\Employee;
use Illuminate\Http\Request;

class EmployeeCtrl extends Controller
{
    //
    public function index()
    {
        //return Employee::all('ID','ArabicDescription','MobileNo');
        return Employee::all();
    }

    public function get($id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $employee = Employee::findOrFail($id);
            if($employee){
                return response()->json( $employee, 200);
            }
        }
    }

    public function add(Request $request)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $employee = new Employee();
            $employee->ar_name = $request->ar_name;
            $employee->en_name = $request->en_name;
            $employee->save();
        }
        return response()->json(null, 201);
    }

    public function update(Request $request, $id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $employee = Employee::findOrFail($id);
            if($employee) {
                $employee->ar_name = $request->ar_name;
                $employee->en_name = $request->en_name;
                $employee->save();
                return $employee;
            }
        }
    }

    public function delete($id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $employee = Employee::findOrFail($id);
            if($employee){
                $employee->delete();
                return response()->json(null, 204);
            }
        }
    }
}
