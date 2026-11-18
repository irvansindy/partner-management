<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\FormLinkController;
use App\Models\{ApprovalProcess, ApprovalProcessStep, ApprovalTemplate, ApprovalTemplateDetail, CompanyInformation, FormLink, User};
use App\Services\{ApprovalService, CompanyAttachmentService, FormLinkService, SystemMaintenanceService};
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{DB, Schema, Storage};
use Tests\TestCase;

class WorkflowPersistenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'queue.failed.database' => 'sqlite']);
        DB::purge('sqlite');
        \App\Helpers\UserActivityLogger::reset();
        $tables = [
            'master_activity_logs' => ['user_id','request_id','actor_type','action'],
            'activity_logs' => ['master_activity_log_id','user_id','action','description','model_type','model_id','data','ip_address'],
            'failed_jobs' => ['uuid','connection','queue','payload','exception','failed_at'],
            'users' => ['name','email','password','office_id','department_id','division_id'],
            'master_divisions' => ['name'], 'master_departments' => ['name','division_id'],
            'form_links' => ['token','form_type','title','created_by','office_id','department_id','is_active','submission_count'],
            'company_informations' => ['user_id','form_link_id','name','type','status','location_id','department_id','group_name','established_year','total_employee','business_classification','business_classification_detail','npwp','website_address','system_management','email_address','credit_limit','term_of_payment'],
            'approval_templates' => ['name','user_id','location_id','department_id','step_ordering','status'],
            'approval_template_details' => ['approval_id','user_id','step_ordering','status'],
            'approval_processes' => ['company_information_id','user_id','location_id','department_id','step_ordering','status'],
            'approval_process_steps' => ['approval_id','user_id','step_ordering','status','notes','approved_at','rejected_at'],
            'company_contacts' => ['company_informations_id','company_id','name','department','position','email','telephone'],
            'company_addresses' => ['company_id','address','country','province','city','zip_code','telephone','fax','latitude','longitude'],
            'company_banks' => ['company_id','name','account_name','account_number'],
            'company_liable_people' => ['company_id','name','position','nik'],
            'sales_surveys' => ['company_id'], 'product_customers' => ['company_id'],
            'company_attachments' => ['company_id','filename','original_name','filepath','storage_disk','filesize','filetype','mime_type','width','height','sha256','sort_order'],
        ];
        foreach ($tables as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($name, $columns) {
                $table->id();
                foreach ($columns as $column) {
                    if (str_ends_with($column, '_id') || in_array($column, ['step_ordering','submission_count','is_active']) || ($column === 'status' && str_starts_with($name, 'approval_'))) $table->integer($column)->nullable();
                    else $table->string($column)->nullable();
                }
                $table->timestamps();
            });
        }
        DB::table('master_divisions')->insert(['id' => 1, 'name' => 'Sales']);
        DB::table('master_departments')->insert(['id' => 1, 'name' => 'Retail', 'division_id' => 1]);
        Storage::fake('company_attachments');
        config(['company_attachments.disk' => 'company_attachments']);
    }

    protected function tearDown(): void
    {
        \App\Helpers\UserActivityLogger::reset();
        parent::tearDown();
    }

    private function submission(): array
    {
        $users = collect(range(1, 3))->map(fn ($id) => User::create([
            'name' => 'QA '.$id, 'email' => "qa{$id}@example.test", 'password' => 'unused',
            'office_id' => 1, 'department_id' => 1, 'division_id' => 1,
        ]));
        $link = FormLink::create(['token' => 'qa-'.uniqid(), 'form_type' => 'vendor', 'title' => 'QA', 'created_by' => $users[0]->id, 'office_id' => 1, 'department_id' => 1, 'is_active' => 1, 'submission_count' => 0]);
        $template = ApprovalTemplate::create(['name' => 'QA', 'user_id' => $users[0]->id, 'location_id' => 1, 'department_id' => 1, 'step_ordering' => 1, 'status' => 1]);
        foreach ($users as $i => $user) ApprovalTemplateDetail::create(['approval_id' => $template->id, 'user_id' => $user->id, 'step_ordering' => $i + 1, 'status' => 0]);
        $company = CompanyInformation::create(['user_id' => $users[0]->id, 'form_link_id' => $link->id, 'name' => 'QA Vendor', 'type' => 'vendor', 'status' => 'checking']);
        $process = $company->approvalProcess;
        $this->assertNotNull($process, 'Company observer must create the real approval process.');
        return [$users, $link, $company, $process];
    }

    public function test_observer_and_three_stage_approval_persist_progress_and_final_status(): void
    {
        [$users, $link, $company, $process] = $this->submission();
        $service = new ApprovalService();
        $this->actingAs($users[0]);
        $this->assertSame(0, $process->getCompletedStepsCount());
        $this->assertSame(3, $process->getTotalStepsCount());
        foreach ($users as $i => $user) {
            $step = $process->steps()->where('step_ordering', $i + 1)->firstOrFail();
            $this->assertTrue($step->isWaiting());
            $service->processApprovalAction($step, 'approve', $user->id, 'QA approval');
            $this->assertSame($i + 1, $process->fresh()->getCompletedStepsCount());
            if ($i === 1) {
                $this->assertFalse((new FormLinkService($service))->canEditSubmission($link, $company->fresh()));
            }
        }
        $this->assertTrue($process->fresh()->isApproved());
        $this->assertSame('approved', $company->fresh()->status);
        $this->assertSame(1, ApprovalProcess::count());
        $this->assertSame(1, $company->fresh()->location_id);
        $this->assertSame(1, $company->fresh()->department_id);
    }

    public function test_wrong_actor_rolls_back_and_repeated_approval_cannot_advance_twice(): void
    {
        [$users, $link, $company, $process] = $this->submission();
        $service = new ApprovalService();
        $step = $process->steps()->firstOrFail();
        try { $service->processApprovalAction($step, 'approve', $users[1]->id); $this->fail('Wrong actor accepted'); }
        catch (\RuntimeException $e) { $this->assertSame(__('approval.You are not the approver for this step.'), $e->getMessage()); }
        $this->assertTrue($step->fresh()->isWaiting());
        $this->assertSame(0, $process->fresh()->getCompletedStepsCount());
        $service->processApprovalAction($step, 'approve', $users[0]->id);
        try { $service->processApprovalAction($step, 'approve', $users[0]->id); $this->fail('Replay accepted'); }
        catch (\RuntimeException $e) { $this->assertSame(__('approval.This approval step has already been processed.'), $e->getMessage()); }
        $this->assertSame(1, $process->fresh()->getCompletedStepsCount());
        $this->assertSame(2, $process->fresh()->step_ordering);
    }

    public function test_rejection_persists_and_blocks_submission_editing(): void
    {
        [$users, $link, $company, $process] = $this->submission();
        $this->actingAs($users[0]);
        $forms = new FormLinkService(new ApprovalService());
        $this->assertTrue($forms->canEditSubmission($link, $company));
        (new ApprovalService())->processApprovalAction($process->steps()->first(), 'reject', $users[0]->id, 'QA rejected');
        $this->assertTrue($process->fresh()->isRejected());
        $this->assertSame('reject', $company->fresh()->status);
        $this->assertFalse($forms->canEditSubmission($link, $company->fresh()));
    }

    public function test_final_approval_cannot_be_changed_by_direct_posts_or_stale_waiting_steps(): void
    {
        [$users, $link, $company, $process] = $this->submission();
        $this->actingAs($users[0]);
        $step = $process->steps()->first();
        $service = new ApprovalService();
        $controller = new class($service) extends \App\Http\Controllers\Admin\ApprovalProcessController {
            protected function authorizeAccess(ApprovalProcess $approval): void {}
        };
        $service->processApprovalAction($step, 'reject', $users[0]->id, 'Final rejection');
        $snapshot = DB::table('approval_process_steps')->orderBy('id')->get()->toJson();
        foreach (['approve', 'reject'] as $action) {
            try {
                $controller->processAction(Request::create('/', 'POST', ['action' => $action, 'notes' => 'Replay']), $step->fresh());
                $this->fail('Direct replay accepted');
            } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
                $this->assertSame(409, $e->getStatusCode());
            }
            try {
                $service->processApprovalAction($step, $action, $users[0]->id, 'Replay');
                $this->fail('Service replay accepted');
            } catch (\RuntimeException $e) {
                $this->assertSame(__('approval.This approval step has already been processed.'), $e->getMessage());
            }
        }
        $response = $controller->showApprovalForm($step->fresh());
        $this->assertSame(route('admin.approvals.show', $process->id), $response->getTargetUrl());
        $this->assertSame($snapshot, DB::table('approval_process_steps')->orderBy('id')->get()->toJson());
        $this->assertTrue($process->fresh()->isRejected());
        $this->assertSame('reject', $company->fresh()->status);

        // A stale waiting row must not bypass a final company status either.
        $step->update(['status' => ApprovalProcessStep::STATUS_WAITING]);
        $process->update(['status' => ApprovalProcess::STATUS_IN_PROGRESS]);
        try {
            $service->processApprovalAction($step, 'approve', $users[0]->id);
            $this->fail('Final company status bypassed');
        } catch (\RuntimeException $e) {
            $this->assertSame('reject', $company->fresh()->status);
        }
    }

    public function test_final_approved_process_cannot_be_rejected_or_approved_again(): void
    {
        [$users, $link, $company, $process] = $this->submission();
        $service = new ApprovalService();
        foreach ($process->steps()->orderBy('step_ordering')->get() as $step) {
            $service->processApprovalAction($step, 'approve', $step->user_id, 'Approved');
        }
        foreach (['approve', 'reject'] as $action) {
            try {
                $service->processApprovalAction($step, $action, $step->user_id, 'Replay');
                $this->fail('Final approval was changed');
            } catch (\RuntimeException $e) {
                $this->assertSame(__('approval.This approval step has already been processed.'), $e->getMessage());
            }
        }
        $this->assertTrue($process->fresh()->isApproved());
        $this->assertSame('approved', $company->fresh()->status);
        $this->assertSame('Approved', $step->fresh()->notes);
    }

    public function test_edit_submission_updates_database_and_replaces_stored_attachment(): void
    {
        [$users, $link, $company, $process] = $this->submission();
        $this->actingAs($users[0]);
        $service = new CompanyAttachmentService();
        $oldFile = UploadedFile::fake()->createWithContent('old.pdf', "%PDF-1.4\nQA old");
        $old = $service->store($company, $oldFile, $service->validate($oldFile), 1);
        $request = Request::create('/admin/form-links/'.$link->id.'/submissions/'.$company->id, 'POST', [
            'name' => 'QA Updated Vendor', 'group_name' => 'QA Group', 'register_number_as_in_tax_invoice' => 'QA-NPWP', 'business_classification' => 'Manufacturer',
            'delete_attachment_ids' => [$old->id],
        ], [], ['new_attachments' => [UploadedFile::fake()->createWithContent('new.pdf', "%PDF-1.4\nQA replacement")]]);
        $request->setUserResolver(fn () => $users[0]);
        $request->setLaravelSession(app('session.store'));
        try {
            $response = (new FormLinkController(new FormLinkService(new ApprovalService()), $service))->submissionUpdate($request, $link, $company->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->fail(json_encode($e->errors()));
        }
        $this->assertSame(302, $response->status());
        $this->assertSame(__('form_links.Data submission berhasil diupdate!'), session('success'), session('error') ?? '');
        $this->assertSame('QA Updated Vendor', $company->fresh()->name);
        $this->assertSame('QA-NPWP', $company->fresh()->npwp);
        $attachments = $company->attachment()->get();
        $this->assertCount(1, $attachments);
        $this->assertSame('new.pdf', $attachments[0]->original_name);
        Storage::disk('company_attachments')->assertMissing($old->filepath);
        Storage::disk('company_attachments')->assertExists($attachments[0]->filepath);
    }

    public function test_maintenance_executes_real_read_only_artisan_command_with_local_labels(): void
    {
        config(['cache.default' => 'array']);
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $result = (new SystemMaintenanceService())->run('queue-failed', 1);
            $this->assertTrue($result['success'], $result['output']);
            $this->assertSame(0, $result['exit_code']);
            $this->assertSame(__('maintenance.Failed queue jobs'), $result['label']);
        }
    }

    public function test_password_notification_is_delivered_to_local_transport_in_both_languages(): void
    {
        config(['mail.default' => 'array', 'mail.from.address' => 'qa-sender@example.test']);
        $callback = \Illuminate\Auth\Notifications\ResetPassword::$createUrlCallback;
        \Illuminate\Auth\Notifications\ResetPassword::createUrlUsing(fn () => 'http://localhost/qa-reset');
        try {
            $user = (new User())->forceFill(['email' => 'qa-recipient@example.test', 'name' => 'QA']);
            foreach (['id', 'en'] as $locale) {
                app()->setLocale($locale);
                $user->notify((new \Illuminate\Auth\Notifications\ResetPassword('qa-token'))->locale($locale));
                $message = app('mailer')->getSwiftMailer()->getTransport()->messages()->last();
                $this->assertSame(__('Reset Password Notification'), $message->getSubject());
                $this->assertStringContainsString(__('If you did not request a password reset, no further action is required.'), $message->getBody());
                $this->assertArrayHasKey('qa-recipient@example.test', $message->getTo());
            }
        } finally {
            \Illuminate\Auth\Notifications\ResetPassword::$createUrlCallback = $callback;
        }
    }
}
