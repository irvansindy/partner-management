<?php

namespace Tests\Feature;

use App\Models\ApprovalProcess;
use App\Models\ApprovalProcessStep;
use App\Models\CompanyInformation;
use App\Models\User;
use App\Services\MenuService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class ApprovalInboxCurrentLayoutTest extends TestCase
{
    /** @test */
    public function pending_approvals_render_in_the_current_submission_table_layout(): void
    {
        $this->app->instance(MenuService::class, Mockery::mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        }));

        $user = (new User())->forceFill([
            'id' => 17,
            'name' => 'Shely',
            'email' => 'shely@example.test',
        ]);
        $this->actingAs($user);

        $company = (new CompanyInformation())->forceFill([
            'id' => 14,
            'form_link_id' => 2,
            'name' => 'PD Jaya Ga Abadi',
            'type' => 'customer',
            'business_classification' => 'Agent',
            'email_address' => 'customer@example.test',
            'created_at' => Carbon::parse('2026-11-04 16:02:00'),
        ]);

        $process = Mockery::mock(ApprovalProcess::class)->makePartial();
        $process->forceFill(['id' => 9, 'step_ordering' => 3]);
        $process->setRelation('company', $company);
        $process->setRelation('initiator', null);
        $process->shouldReceive('getProgressPercentage')->andReturn(67.0);
        $process->shouldReceive('getCompletedStepsCount')->andReturn(2);
        $process->shouldReceive('getTotalStepsCount')->andReturn(3);

        $step = (new ApprovalProcessStep())->forceFill([
            'id' => 22,
            'approval_id' => 9,
            'user_id' => 17,
            'step_ordering' => 3,
            'created_at' => Carbon::parse('2026-11-04 16:07:00'),
        ]);
        $step->setRelation('process', $process);
        $process->setRelation('steps', new Collection([$step]));

        $html = view('admin.approvals.my_approvals', [
            'pendingSteps' => new Collection([$step]),
        ])->render();

        $this->assertStringContainsString('class="container-fluid form-links-page submissions-page approval-inbox-page"', $html);
        $this->assertStringContainsString('id="pendingApprovalsTable"', $html);
        $this->assertStringContainsString('PD Jaya Ga Abadi', $html);
        $this->assertStringContainsString('Progress 2/3', $html);
        $this->assertStringNotContainsString('Step 3/3', $html);
        $this->assertStringContainsString(route('admin.form-links.submission-detail', [2, 14]), $html);
        $this->assertStringContainsString(route('admin.approvals.step.action', 22), $html);
        $this->assertStringNotContainsString('content-wrapper', $html);
    }
}
