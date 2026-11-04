<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ParallelApprovalProcessTest extends TestCase
{
    /** @test */
    public function approval_service_transitions_entire_stage_as_one_or_group(): void
    {
        $service = file_get_contents(dirname(__DIR__, 2).'/app/Services/ApprovalService.php');

        $this->assertStringContainsString('$firstStepOrdering', $service);
        $this->assertStringContainsString("where('step_ordering', \$lockedStep->step_ordering)", $service);
        $this->assertStringContainsString('ApprovalProcessStep::STATUS_SKIPPED', $service);
        $this->assertStringContainsString("where('step_ordering', \$nextStepOrdering)", $service);
        $this->assertStringContainsString("update(['status' => ApprovalProcessStep::STATUS_WAITING])", $service);
    }
}
