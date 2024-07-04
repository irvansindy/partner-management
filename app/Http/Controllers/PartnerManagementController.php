<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInfomation;
use App\Models\CompanyDocumentTypeCategories;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyTax;
use App\Models\Approval;
use App\Models\CompanySupportingDocument;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\File;
class PartnerManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('partner.index');
    }
    public function fetchPartner()
    {
        try {
            if (auth()->user()->roles->pluck('name')[0] == 'super-user') {
                $partners = CompanyInfomation::where('status', 'checking')->get();
            } else if (auth()->user()->roles->pluck('name')[0] == 'admin' || auth()->user()->roles->pluck('name')[0] == 'super-admin') {
                $partners = CompanyInfomation::all();
            }
            return FormatResponseJson::success($partners, 'Data partner fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function detailPartner(Request $request)
    {
        try {
            $partner_detail = CompanyInfomation::with(['user', 'address', 'bank', 'tax', 'attachment'])
            ->find($request->partner_id);

            $doc_type = CompanyDocumentTypeCategories::all();

            $doc_pt = CompanySupportingDocument::where('company_id', $partner_detail->id)
            ->where('company_doc_type', 'pt')
            ->get();
            
            $doc_cv = CompanySupportingDocument::where('company_id', $partner_detail->id)
            ->where('company_doc_type', 'cv')
            ->get();
            
            $doc_ud_or_pd = CompanySupportingDocument::where('company_id', $partner_detail->id)
            ->where('company_doc_type', 'ud_or_pd')
            ->get();
            
            $doc_perorangan = CompanySupportingDocument::where('company_id', $partner_detail->id)
            ->where('company_doc_type', 'perorangan')
            ->get();

            $data = [
                $partner_detail,
                $doc_type,
                'pt' => $doc_pt,
                'cv' => $doc_cv,
                'ud_or_pd' => $doc_ud_or_pd,
                'perorangan' => $doc_perorangan
            ];
            return FormatResponseJson::success($data, 'Company profile fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function approvalPartner(Request $request)
    {
        try {
            $status = $request->status;
            if (auth()->user()->roles->pluck('name')[0] == 'super-user') {
                $update_partner = CompanyInfomation::findOrFail($request->partner_id);
                if ($status == 'approved') {
                    $update_partner->update([
                        'status' => 'checking 2'
                    ]);
                } else if ($status == 'reject') {
                    $update_partner->update([
                        'status' => 'reject'
                    ]);

                    $delete_address = CompanyAddress::where('company_id', $request->partner_id);
                    $delete_bank = CompanyBank::where('company_id', $request->partner_id);
                    $delete_tax =CompanyTax::where('company_id', $request->partner_id);
                    $delete_attachment =CompanySupportingDocument::where('company_id', $request->partner_id)->get();

                    $delete_address->delete();
                    $delete_bank->delete();
                    $delete_tax->delete();
                    
                    for ($i=0; $i < count($delete_attachment); $i++) { 
                        # code...
                    }

                    \File::exists(public_path('storage/uploads/pt'. $delete_attachment->partner_id .''));
                }
            } else if (auth()->user()->roles->pluck('name')[0] == 'admin') {
                $update_partner = CompanyInfomation::findOrFail($request->partner_id);
                if ($status == 'approved') {
                    $update_partner->update([
                        'status' => 'approved'
                    ]);
                } else if ($status == 'reject') {
                    $update_partner->update([
                        'status' => 'reject'
                    ]);
                }
            }
            return FormatResponseJson::success($status, 'Company partner updated successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
        
    }
}
