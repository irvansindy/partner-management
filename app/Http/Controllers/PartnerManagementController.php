<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// use App\Models\CompanyInformation;
use App\Models\CompanyInformation;
use App\Models\CompanyDocumentTypeCategories;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyTax;
use App\Models\ApprovalMaster;
use App\Models\ApprovalDetails;
use App\Models\MasterApprovalModel;
use App\Models\DetailApprovalModel;
use App\Models\CompanySupportingDocument;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\File;
use \Mpdf\Mpdf as mPDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PartnerExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\SubMenu;
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
            $user = Auth::user();
            $query_company = CompanyInformation::query();
            
            // Check user role
            $role = $user->roles->pluck('name')[0];

            if ($role == 'super-user') {
                $query_company->where('status', 'checking');
                // Additional conditions for super-user
                switch ($role) {
                    case 'super-user':
                        $query_company->orWhere(function($query) use ($user) {
                            $query->whereIn('status', ['checking 2', 'approved', 'reject'])
                                ->where('location_id', $user->office_id)
                                ->where('department_id', $user->department_id);
                        });
                        break;
                    default:
                        break;
                }

            } elseif ($role == 'admin') {
                // Conditions for admin
                switch ($role) {
                    case 'admin':
                        $query_company->whereIn('status', ['checking 2', 'approved', 'reject'])
                            ->where('location_id', $user->office_id)
                            ->where('department_id', $user->department_id);
                        break;
                    default:
                        break;
                }

            } elseif ($role == 'super-admin') {
                // Super-admin has access to all data
                $query_company->whereIn('status', ['checking', 'checking 2', 'approved', 'reject']);
            
            } else {
                // For any other role, return no data
                $query_company->whereRaw('1 = 0');
            }

            $company_informations = $query_company->get();
            $message = count($company_informations) == 0 ? 'Data partner empty' : 'Data partner fetched successfully';

            return FormatResponseJson::success($company_informations, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function detailPartner(Request $request)
    {
        try {
            // fetch partner detail
            $partner_detail = CompanyInformation::with(['user', 'address', 'bank', 'tax', 'attachment'])
            ->find($request->partner_id);

            $is_approved = false;
            // check role and check can be approve
            $existing_stagging_approval_master = ApprovalMaster::where('company_information_id', $request->partner_id)->first();

            if (auth()->user()->roles->pluck('name')[0] == 'super-user') {
                switch (true) {
                    case is_null($existing_stagging_approval_master):
                        $is_approved = true;
                        break;
                    case $existing_stagging_approval_master != null:
                        $existing_stagging_approval_detail = ApprovalDetails::where([
                            'approval_id' => $existing_stagging_approval_master->id,
                            'user_id' => \Auth::user()->id,
                            'status'=> 1,
                        ])->first();
                        $existing_stagging_approval_detail != null ? $is_approved = true : $is_approved = false;
                        $is_approved = $existing_stagging_approval_detail != null;
                }

            } else if (auth()->user()->roles->pluck('name')[0] == 'admin') {
                switch (true) {
                    case is_null($existing_stagging_approval_master):
                        $is_approved = false;
                        break;
                    case $existing_stagging_approval_master != null:
                        $existing_stagging_approval_detail = ApprovalDetails::
                        where([
                            'approval_id'=> $existing_stagging_approval_master->id,
                            'user_id'=> \Auth::user()->id,
                            'status'=> 1,
                        ])->first();
                        $is_approved = $existing_stagging_approval_detail != null;
                    break;
                }
            } 

            $data = [
                $partner_detail,
                'is_approved' => $is_approved,
                'role' => auth()->user()->roles->pluck('name')[0],
                'office' => \auth()->user()->office_id,
                'department' => \auth()->user()->department_id,
            ];
            return FormatResponseJson::success($data, 'Company profile fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function exportPartnerToExcel()
    {
        return Excel::download(new PartnerExport(), 'list vendor.xlsx');
    }
    public function exportPartnerToPdf()
    {
        $partners = CompanyInformation::all();
        $imageLogo          = '<img src="'.public_path('uploads/logo/logo.png').'" width="70px" style="float: right;"/>';
        $header             = '';
        $header             .= '<table width="100%">
                                    <tr>
                                        <td style="padding-left:10px;">
                                        <span style="font-size: 6px; font-weight: bold;margin-top:-10px"> '.$imageLogo.'</span>
                                        <br>
                                        <span style="font-size:8px;">Synergy Building #08-08</span> 
                                        <br>
                                        <span style="font-size:8px;">Jl. Jalur Sutera Barat 17 Alam Sutera, Serpong Tangerang 15143 - Indonesia</span>
                                        <br>
                                        <span style="font-size:8px;">Tangerang 15143 - Indonesia +62 21 304 38808</span>
                                    </td>
                                        </tr>
                                        
                                    </table>
                                ';
        $footer             = '<hr>
        <table width="100%" style="font-size: 10px;">
            <tr>
                <td width="90%" align="left"><b>Disclaimer</b><br>this document is strictly private, confidential and personal to recipients and should not be copied, distributed or reproduced in whole or in part, not passed to any third party.</td>
                <td width="10%" style="text-align: right;"> {PAGENO}</td>
            </tr>
        </table>';
        $data = [
            'partners' => $partners,
        ];
        $mpdf = new mPDF();
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
            $mpdf->AddPage(
                'L', // L - landscape, P - portrait 
                '',
                '',
                '',
                '',
                5, // margin_left
                5, // margin right
                35, // margin top
                20, // margin bottom
                5, // margin header
                5
            ); // m
        $cetak = view('partner.export_pdf',$data);
        $mpdf->WriteHTML($cetak);
        ob_clean();
        $mpdf->Output('Report Daily'.'('.date('Y-m-d').').pdf', 'I');
    }
    public function approvalPartner(Request $request)
    {
        try {
            $status = $request->status;
            $user = auth()->user();
            $get_office_approval = $user->office_id;
            $get_office_department = $user->department_id;
            $role = $user->roles->pluck('name')[0];

            if ($role == 'super-user') {
                $update_partner = CompanyInformation::find($request->partner_id);
    
                switch ($status) {
                    case 'approved':
                        // get model approval master by office location and department
                        $get_master_approval_model = MasterApprovalModel::where([
                            'location_id' => $get_office_approval,
                            'department_id' => $get_office_department
                        ])
                        ->whereHas('detailApprovalModel', function($q) {
                            $q->where('user_id', \auth()->user()->id);
                        })
                        ->first();
                        // dd($get_master_approval_model);
                        // check model approval master 
                        if (is_null($get_master_approval_model)) {
                            return FormatResponseJson::error(null, 'Approval belum ada, silahkan hubungi pihak ICT.', 404);
                        }

                        // check existing approval master
                        $existing_approval_master = ApprovalMaster::where([
                            'company_information_id' => $update_partner->id,
                        ])->first();
                        if ($existing_approval_master) {
                            return FormatResponseJson::error(null, 'Anda sudah melakukan approval untuk data ini!.', 403);
                        }
                        
                        // get model approval detail by model approval master
                        $get_detail_approval_model = $get_master_approval_model 
                            ? DetailApprovalModel::where('approval_id', $get_master_approval_model->id)->get() 
                            : [];
                        
                        $create_approval_master = ApprovalMaster::create([
                            'company_information_id' => $request->partner_id,
                            'user_id' => $user->id,
                            'location_id' => $get_office_approval,
                            'department_id' => $get_office_department,
                            'step_ordering' => 1,
                            'status' => 1
                        ]);
    
                        foreach ($get_detail_approval_model as $approval_detail) {
                            ApprovalDetails::create([
                                'approval_id' => $create_approval_master->id,
                                'user_id' => $approval_detail->user_id,
                                'step_ordering' => $approval_detail->step_ordering,
                                'status' => $approval_detail->status
                            ]);
                        }
                        
                        // update master company information
                        $update_partner->update([
                            'status' => 'checking 2',
                            'location_id' => $get_office_approval,
                            'department_id' => $get_office_department,
                        ]);
                        
                        // update approval master
                        $create_approval_master->update([
                            'status' => 2,
                            'step_ordering' => 2
                        ]);

                        // update approval detail
                        $existing_approval_detail = ApprovalDetails::where([
                            'approval_id' => $create_approval_master->id,
                            'user_id' => $user->id,
                        ])->first();
                        // dd($existing_approval_detail);
                        $existing_approval_detail->update(['status' => 2]);
                        $existing_approval_detail_next = ApprovalDetails::where('approval_id', $create_approval_master->id)
                        ->where('user_id', '!=', $user->id)
                        ->first();
                        $existing_approval_detail_next->update(['status' => 1]);
                        break;
    
                    case 'reject':
                        $update_partner->update(['status' => 'reject']);
    
                        $delete_address = CompanyAddress::where('company_id', $request->partner_id)->get();
                        $delete_bank = CompanyBank::where('company_id', $request->partner_id)->get();
                        $delete_tax = CompanyTax::where('company_id', $request->partner_id)->get();
                        $delete_attachment = CompanySupportingDocument::where('company_id', $request->partner_id)->get();
    
                        foreach ($delete_attachment as $attachment) {
                            if (File::exists(public_path($attachment->document))) {
                                File::delete(public_path($attachment->document));
                            }
                        }
    
                        $delete_address->each->delete();
                        $delete_bank->each->delete();
                        $delete_tax->each->delete();
                        $delete_attachment->each->delete();
                        CompanyInformation::where('id', $request->partner_id)->delete();
                        break;
                    default:
                        break;
                }
    
            } elseif ($role == 'admin') {
                $update_partner = CompanyInformation::findOrFail($request->partner_id);
    
                switch ($status) {
                    case 'approved':
                        $existing_approval_master = ApprovalMaster::where('company_information_id', $request->partner_id)->first();
                        
                        if ($existing_approval_master) {
                            $existing_approval_master->update(['status' => 2]);
    
                            ApprovalDetails::where([
                                'approval_id' => $existing_approval_master->id,
                                'user_id' => $user->id,
                            ])->update(['status' => 2]);
    
                            $update_partner->update(['status' => 'approved']);
                        }
                        break;
    
                    case 'reject':
                        $update_partner->update(['status' => 'reject']);
    
                        $delete_address = CompanyAddress::where('company_id', $request->partner_id)->get();
                        $delete_bank = CompanyBank::where('company_id', $request->partner_id)->get();
                        $delete_tax = CompanyTax::where('company_id', $request->partner_id)->get();
                        $delete_attachment = CompanySupportingDocument::where('company_id', $request->partner_id)->get();
    
                        foreach ($delete_attachment as $attachment) {
                            if (File::exists(public_path($attachment->document))) {
                                File::delete(public_path($attachment->document));
                            }
                        }
    
                        $delete_address->each->delete();
                        $delete_bank->each->delete();
                        $delete_tax->each->delete();
                        $delete_attachment->each->delete();
                        CompanyInformation::where('id', $request->partner_id)->delete();
                        break;
                }
            }
    
            return FormatResponseJson::success($status, 'Company partner updated successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function getMenusWithSubmenus()
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        // Ambil data Menu yang diizinkan untuk user
        $menus = Menu::with(['submenus' => function ($query) use ($user) {
            $query->whereHas('permissions', function ($permissionQuery) use ($user) {
                $permissionQuery->where('name', $user->getAllPermissions()->pluck('name')->toArray());
            });
        }])
        ->get();
        dd($menus);


        return response()->json($menus);
    }
    public function fetchVendorForTender(){
        try {
            $list_company = CompanyInformation::where([
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
