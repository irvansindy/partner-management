<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicFormAttachmentMarkupTest extends TestCase
{
    /** @test */
    public function upload_control_exposes_the_server_constraints(): void
    {
        $html = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/form/file_upload.blade.php');

        $this->assertStringContainsString('accept="application/pdf,image/jpeg,image/png"', $html);
        $this->assertStringContainsString('Maksimal 10 file', $html);
        $this->assertStringContainsString('5 MB', $html);
        $this->assertStringContainsString('6000 × 6000', $html);
    }

    /** @test */
    public function attachment_input_belongs_to_the_main_submission_form(): void
    {
        $html = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/form/file_upload.blade.php');

        $this->assertStringNotContainsString('<form', $html);
        $this->assertStringNotContainsString('</form>', $html);
        $this->assertStringContainsString('form="form_company"', $html);
    }
}
