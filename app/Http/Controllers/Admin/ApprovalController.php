<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalProcess;
use App\Models\CompanyInformation;
use App\Services\ApprovalProcessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    protected $approvalProcessService;

    public function __construct(ApprovalProcessService $approvalProcessService)
    {
        $this->approvalProcessService = $approvalProcessService;
    }

    /**
     * Display list of pending approvals for current user
     */
    public function index()
    {
        $pendingApprovals = $this->approvalProcessService->getPendingApprovalsForUser(Auth::id());

        return view('admin.approvals.index', compact('pendingApprovals'));
    }

    /**
     * Display approval detail
     */
    public function show(ApprovalProcess $approvalProcess)
    {
        $approvalProcess->load([
            'company.contact',
            'company.address',
            'company.bank',
            'company.liablePeople',
            'company.attachment',
            'steps.approver',
            'initiator',
            'department',
            'office'
        ]);

        return view('admin.approvals.show', compact('approvalProcess'));
    }

    /**
     * Approve current step
     */
    public function approve(Request $request, ApprovalProcess $approvalProcess)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $this->approvalProcessService->approveStep(
                $approvalProcess,
                Auth::id(),
                $request->notes
            );

            return redirect()
                ->back()
                ->with('success', 'Approval berhasil disetujui!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Reject current step
     */
    public function reject(Request $request, ApprovalProcess $approvalProcess)
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ], [
            'notes.required' => 'Alasan penolakan harus diisi'
        ]);

        try {
            $this->approvalProcessService->rejectStep(
                $approvalProcess,
                Auth::id(),
                $request->notes
            );

            return redirect()
                ->back()
                ->with('success', 'Approval berhasil ditolak!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Create approval for single company (manual trigger)
     */
    public function createForCompany(CompanyInformation $company)
    {
        try {
            if ($company->hasApproval()) {
                return redirect()
                    ->back()
                    ->with('error', 'Company sudah memiliki approval process!');
            }

            if (!$company->form_link_id) {
                return redirect()
                    ->back()
                    ->with('error', 'Company tidak memiliki form link!');
            }

            $approvalProcess = $this->approvalProcessService->createApprovalFromFormLink($company);

            if (!$approvalProcess) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak ada approval template yang sesuai untuk form link ini!');
            }

            return redirect()
                ->back()
                ->with('success', 'Approval process berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Bulk create approvals for companies without approval
     */
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'company_ids' => 'nullable|array',
            'company_ids.*' => 'exists:company_informations,id'
        ]);

        try {
            $stats = $this->approvalProcessService->bulkCreateApprovals(
                $request->company_ids ?? []
            );

            $message = "Total: {$stats['total']}, Berhasil: {$stats['success']}, Gagal: {$stats['failed']}, Dilewati: {$stats['skipped']}";

            if (!empty($stats['errors'])) {
                $message .= "\n\nErrors:\n" . implode("\n", array_slice($stats['errors'], 0, 5));
            }

            return redirect()
                ->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get approval history for company
     */
    public function history(CompanyInformation $company)
    {
        $approvalProcess = $this->approvalProcessService->getApprovalProcess($company);

        if (!$approvalProcess) {
            return redirect()
                ->back()
                ->with('error', 'Company belum memiliki approval process!');
        }

        return view('admin.approvals.history', compact('company', 'approvalProcess'));
    }

    /**
     * Dashboard - show approval statistics
     */
    public function dashboard()
    {
        $userId = Auth::id();

        $stats = [
            'pending_count' => $this->approvalProcessService
                ->getPendingApprovalsForUser($userId)
                ->count(),

            'all_pending' => ApprovalProcess::with(['company', 'steps.approver'])
                ->whereHas('steps', function($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('status', \App\Models\ApprovalProcessStep::STATUS_WAITING);
                })
                ->inProgress()
                ->get(),

            'recently_approved' => ApprovalProcess::with(['company', 'steps.approver'])
                ->whereHas('steps', function($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('status', \App\Models\ApprovalProcessStep::STATUS_APPROVED);
                })
                ->latest('updated_at')
                ->take(10)
                ->get(),

            'recently_rejected' => ApprovalProcess::with(['company', 'steps.approver'])
                ->whereHas('steps', function($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('status', \App\Models\ApprovalProcessStep::STATUS_REJECTED);
                })
                ->latest('updated_at')
                ->take(10)
                ->get(),
        ];

        return view('admin.approvals.dashboard', compact('stats'));
    }
}