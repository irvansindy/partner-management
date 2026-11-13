<?php

namespace App\Services;

use App\Models\ApprovalProcess;
use App\Models\ApprovalProcessStep;
use App\Models\ApprovalTemplate;
use App\Models\CompanyInformation;
use App\Models\FormLink;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovalService
{
    /**
     * Create approval process for company information from form link
     *
     * @param CompanyInformation $company
     * @return ApprovalProcess|null
     * @throws \Exception
     */
    public function createApprovalFromFormLink(CompanyInformation $company): ?ApprovalProcess
    {
        try {
            return DB::transaction(function () use ($company) {
                $company = CompanyInformation::whereKey($company->id)->lockForUpdate()->firstOrFail();
                $existingProcess = ApprovalProcess::where('company_information_id', $company->id)->first();
                if ($existingProcess) {
                    return $existingProcess->load('steps');
                }

                $formLink = FormLink::find($company->form_link_id);

                if (!$formLink) {
                    Log::warning("Form link not found for company ID: {$company->id}");
                    return null;
                }

                // Find matching approval template
                $template = ApprovalTemplate::where('location_id', $formLink->office_id)
                    ->where('department_id', $formLink->department_id)
                    ->where('status', 1) // Active template
                    ->first();

                if (!$template) {
                    Log::warning("No active approval template found for Office ID: {$formLink->office_id}, Department ID: {$formLink->department_id}");
                    return null;
                }

                // Get template details (approvers)
                $templateDetails = $template->details()
                    ->where('status', 0) // Active approvers
                    ->orderBy('step_ordering')
                    ->get();

                if ($templateDetails->isEmpty()) {
                    Log::warning("No approvers found in template ID: {$template->id}");
                    return null;
                }

                $firstStepOrdering = $templateDetails->min('step_ordering');

                // Create approval process
                $approvalProcess = ApprovalProcess::create([
                    'company_information_id' => $company->id,
                    'user_id' => $company->user_id, // Initiator (form submitter)
                    'location_id' => $formLink->office_id,
                    'department_id' => $formLink->department_id,
                    'step_ordering' => $firstStepOrdering,
                    'status' => ApprovalProcess::STATUS_PENDING,
                ]);

                // Create approval process steps
                foreach ($templateDetails as $detail) {
                    ApprovalProcessStep::create([
                        'approval_id' => $approvalProcess->id,
                        'user_id' => $detail->user_id,
                        'step_ordering' => $detail->step_ordering,
                        'status' => $detail->step_ordering === $firstStepOrdering
                            ? ApprovalProcessStep::STATUS_WAITING
                            : ApprovalProcessStep::STATUS_PENDING,
                        'notes' => null,
                        'approved_at' => null,
                        'rejected_at' => null,
                    ]);
                }

                // Update approval process status to IN_PROGRESS
                $approvalProcess->update([
                    'status' => ApprovalProcess::STATUS_IN_PROGRESS
                ]);

                Log::info("Approval process created successfully for company ID: {$company->id}, Process ID: {$approvalProcess->id}");

                return $approvalProcess->fresh(['steps']);
            });
        } catch (\Exception $e) {
            Log::error("Failed to create approval process for company ID: {$company->id}. Error: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Process approval action (approve/reject)
     *
     * @param ApprovalProcessStep $step
     * @param string $action ('approve' or 'reject')
     * @param int $actorId
     * @param string|null $notes
     * @return bool
     * @throws \Exception
     */
    public function processApprovalAction(
        ApprovalProcessStep $step,
        string $action,
        int $actorId,
        ?string $notes = null,
        ?string $termOfPayment = null,
        $creditLimit = null
    ): bool
    {
        if (!in_array($action, ['approve', 'reject'], true)) {
            throw new \InvalidArgumentException(__('approval.Aksi tidak valid.'));
        }
        if ($action === 'reject' && trim((string) $notes) === '') {
            throw new \InvalidArgumentException(__('approval.Rejection notes are required.'));
        }

        try {
            return DB::transaction(function () use ($step, $action, $actorId, $notes, $termOfPayment, $creditLimit) {
                $process = ApprovalProcess::whereKey($step->approval_id)->lockForUpdate()->firstOrFail();
                $lockedStep = ApprovalProcessStep::whereKey($step->id)->lockForUpdate()->firstOrFail();
                $company = CompanyInformation::whereKey($process->company_information_id)->lockForUpdate()->firstOrFail();

                if ($lockedStep->user_id !== $actorId) {
                    throw new \RuntimeException(__('approval.You are not the approver for this step.'));
                }
                if (!$lockedStep->isWaiting()
                    || !$process->isInProgress()
                    || $process->step_ordering !== $lockedStep->step_ordering) {
                    throw new \RuntimeException(__('approval.This approval step has already been processed.'));
                }

                $stageSteps = ApprovalProcessStep::where('approval_id', $process->id)
                    ->where('step_ordering', $lockedStep->step_ordering)
                    ->lockForUpdate()
                    ->get();

                if ($action === 'reject') {
                    $lockedStep->reject($notes);
                    ApprovalProcessStep::whereIn('id', $stageSteps->where('id', '!=', $lockedStep->id)->pluck('id'))
                        ->where('status', ApprovalProcessStep::STATUS_WAITING)
                        ->update(['status' => ApprovalProcessStep::STATUS_SKIPPED]);
                    $process->update(['status' => ApprovalProcess::STATUS_REJECTED]);
                    $company->update(['status' => 'reject']);
                } else {
                    $actor = User::with(['division', 'dept'])->whereKey($actorId)->lockForUpdate()->firstOrFail();
                    if (!$actor->office_id || !$actor->department_id) {
                        throw new \RuntimeException(__('approval.Approver office and department must be configured.'));
                    }

                    $companyUpdates = [
                        'location_id' => $actor->office_id,
                        'department_id' => $actor->department_id,
                    ];

                    if ($company->type === 'customer' && $actor->isFatFinanceApprover()) {
                        [$normalizedTermOfPayment, $normalizedCreditLimit] = $this->validateFinancialTerms(
                            $termOfPayment,
                            $creditLimit
                        );
                        $companyUpdates['term_of_payment'] = $normalizedTermOfPayment;
                        $companyUpdates['credit_limit'] = $normalizedCreditLimit;
                    }

                    $company->update($companyUpdates);
                    $lockedStep->approve($notes);
                    ApprovalProcessStep::whereIn('id', $stageSteps->where('id', '!=', $lockedStep->id)->pluck('id'))
                        ->where('status', ApprovalProcessStep::STATUS_WAITING)
                        ->update(['status' => ApprovalProcessStep::STATUS_SKIPPED]);

                    $nextStepOrdering = ApprovalProcessStep::where('approval_id', $process->id)
                        ->where('step_ordering', '>', $lockedStep->step_ordering)
                        ->where('status', ApprovalProcessStep::STATUS_PENDING)
                        ->orderBy('step_ordering')
                        ->lockForUpdate()
                        ->value('step_ordering');

                    if ($nextStepOrdering !== null) {
                        ApprovalProcessStep::where('approval_id', $process->id)
                            ->where('step_ordering', $nextStepOrdering)
                            ->where('status', ApprovalProcessStep::STATUS_PENDING)
                            ->update(['status' => ApprovalProcessStep::STATUS_WAITING]);
                        $process->update(['step_ordering' => $nextStepOrdering]);
                    } else {
                        $process->update(['status' => ApprovalProcess::STATUS_APPROVED]);
                        $company->update(['status' => 'approved']);
                    }
                }

                Log::info('Approval action processed', [
                    'step_id' => $lockedStep->id,
                    'approval_process_id' => $process->id,
                    'action' => $action,
                    'user_id' => $actorId,
                ]);

                return true;
            }, 3);
        } catch (\Exception $e) {
            Log::error("Failed to process approval action for step ID: {$step->id}. Error: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Check if company has approval process
     *
     * @param int $companyId
     * @return bool
     */
    public function hasApprovalProcess(int $companyId): bool
    {
        return ApprovalProcess::where('company_information_id', $companyId)->exists();
    }

    /**
     * Get approval process for company
     *
     * @param int $companyId
     * @return ApprovalProcess|null
     */
    public function getApprovalProcess(int $companyId): ?ApprovalProcess
    {
        return ApprovalProcess::where('company_information_id', $companyId)
            ->with(['steps.approver', 'initiator', 'department', 'office'])
            ->first();
    }

    /**
     * Get pending approvals for user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingApprovalsForUser(int $userId)
    {
        return ApprovalProcessStep::where('user_id', $userId)
            ->where('status', ApprovalProcessStep::STATUS_WAITING)
            ->with(['process.company', 'process.initiator'])
            ->get();
    }

    private function validateFinancialTerms(?string $termOfPayment, $creditLimit): array
    {
        $normalizedTermOfPayment = trim((string) $termOfPayment);
        $normalizedCreditLimit = filter_var($creditLimit, FILTER_VALIDATE_INT);

        if ($normalizedTermOfPayment === ''
            || mb_strlen($normalizedTermOfPayment) > 255
            || $normalizedCreditLimit === false
            || $normalizedCreditLimit < 0
            || $normalizedCreditLimit > 2147483647) {
            throw new \InvalidArgumentException(__('approval.Financial terms are required for FAT Finance approval.'));
        }

        return [$normalizedTermOfPayment, $normalizedCreditLimit];
    }
}
