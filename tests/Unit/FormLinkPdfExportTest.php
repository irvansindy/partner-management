<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormLinkPdfExportTest extends TestCase
{
    /** @test */
    public function pdf_export_uses_an_application_writable_temporary_directory(): void
    {
        $controller = file_get_contents(
            dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php'
        );

        $this->assertStringContainsString("storage_path('framework/cache/mpdf')", $controller);
        $this->assertStringContainsString("'tempDir'", $controller);
        $this->assertStringContainsString('ensureDirectoryExists', $controller);
    }
}
