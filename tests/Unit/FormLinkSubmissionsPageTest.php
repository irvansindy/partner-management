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
        $this->assertStringContainsString('form_links_remaining.View submission details', $view);
        $this->assertStringContainsString('$completedSteps = $approval->getCompletedStepsCount()', $view);
        $this->assertStringContainsString("__('form_links.progress', ['done' => \$completedSteps, 'total' => \$totalSteps])", $view);
        $this->assertStringContainsString("__('form_links.approved_count', ['done' => \$completedSteps, 'total' => \$totalSteps])", $view);
        $this->assertStringContainsString("__('form_links.rejected_count', ['done' => \$completedSteps, 'total' => \$totalSteps])", $view);
        $this->assertGreaterThanOrEqual(3, substr_count($view, "__('form_links_remaining.Approval progress')"));
        $this->assertStringNotContainsString('Step {{ $approval->step_ordering }}', $view);
    }

    /** @test */
    public function submission_detail_uses_a_neutral_approval_timeline_with_compact_status_accents(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/form_links/submission_detail.blade.php');

        $view .= file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/form_links/_submission_detail_panels.blade.php');

        $this->assertStringContainsString('approval-summary', $view);
        $this->assertStringContainsString('approval-step-card', $view);
        $this->assertStringContainsString('approval-status-dot', $view);
        $this->assertStringContainsString('approval-note', $view);
        $this->assertStringContainsString("__('form_links_remaining.Approval progress')", $view);
        $this->assertStringNotContainsString('alert alert-{{ $step->isApproved()', $view);
        $this->assertStringNotContainsString('class="time-label"', $view);
        $this->assertStringContainsString('{{ $step->notes }}', $view);
        $this->assertStringNotContainsString('{!! $step->notes !!}', $view);
    }
}
