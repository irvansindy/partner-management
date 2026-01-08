<?php

namespace App\Observers;

use App\Models\CompanyInformation;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\Log;

class CompanyInformationObserver
{
    /**
     * Handle the CompanyInformation "created" event.
     *
     * ✅ Automatically create approval process when company is submitted via form link
     *
     * NOTE: We don't inject ApprovalService via constructor to avoid binding issues
     * Instead, we resolve it manually using app() helper
     */
    public function created(CompanyInformation $company): void
    {
        // Only create approval if submitted via form link
        if (!empty($company->form_link_id)) {
            try {
                Log::info("🔔 Observer triggered for company ID: {$company->id}, form_link_id: {$company->form_link_id}");

                // ✅ Resolve service manually to avoid constructor binding issues
                $approvalService = app(ApprovalService::class);

                $approvalProcess = $approvalService->createApprovalFromFormLink($company);

                if ($approvalProcess) {
                    Log::info("✅ Observer: Approval process created successfully for company ID: {$company->id}, Approval ID: {$approvalProcess->id}");
                } else {
                    Log::warning("⚠️ Observer: No approval template found for company ID: {$company->id}");
                }

            } catch (\Exception $e) {
                // Log error but don't fail the company creation
                Log::error("❌ Observer: Failed to auto-create approval for company ID: {$company->id}. Error: {$e->getMessage()}");
                Log::error("Stack trace: " . $e->getTraceAsString());
            }
        } else {
            Log::info("ℹ️ Observer: Company ID: {$company->id} has no form_link_id, skipping approval creation");
        }
    }
}