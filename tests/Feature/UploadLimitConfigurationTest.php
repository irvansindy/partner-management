<?php

namespace Tests\Feature;

use Tests\TestCase;

class UploadLimitConfigurationTest extends TestCase
{
    public function test_upload_widget_and_localized_hint_follow_changed_server_configuration(): void
    {
        config([
            'company_attachments.max_files' => 3,
            'company_attachments.max_bytes' => 2 * 1024 * 1024,
            'company_attachments.max_width' => 1200,
            'company_attachments.max_height' => 900,
        ]);
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $script = view('cs_vendor.partner_js')->render();
            $this->assertStringContainsString('"max_files":3', $script);
            $this->assertStringContainsString('"max_bytes":2097152', $script);
            $this->assertStringContainsString('maxFileCount: partnerAttachmentLimits.max_files', $script);
            $this->assertStringContainsString('Math.ceil(partnerAttachmentLimits.max_bytes / 1024)', $script);
            $hint = view('cs_vendor.form.file_upload')->render();
            $this->assertStringContainsString(__('public_form.upload_limits_hint', [
                'max_files' => 3, 'max_mb' => 2, 'max_width' => 1200, 'max_height' => 900,
            ]), $hint);
        }
    }
}
