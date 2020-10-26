<?php

namespace App\Imis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImisCustomer extends Model
{
    //
    protected $table = null;

    public static function all($columns = array('*')) {
        $results = DB::select(
            'EXEC SP_Customer_FindAllWithOutBalance @Company=:Company , @WhereStatement=:WhereStatement , @Branch=:Branch , @UserId=:UserId ;',
            [
                // Just ignore this error
                ':Company' => 1,
                ':Branch'=>0,
                ':UserId'=>0,
                ':WhereStatement' => ''
            ]
        );
        return collect($results);
    }
}

