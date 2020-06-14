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
            $mapped = Department::where()->get();;
        }
        else {
            $mapped = [];
        }

        return response()->json($mapped, 200);
    }

    public function addDepartment(Request $request)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $branch = new Department();
            $branch->ar_name = $request->ar_name;
            $branch->ar_description = $request->ar_description;
            $branch->save();
        }
    }
}
