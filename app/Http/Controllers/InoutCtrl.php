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
            $inOutRecord->push = $inout;
            $inOutRecord->save();
        }
        return response()->json(null, 201);
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
        }
        return response()->json(null, 204);
    }
}
