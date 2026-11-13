<?php

namespace Tests\Feature;

use App\Models\ApprovalProcess;
use App\Models\ApprovalProcessStep;
use App\Models\CompanyInformation;
use App\Models\User;
use App\Services\ApprovalService;
use App\Services\MenuService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class ApprovalLocalizationTest extends TestCase
{
    public function test_dictionaries_have_matching_keys_and_placeholders(): void
    {
        foreach (['approval', 'approval_validation'] as $group) {
            $id = Arr::dot(require resource_path("lang/id/{$group}.php"));
            $en = Arr::dot(require resource_path("lang/en/{$group}.php"));
            $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en));
            foreach ($id as $key => $text) {
                preg_match_all('/:[a-z_]+/', $text, $idTokens);
                preg_match_all('/:[a-z_]+/', $en[$key], $enTokens);
                $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], "{$group}.{$key}");
            }
        }
    }

    public function test_pages_modals_and_dialogs_render_in_both_languages(): void
    {
        $this->app->instance(MenuService::class, Mockery::mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        }));
        $user = (new User())->forceFill(['id' => 17, 'name' => 'Reviewer', 'email' => 'reviewer@example.test']);
        $this->actingAs($user);
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
        $company = (new CompanyInformation())->forceFill([
            'id' => 14, 'form_link_id' => 2, 'type' => 'customer', 'name' => 'Example Partner',
            'created_at' => '2026-05-04 16:00:00',
        ])->setRelation('formLink', null);
        $process = Mockery::mock(ApprovalProcess::class)->makePartial();
        $process->forceFill(['id' => 9, 'status' => ApprovalProcess::STATUS_IN_PROGRESS, 'created_at' => '2026-05-04', 'updated_at' => '2026-05-05']);
        $process->setRelation('company', $company);
        foreach (['initiator', 'department', 'office'] as $relation) {
            $process->setRelation($relation, null);
        }
        $step = (new ApprovalProcessStep())->forceFill([
            'id' => 22, 'approval_id' => 9, 'user_id' => 17, 'step_ordering' => 2,
            'status' => ApprovalProcessStep::STATUS_WAITING, 'created_at' => '2026-05-04',
            'notes' => '<script>alert(1)</script>',
        ])->setRelation('process', $process)->setRelation('approver', $user);
        $process->setRelation('steps', collect([$step]));
        $process->shouldReceive('getCurrentStep')->andReturn($step);
        $process->shouldReceive('getProgressPercentage')->andReturn(33.0);
        $process->shouldReceive('getCompletedStepsCount')->andReturn(1);
        $process->shouldReceive('getTotalStepsCount')->andReturn(3);

        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $views = [
                'admin.approvals.index' => ['approvals' => new LengthAwarePaginator([$process], 1, 20), 'approvalStats' => ['in_progress' => 1, 'approved' => 0, 'rejected' => 0, 'total' => 1]],
                'admin.approvals.my_approvals' => ['pendingSteps' => collect([$step])],
                'admin.approvals.show' => ['approval' => $process, 'currentStep' => $step, 'progress' => 33],
                'admin.approvals.action' => ['step' => $step, 'requiresFinancialTerms' => true],
                'admin.approval_setting.index' => [],
            ];
            foreach ($views as $view => $data) {
                $html = view($view, $data)->render();
                $this->assertStringNotContainsString('approval.', $html, $view);
                $this->assertStringNotContainsString('approval_validation.', $html, $view);
                if ($view === 'admin.approvals.action') {
                    $this->assertStringContainsString(__('approval.stage_review', ['number' => 2, 'total' => 3]), $html);
                    $this->assertStringContainsString(__('approval.Contoh: 30 hari'), $html);
                }
                if ($view === 'admin.approvals.show') {
                    $this->assertStringContainsString($locale === 'id' ? '04 Mei 2026' : '04 May 2026', $html);
                    $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
                    $this->assertStringContainsString(__('approval.Waiting'), $html);
                }
                if ($view === 'admin.approval_setting.index') {
                    $this->assertStringContainsString(__('approval.Create Approval Template'), $html);
                    $this->assertStringContainsString(__('approval.Atur Tahap dan PIC'), $html);
                }
            }
        }
    }

    public function test_validation_and_service_guards_use_the_active_locale(): void
    {
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $validator = Validator::make(
                ['credit_limit' => 2147483648, 'approval_stages' => [['user_ids' => 'invalid']]],
                ['credit_limit' => 'integer|max:2147483647', 'approval_stages.*.user_ids' => 'array'],
                __('approval_validation.messages'), __('approval_validation.attributes')
            );
            $this->assertTrue($validator->fails());
            $this->assertSame(strtr(__('approval_validation.messages')['max']['numeric'], [':attribute' => __('approval_validation.attributes')['credit_limit'], ':max' => '2147483647']), $validator->errors()->first('credit_limit'));
            $this->assertSame(str_replace(':attribute', __('approval_validation.attributes')['approval_stages.*.user_ids'], __('approval_validation.messages.array')), $validator->errors()->first('approval_stages.0.user_ids'));
            try {
                (new ApprovalService())->processApprovalAction(new ApprovalProcessStep(), 'reject', 17);
                $this->fail('Rejection without notes must fail.');
            } catch (\InvalidArgumentException $e) {
                $this->assertSame(__('approval.Rejection notes are required.'), $e->getMessage());
            }
        }
    }

    public function test_setting_controller_returns_localized_validation_errors(): void
    {
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $controller = new \App\Http\Controllers\ApprovalSettingController();
            $response = $controller->storeApprovalTemplate(new \Illuminate\Http\Request());
            $this->assertSame(422, $response->getStatusCode());
            $this->assertSame(__('approval.Nama template approval wajib diisi'), $response->getData(true)['meta']['message']['errors']['approval_master_name'][0]);
            $response = $controller->submitApprovalDetail(new \Illuminate\Http\Request());
            $this->assertSame(422, $response->getStatusCode());
            $this->assertSame(__('approval.Minimal satu tahap approval harus dibuat'), $response->getData(true)['meta']['message']['errors']['approval_stages'][0]);
        }
    }
}
