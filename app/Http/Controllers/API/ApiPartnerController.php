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
class ApiPartnerController extends Controller
{
    public function fetchPartner()
    {
        try {
            $partner = CompanyInformation::with(['address', 'bank', 'tax', 'attachment'])
            ->where('blacklist', 0)
            ->where('status', 'approved')
            ->get();
            return FormatResponseJson::success($partner, 'Data partner fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
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
            return FormatResponseJson::success($partner,'Partner data has been successfully blacklisted');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
}
