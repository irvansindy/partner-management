<?php

namespace Tests\Unit;

use App\Models\ApprovalProcess;
use App\Models\ApprovalProcessStep;
use App\Models\CompanyInformation;
use App\Models\FormLink;
use App\Models\MasterDepartment;
use App\Models\MasterDivision;
use App\Models\User;
use App\Services\ApprovalService;
use App\Services\FormLinkService;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class FormLinkSubmissionEditabilityTest extends TestCase
{
    /** @test */
    public function matching_organization_can_edit_before_the_final_active_stage(): void
    {
        [$service, $formLink, $company, $user] = $this->fixture(1, 2);

        $this->assertTrue($service->canEditSubmission($formLink, $company, $user));
    }

    /** @test */
    public function submission_is_locked_when_the_final_stage_becomes_active(): void
    {
        [$service, $formLink, $company, $user] = $this->fixture(2, 2);

        $this->assertFalse($service->canEditSubmission($formLink, $company, $user));
    }

    /** @test */
    public function parallel_approvers_on_the_final_order_are_one_final_stage(): void
    {
        [$service, $formLink, $company, $user, $approval] = $this->fixture(2, 2);
        $steps = $approval->steps;
        $steps->push($this->step(2, ApprovalProcessStep::STATUS_WAITING));
        $approval->setRelation('steps', $steps);

        $this->assertFalse($service->canEditSubmission($formLink, $company, $user));
    }

    /** @test */
    public function user_must_match_both_division_and_department(): void
    {
        [$service, $formLink, $company, $user] = $this->fixture(1, 2);
        $user->division_id = 99;

        $this->assertFalse($service->canEditSubmission($formLink, $company, $user));

        $user->division_id = 10;
        $user->department_id = 99;

        $this->assertFalse($service->canEditSubmission($formLink, $company, $user));
    }

    /** @test */
    public function legacy_department_without_division_uses_the_form_link_creator_division(): void
    {
        [$service, $formLink, $company, $user] = $this->fixture(1, 2);
        $formLink->department->division_id = null;
        $formLink->setRelation('creator', (new User())->forceFill([
            'id' => 70,
            'division_id' => 10,
            'department_id' => 20,
        ]));

        $this->assertTrue($service->canEditSubmission($formLink, $company, $user));
    }

    /** @test */
    public function completed_or_incomplete_approval_data_fails_closed(): void
    {
        [$service, $formLink, $company, $user, $approval] = $this->fixture(1, 2);
        $approval->status = ApprovalProcess::STATUS_APPROVED;
        $this->assertFalse($service->canEditSubmission($formLink, $company, $user));

        $approval->status = ApprovalProcess::STATUS_IN_PROGRESS;
        $approval->setRelation('steps', new Collection());
        $this->assertFalse($service->canEditSubmission($formLink, $company, $user));

        $company->unsetRelation('approvalProcess');
        $company->setRelation('approvalProcess', null);
        $this->assertFalse($service->canEditSubmission($formLink, $company, $user));
    }

    private function fixture(int $activeOrder, int $finalOrder): array
    {
        $division = (new MasterDivision())->forceFill(['id' => 10, 'name' => 'Operations']);
        $department = (new MasterDepartment())->forceFill([
            'id' => 20,
            'division_id' => 10,
            'name' => 'Purchasing',
        ]);
        $department->setRelation('division', $division);

        $formLink = (new FormLink())->forceFill(['id' => 30, 'department_id' => 20]);
        $formLink->setRelation('department', $department);
        $formLink->setRelation('creator', (new User())->forceFill([
            'id' => 70,
            'division_id' => 10,
            'department_id' => 20,
        ]));

        $user = (new User())->forceFill(['id' => 40, 'division_id' => 10, 'department_id' => 20]);

        $approval = (new ApprovalProcess())->forceFill([
            'id' => 50,
            'status' => ApprovalProcess::STATUS_IN_PROGRESS,
        ]);
        $steps = new Collection();
        for ($order = 1; $order <= $finalOrder; $order++) {
            $status = $order < $activeOrder
                ? ApprovalProcessStep::STATUS_APPROVED
                : ($order === $activeOrder
                    ? ApprovalProcessStep::STATUS_WAITING
                    : ApprovalProcessStep::STATUS_PENDING);
            $steps->push($this->step($order, $status));
        }
        $approval->setRelation('steps', $steps);

        $company = (new CompanyInformation())->forceFill(['id' => 60, 'form_link_id' => 30]);
        $company->setRelation('approvalProcess', $approval);

        $approvalService = $this->createMock(ApprovalService::class);

        return [new FormLinkService($approvalService), $formLink, $company, $user, $approval];
    }

    private function step(int $order, int $status): ApprovalProcessStep
    {
        return (new ApprovalProcessStep())->forceFill([
            'step_ordering' => $order,
            'status' => $status,
        ]);
    }
}
