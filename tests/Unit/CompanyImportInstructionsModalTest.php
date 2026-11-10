<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CompanyImportInstructionsModalTest extends TestCase
{
    public function test_import_instructions_are_available_from_an_accessible_header_modal(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/company/import_form.blade.php');

        $this->assertStringContainsString('class="page-actions"', $view);
        $this->assertStringContainsString('data-target="#importInstructionsModal"', $view);
        $this->assertStringContainsString('aria-controls="importInstructionsModal"', $view);
        $this->assertStringContainsString('id="importInstructionsModal"', $view);
        $this->assertStringContainsString('modal-dialog modal-lg modal-dialog-scrollable', $view);
        $this->assertStringContainsString('aria-labelledby="importInstructionsModalLabel"', $view);
        $this->assertStringContainsString('id="importInstructionsModalLabel"', $view);
        $this->assertStringNotContainsString('<!-- Info Section -->', $view);
        $this->assertStringContainsString('import-instructions-modal', $view);
        $this->assertStringContainsString('instruction-action-example', $view);
        $this->assertStringContainsString('import-guidance import-guidance--warning', $view);
        $this->assertStringContainsString('import-guidance import-guidance--tip', $view);
        $this->assertStringContainsString('font-weight: 400', $view);
        $this->assertStringContainsString('box-shadow: none', $view);
    }
}
