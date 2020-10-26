<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imis\ImisCustomer;

class ImisCustomersCtrl extends Controller
{

    public function getCustomers(Request $request)
    {
        $mapped = [];
        
        $collection = ImisCustomer::all('*');
        $mapped = $collection->map(function ($item, $key) {
            return [
                'GUID' => $item->GUID,
                'Name' => $item->Name,
            ];
        });
        return response()->json($mapped, 200);
    }

}
