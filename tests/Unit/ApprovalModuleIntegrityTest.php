<?php

namespace Tests\Unit;

use App\Models\Approval;
use App\Models\ApprovalProcess;
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
}
