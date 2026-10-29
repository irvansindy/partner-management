<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ApprovalSettingModalUiTest extends TestCase
{
    /** @test */
    public function approval_setting_modals_have_labeled_responsive_and_accessible_states(): void
    {
        $create = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approval_setting/create_approval_master.blade.php');
        $detail = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approval_setting/add_view_approval_detail_modal.blade.php');
        $index = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approval_setting/index.blade.php');

        $this->assertStringContainsString('modal-dialog-centered modal-lg', $create);
        $this->assertStringContainsString('for="approval_master_name"', $create);
        $this->assertStringContainsString('required maxlength="255"', $create);
        $this->assertStringContainsString('approval-modal-context', $detail);
        $this->assertStringContainsString('data-approver-list', $detail);
        $this->assertStringContainsString('data-approval-loading', $detail);
        $this->assertStringContainsString('aria-live="polite"', $detail);
        $this->assertStringContainsString('.approval-modal .modal-content', $index);
        $this->assertStringContainsString('.approver-step-card', $index);
        $this->assertStringContainsString('.approval-modal .select2-selection--single', $index);
        $this->assertStringContainsString('.approval-select2-dropdown', $index);
        $this->assertStringContainsString("assets/plugins/select2/css/select2.min.css", $index);
        $this->assertStringNotContainsString('select2-material.css', $index);
        $this->assertStringContainsString('width: 100% !important;', $index);
        $this->assertStringContainsString('box-shadow: none;', $index);
        $this->assertStringContainsString('padding: 9px 12px;', $index);
        $this->assertStringContainsString('padding: 10px 14px;', $index);
        $this->assertStringContainsString('min-width: 36px !important;', $index);
        $this->assertStringContainsString('width: 36px !important;', $index);
        $this->assertStringContainsString('.select2-results__option--highlighted', $index);
    }

    /** @test */
    public function modal_script_escapes_data_and_handles_loading_and_validation_errors(): void
    {
        $script = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/approval_setting/approval_js.blade.php');
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/ApprovalSettingController.php');

        $this->assertStringContainsString('function escapeHtml', $script);
        $this->assertStringContainsString('function renderApproverRow', $script);
        $this->assertStringContainsString('showValidationErrors', $script);
        $this->assertStringContainsString(".prop('disabled', true)", $script);
        $this->assertStringContainsString('error: function', $script);
        $this->assertStringContainsString("dropdownCssClass: 'approval-select2-dropdown'", $script);
        $this->assertStringContainsString("select(['id', 'name', 'email'])", $controller);
    }
}
