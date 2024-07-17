<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInformation;
use App\Models\CompanyDocumentTypeCategories;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyTax;
use App\Models\Approval;
use App\Models\CompanySupportingDocument;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\File;
use \Mpdf\Mpdf as mPDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PartnerExport;
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
                $partners = CompanyInformation::where('status', 'checking')->get();
            } else if (auth()->user()->roles->pluck('name')[0] == 'admin' || auth()->user()->roles->pluck('name')[0] == 'super-admin') {
                $partners = CompanyInformation::all();
            }
            return FormatResponseJson::success($partners, 'Data partner fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null,$e->getMessage(), 404);
        }
    }
    public function detailPartner(Request $request)
    {
        try {
            $partner_detail = CompanyInformation::with(['user', 'address', 'bank', 'tax', 'attachment'])
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
                $update_partner = CompanyInformation::findOrFail($request->partner_id);
                if ($status == 'approved') {
                    $update_partner->update([
                        'status' => 'checking 2'
                    ]);
                } else if ($status == 'reject') {
                    $update_partner->update([
                        'status' => 'reject'
                    ]);

                    $delete_address = CompanyAddress::where('company_id', $request->partner_id)->get();
                    $delete_bank = CompanyBank::where('company_id', $request->partner_id)->get();
                    $delete_tax =CompanyTax::where('company_id', $request->partner_id)->get();
                    $delete_attachment =CompanySupportingDocument::where('company_id', $request->partner_id)->get();

                    if (count($delete_attachment) > 0) {
                        for ($i=0; $i < count($delete_attachment); $i++) { 
                            if (File::exists(public_path($delete_attachment[$i]->document))) {
                                // dd($delete_attachment[$i]->document);
                                File::delete(public_path($delete_attachment[$i]->document));
                            }
                        }
                    }

                    $delete_address->each->delete();
                    $delete_bank->each->delete();
                    $delete_tax->each->delete();
                    $delete_attachment->each->delete();
                    $list_partner = CompanyInformation::where('id', $request->partner_id)->get()->each->delete();
                }
            } else if (auth()->user()->roles->pluck('name')[0] == 'admin') {
                $update_partner = CompanyInformation::findOrFail($request->partner_id);
                if ($status == 'approved') {
                    $update_partner->update([
                        'status' => 'approved'
                    ]);
                } else if ($status == 'reject') {
                    $update_partner->update([
                        'status' => 'reject'
                    ]);

                    $delete_address = CompanyAddress::where('company_id', $request->partner_id)->get();
                    $delete_bank = CompanyBank::where('company_id', $request->partner_id)->get();
                    $delete_tax =CompanyTax::where('company_id', $request->partner_id)->get();
                    $delete_attachment =CompanySupportingDocument::where('company_id', $request->partner_id)->get();

                    if (count($delete_attachment) > 0) {
                        for ($i=0; $i < count($delete_attachment); $i++) { 
                            if (File::exists(public_path($delete_attachment[$i]->document))) {
                                // dd($delete_attachment[$i]->document);
                                File::delete(public_path($delete_attachment[$i]->document));
                            }
                        }
                    }

                    $delete_address->each->delete();
                    $delete_bank->each->delete();
                    $delete_tax->each->delete();
                    $delete_attachment->each->delete();
                    $list_partner = CompanyInformation::where('id', $request->partner_id)->get()->each->delete();
                    // \File::exists(public_path('storage/uploads/pt'. $delete_attachment->partner_id .''));
                }
            }
            return FormatResponseJson::success($status, 'Company partner updated successfully');
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
}
