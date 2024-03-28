<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInfomation;
use App\Models\CompanyDocumentTypeCategories;
use App\Helpers\FormatResponseJson;
class PartnerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view("company.index");
    }

    public function fetchCompany()
    {
        try {
            $company_profile = CompanyInfomation::with(['user', 'address', 'bank', 'tax', 'add_info'])
            ->where("user_id", auth()->user()->id)->first();

            return FormatResponseJson::success($company_profile, 'Company profile fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function fetchDocTypeCategories()
    {
        try {
            $doc_type = CompanyDocumentTypeCategories::all();
            return FormatResponseJson::success($doc_type,'Document Type fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required|string',
                'company_group_name' => 'required|string',
                'established_year' => 'required|string',
                'total_employee' => 'required|integer',
                'liable_person_and_position' => 'required|string',
                'owner_name' => 'required|string',
                'board_of_directors' => 'required|string',
                'major_shareholders' => 'required|string',
                'business_classification' => 'required|string',
                'website_address' => 'required|string',
                'system_management' => 'required|string',
                'contact_person' => 'required|string',
                'communication_language' => 'required|string',
            ]);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);

        }
    }
}
