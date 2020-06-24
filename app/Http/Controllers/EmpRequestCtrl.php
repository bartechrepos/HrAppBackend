<?php

namespace App\Http\Controllers;

use App\Standalone\EmpRequest;
use Illuminate\Http\Request;

class EmpRequestCtrl extends Controller
{
    //
    public function index(Request $request)
    {
        $mapped = [];
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $mapped = EmpRequest::with(['employee','resp_employee','request_type','to_department'])->get();
            /*
            $mapped = $collection->map(function ($item, $key) {
                //var_dump($item->employee());
                return $item;
            });
            */
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
            $empRequest = new EmpRequest();
            $empRequest->employee_id = $request->employee_id;
            $empRequest->request_type_id = $request->request_type_id;
            $empRequest->to_dep_id = $request->to_dep_id;
            $empRequest->save();
        }
        return response()->json(null, 201);
    }

    public function update(Request $request, $id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $empRequest = EmpRequest::findOrFail($id);
            if($request) {
                $empRequest->request_status_id = $request->request_status_id;
                $empRequest->save();
                return $empRequest;
            }
        }
    }

    public function delete($id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $empRequest = EmpRequest::findOrFail($id);
            if($empRequest){
                $empRequest->delete();
                return response()->json(null, 204);
            }
        }
    }
}
