<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;

class DepartmentCtrl extends Controller
{

    public function index(Request $request)
    {
        $mapped = [];
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $mapped = Department::all();
        }
        else {
            $mapped = [];
        }

        return response()->json($mapped, 200);
    }

    public function add(Request $request)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $department = new Department();
            $department->ar_name = $request->ar_name;
            $department->ar_description = $request->ar_description;
            $department->save();
        }
        return response()->json(null, 201);
    }

    public function update(Request $request, $id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $department = Department::findOrFail($id);
            if($department) {
                $department->ar_name = $request->ar_name;
                $department->save();
                return $department;
            }
        }
    }

    public function delete($id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $department = Department::findOrFail($id);
            if($department){
                $department->delete();
                return response()->json(null, 204);
            }
        }
    }
}
