<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormLinkSubmissionDetailTabsTest extends TestCase
{
    public function test_submission_detail_uses_accessible_section_tabs_and_uniform_attachments(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/form_links/submission_detail.blade.php');

        $this->assertStringContainsString('id="submission-detail-tabs"', $view);
        $this->assertStringContainsString('role="tablist"', $view);
        $this->assertStringContainsString('id="approval-pane"', $view);
        $this->assertStringContainsString('id="company-pane"', $view);
        $this->assertStringContainsString('id="liable-pane"', $view);
        $this->assertStringContainsString('id="contacts-pane"', $view);
        $this->assertStringContainsString('id="addresses-pane"', $view);
        $this->assertStringContainsString('id="bank-pane"', $view);
        $this->assertStringContainsString('id="attachments-pane"', $view);
        $this->assertStringContainsString('.attachment-card', $view);
        $this->assertStringContainsString('.attachment-preview', $view);
        $this->assertStringContainsString('object-fit: contain', $view);
        $this->assertStringContainsString('height: 180px', $view);
        $this->assertStringContainsString('h-100 attachment-card', $view);
    }
}
