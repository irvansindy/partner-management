<?php

namespace Tests\Feature;

use App\Exports\CompanyCustomExport;
use App\Exports\CompanyTemplateExport;
use App\Http\Controllers\Admin\PostalCodeImportController;
use App\Http\Controllers\CompanyExportController;
use App\Http\Controllers\CompanyImportController;
use App\Http\Requests\CompanyImportRequest;
use App\Imports\CompanyImport;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Mockery;
use Tests\TestCase;

class DataToolsLocalizationTest extends TestCase
{
    public function test_dictionaries_have_matching_keys_and_placeholders(): void
    {
        foreach (['data_tools', 'data_tools_validation'] as $group) {
            $id = Arr::dot(require resource_path("lang/id/{$group}.php"));
            $en = Arr::dot(require resource_path("lang/en/{$group}.php"));
            $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en));
            foreach ($id as $key => $text) {
                preg_match_all('/:[a-z_]+/', $text, $idTokens);
                preg_match_all('/:[a-z_]+/', $en[$key], $enTokens);
                $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], $key);
            }
        }
    }

    public function test_forms_preview_and_pdf_render_in_both_languages(): void
    {
        $this->app->instance(MenuService::class, Mockery::mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        }));
        $this->actingAs((new User())->forceFill(['id' => 1, 'name' => 'Reviewer', 'email' => 'reviewer@example.test']));
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
        session(['postal_import_result' => ['inserted' => 1, 'existing' => 2, 'rejected' => 1, 'errors' => []]]);
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach (['admin.company.export_form', 'admin.company.import_form', 'admin.postal_codes.import', 'admin.company.import_preview'] as $view) {
                $html = view($view, ['rows' => collect([collect(['nama_perusahaan' => 'Example Company'])]), 'errorsList' => [], 'filePath' => null])->render();
                $this->assertDoesNotMatchRegularExpression('/\b(?:data_tools\.[A-Za-z]|data_tools_validation\.[A-Za-z]|tools\.[A-Za-z])/', $html, $view);
                if ($view === 'admin.company.import_form') {
                    $this->assertStringContainsString(__('data_tools.import_instructions')[0], $html);
                }
                if ($view === 'admin.company.import_preview') {
                    $this->assertStringContainsString(__('data_tools.columns')['nama_perusahaan'], $html);
                }
                if ($view === 'admin.postal_codes.import') {
                    $this->assertStringContainsString(__('data_tools.postal_result', ['inserted' => 1, 'existing' => 2, 'rejected' => 1]), $html);
                    $this->assertStringContainsString('alert-warning', $html);
                }
            }
            $empty = view('admin.company.import_preview', ['rows' => collect(), 'errorsList' => [], 'filePath' => null])->render();
            $this->assertStringContainsString(__('data_tools.Data Preview'), $empty);
            $failed = view('admin.company.import_preview', ['rows' => collect(), 'errorsList' => [__('data_tools.row_name', ['row' => 2])], 'filePath' => null])->render();
            $this->assertStringContainsString(__('data_tools.Terjadi Error Validasi:'), $failed);
            $pdf = view('admin.company.export_pdf', ['companies' => collect(), 'selectedFields' => ['name', 'telephone_address']])->render();
            $this->assertStringContainsString(__('data_tools.Telepon Alamat'), $pdf);
            $this->assertStringContainsString(__('data_tools.Tidak ada data'), $pdf);
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().'/partner-data-tools-tests']);
            $mpdf->WriteHTML($pdf);
            $this->assertStringStartsWith('%PDF-', $mpdf->Output('', 'S'));
        }
    }

    public function test_template_workbook_is_localized_without_changing_machine_headers(): void
    {
        $headings = (new CompanyTemplateExport())->headings();
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $this->assertSame($headings, (new CompanyTemplateExport())->headings());
            $this->assertSame(['nama_perusahaan', 'e_mail_kontak'], (new CompanyCustomExport(['name', 'contact_email']))->headings());
            $bytes = Excel::raw(new CompanyTemplateExport(), \Maatwebsite\Excel\Excel::XLSX);
            $path = tempnam(sys_get_temp_dir(), 'localized-template-');
            try {
                file_put_contents($path, $bytes);
                $workbook = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx')->load($path);
                $this->assertSame('nama_perusahaan', $workbook->getSheet(0)->getCell('A1')->getValue());
                $this->assertSame(__('data_tools.template_notes.A1'), $workbook->getSheet(0)->getComment('A1')->getText()->getPlainText());
                $this->assertSame(__('data_tools.instructions_title'), $workbook->getSheet(1)->getTitle());
                $this->assertSame('"vendor,customer"', $workbook->getSheet(0)->getCell('C2')->getDataValidation()->getFormula1());
                $workbook->disconnectWorksheets();
            } finally {
                unlink($path);
            }
        }
    }

    public function test_validation_import_rows_and_session_errors_are_localized(): void
    {
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $request = new CompanyImportRequest();
            $validator = Validator::make([], $request->rules(), $request->messages(), $request->attributes());
            $this->assertSame(str_replace(':attribute', __('data_tools_validation.attributes.file'), __('data_tools_validation.messages.required')), $validator->errors()->first('file'));
            try {
                (new CompanyExportController())->exportCustom(new Request());
                $this->fail('Export without columns must fail.');
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->assertSame(__('data_tools.Pilih minimal 1 field untuk di-export'), $e->errors()['fields'][0]);
            }
            $import = new CompanyImport(true);
            $validate = new \ReflectionMethod(CompanyImport::class, 'validateRow');
            $validate->setAccessible(true);
            $validate->invoke($import, ['name' => '', 'email_address' => 'invalid'], 2);
            $this->assertSame(__('data_tools.row_name', ['row' => 2]), $import->getErrors()[0]);
            $this->assertSame(__('data_tools.row_email', ['row' => 2]), $import->getErrors()[1]);
            session()->forget('import_file_path');
            (new CompanyImportController())->importConfirm(new Request());
            $this->assertSame(__('data_tools.Session expired. Silakan upload ulang file.'), session('error'));
        }
    }

    public function test_postal_error_download_uses_current_locale(): void
    {
        session(['postal_import_errors' => [['row' => 2, 'values' => ['X', 'Y', 'Z', 'V', '1'], 'reason' => 'old language', 'reason_key' => 'Kode pos harus terdiri dari lima digit.']]]);
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $response = (new PostalCodeImportController())->errors();
            ob_start();
            $response->sendContent();
            $csv = ob_get_clean();
            $this->assertStringContainsString(__('data_tools.Kode pos harus terdiri dari lima digit.'), $csv);
        }
    }
}
