<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EndUserLicenseAgreement;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class EndUserLicenseAgreementController extends Controller
{
    public function index()
    {
        return view('admin.eula.index');
    }
    public function fetchEula()
    {
        try {
            //code...
            $end_user_license_agreement = EndUserLicenseAgreement::first();
            return FormatResponseJson::success($end_user_license_agreement, 'End User License Agreement fetched successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function submitEula(Request $request)
    {
        try {
            $data_eula = $request->data_eula;
            $store_eula = [
                'user_id' => \auth()->user()->id,
                'content' => $data_eula,
            ];
            $create_or_update = EndUserLicenseAgreement::updateOrCreate(['id' => $request->id], $store_eula);
            return FormatResponseJson::success($create_or_update, 'End User License Agreement submitted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}
