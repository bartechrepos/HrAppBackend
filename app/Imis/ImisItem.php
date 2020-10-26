<?php

namespace App\Imis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImisItem extends Model
{
    //
    protected $table = null;

    public static function all($columns = array('*')) {
        $results = DB::select(
            'EXEC SP_Items_FindAllWithOutBalance @Company=:Company , @WhereStatement=:WhereStatement ;',
            [
                ':Company' => 1,
                ':WhereStatement' => 'AND ItemClass = 3'
            ]
        );
        return collect($results);
    }

    public static function getItemspcifications($columns = array('*'),$GUID) {
        $results = DB::select(
            'EXEC SP_Items_FindSingle @GUID=:GUID ;',
            [
                ':GUID' => $GUID,
            ]
        );
        $itemData = (object)[
            'GUID' => $GUID,
            'ArabicDescription' => $results[0]->ArabicDescription,
            'SpecificationId' => $results[0]->SpecificationId,
        ];

        $results = DB::select(
            'EXEC SP_SpecificationElement_FindAll @HeadId=:HeadId ;',
            [
                ':HeadId' => $itemData->SpecificationId,
            ]
        );
        $specsResults = collect($results);
        return ["itemData"=> $itemData, "specsResults"=>$specsResults];
    }

    public static function getSingleSpecificationList($columns = array('*'),$HeadId) {
        $results = DB::select(
            'EXEC SP_SpecificationElementDet_FindAll @HeadId=:HeadId ;',
            [
                ':HeadId' => $HeadId,
            ]
        );
        return collect($results);
    }
}
