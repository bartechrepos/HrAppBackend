<?php

namespace App\Http\Controllers;

use App\Standalone\Employee;

class EmployeeCtrl extends Controller
{
    //
    public function index()
    {
        //return Employee::all('ID','ArabicDescription','MobileNo');
        return Employee::all();
    }

}
