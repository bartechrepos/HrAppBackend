<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imis\ImisItem;

class ImisItemsCtrl extends Controller
{

    public function getItems(Request $request)
    {
        $mapped = [];

        $collection = ImisItem::all('*');
        $mapped = $collection->map(function ($item, $key) {
            return [
                'ArabicDescription' => $item->ArabicDescription,
                'GUID' => $item->GUID,
            ];
        });
        return response()->json($mapped, 200);
    }

    public function getItemSpecification(Request $request)
    {
        $mapped = [];

        $results_arr = ImisItem::getItemspcifications('*',$request->GUID);
        $collection = $results_arr['specsResults'];

        $mapped = $collection->map(function ($item, $key) {
/* -- Results
ID
Company
Branch
GUID
HeadId
Code
ArabicDescription
EnglishDescription
CategoryId
InputType
ValidateMin
MinValue
ValidateMax
MaxValue
ValidatePattern
Pattern
UnitId
CreatedBy
CreatedDate
UpdatedBy
LastUpdate
Deleted
DeletedBy
DeletedDate
CreatedMacNo
Revision
SyncGUID
rowguid
*/
            return [
                'GUID' => $item->GUID,
                'ArabicDescription' => $item->ArabicDescription,
                'CategoryId' => $item->CategoryId,
                'InputType' => $item->InputType,
                'MinValue' => $item->MinValue,
                'MaxValue' => $item->MaxValue,
                'Pattern' => $item->Pattern,
                'UnitId' => $item->UnitId,
            ];

        });

        return response()->json(["specs"=>$mapped,"item"=>$results_arr['itemData']], 200);
    }

    public function getSingleSpecificationList(Request $request) {

        $mapped = [];

        $collection = ImisItem::getSingleSpecificationList('*',$request->HeadId);
        // LINK ../../../docs/my/sql-examples.md#getSingleSpecificationList
        $mapped = $collection->map(function ($item, $key) {
            return [
                'ArabicDescription' => $item->ArabicDescription,
                'GUID' => $item->GUID,
            ];

        });

        return response()->json($mapped, 200);
    }


}
