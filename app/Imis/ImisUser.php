<?php

namespace App\Imis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImisUser extends Model
{
    //
    protected $table = null;

    public static function login($request) {
        /*
        $results = DB::select(
            'EXEC SP_Branch_FindAll @CompanyId=:CompanyId ;',
            [
                // Just ignore this error
                ':CompanyId' => $companyId
            ]
        );
        return collect($results);
        */
        if(file_exists("..\bins\CryptoCMD.exe")) {
            $encPass = "";
            $FailedCase = null;
            try {
                $output = shell_exec("..\bins\CryptoCMD.exe ".escapeshellarg($request->username)." ".escapeshellarg($request->password));
                $encPass = trim($output);
            } catch (\Exception $error) {
                return $error->getMessage();
            }
            $results = DB::select(
                'EXEC SP_Employee_Login @BranchId=:BranchId, @UserName=:UserName, @Password=:Password, @MacNo=:MacNo, @FailedCase=:FailedCase ;',
                [
                    // Just ignore this error
                    ':BranchId' => $request->branch_id,
                    ':UserName' => $request->username,
                    ':Password' => $encPass,
                    ':MacNo' => "",
                    ':FailedCase'=> $FailedCase
                ]
            );

            return collect($results);
        }
        return getcwd();

    }
}
