<?php

namespace App\Http\Controllers;

use App\Standalone\Inout;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InoutCtrl extends Controller
{

    //
    public function push(Request $request, $inout )
    {
        // GET APP MODE

        if(env('APP_MODE','') == 'standalone') {
            $inOutRecord = new Inout();
            $inOutRecord->employee_id = $request->employee_id;
            $inOutRecord->push_time = $request->time? $request->time : Carbon::now() ;
            $inOutRecord->push = $inout;
            $inOutRecord->save();
        }
        return response()->json(null, 201);
    }

    public function getEmpInouts(Request $request )
    {
        $mapped = [];
        if(env('APP_MODE','') == 'standalone') {
            $collection = Inout::where('employee_id',$request->input('employee_id'))->get();
            $mapped = $collection->map(function ($item, $key) {
                $item->time = $item->push_time ? timeToH($item->push_time): timeToH($item->created_at);
                $item->day = $item->push_time ? timeToD($item->push_time): timeToD($item->created_at);
                $item->location = ["name_ar"=>"dummy","ref"=>null];
                return $item;
            });
        }
        return response()->json($mapped, 200);
    }

    public function getEmpTodayInouts(Request $request )
    {
        $mapped = [];
        if(env('APP_MODE','') == 'standalone') {
            // TODO function for mapping
            $collection = Inout::whereDate('push_time','=',Carbon::today()->toDateString())->where('employee_id',$request->input('employee_id'))->get();
            $mapped = $collection->map(function ($item, $key) {
                $item->time = $item->push_time ? timeToH($item->push_time): timeToH($item->created_at);
                $item->day = $item->push_time ? timeToD($item->push_time): timeToD($item->created_at);
                $item->location = ["name_ar"=>"dummy","ref"=>null];
                return $item;
            });
        }
        return response()->json($mapped, 200);
    }

    public function pushin(Request $request)
    {
        $this->push($request, 0);
        return response()->json(null, 201);
    }

    public function pushout(Request $request)
    {
        $this->push($request, 1);
        return response()->json(null, 201);
    }

    public function pushClearToday(Request $request)
    {
        if(env('APP_MODE','') == 'standalone') {
            Inout::whereDate('created_at','=',Carbon::today()->toDateString())
            ->where('employee_id','=',$request->employee_id)
            ->delete();

            Inout::whereDate('push_time','=',Carbon::today()->toDateString())
            ->where('employee_id','=',$request->employee_id)
            ->delete();
        }
        return response()->json(null, 204);
    }
}
