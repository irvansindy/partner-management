<?php

namespace App\Http\Controllers;

use App\Exports\CompanyExport;
use App\Exports\CompanyCustomExport;
use App\Exports\CompanyTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

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
            $selectedFields = $request->input('fields');
            $filename = 'company_export_custom_' . date('Y-m-d_His') . '.xlsx';

            Log::info("Custom export with fields: " . implode(', ', $selectedFields));

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
            $filename = 'company_data_all_' . date('Y-m-d_His') . '.xlsx';

            Log::info("Exporting all companies to: $filename");

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
}