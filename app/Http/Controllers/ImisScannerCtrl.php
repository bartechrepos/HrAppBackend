<?php

namespace App\Http\Controllers;

use App\Imis\ImisEmpUser;
use App\Imis\ImisItemView;
use App\Imis\ImisUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client as TwilloClient;

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

    public function getOTP(Request $request){
        $user = ImisEmpUser::where('Mobile',$request->mobile)->first();
        if($user) {
            $rand_otp= mt_rand(2000,9000);
            $user->Internal = $rand_otp;
            $user->save();
            $account_sid = 'ACa111e434f70ec0010d5019a67c9c1908';
            $auth_token = "bbc654f762bed40091c3438abebbbdc6";
            $twilio_number = "+18442324681";

            $client = new TwilloClient($account_sid, $auth_token);
            $client->messages->create(
                // Where to send a text message (your cell phone?)
                $request->mobile ,
                array(
                    'from' => $twilio_number,
                    'body' => 'Your Code is : '.$rand_otp
                )
            );

            return response()->json( [] , 200);
        } else
            return response()->json( [] , 204);
    }

    public function loginOTP(Request $request){
        $user = ImisEmpUser::where('Mobile',$request->mobile)->where('Internal',$request->otp)->first();
        if($user) {
            return response()->json( $user , 200);
        } else
            return response()->json( [] , 204);
    }

    public function getRFIDLogAll(Request $request){
        //$results = DB::select('EXEC SP_RFIDScannerLog_FindAll @Company= :Company;',
        $sql = <<<EOD
SELECT DISTINCT TOP (20)
[Company],
[Tag],
[ItemId],
[SalesInvoiceId],
[CashTrxId],
[PurchaseInvoiceId],
[Status],
[Acknowledge],
FORMAT(CreatedDate, 'MM-dd-yyyy HH:mm') AS CreatedDate
    FROM [TBL_RFIDScannerLog]
    WHERE [Deleted] = 0
    AND [Status] = :Status
    AND [Company] = :Company Order By CreatedDate Desc ;
EOD;
        $results = DB::select($sql, [
            ':Company' => $request->company,
            ':Status' => $request->status,
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            $itemModel = ImisItemView::where('GUID',$item->ItemId)->first();
            //return var_export($itemModel[0], true);
            if($itemModel)
                return [
                    //'GUID'=> $item->GUID,
                    'Tag' => $item->Tag,
                    'Acknowledge' => $item->Acknowledge,
                    'Status'=> $item->Status,
                    'ItemId'=> $item->ItemId,
                    'CreatedDate'=> $item->CreatedDate,
                    'SalesInvoiceId'=> $item->SalesInvoiceId,
                    'CashTrxId'=> $item->CashTrxId,
                    'PurchaseInvoiceId'=> $item->PurchaseInvoiceId,
                    'ArabicDescription' => $itemModel->ArabicDescription,
                    'Code' => $itemModel->Code,
                    'ItemImage' => 'http://13.90.214.197:8081/hrback/public/api/Scanner/item_image/'.$item->ItemId.'.png'
                ];
            else
                return ['GUID' => $item->GUID];
        });
        return response()->json($mapped , 200);
    }

    public function getRFIDLogNotAcknowledge(Request $request)
    {
        $results = DB::select('EXEC SP_RFIDScannerLog_FindAll_NotAcknowledge @Company= :Company;',
        [
            ':Company' => $request->company,
        ]);
        $collection = collect($results);
        $mapped = $collection->map(function($item, $key) {
            $itemModel = ImisItemView::where('GUID',$item->ItemId)->first();
            //return var_export($itemModel[0], true);
            if($itemModel && $item->Status == 1)
                return [
                    //'GUID'=> $item->GUID,
                    'Tag' => $item->Tag,
                    'Acknowledge' => $item->Acknowledge,
                    'Status'=> $item->Status,
                    'ItemId'=> $item->ItemId,
                    'CreatedDate'=> $item->CreatedDate,
                    'SalesInvoiceId'=> $item->SalesInvoiceId,
                    'CashTrxId'=> $item->CashTrxId,
                    'PurchaseInvoiceId'=> $item->PurchaseInvoiceId,
                    'ArabicDescription' => $itemModel->ArabicDescription,
                    'Code' => $itemModel->Code,
                    'ItemImage' => 'http://13.90.214.197:8081/hrback/public/api/Scanner/item_image/'.$item->ItemId.'.png'
                ];
            else
                return ['GUID' => $item->GUID];
        });
        return response()->json($mapped , 200);
    }

    public function getItemImage($ItemId)
    {
        $itemModel = ImisItemView::where('GUID',$ItemId)->first();
        return response()->make($itemModel->ItemImage, 200,['Content-Type'=>'image/png']);
    }

    public function generateRandomTheftLog(){
        $results = DB::select('SELECT TOP 1 GUID FROM View_RPT_ItemsData ORDER BY NEWID()');
        $itemId = $results[0]->GUID;
        $results = DB::statement(' insert into TBL_RFIDScannerLog (Company, GUID, ItemId, Status, Tag) Values(1, :GUID, :ItemId, 1, :Tag ) ;',
        [
            ':ItemId' => $itemId,
            ':GUID' => $itemId."101",
            ':Tag' => "1234"
        ]);
        return response()->json(null , 200);
    }

    public function updateAcknowledge(Request $request) {
        /*
        $results = DB::statement(' update TBL_RFIDScannerLog set Acknowledge = 1 where GUID= :GUID;',
        [
            ':GUID' => $request->GUID,
        ]);
        */
        $results = DB::statement('EXEC SP_RFIDScannerLog_UpdateAcknowledge @Tag= :Tag, @CreatedDate= :CreatedDate, @UpdatedBy= :UpdatedBy, @UpdateMacNo= :UpdateMacNo', [
            ':Tag' => $request->Tag,
            ':CreatedDate' => $request->CreatedDate,
            ':UpdatedBy' => $request->UpdatedBy,
            ':UpdateMacNo' => 0,
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
