<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CrossDepartmentSubmissionAccessTest extends TestCase
{
    /** @test */
    public function assigned_approvers_receive_read_only_submission_access(): void
    {
        $root = dirname(__DIR__, 2);
        $service = file_get_contents($root.'/app/Services/FormLinkService.php');
        $controller = file_get_contents($root.'/app/Http/Controllers/Admin/FormLinkController.php');
        $attachmentController = file_get_contents($root.'/app/Http/Controllers/Admin/CompanyAttachmentController.php');

        $this->assertStringContainsString('function canManageFormLink(', $service);
        $this->assertStringContainsString('function authorizeSubmissionRead(', $service);
        $this->assertStringContainsString("steps()->where('user_id', \$user->id)->exists()", $service);
        $this->assertSame(2, substr_count($controller, 'authorizeSubmissionRead($formLink, $company)'));
        $this->assertStringContainsString('authorizeSubmissionRead(', $attachmentController);
        $this->assertStringContainsString('$attachment->company->formLink', $attachmentController);
    }

    /** @test */
    public function management_and_edit_routes_keep_the_stricter_form_link_authorization(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/FormLinkController.php');

        $this->assertStringContainsString('public function submissionEdit(', $controller);
        $this->assertStringContainsString('public function submissionUpdate(', $controller);
        $this->assertGreaterThanOrEqual(8, substr_count($controller, 'authorizeAccess($formLink)'));
    }

    /** @test */
    public function read_only_approvers_are_not_linked_to_form_management(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/form_links/submission_detail.blade.php');

        $this->assertStringContainsString('@if ($canManageFormLink)', $view);
        $this->assertStringContainsString("route('admin.approvals.show', \$company->approvalProcess)", $view);
        $this->assertStringContainsString("route('admin.approvals.my')", $view);
    }
}
