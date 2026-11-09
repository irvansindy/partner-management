<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SupportingDocumentUploadUiTest extends TestCase
{
    /** @test */
    public function supporting_document_form_collects_each_file_explicitly(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/attachment.blade.php');

        $this->assertStringContainsString('function buildSupportingDocumentFormData()', $view);
        $this->assertStringContainsString("input[name=\"file_supporting_document[]\"]", $view);
        $this->assertStringContainsString("formData.append('file_supporting_document[]', fileInput.files[0])", $view);
        $this->assertStringNotContainsString("new FormData($('#form_submit_supporting_document')[0])", $view);
    }
}
