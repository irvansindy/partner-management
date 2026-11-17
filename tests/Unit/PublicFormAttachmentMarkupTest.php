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
        $this->assertStringContainsString("__('public_form.upload_limits_hint',", $html);
        foreach (['id', 'en'] as $locale) {
            $copy = require dirname(__DIR__, 2)."/resources/lang/{$locale}/public_form.php";
            foreach ([':max_files', ':max_mb', ':max_width', ':max_height'] as $placeholder) {
                $this->assertStringContainsString($placeholder, $copy['upload_limits_hint']);
            }
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
