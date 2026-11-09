<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormLinkSubmissionEditUiTest extends TestCase
{
    /** @test */
    public function controller_supplies_the_central_editability_state_to_list_and_detail(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertStringContainsString("'can_edit_submission'", $controller);
        $this->assertStringContainsString('$canEditSubmission = $this->formLinkService->canEditSubmission(', $controller);
        $this->assertStringContainsString("compact('formLink', 'company', 'canManageFormLink', 'canEditSubmission')", $controller);
    }

    /** @test */
    public function list_and_detail_show_edit_actions_only_when_editing_is_allowed(): void
    {
        $root = dirname(__DIR__, 2).'/resources/views/admin/form_links/';
        $list = file_get_contents($root.'submissions.blade.php');
        $detail = file_get_contents($root.'submission_detail.blade.php');

        $this->assertStringContainsString('@if ($company->can_edit_submission)', $list);
        $this->assertStringContainsString("route('admin.form-links.submission-edit'", $list);
        $this->assertStringContainsString('aria-label="Edit submission', $list);

        $this->assertStringContainsString('@if ($canEditSubmission)', $detail);
        $this->assertStringContainsString("route('admin.form-links.submission-edit'", $detail);
        $this->assertStringContainsString('Edit Submission', $detail);
        $this->assertStringContainsString('submission-detail-actions', $detail);
        $this->assertGreaterThanOrEqual(5, substr_count($detail, 'submission-header-action'));
    }

    /** @test */
    public function repeatable_delete_button_has_an_independent_accessible_size(): void
    {
        $root = dirname(__DIR__, 2).'/resources/views/admin/form_links/';
        $page = file_get_contents($root.'submission_edit.blade.php');
        $row = file_get_contents($root.'_submission_edit_row.blade.php');

        $this->assertStringContainsString('height: 44px;', $page);
        $this->assertStringContainsString('width: 44px;', $page);
        $this->assertStringContainsString(':focus-visible', $page);
        $this->assertStringNotContainsString('btn btn-outline-danger btn-sm repeatable-item__remove', $row);
        $this->assertStringContainsString('class="repeatable-item__remove"', $row);
    }
}
