<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class CompanyCtrl extends Controller
{

    public function getBranches()
    {
        $results = DB::select(
            'EXEC SP_Branch_FindAll @CompanyId=:CompanyId ;',
            [
                ':CompanyId' => 1
            ]
        );
        $collection = collect($results);
        $mapped = $collection->map(function ($item, $key) {
            return [
                'branchNameAr' => $item->ArabicDescription,
                'dummyLatitude' => '30.055760',
                'dummyLongitude' => '31.357623'
            ];
        });
        return response()->json($mapped, 200);
    }

    public function autoDetectBranch()
    {
        // echo distance(30.055760, 31.357623, 30.055073, 31.358675, "K") . " Kilometers<br>"; // 0.126 K
        // echo distance(30.055760, 31.357623, 30.055036, 31.361960, "K") . " Kilometers<br>"; // 0.425 K
        $results = DB::select(
            'EXEC SP_Branch_FindAll @CompanyId=:CompanyId ;',
            [
                ':CompanyId' => 1
            ]
        );
        $collection = collect($results);
        $mapped = $collection->map(function ($item, $key) {
            $newItem = new stdClass();
            $newItem->branchNameAr = $item->ArabicDescription;
            $newItem->dummyLatitude = 30.055760;
            $newItem->dummyLongitude = 31.357623;
            $newItem->distance = distance($newItem->dummyLatitude, $newItem->dummyLongitude, 30.055073, 31.358675, "K");
            return $newItem;
        });
        return response()->json($mapped, 200);
    }
}
