<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInformation;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyTax;
use App\Models\CompanySupportingDocument;
use App\Helpers\FormatResponseJson;
use App\Helpers\JsonCollection;
use App\Http\Resources\PartnerResource;
class ApiPartnerController extends Controller
{
    public function fetchPartner()
    {
        try {
            $partners = CompanyInformation::with(['address', 'bank', 'tax', 'attachment'])
            ->where('blacklist', 0)
            ->where('status', 'approved')
            ->get();
            // return FormatResponseJson::success($partners, 'Data partners fetched successfully');
            return JsonCollection::success(PartnerResource::collection($partners), 'Data partners fetched successfully');

        } catch (\Exception $e) {
            // return FormatResponseJson::error(null, $e->getMessage(), 404);
            return JsonCollection::error(null, $e->getMessage(), 404);
        } catch (\Throwable $th) {
            // return FormatResponseJson::error(null, $th->getMessage(), 500);
            return JsonCollection::error(null, $th->getMessage(), 500);
        }
    }
    public function blacklistPartner(Request $request)
    {
        try {
            
            $id = $request->partner_id;
            $partner = CompanyInformation::find($id);
            $partner->update([
                'blacklist' => 1,
            ]);
            // return FormatResponseJson::success($partner,'Partner data has been successfully blacklisted');
            return JsonCollection::success(new PartnerResource($partner), 'Partner data has been successfully blacklisted');
        } catch (\Exception $e) {
            // return FormatResponseJson::error(null, $e->getMessage(), 404);
            return JsonCollection::error(null, $e->getMessage(), 404);
        } catch (\Throwable $th) {
            // return FormatResponseJson::error(null, $th->getMessage(), 500);
            return JsonCollection::error(null, $th->getMessage(), 500);
        }
    }
    public function fetchVendorForTender(){
        try {
            $list_company = CompanyInformation::with(['address', 'bank', 'tax', 'attachment'])->
                where([
                'type' => 'vendor',
                'status' => 'approved',
                'blacklist' => 0
            ])->get([
                'id',
                'name',
                'type',
                'liable_person_and_position',
                'owner_name',
                'board_of_directors',
                'major_shareholders',
                'business_classification',
                'business_classification_detail',
                'contact_person',
            ]);
            return FormatResponseJson::success($list_company, 'vendor fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
}
