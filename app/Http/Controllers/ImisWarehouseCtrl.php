<?php

namespace App\Http\Controllers;

use App\Imis\ImisWarehouse;
use Illuminate\Http\Request;

class ImisWarehouseCtrl extends Controller
{
    //
        //
        public function index()
        {
            $collection = ImisWarehouse::all();
            $mapped = $collection->map(function ($item, $key) {
                return [
                    "ArabicDescription" => $item->ArabicDescription,
                    "ID" => $item->ID,
                    "GUID" => $item->GUID
                ];
            });
            return response()->json( $mapped , 200);
        }
}
