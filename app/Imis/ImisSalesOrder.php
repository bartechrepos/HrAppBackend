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

    public static function getSalesQuotationTypes($columns = array('*')) {
        $results = DB::select(
            'EXEC SP_SalesOrderTypes_FindAll @Company=:Company ;',
            [
                // Just ignore this error
                ':Company' => 1,
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

    public static function getSalesOrders($columns = array('*')) {

        $sql =<<<EOD
SELECT TOP (1000) [GUID]
        ,[SerialNo]
        ,[SalesOrderTypeArabicDescription]
        ,[SalesOrderTypeEnglishDescription]
        ,[BranchArabicDescription]
        ,[BranchEnglishDescription]
        ,[CurrencyArabicDescription]
        ,[CurrencyEnglishDescription]
        ,[SerialCombination]
        ,[Qnt]
        ,[UOMArabicDescription]
        ,[UOMEnglishDescription]
        ,[TotalValue]
        ,[DiscountValue]
        ,[NetValue]
        ,[Company]
        ,[Branch]
        ,[OrderDate]
        ,[WareHouseID]
        ,[WareHouseLocationID]
        ,[PriceValue]
        ,[OrderType]
        ,[CurrenceyID]
        ,[SalesOrderNetValue]
        ,[IsFirmed]
        ,[CreatedBy]
        ,[EmployeeArabicDescription]
        ,[EmployeeEnglishDescription]
        ,[CustomerCode]
        ,[Name]
        ,[CustomerAr]
        ,[CustomerEn]
        ,[ShortName]
        ,[PhoneNo1]
        ,[PhoneNo2]
        ,[MobileNo]
        ,[FaxNo]
        ,[EmailAddress]
        ,[CustomerCategoryArabicDescription]
        ,[CustomerCategoryEnglishDescription]
        ,[SegmentArabicDescription]
        ,[SegmentEnglishDescription]
        ,[CustomerGroupArabicDescription]
        ,[CustomerGroupEnglishDescription]
        ,[ClassArabicDescription]
        ,[ClassEnglishDescription]
        ,[TypeArabicDescription]
        ,[TypeEnglishDescription]
        ,[CreditLimit]
        ,[CustomerCurrencyArabicDescription]
        ,[CustomerCurrencyEnglishDescription]
        ,[PriceCodeArabicDescription]
        ,[PriceCodeEnglishDescription]
        ,[ItemCode]
        ,[ArabicDescription]
        ,[EnglishDescription]
        ,[ItemType]
        ,[ItemKind]
        ,[ItemClass]
        ,[PurchasedType]
        ,[ItemCategoryArabicDescription]
        ,[ItemCategoryEnglishDescription]
        ,[ItemGroupArabicDescription]
        ,[ItemGroupEnglishDescription]
        ,[SeasonArabicDescription]
        ,[SeasonEnglishDescription]
        ,[ItemUOMArabicDescription]
        ,[ItemUOMEnglishDescription]
        ,[NotActive]
        ,[CostStandard]
        ,[CostCalculated]
        ,[BrandArabicDescription]
        ,[BrandEnglishDescription]
        ,[Notes]
        ,[BranchID]
        ,[SalesManID]
        ,[CustomerID]
        ,[ItemID]
        ,[UOMID]
        ,[PaperNo]
        ,[WarehouseArabicDescription]
        ,[WarehouseEnglishDescription]
        ,[SalesSalesQuotationerialNo]
        ,[TendersNotifySerialNo]
        ,[HeadNotes]
        ,[ConfirmationLevel]
        ,[CostingSheetSerialNo]
        ,[TypeAr]
        ,[TypeEn]
        ,[UomFactor]
        ,[SalesDeliverySerialNo]
        ,[PickingSlipSerialNo]
        ,[SalesInvoiceSerialNo]
        ,[SalesManArabicDescription]
        ,[SalesManEnglishDescription]
    FROM [BarTechImis].[dbo].[View_SalesOrders]
EOD;
        $results = DB::select(
            $sql,
            []
        );
        return collect($results);
    }

}

