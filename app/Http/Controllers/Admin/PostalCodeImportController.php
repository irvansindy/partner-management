<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PostalCodeImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostalCodeImportController extends Controller
{
    public function show() { return view('admin.postal_codes.import'); }

    public function store(Request $request, PostalCodeImportService $importer)
    {
        $validated = $request->validate(['csv' => 'required|file|max:10240|mimes:csv,txt']);
        $path = $validated['csv']->store('imports/postal-codes', 'local');
        try {
            $result = $importer->import(Storage::disk('local')->path($path));
            session(['postal_import_errors' => $result['errors']]);
            return back()->with('postal_import_result', $result);
        } finally {
            Storage::disk('local')->delete($path);
        }
    }

    public function errors()
    {
        $errors = session('postal_import_errors', []);
        abort_if(!$errors, 404);
        return response()->streamDownload(function () use ($errors) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, ['row', 'province', 'regency', 'district', 'village', 'postal_code', 'reason']);
            foreach ($errors as $error) fputcsv($stream, array_merge([$error['row']], array_pad($error['values'], 5, ''), [$error['reason']]));
            fclose($stream);
        }, 'postal-code-import-errors.csv', ['Content-Type' => 'text/csv', 'X-Content-Type-Options' => 'nosniff']);
    }
}
