<?php

namespace Tests\Feature;

use App\Models\CompanyInformation;
use App\Models\FormLink;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class MultilanguageAuditTest extends TestCase
{
    public function test_all_application_catalogues_have_matching_keys_and_placeholders(): void
    {
        foreach (glob(resource_path('lang/en/*.php')) as $path) {
            $group = basename($path, '.php');
            $idPath = resource_path("lang/id/{$group}.php");
            $this->assertFileExists($idPath, $group);
            $id = Arr::dot(require $idPath);
            $en = Arr::dot(require $path);
            $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en), $group);
            foreach ($id as $key => $text) {
                if (!is_string($text)) continue;
                preg_match_all('/:[a-zA-Z_]+|\{[a-zA-Z_]+\}|_[A-Z]+_/', $text, $idTokens);
                preg_match_all('/:[a-zA-Z_]+|\{[a-zA-Z_]+\}|_[A-Z]+_/', $en[$key], $enTokens);
                $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], "{$group}.{$key}");
            }
        }
        $id = json_decode(file_get_contents(resource_path('lang/id.json')), true, 512, JSON_THROW_ON_ERROR);
        $en = json_decode(file_get_contents(resource_path('lang/en.json')), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en));
        foreach ($id as $key => $text) {
            preg_match_all('/:[a-zA-Z_]+/', $text, $idTokens);
            preg_match_all('/:[a-zA-Z_]+/', $en[$key], $enTokens);
            $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], $key);
        }
    }

    public function test_framework_validation_and_pagination_use_the_selected_locale(): void
    {
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $errors = Validator::make(['amount' => 'wrong', 'start' => '2026-01-01'], [
                'email' => 'required', 'amount' => 'numeric', 'start' => 'after:2026-02-01',
            ])->errors();
            $this->assertSame(str_replace(':attribute', 'email', __('validation.required')), $errors->first('email'));
            $this->assertSame(str_replace(':attribute', 'amount', __('validation.numeric')), $errors->first('amount'));
            $this->assertStringContainsString('2026-02-01', $errors->first('start'));
            $this->assertStringContainsString($locale === 'id' ? 'Berikutnya' : 'Next', __('pagination.next'));
        }
    }

    public function test_primary_pages_and_inline_scripts_render_in_both_languages(): void
    {
        $connection = new class(null) extends \Illuminate\Database\Connection {
            public function select($query, $bindings = [], $useReadPdo = true) { return []; }
        };
        $original = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
        $resolver = new \Illuminate\Database\ConnectionResolver(['testing' => $connection]);
        $resolver->setDefaultConnection('testing');
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
        $this->beforeApplicationDestroyed(fn () => \Illuminate\Database\Eloquent\Model::setConnectionResolver($original));
        $this->app->instance(MenuService::class, Mockery::mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        }));
        $user = (new User())->forceFill(['id' => 1, 'name' => 'QA Reviewer', 'email' => 'qa@example.test']);
        $user->setRelation('roles', collect([(object) ['name' => 'super-admin', 'permissions' => collect()]]));
        $user->setRelation('companyInformation', collect());
        $this->actingAs($user);
        Gate::shouldReceive('check')->andReturn(true);
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
        $link = (new FormLink())->forceFill(['id' => 2, 'title' => 'QA Registration', 'token' => 'qa-token', 'form_type' => 'customer', 'is_active' => true, 'created_at' => '2026-11-01', 'submission_count' => 1]);
        $company = (new CompanyInformation())->forceFill(['id' => 17, 'form_link_id' => 2, 'name' => 'QA Company', 'type' => 'customer', 'created_at' => '2026-11-01']);
        foreach (['liablePeople', 'contact', 'bank', 'productCustomers', 'address', 'attachment'] as $relation) $company->setRelation($relation, collect());
        $company->setRelation('salesSurvey', null)->setRelation('approvalProcess', null);
        $link->setRelation('companies', collect([$company]))->setRelation('creator', $user);
        $process = Mockery::mock(\App\Models\ApprovalProcess::class)->makePartial();
        $process->forceFill(['id' => 9, 'status' => 'in_progress', 'created_at' => '2026-11-01', 'updated_at' => '2026-11-01']);
        $process->setRelation('company', $company);
        $company->setRelation('formLink', $link);
        foreach (['initiator', 'department', 'office'] as $relation) $process->setRelation($relation, null);
        $step = (new \App\Models\ApprovalProcessStep())->forceFill(['id' => 22, 'approval_id' => 9, 'user_id' => 1, 'step_ordering' => 1, 'status' => 'waiting', 'created_at' => '2026-11-01']);
        $step->setRelation('process', $process)->setRelation('approver', $user);
        $process->setRelation('steps', collect([$step]));
        $process->shouldReceive('getCurrentStep')->andReturn($step);
        $process->shouldReceive('getProgressPercentage')->andReturn(0.0);
        $process->shouldReceive('getCompletedStepsCount')->andReturn(0);
        $process->shouldReceive('getTotalStepsCount')->andReturn(3);
        $artifactDir = getenv('LOCALIZATION_ARTIFACT_DIR');
        if ($artifactDir) {
            $this->assertStringStartsWith(sys_get_temp_dir().'/', $artifactDir);
            if (!is_dir($artifactDir)) mkdir($artifactDir, 0700, true);
            \Illuminate\Support\Facades\URL::forceRootUrl('http://localhost:8085');
        }
        $manifest = [];
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $views = [
                'admin.approvals.index' => ['approvals' => new \Illuminate\Pagination\LengthAwarePaginator([$process], 1, 20), 'approvalStats' => ['in_progress' => 1, 'approved' => 0, 'rejected' => 0, 'total' => 1]],
                'admin.approvals.my_approvals' => ['pendingSteps' => collect([$step])],
                'admin.approvals.show' => ['approval' => $process, 'currentStep' => $step, 'progress' => 0],
                'admin.approvals.action' => ['step' => $step, 'requiresFinancialTerms' => true],
                'admin.approval_setting.index' => [],
                'admin.form_links.index' => ['formLinks' => collect([$link])],
                'admin.form_links.create' => ['pic' => collect([$user])],
                'admin.form_links.edit' => ['formLink' => $link],
                'admin.form_links.show' => ['formLink' => $link],
                'admin.form_links.submissions' => ['formLink' => $link, 'companies' => new \Illuminate\Pagination\LengthAwarePaginator([$company], 1, 20), 'submissionStats' => ['total' => 1, 'approved' => 0, 'in_progress' => 1, 'rejected' => 0]],
                'admin.form_links.submission_detail' => ['formLink' => $link, 'company' => $company, 'canManageFormLink' => true, 'canEditSubmission' => true],
                'admin.form_links.submission_edit' => ['formLink' => $link, 'company' => $company],
                'users.index' => [], 'admin.menus.index' => [], 'admin.role_and_permission.index' => [],
                'admin.office.index' => [], 'admin.department.index' => [],
                'admin.division.index' => ['generalManagers' => collect(), 'offices' => collect()],
                'admin.api_white_list.index' => [],
                'partner.index' => [], 'admin.dashboard.index_live' => ['filterOffices' => collect(), 'filterDepartments' => collect(), 'canFilterDashboard' => true],
                'admin.company.export_form' => [], 'admin.company.import_form' => [],
                'admin.company.import_preview' => ['rows' => collect(), 'errorsList' => [], 'filePath' => null],
                'admin.postal_codes.import' => [],
                'admin.system_maintenance.index' => ['commandGroups' => (new \App\Services\SystemMaintenanceService())->groupedCommands(), 'result' => null],
                'home' => [], 'company.detail_company' => [], 'cs_vendor.attachment' => [],
                'admin.eula.index' => [], 'admin.tenders.vendor.index' => [],
                'public.form_index' => ['formLink' => $link],
                'cs_vendor.index' => [],
                'public.form_success' => ['formLink' => $link],
                'public.form_closed' => ['formLink' => $link, 'message' => __('messages.Public Form Inactive')],
            ];
            foreach ($views as $name => $data) {
                $html = view($name, $data)->render();
                $this->assertStringContainsString('<html', $html, $name);
                if ($name === 'public.form_index') {
                    $this->assertStringContainsString(__('public_form.registration_type_hint', ['type' => __('messages.Dashboard Customer')]), $html);
                    $this->assertStringContainsString(__('public_form.upload_hint'), $html);
                }
                $this->assertDoesNotMatchRegularExpression('/\b(?:admin\.[A-Z]|(?:supporting|public_form|form_links_audit|form_links_remaining|approval_validation|settings_validation|data_tools_validation)\.[A-Za-z])/', $html, $name);
                preg_match_all('/<script\b[^>]*>(.*?)<\/script>/s', $html, $scripts);
                foreach ($scripts[1] as $index => $script) {
                    if (trim($script) === '') continue;
                    $path = tempnam(sys_get_temp_dir(), 'localization-js-');
                    try {
                        file_put_contents($path, $script);
                        exec('node --check '.escapeshellarg($path).' 2>&1', $output, $status);
                        $this->assertSame(0, $status, "{$locale}:{$name}:{$index}\n".implode("\n", $output));
                    } finally { unlink($path); }
                    $output = [];
                }
                if ($artifactDir) {
                    $file = $locale.'--'.str_replace('.', '--', $name).'.html';
                    file_put_contents($artifactDir.'/'.$file, $html);
                    $manifest[] = ['locale' => $locale, 'view' => $name, 'file' => $file];
                }
            }
        }
        if ($artifactDir) file_put_contents($artifactDir.'/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
    }
}
