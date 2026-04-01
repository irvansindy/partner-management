<?php

namespace App\Http\Controllers;

use App\Exports\CompanyExport;
use App\Exports\CompanyCustomExport;
use App\Exports\CompanyTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CompanyExportController extends Controller
{
    /**
     * Tampilkan form pilih field export
     */
    public function showForm()
    {
        return view('admin.company.export_form');
    }

    /**
     * Process export dengan field yang dipilih
     */
    public function exportCustom(Request $request)
    {
        $request->validate([
            'fields' => 'required|array|min:1',
            'fields.*' => 'string',
        ], [
            'fields.required' => 'Pilih minimal 1 field untuk di-export',
            'fields.min' => 'Pilih minimal 1 field untuk di-export',
        ]);

        try {
            $user = Auth::user();
            $selectedFields = $request->input('fields');
            $filename = 'company_export_custom_' . date('Y-m-d_His') . '.xlsx';

            Log::info("Custom export by user: {$user->name} (ID: {$user->id})");
            Log::info("Filter: location_id={$user->location_id}, department_id={$user->department_id}");
            Log::info("Fields: " . implode(', ', $selectedFields));

            return Excel::download(
                new CompanyCustomExport($selectedFields),
                $filename
            );

        } catch (\Exception $e) {
            Log::error('Custom Export Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->with('error', 'Export gagal: ' . $e->getMessage());
        }
    }

    /**
     * Export semua field
     */
    public function exportAll()
    {
        try {
            $user = Auth::user();
            $filename = 'company_data_all_' . date('Y-m-d_His') . '.xlsx';

            Log::info("Export all by user: {$user->name} (ID: {$user->id})");
            Log::info("Filter: location_id={$user->location_id}, department_id={$user->department_id}");

            return Excel::download(new CompanyExport, $filename);

        } catch (\Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->with('error', 'Export gagal: ' . $e->getMessage());
        }
    }

    /**
     * Download template untuk import
     */
    public function downloadTemplate()
    {
        try {
            $filename = 'template_import_company_' . date('Y-m-d') . '.xlsx';

            Log::info("Downloading template: $filename");

            return Excel::download(new CompanyTemplateExport, $filename);

        } catch (\Exception $e) {
            Log::error('Template Download Error: ' . $e->getMessage());

            return back()->with('error', 'Download template gagal: ' . $e->getMessage());
        }
    }

    /**
     * Export ke PDF dengan field yang dipilih
     */
    public function exportCustomPdf(Request $request)
    {
        $request->validate([
            'fields' => 'required|array|min:1',
            'fields.*' => 'string',
        ], [
            'fields.required' => 'Pilih minimal 1 field untuk di-export',
            'fields.min'      => 'Pilih minimal 1 field untuk di-export',
        ]);

        try {
            $user           = Auth::user();
            $selectedFields = $request->input('fields');

            Log::info("Custom PDF export by user: {$user->name} (ID: {$user->id})");
            Log::info("Fields: " . implode(', ', $selectedFields));

            // Ambil data company (sesuaikan query filter jika perlu)
            $companies = \App\Models\CompanyInformation::with([
                'contact',
                'address',
                'bank',
                'liablePeople',
            ])->get();

            $imageLogo = '<img src="' . public_path('uploads/logo/logo.png') . '" width="70px" style="float: right;"/>';

            $header = '<table width="100%">
                            <tr>
                                <td style="padding-left:10px;">
                                    <span style="font-size: 6px; font-weight: bold; margin-top:-10px">' . $imageLogo . '</span>
                                    <br>
                                    <span style="font-size:8px;">Synergy Building #08-08</span>
                                    <br>
                                    <span style="font-size:8px;">Jl. Jalur Sutera Barat 17 Alam Sutera, Serpong Tangerang 15143 - Indonesia</span>
                                    <br>
                                    <span style="font-size:8px;">Tangerang 15143 - Indonesia +62 21 304 38808</span>
                                </td>
                            </tr>
                        </table>';

            $footer = '<hr>
            <table width="100%" style="font-size: 10px;">
                <tr>
                    <td width="90%" align="left">
                        <b>Disclaimer</b><br>
                        this document is strictly private, confidential and personal to recipients and should not be
                        copied, distributed or reproduced in whole or in part, not passed to any third party.
                    </td>
                    <td width="10%" style="text-align: right;">{PAGENO}</td>
                </tr>
            </table>';

            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
            $mpdf->AddPage(
                'L',   // Landscape
                '', '', '', '',
                5,     // margin_left
                5,     // margin_right
                35,    // margin_top
                20,    // margin_bottom
                5,     // margin_header
                5      // margin_footer
            );

            $html = view('admin.company.export_pdf', [
                'companies'      => $companies,
                'selectedFields' => $selectedFields,
            ])->render();

            $mpdf->WriteHTML($html);

            ob_clean();
            $filename = 'company_export_' . date('Y-m-d') . '.pdf';
            $mpdf->Output($filename, 'I');

        } catch (\Exception $e) {
            Log::error('Custom PDF Export Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->with('error', 'Export PDF gagal: ' . $e->getMessage());
        }
    }
}
