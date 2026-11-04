<?php

namespace Tests\Unit;

use App\Models\Approval;
use App\Models\ApprovalProcess;
use App\Models\ApprovalProcessStep;
use PHPUnit\Framework\TestCase;

class ApprovalModuleIntegrityTest extends TestCase
{
    /** @test */
    public function legacy_models_and_template_details_use_the_current_approval_schema(): void
    {
        $detail = file_get_contents(dirname(__DIR__, 2).'/app/Models/ApprovalTemplateDetail.php');
        $legacyDetail = file_get_contents(dirname(__DIR__, 2).'/app/Models/ApprovalDetails.php');

        $this->assertTrue(is_subclass_of(Approval::class, ApprovalProcess::class));
        $this->assertStringContainsString("'approval_id'", $detail);
        $this->assertStringNotContainsString("'approval_template_id'", $detail);
        $this->assertStringContainsString("belongsTo(ApprovalTemplate::class, 'approval_id', 'id')", $detail);
        $this->assertStringContainsString('function user()', $legacyDetail);
    }

    /** @test */
    public function approval_actions_are_locked_authorized_and_safe_to_render(): void
    {
        $service = file_get_contents(dirname(__DIR__, 2).'/app/Services/ApprovalService.php');
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/ApprovalProcessController.php');
        $show = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approvals/show.blade.php');
        $action = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approvals/action.blade.php');

        $this->assertStringContainsString('lockForUpdate()', $service);
        $this->assertStringContainsString('CompanyInformation::whereKey($company->id)->lockForUpdate()', $service);
        $this->assertStringContainsString('$actorId', $service);
        $this->assertStringContainsString("required_if:action,reject", $controller);
        $this->assertStringContainsString('hasAnyRole', $controller);
        $this->assertStringNotContainsString('roles[0]', $controller);
        $this->assertStringContainsString('{{ $step->notes }}', $show);
        $this->assertStringNotContainsString('{!! $step->notes !!}', $show);
        $this->assertStringContainsString('Wajib jika memilih Reject', $action);
        $this->assertStringContainsString('maxlength="1000"', $action);
        $this->assertStringNotContainsString('window.confirm(', $action);
        $this->assertStringContainsString('Swal.fire({', $action);
        $this->assertStringContainsString("vendor/sweetalert2/sweetalert2.all.min.js", $action);
    }

    /** @test */
    public function approving_a_step_assigns_the_company_to_the_approver_organization_atomically(): void
    {
        $service = file_get_contents(dirname(__DIR__, 2).'/app/Services/ApprovalService.php');

        $this->assertStringContainsString("User::with(['division', 'dept'])->whereKey(\$actorId)->lockForUpdate()->firstOrFail()", $service);
        $this->assertStringContainsString("'location_id' => \$actor->office_id", $service);
        $this->assertStringContainsString("'department_id' => \$actor->department_id", $service);
        $this->assertStringContainsString('Approver office and department must be configured.', $service);
        $this->assertStringContainsString("if (\$action === 'reject')", $service);
    }

    /** @test */
    public function approval_list_and_action_use_the_current_admin_layout(): void
    {
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/Admin/ApprovalProcessController.php');
        $index = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approvals/index.blade.php');
        $action = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approvals/action.blade.php');
        $show = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approvals/show.blade.php');

        $this->assertStringContainsString("@extends('layouts.main')", $index);
        $this->assertStringContainsString("@extends('layouts.main')", $action);
        $this->assertStringNotContainsString("@extends('adminlte::page')", $index);
        $this->assertStringNotContainsString("@extends('adminlte::page')", $action);
        $this->assertStringContainsString('approval-action-shell', $action);
        $this->assertStringContainsString('decision-option', $action);
        $this->assertStringContainsString('approval-field', $action);
        $this->assertStringNotContainsString('<div class="form-group">', $action);
        $this->assertStringContainsString("@extends('layouts.main')", $show);
        $this->assertStringContainsString('approval-detail-shell', $show);
        $this->assertStringContainsString("'company.formLink'", $controller);
        $this->assertStringContainsString("'process.steps.approver'", $controller);
    }

    /** @test */
    public function approval_setting_is_super_admin_only_and_uses_atomic_writes(): void
    {
        $routes = file_get_contents(dirname(__DIR__, 2).'/routes/web.php');
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/ApprovalSettingController.php');

        $this->assertStringContainsString("middleware(['auth', 'role:super-admin'])->group", $routes);
        $this->assertStringContainsString('DB::transaction', $controller);
        $this->assertStringNotContainsString('DB::beginTransaction()', $controller);
        $this->assertStringNotContainsString('$e->getMessage(), 500', $controller);
    }

    /** @test */
    public function approval_sequences_are_protected_by_database_constraints(): void
    {
        $files = glob(dirname(__DIR__, 2).'/database/migrations/*_harden_approval_constraints.php');
        $this->assertCount(1, $files);
        $migration = file_get_contents($files[0]);

        $this->assertStringContainsString("unique(['location_id', 'department_id']", $migration);
        $this->assertStringContainsString("unique(['approval_id', 'step_ordering']", $migration);
        $this->assertStringContainsString("unique(['approval_id', 'user_id']", $migration);
        $this->assertStringContainsString("unique('company_information_id'", $migration);
    }

    /** @test */
    public function parallel_approval_stages_have_a_non_actionable_skipped_state(): void
    {
        $step = new ApprovalProcessStep(['status' => ApprovalProcessStep::STATUS_SKIPPED]);

        $this->assertSame(4, ApprovalProcessStep::STATUS_SKIPPED);
        $this->assertTrue($step->isSkipped());
        $this->assertFalse($step->isWaiting());
    }

    /** @test */
    public function parallel_stage_migration_removes_only_step_ordering_uniqueness(): void
    {
        $files = glob(dirname(__DIR__, 2).'/database/migrations/*_enable_parallel_approval_stages.php');

        $this->assertCount(1, $files);
        $migration = file_get_contents($files[0]);
        $this->assertStringContainsString("dropUnique('approval_template_steps_unique')", $migration);
        $this->assertStringContainsString("dropUnique('approval_process_steps_unique')", $migration);
        $this->assertStringNotContainsString("dropUnique('approval_template_users_unique')", $migration);
    }

    /** @test */
    public function parallel_stage_progress_and_history_treat_skipped_peers_correctly(): void
    {
        $process = file_get_contents(dirname(__DIR__, 2).'/app/Models/ApprovalProcess.php');
        $show = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approvals/show.blade.php');
        $action = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approvals/action.blade.php');

        $this->assertStringContainsString("distinct('step_ordering')", $process);
        $this->assertStringContainsString('isSkipped()', $show);
        $this->assertStringContainsString('Skipped', $show);
        $this->assertStringContainsString('isSkipped()', $action);
    }
}
