<?php

namespace App\Http\Controllers;

use App\Standalone\RequestType;
use Illuminate\Http\Request;

class RequestTypeCtrl extends Controller
{
    //
    public function index(Request $request)
    {
        $mapped = [];
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $mapped = RequestType::all();
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
            $requestType = new RequestType();
            $requestType->ar_name = $request->ar_name;
            $requestType->ar_description = $request->ar_description;
            $requestType->en_name = $request->en_name;
            $requestType->en_description = $request->en_description;
            $requestType->save();
        }
        return response()->json(null, 201);
    }

    public function update(Request $request, $id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $requestType = RequestType::findOrFail($id);
            if($requestType) {
                $requestType->ar_name = $request->ar_name;
                $requestType->ar_description = $request->ar_description;
                $requestType->en_name = $request->en_name;
                $requestType->en_description = $request->en_description;
                $requestType->save();
                return $requestType;
            }
        }
    }
}
