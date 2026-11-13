<?php

namespace Tests\Feature;

use App\Http\Requests\FormLinkStoreRequest;
use App\Http\Requests\FormLinkUpdateRequest;
use App\Models\CompanyAddress;
use App\Models\CompanyAttachment;
use App\Models\CompanyInformation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class FormLinkLocalizationTest extends TestCase
{
    public function test_translation_keys_and_placeholders_match_recursively(): void
    {
        foreach (['form_links', 'submission', 'submission_validation', 'form_links_remaining', 'form_links_audit'] as $group) {
            $id = Arr::dot(require resource_path("lang/id/{$group}.php"));
            $en = Arr::dot(require resource_path("lang/en/{$group}.php"));
            $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en), $group);
            foreach ($id as $key => $value) {
                preg_match_all('/:[a-z_]+|_[A-Z]+_/', $value, $idTokens);
                preg_match_all('/:[a-z_]+|_[A-Z]+_/', $en[$key], $enTokens);
                $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], "{$group}.{$key}");
            }
        }
    }

    public function test_invalid_form_fields_and_nested_coordinates_use_the_active_locale(): void
    {
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            foreach ([new FormLinkStoreRequest(), new FormLinkUpdateRequest()] as $request) {
                $validator = Validator::make(
                    ['title' => 'Test', 'form_type' => 'customer', 'expires_at' => 'invalid', 'max_submissions' => 'abc'],
                    $request->rules(), $request->messages(), $request->attributes()
                );
                $this->assertTrue($validator->fails());
                $this->assertSame(str_replace(':attribute', __('admin.Expires At'), __('submission_validation.messages.date')), $validator->errors()->first('expires_at'));
                $this->assertSame(str_replace(':attribute', __('admin.Submissions'), __('submission_validation.messages.integer')), $validator->errors()->first('max_submissions'));
            }
            $validator = Validator::make(['addresses' => [['latitude' => 'invalid']]], ['addresses.*.latitude' => 'numeric'], __('submission_validation.messages'), __('submission_validation.attributes'));
            $this->assertSame(str_replace(':attribute', __('submission_validation.attributes')['addresses.*.latitude'], __('submission_validation.messages.numeric')), $validator->errors()->first('addresses.0.latitude'));
        }
    }

    public function test_shared_partner_panels_and_pdf_render_in_both_languages(): void
    {
        $company = new CompanyInformation(['id' => 17, 'name' => 'Example Partner', 'type' => 'vendor', 'credit_limit' => 1234.5, 'created_at' => '2026-05-04', 'system_management' => 'ISO 9001']);
        foreach (['liablePeople', 'contact', 'bank', 'productCustomers'] as $relation) {
            $company->setRelation($relation, collect());
        }
        $company->setRelation('salesSurvey', null);
        $company->setRelation('address', collect([new CompanyAddress(['address' => 'Example Address', 'province' => 'Banten', 'latitude' => -6.2, 'longitude' => 106.8])]));
        $company->setRelation('attachment', collect([(new CompanyAttachment())->forceFill(['id' => 1, 'filename' => 'company.pdf', 'filetype' => 'application/pdf', 'filesize' => 1536])]));

        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $row = view('admin.form_links._submission_edit_row', ['kind' => 'addresses', 'index' => 0, 'data' => []])->render();
            $panels = view('admin.form_links._submission_detail_panels', ['company' => $company, 'showApproval' => false])->render();
            $pdf = view('admin.form_links.export_submission_pdf', compact('company'))->render();
            $this->assertStringContainsString(__('admin.Address').' 1', $panels);
            $this->assertStringContainsString(__('form_links_audit.Latitude'), $panels);
            $this->assertStringContainsString(__('form_links_audit.address_search'), $row);
            $this->assertStringContainsString($locale === 'id' ? '1.234,50' : '1,234.50', $panels);
            $this->assertStringNotContainsString('id="approval-pane"', $panels);
            $this->assertStringContainsString(__('submission.identity_heading'), $pdf);
            $this->assertStringContainsString(__('form_links_audit.Tersertifikasi'), $pdf);
            $this->assertStringContainsString(__('form_links_audit.files', ['count' => 1]), $pdf);
            $this->assertStringContainsString($locale === 'id' ? '4 Mei 2026' : '4 May 2026', $pdf);
            foreach (['form_links_audit.', 'form_links_remaining.', 'submission.'] as $rawKey) {
                $this->assertStringNotContainsString($rawKey, $panels.$pdf);
            }
            $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().'/partner-form-links-pdf-tests']);
            $mpdf->WriteHTML($pdf);
            $this->assertStringStartsWith('%PDF-', $mpdf->Output('', 'S'));
        }
    }
}
