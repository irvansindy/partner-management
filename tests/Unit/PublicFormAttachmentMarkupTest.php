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
        $this->assertStringContainsString("__('public_form.upload_hint')", $html);
        foreach (['id' => 'Maksimal 10 file', 'en' => 'Up to 10 files'] as $locale => $limit) {
            $copy = require dirname(__DIR__, 2)."/resources/lang/{$locale}/public_form.php";
            $this->assertStringContainsString($limit, $copy['upload_hint']);
            $this->assertStringContainsString('5 MB', $copy['upload_hint']);
            $this->assertStringContainsString('6000 × 6000', $copy['upload_hint']);
        }
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
