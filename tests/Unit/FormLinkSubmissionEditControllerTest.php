<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormLinkSubmissionEditControllerTest extends TestCase
{
    /** @test */
    public function edit_and_update_use_the_same_backend_authorization_guard(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertSame(2, substr_count(
            $controller,
            'authorizeSubmissionEdit($formLink, $company)'
        ));
        $this->assertStringNotContainsString('if (!in_array($status, [0, 1]))', $controller);
    }

    /** @test */
    public function nested_record_ids_are_validated_within_the_current_company(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        foreach (['company_liable_people', 'company_contacts', 'company_addresses', 'company_banks', 'company_attachments'] as $table) {
            $this->assertStringContainsString("Rule::exists('{$table}', 'id')", $controller);
        }
    }

    /** @test */
    public function employee_count_uses_the_same_range_values_as_the_public_form(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertStringContainsString("Rule::in(EmployeeRange::values())", $controller);
    }

    /** @test */
    public function attachment_updates_use_the_central_attachment_service(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertStringContainsString('CompanyAttachmentService $attachmentService', $controller);
        $this->assertStringContainsString('$this->attachmentService->deleteStored($toDelete);', $controller);
        $this->assertStringContainsString('$this->attachmentService->validate($file)', $controller);
        $this->assertStringContainsString('$this->attachmentService->store(', $controller);
        $this->assertStringNotContainsString("Storage::disk('public')->delete", $controller);
        $this->assertStringNotContainsString("->store('attachments/'", $controller);
    }

    /** @test */
    public function indonesia_location_update_accepts_existing_ids_or_names(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertStringContainsString("->orWhere('name', \$address['province'])", $controller);
        $this->assertStringContainsString("->orWhere('name', \$address['city'])", $controller);
    }

    /** @test */
    public function tax_register_input_is_persisted_to_the_company_npwp_column(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/form_links/submission_edit.blade.php');

        $this->assertMatchesRegularExpression(
            "/'npwp'\\s*=>\\s*\\\$validated\\['register_number_as_in_tax_invoice'\\]\\s*\\?\\?\\s*null/",
            $controller
        );
        $this->assertStringNotContainsString("'register_number_as_in_tax_invoice' => \$validated", $controller);
        $this->assertStringContainsString("old('register_number_as_in_tax_invoice', \$company->npwp)", $view);
    }

    /** @test */
    public function update_errors_are_logged_without_exposing_database_details(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertStringContainsString("Log::error('Submission update failed'", $controller);
        $this->assertStringContainsString("__('form_links.Gagal menyimpan perubahan submission. Silakan periksa data dan coba kembali.')", $controller);
        $this->assertStringNotContainsString("'Gagal mengupdate data: ' . \$e->getMessage()", $controller);
    }
}
