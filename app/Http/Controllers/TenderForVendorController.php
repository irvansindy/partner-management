<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenders;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
class TenderForVendorController extends Controller
{
    public function index()
    {
        return view('admin.tenders.vendor.index');
    }
    public function fetchTenderVendor()
    {
        try {
            $tenders = Tenders::with(['detailProduct', 'vendorSubmission', 'user', 'eula'])->get();
            $message = '';
            $list_tender = count($tenders) == 0 ? null : $tenders;
            $message = count($tenders) == 0 ? 'tender vendor is empty':'tender vendor fetched successfully';
            return FormatResponseJson::success($list_tender, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}
