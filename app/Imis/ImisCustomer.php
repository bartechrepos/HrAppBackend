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

    public static function insert($data ) {
        $sql = <<<EOD
SET NOCOUNT ON ;

DECLARE	@return_value int,
		@ID int,
		@GUID varchar(50)

EXEC	@return_value = [dbo].[SP_Customer_Insert]
		@ID = @ID OUTPUT,
		@Company = 1,
		@Branch = 1,
		@Code = '',
		@GUID = @GUID OUTPUT,
		@ArabicDescription = :ArabicDescription,
		@EnglishDescription = '',
		@Name = :Name,
		@ShortName = '',
		@Website = '',
		@TaxFileNo = '',
		@SalesTaxNo = '',
		@RegNo = '',
		@PhoneNo1 = '',
		@PhoneNo2 = '',
		@MobileNo = '',
		@FaxNo = '',
		@EmailAddress = '',
		@SalesManId = '',
		@ThisSalesManOnly = 0,
		@CategoryId = '',
		@TaxId = '',
		@PriceCode = '',
		@PriceCodeOverride = '',
		@DepartmentId = '',
		@SegmentId = '',
		@GroupId = '',
		@ClassId = '',
		@TypeId = '',
		@PaymentTermId = '',
		@AllowCheks = 0,
		@AllowCash = 0,
		@AllowPromissory = 0,
		@AllowCreditCard = 0,
		@PaymentScheduleId = '',
		@CashDiscountId = '',
		@UnpaidInvoice = '',
		@UnpaidInvoiceCount = 0,
		@CreditLimit = 0,
		@CreditLimitExceed = 0,
		@InvoiceDueDateExceed = 0 ,
		@AccountId = '',
		@CurrencyId = '',
		@ProjectId = '',
		@glDepartmentId = '',
		@JournalID = '',
		@BudjetId = '',
		@PortId = '',
		@ShipperId = '',
		@ShippingTypeID = '',
		@ShippingTermsId = '',
		@ShippingMethodId = '',
		@LastVisit = '',
		@TotalVisits = 0,
		@TotalSales = 0,
		@TotalSavings = 0,
		@Notes = '',
		@LoyaltyProgramId = '',
		@SpecificBranches = 0,
		@BlackListPOSQuotation = 0,
		@BlackListPOSTransaction = 0,
		@TenderId = '',
		@DetailLine1 = '',
		@DetailLine2 = '',
		@DetailLine3 = '',
		@DetailLine4 = '',
		@DetailLine5 = '',
		@DetailLine6 = '',
		@DetailLine7 = '',
		@DetailLine8 = '',
		@DetailLine9 = '',
		@DetailLine10 = '',
		@DateLine1 ='',
		@DateLine2 ='',
		@DateLine3 = '',
		@DateLine4 = '',
		@DateLine5 = '',
		@ComboLine1 = '',
		@ComboLine2 = '',
		@ComboLine3 = '',
		@ComboLine4 = '',
		@ComboLine5 = '',
		@CreatedBy = 1,
        @CreatedMacNo = 0

SELECT  @GUID as 'GUID'
SELECT	'Return Value' = @return_value
EOD;

        $results = DB::select($sql,[
            ':Name' => $data->name,
            ':ArabicDescription' => $data->name,
        ]);

        return collect($results);
    }

}

