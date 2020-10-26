<?php

namespace App\Imis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImisSalesOrder extends Model
{
    //
    protected $table = null;

    public static function getSalesOrderTypes($columns = array('*')) {
        $results = DB::select(
            'EXEC SP_SalesOrderTypes_FindAll @Company=:Company , @BranchID=:BranchID ;',
            [
                // Just ignore this error
                ':Company' => 1,
                ':BranchID'=>1,
            ]
        );
        return collect($results);
    }

    public static function getCurrencies($columns = array('*')) {
        $results = DB::select(
            'EXEC SP_Currency_FindAll @Company=:Company  ;',
            [
                // Just ignore this error
                ':Company' => 1
            ]
        );
        return collect($results);
    }
}

