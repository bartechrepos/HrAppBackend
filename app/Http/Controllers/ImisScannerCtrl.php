<?php

namespace App\Http\Controllers;

use App\Imis\ImisItemView;
use App\Imis\ImisUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImisScannerCtrl extends Controller
{
    //
    public function login(Request $request)
    {
        $data= ImisUser::login($request);
        $user = [
            'ID' => $data[0]->ID,
            'Branch' => $data[0]->Branch,
            'GUID' => $data[0]->GUID,
            'ArabicDescription' => $data[0]->ArabicDescription,
            'EnglishDescription' => $data[0]->EnglishDescription,
            'UserName' => $data[0]->UserName,
            // 'Password' => $data[0]->Password,
            'Deleted' => $data[0]->Deleted
        ];
        return response()->json( $user , 200);
        //return var_export($data[0], true);
    }

    public function getScannerLogNotAcknowledge(Request $request)
    {
        $results = DB::select('EXEC SP_RFIDScannerLog_FindAll_NotAcknowledge @Company= :Company;',
        [
            ':Company' => $request->company,
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            $itemModel = ImisItemView::where('GUID',$item->ItemId)->first();
            //return var_export($itemModel[0], true);
            if($itemModel)
                return [
                    'GUID'=> $item->GUID,
                    'Tag' => $item->Tag,
                    'Status'=> $item->Status,
                    'ItemId'=> $item->ItemId,
                    'ArabicDescription' => $itemModel->ArabicDescription,
                    'Code' => $itemModel->Code,
                    'ItemImage' => pack('H*',$itemModel->ItemImage)
                ];
            else
                return ['GUID' => $item->GUID];
        });
        return response()->json($mapped , 200);
    }

    public function generateRandomTheftLog(){
        $results = DB::select('SELECT TOP 1 GUID FROM View_RPT_ItemsData ORDER BY NEWID()');
        $itemId = $results[0]->GUID;
        $results = DB::statement(' insert into TBL_RFIDScannerLog (Company, GUID, ItemId, Status) Values(1, :GUID, :ItemId, 1) ;',
        [
            ':ItemId' => $itemId,
            ':GUID' => $itemId."101",
        ]);
        return response()->json(null , 200);
    }

    public function updateAcknowledge(Request $request) {
        $results = DB::statement(' update TBL_RFIDScannerLog set Acknowledge = 1 where GUID= :GUID;',
        [
            ':GUID' => $request->GUID,
        ]);
        return response()->json(null , 200);
    }

    public function transHeaderGUID(Request $request) {
/*
        $sql = <<<EOD
    EXEC SP_GoodDeliveryNote_Insert
    @ID = :ID OUTPUT,
    @Company = :Company,
    @Branch = :Branch,
    @GUID = :GUID OUTPUT,
    @SerialNo = :SerialNo,
    @BranchId = :BranchId,
    @Date = :Date,
    @Type = :Type,
    @TypeId = :TypeId,
    @StoreKeeperId = :StoreKeeperId,
    @FromWarehouseId = :FromWarehouseId,
    @ToWarehouseId = :ToWarehouseId,
    @CustomerId = :CustomerId,

    @CreatedBy = :CreatedBy,
    @CreatedMacNo = :CreatedMacNo;
EOD;

        $results = DB::select($sql,[
            ':ID' => null,
            ':Company' => $request->company,
            ':Branch' => $request->branch_id,
            ':GUID' => null,
            ':SerialNo' => '',
            ':BranchId' => $request->branch_id,
            ':Date' => Carbon::today()->toDateString(),
            ':Type' => $request->type,
            ':TypeId' => $request->type_id,
            ':StoreKeeperId' => $request->emp_id,
            ':FromWarehouseId' => $request->warehouse_id,
            ':ToWarehouseId' => '',
            ':CustomerId' => '',

            ':CreatedBy' => 0,
            ':CreatedMacNo' => 0
        ]);
*/

        $sql = <<<EOD

        DECLARE	@return_value int,
                @ID int,
                @GUID varchar(50),
                @Date DATETIME,
                @SerialNo varchar(50),
                @ToWarehouseId varchar(50),
                @CustomerId varchar(50),
                @VendorId varchar(50),
                @ScheduleId varchar(50),
                @CreatedBy int,
                @CreatedMacNo int

        EXEC	@return_value = [dbo].[SP_GoodDeliveryNote_Insert]
                @ID = @ID OUTPUT,
                @Company = :Company,
                @Branch = :Branch,
                @GUID = @GUID OUTPUT,
                @SerialNo = '',
                @BranchId = :BranchId,
                @Date = :Date,
                @Type = :Type,
                @TypeId = :TypeId,
                @StoreKeeperId = :StoreKeeperId,
                @FromWarehouseId = :FromWarehouseId,
                @ToWarehouseId = '',
                @CustomerId = '',
                @VendorId = '',
                @ScheduleId = '',
                @CreatedBy = 0,
                @CreatedMacNo = 0

        SELECT	@ID as '@ID',
                @GUID as '@GUID'

        SELECT	'Return Value' = @return_value ;
EOD;

        $results = DB::select($sql,[
            ':Company' => $request->company,
            ':Branch' => $request->branch_id,
            ':BranchId' => $request->branch_id,
            ':Date' => Carbon::today()->toDateString(),
            ':Type' => $request->type,
            ':TypeId' => $request->type_id,
            ':StoreKeeperId' => $request->emp_id,
            ':FromWarehouseId' => $request->warehouse_id
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            return [
                'GUID'=> $item->GUID,
            ];
        });
        return response()->json($mapped , 200);

    }

    public function postCodes(Request $request)
    {
        foreach($request->input('codes') as $code) {
            DB::statement('EXEC SP_GoodDeliveryNoteDetails_Insert @Company= :Company, @Branch= :Branch, @HeadId= :HeadId, @Barcode= :Barcode, @CreatedBy= :CreatedBy ;',
            [
                ':Company' => $request->company,
                ':Branch' => $request->branch_id,
                ':HeadId' => $request->head_guid,
                ':Barcode' => $code,
                ':CreatedBy' => 0
            ]);
        }

        // return var_export($request->input('codes'), true);

        return response()->json(null , 201);
    }


    // 0: store transaction

    public function getStoreTransactionsTypes(Request $request)
    {
        $results = DB::select('EXEC SP_StoreTransType_FindAll @Company= :Company, @BranchID= :BranchID ;',
        [
            ':Company' => $request->company,
            ':BranchID' => $request->branch_id,
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            return [
                'GUID'=> $item->GUID,
                'ArabicDescription' => $item->ArabicDescription
            ];
        });
        return response()->json($mapped , 200);
    }

    // 1: Sales delivery
    public function getSalesDeliveryTypes(Request $request)
    {
        $results = DB::select('EXEC SP_SalesDeliveryTypes_FindAll @Company= :Company, @BranchID= :BranchID ,@WhereStatement= :WhereStatement ;',
        [
            ':Company' => $request->company,
            ':BranchID' => $request->branch_id,
            ':WhereStatement' => "",
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            return [
                'GUID'=> $item->GUID,
                'ArabicDescription' => $item->ArabicDescription
            ];
        });
        return response()->json($mapped , 200);
    }

    // 2: Stock Count
    public function getStockCountTpes(Request $request)
    {
        $results = DB::select('EXEC SP_StockCountEntryType_FindAll @Company= :Company, @BranchID= :BranchID ;',
        [
            ':Company' => $request->company,
            ':BranchID' => $request->branch_id
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            return [
                'GUID'=> $item->GUID,
                'ArabicDescription' => $item->ArabicDescription
            ];
        });
        return response()->json($mapped , 200);
    }

    // 3: Good Receipt
    public function getGoodReceiptTypes(Request $request)
    {
        $results = DB::select('EXEC SP_GoodReceiptDefinition_FindAll @Company= :Company, @BranchID= :BranchID ,@WhereStatement= :WhereStatement ;',
        [
            ':Company' => $request->company,
            ':BranchID' => $request->branch_id,
            ':WhereStatement' => "",
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            return [
                'GUID'=> $item->GUID,
                'ArabicDescription' => $item->ArabicDescription
            ];
        });
        return response()->json($mapped , 200);
    }

}
