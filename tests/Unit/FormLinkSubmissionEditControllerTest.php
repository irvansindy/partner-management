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
}
