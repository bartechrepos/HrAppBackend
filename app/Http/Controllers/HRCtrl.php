<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HRCtrl extends Controller
{
    public function logAttend()
    {
        $ok = DB::update("EXEC SP_HR_EmployeeAttendanceLog_Insert
        @PhoneNumber=:PhoneNumber,
        @InOutMode=:InOutMode,
        @Date=:Date ;",
        [
            ':PhoneNumber' => '01101',
            ':InOutMode' => 0,
            ':Date' => '2020-05-12'
        ]);
        return response()->json($ok , 200);
    }


    public function getVacationsTypes()
    {
        $results = DB::select('EXEC SP_HR_VacationsType_FindAll @Company=:Company ;',
        [
            ':Company' => 1
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            return ['typeNameAr'=> $item->ArabicDescription];
        });
        return response()->json($mapped , 200);
    }

    public function requestVacation()
    {
        $results = DB::select("
        EXEC [dbo].[SP_HR_Vacations_Insert] @PhoneNumber = '0100',
        @Type = :Type,
        @Dayes = :Dayes,
        @SerialNo = '',
        @FromDate = :FromDate,
        @ToDate = :ToDate;
        ",
        [
            ':Type' => '02003-BFAE6B9E-44DB-4C22-BA93-67B5CE2BD6AC',
            ':Dayes' => 1,
            ':FromDate' => '2020-05-25',
            ':ToDate' => '2020-05-25',
        ]);
        var_dump($results);

        // GET Vacation Row itself
    }
}
