<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInformation;
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
use Dedoc\Scramble\Attributes\Hidden;
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
    #[Hidden]
    public function fetchPartner()
    {
        try {
            $user = User::with('dept')->find(Auth::id());
            $query_company = CompanyInformation::query();
            $role = $user->roles->pluck('name')[0];
            // dd($role);
            // Ambil nama departemen user
            $departmentName = optional($user->dept)->name;

            // Tentukan apakah user boleh lihat customer dan/atau vendor
            $canSeeCustomer = in_array($departmentName, ['Sales Retail 1', 'Sales Retail 2', 'Sales Project']);
            $canSeeVendor = in_array($departmentName, ['Purchasing']);

            // Filter berdasarkan role
            $query_company->where(function ($query) use ($user, $role) {
                if ($role == 'super-user') {
                    $query->where(function ($q) use ($user) {
                        $q->where('status', 'checking')
                        ->orWhere(function ($subQ) use ($user) {
                            $subQ->whereIn('status', ['checking 2', 'approved', 'reject'])
                                ->where('location_id', $user->office_id)
                                ->where('department_id', $user->department_id);
                        });
                    });
                } elseif ($role == 'admin') {
                    $query->whereIn('status', ['checking 2', 'approved', 'reject'])
                        ->where('location_id', $user->office_id)
                        ->where('department_id', $user->department_id);
                } elseif ($role == 'super-admin') {
                    $query->whereIn('status', ['checking', 'checking 2', 'approved', 'reject']);
                } else {
                    $query->whereRaw('1 = 0');
                }
            });

            // Filter tambahan berdasarkan hak akses type
            if ($role !== 'super-admin') {
                $query_company->where(function ($query) use ($canSeeCustomer, $canSeeVendor) {
                    if ($canSeeCustomer === true) {
                        $query->where('type', 'customer');
                    } elseif ($canSeeVendor === true) {
                        $query->where('type', 'vendor');
                    } else {
                        $query->whereRaw('1 = 0'); // Tidak boleh lihat apapun
                    }
                });
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
            DB::beginTransaction();
            $status = $request->status;
            $user = auth()->user();
            $role = $user->roles->pluck('name')[0];
            $partner = CompanyInformation::findOrFail($request->partner_id);

            $approvalMaster = ApprovalMaster::where('company_information_id', $partner->id)->first();
            $approvalDetail = $approvalMaster 
                ? ApprovalDetails::where([
                    'approval_id' => $approvalMaster->id,
                    'user_id' => $user->id
                ])->first()
                : null;

            if ($status === 'approved') {
                if (!$approvalMaster) {
                    // APPROVAL PERTAMA
                    $masterModel = MasterApprovalModel::where([
                        'location_id' => $user->office_id,
                        'department_id' => $user->department_id,
                    ])->first();

                    if (!$masterModel) {
                        return FormatResponseJson::error(null, 'Approval model tidak ditemukan, hubungi ICT.', 404);
                    }

                    // Buat approval master
                    $approvalMaster = ApprovalMaster::create([
                        'company_information_id' => $partner->id,
                        'user_id' => $user->id,
                        'location_id' => $user->office_id,
                        'department_id' => $user->department_id,
                        'step_ordering' => 2, // akan masuk ke step kedua setelah ini
                        'status' => 1,
                    ]);

                    // Masukkan user pertama (yang bukan bagian dari DetailApprovalModel)
                    ApprovalDetails::create([
                        'approval_id' => $approvalMaster->id,
                        'user_id' => $user->id,
                        'step_ordering' => 1,
                        'status' => 2 // sudah diapprove
                    ]);

                    // Masukkan semua detail approval dari model
                    $detailModels = DetailApprovalModel::where('approval_id', $masterModel->id)
                        ->orderBy('step_ordering')
                        ->get();

                    foreach ($detailModels as $detail) {
                        ApprovalDetails::create([
                            'approval_id' => $approvalMaster->id,
                            'user_id' => $detail->user_id,
                            'step_ordering' => $detail->step_ordering + 1, // geser step_ordering +1 karena step 1 sudah dipakai
                            'status' => $detail->step_ordering === 1 ? 1 : 0 // step 2 (asli step 1) = waiting
                        ]);
                    }

                    // Update status partner
                    $partner->update([
                        'status' => 'checking 2',
                        'location_id' => $user->office_id,
                        'department_id' => $user->department_id,
                    ]);
                } else {
                    // APPROVAL LANJUTAN
                    if (!$approvalDetail || $approvalDetail->status === 2) {
                        return FormatResponseJson::error(null, 'Anda sudah melakukan approval.', 403);
                    }

                    $approvalDetail->update(['status' => 2]);

                    $nextStep = ApprovalDetails::where('approval_id', $approvalMaster->id)
                        ->where('step_ordering', '>', $approvalDetail->step_ordering)
                        ->orderBy('step_ordering')
                        ->first();

                    if ($nextStep) {
                        $nextStep->update(['status' => 1]);
                        $approvalMaster->update([
                            'status' => 1,
                            'step_ordering' => $nextStep->step_ordering,
                        ]);
                        $partner->update(['status' => 'checking 2']);
                    } else {
                        $approvalMaster->update(['status' => 2]);
                        $partner->update(['status' => 'approved']);
                    }
                }
            } elseif ($status === 'reject') {
                // SET STATUS REJECT
                $partner->update(['status' => 'reject']);
                $approvalMaster?->update(['status' => 3]);
                $approvalDetail?->update(['status' => 3]);

                // DELETE ALL RELATED DATA
                $attachments = CompanySupportingDocument::where('company_id', $partner->id)->get();
                foreach ($attachments as $file) {
                    if (File::exists(public_path($file->document))) {
                        File::delete(public_path($file->document));
                    }
                }

                CompanyAddress::where('company_id', $partner->id)->delete();
                CompanyBank::where('company_id', $partner->id)->delete();
                CompanyTax::where('company_id', $partner->id)->delete();
                CompanySupportingDocument::where('company_id', $partner->id)->delete();
                $partner->delete();
            }

            DB::commit();
            return FormatResponseJson::success($status, 'Approval berhasil diproses');
        } catch (\Exception $e) {
            DB::rollBack();
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
    #[Hidden]
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
