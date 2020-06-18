<?php

namespace App\Http\Controllers;

use App\Imis\ImisBranch;
use App\Standalone\Branch;
use App\Standalone\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class CompanyCtrl extends Controller
{

    /**
     * INPUT
     * name
     */
    public function addCompany(Request $request)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $company = new Company();
            $company->ar_name = $request->ar_name;
            $company->save();
            return response()->json(null, 201);
        }
    }

    public function addBranch(Request $request)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $branch = new Branch();
            $branch->company_id = $request->company_id;
            $branch->ar_name = $request->ar_name;
            $branch->latitude = $request->latitude;
            $branch->longitude = $request->longitude;
            $branch->save();
        }
    }

    public function updateBranch(Request $request, $id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $branch = Branch::findOrFail($id);
            if($branch) {
                $branch->ar_name = $request->ar_name;
                $branch->latitude = $request->latitude;
                $branch->longitude = $request->longitude;
                $branch->save();
                return $branch;
            }
        }
    }

    public function deleteBranch($id)
    {
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $branch = Branch::findOrFail($id);
            if($branch)
                $branch->delete();
        }
    }

    /**
     * INPUT
     * company: companyID
     */
    public function getBranches(Request $request)
    {
        $mapped = [];
        // GET APP MODE
        $app_mode = env('APP_MODE','');
        if($app_mode == 'standalone') {
            $mapped = Branch::where('company_id',$request->input('company_id'))->get();;
        }
        else {
            $collection = ImisBranch::all('*',$request->input('company_id'));
            $mapped = $collection->map(function ($item, $key) {
                return [
                    'branchNameAr' => $item->ArabicDescription,
                    'dummyLatitude' => '30.055760',
                    'dummyLongitude' => '31.357623'
                ];
            });
        }

        return response()->json($mapped, 200);
    }

    /**
     * INPUT
     * company: companyID
     */
    public function autoDetectBranch(Request $request)
    {
        // echo distance(30.055760, 31.357623, 30.055073, 31.358675, "K") . " Kilometers<br>"; // 0.126 K
        // echo distance(30.055760, 31.357623, 30.055036, 31.361960, "K") . " Kilometers<br>"; // 0.425 K
        $results = DB::select(
            'EXEC SP_Branch_FindAll @CompanyId=:CompanyId ;',
            [
                ':CompanyId' => $request->input('company')
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
