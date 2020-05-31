<?php

namespace App\Imis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImisBranch extends Model
{
    //
    protected $table = null;

    public static function all($columns = array('*'), $companyId = null) {
        $results = DB::select(
            'EXEC SP_Branch_FindAll @CompanyId=:CompanyId ;',
            [
                // Just ignore this error
                ':CompanyId' => $companyId
            ]
        );
        return collect($results);
    }
}
