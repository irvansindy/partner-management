<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyImportRequest;
use App\Imports\CompanyImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class CompanyImportController extends Controller
{
    /**
     * STEP 1: Preview file
     */
    public function preview(CompanyImportRequest $request)
    {
        try {
            // $user = Auth::user();
            // dd($user->id);
            $file = $request->file('file');

            // Simpan file ke storage/app/temp_imports
            $filename = 'import_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('temp_imports', $filename);

            Log::info("File uploaded: $path");

            // Baca untuk preview dengan validasi
            $importer = new CompanyImport(previewOnly: true);
            $fullPath = storage_path('app/' . $path);

            $data = Excel::toCollection($importer, $fullPath);

            Log::info("Preview data count: " . $data->first()->count());

            // Jika ada error validasi
            if (count($importer->getErrors()) > 0) {
                Storage::delete($path);
                Log::warning("Preview validation errors: " . implode(', ', $importer->getErrors()));

                return view('admin.company.import_preview', [
                    'rows' => collect(),
                    'errorsList' => $importer->getErrors(),
                    'filePath' => null
                ]);
            }

            // Simpan path di session
            session(['import_file_path' => $path]);

            return view('admin.company.import_preview', [
                'rows' => $data->first(),
                'errorsList' => [],
                'filePath' => $path
            ]);

        } catch (\Exception $e) {
            Log::error("Preview error: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * STEP 2: Confirm save
     */
    public function importConfirm(Request $request)
    {
        try {
            // Ambil path dari session
            $filePath = session('import_file_path');

            if (!$filePath) {
                Log::warning("Import confirm: No file path in session");
                return back()->with('error', 'Session expired. Silakan upload ulang file.');
            }

            // Cek apakah file masih ada
            if (!Storage::exists($filePath)) {
                session()->forget('import_file_path');
                Log::warning("Import confirm: File not found - $filePath");
                return back()->with('error', 'File tidak ditemukan. Silakan upload ulang.');
            }

            $fullPath = storage_path('app/' . $filePath);
            Log::info("Starting import from: $fullPath");

            // Import data (previewOnly = false)
            $importer = new CompanyImport(previewOnly: false);

            Excel::import($importer, $fullPath);

            // Cek apakah ada error setelah import
            if (count($importer->getErrors()) > 0) {
                Storage::delete($filePath);
                session()->forget('import_file_path');

                Log::error("Import errors: " . implode(', ', $importer->getErrors()));

                return back()->withErrors([
                    'import' => 'Import gagal: ' . implode(', ', $importer->getErrors())
                ]);
            }

            // Hapus file temporary & session
            Storage::delete($filePath);
            session()->forget('import_file_path');

            Log::info("Import completed successfully");

            return redirect()->route('admin.company.import.form')
                ->with('success', 'Import berhasil disimpan!');

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Import Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Hapus file & session jika ada
            if ($filePath = session('import_file_path')) {
                Storage::delete($filePath);
                session()->forget('import_file_path');
            }

            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function showForm()
    {
        return view('admin.company.import_form');
    }
}