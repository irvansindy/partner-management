<?php

namespace App\Services;

use App\Models\ApprovalProcess;
use App\Models\ApprovalProcessStep;
use App\Models\ApprovalTemplate;
use App\Models\CompanyInformation;
use App\Models\FormLink;
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
            DB::beginTransaction();

            // Get form link
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

            // Create approval process
            $approvalProcess = ApprovalProcess::create([
                'company_information_id' => $company->id,
                'user_id' => $company->user_id, // Initiator (form submitter)
                'location_id' => $formLink->office_id,
                'department_id' => $formLink->department_id,
                'step_ordering' => 1, // Start at step 1
                'status' => ApprovalProcess::STATUS_PENDING,
            ]);

            // Create approval process steps
            foreach ($templateDetails as $detail) {
                ApprovalProcessStep::create([
                    'approval_id' => $approvalProcess->id,
                    'user_id' => $detail->user_id,
                    'step_ordering' => $detail->step_ordering,
                    'status' => $detail->step_ordering === 1
                        ? ApprovalProcessStep::STATUS_WAITING // First step is waiting
                        : ApprovalProcessStep::STATUS_PENDING, // Others are pending
                    'notes' => null,
                    'approved_at' => null,
                    'rejected_at' => null,
                ]);
            }

            // Update approval process status to IN_PROGRESS
            $approvalProcess->update([
                'status' => ApprovalProcess::STATUS_IN_PROGRESS
            ]);

            DB::commit();

            Log::info("Approval process created successfully for company ID: {$company->id}, Process ID: {$approvalProcess->id}");

            return $approvalProcess->fresh(['steps']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create approval process for company ID: {$company->id}. Error: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Process approval action (approve/reject)
     *
     * @param ApprovalProcessStep $step
     * @param string $action ('approve' or 'reject')
     * @param string|null $notes
     * @return bool
     * @throws \Exception
     */
    public function processApprovalAction(ApprovalProcessStep $step, string $action, ?string $notes = null): bool
    {
        try {
            \Log::info('Processing approval action', [
                'step_id' => $step->id,
                'approval_process_id' => $step->approval_id,
                'action' => $action,
                'user_id' => auth()->id()
            ]);
            DB::beginTransaction();

            if (!in_array($action, ['approve', 'reject'])) {
                throw new \InvalidArgumentException("Invalid action: {$action}");
            }

            // Check if step is in waiting status
            if (!$step->isWaiting()) {
                throw new \Exception("This step is not waiting for approval");
            }

            // Perform action
            if ($action === 'approve') {
                $step->approve($notes);

                // Check if there's next step
                $nextStep = ApprovalProcessStep::where('approval_id', $step->approval_id)
                    ->where('step_ordering', $step->step_ordering + 1)
                    ->where('status', ApprovalProcessStep::STATUS_PENDING)
                    ->first();

                if ($nextStep) {
                    // Activate next step
                    $nextStep->update(['status' => ApprovalProcessStep::STATUS_WAITING]);

                    // Update process step_ordering
                    $step->process->update([
                        'step_ordering' => $nextStep->step_ordering
                    ]);
                } else {
                    // All steps approved - complete the process
                    $step->process->update([
                        'status' => ApprovalProcess::STATUS_APPROVED
                    ]);
                }
            } else {
                // Reject
                $step->reject($notes);

                // Reject the entire process
                $step->process->update([
                    'status' => ApprovalProcess::STATUS_REJECTED
                ]);
            }

            DB::commit();

            Log::info("Approval action '{$action}' processed successfully for step ID: {$step->id}");

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
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
}