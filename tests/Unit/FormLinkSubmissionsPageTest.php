<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormLinkSubmissionsPageTest extends TestCase
{
    /** @test */
    public function submissions_page_uses_compact_stats_and_responsive_actions(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/form_links/submissions.blade.php');
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertStringContainsString("'submissionStats'", $controller);
        $this->assertStringContainsString('$submissionStats[\'total\']', $view);
        $this->assertStringContainsString('$submissionStats[\'approved\']', $view);
        $this->assertStringContainsString('$submissionStats[\'in_progress\']', $view);
        $this->assertStringContainsString('$submissionStats[\'rejected\']', $view);
        $this->assertSame(4, substr_count($view, 'class="stat-card '));
        $this->assertStringNotContainsString('small-box', $view);
        $this->assertStringContainsString('submissions-table-wrap', $view);
        $this->assertStringContainsString('submission-actions', $view);
        $this->assertStringContainsString('aria-label="View submission details', $view);
    }
}
