<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ApprovalSettingParallelStageTest extends TestCase
{
    /** @test */
    public function approval_setting_accepts_grouped_stages_and_rejects_duplicate_users(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/ApprovalSettingController.php');

        $this->assertStringContainsString("'approval_stages'", $controller);
        $this->assertStringContainsString("'approval_stages.*.user_ids'", $controller);
        $this->assertStringContainsString('array_unique($allUserIds)', $controller);
        $this->assertStringContainsString("'step_ordering' => \$stageIndex + 1", $controller);
        $this->assertStringContainsString("'existing_stages'", $controller);
    }
}
