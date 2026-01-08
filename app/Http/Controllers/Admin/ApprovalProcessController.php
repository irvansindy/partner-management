<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalProcess;
use App\Models\ApprovalProcessStep;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalProcessController extends Controller
{
    protected $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    /**
     * Display list of approval processes
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ApprovalProcess::with(['company', 'initiator', 'department', 'office', 'steps']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by user role
        $role = $user->roles[0]->name;

        if ($role === 'admin' || $role === 'super-user') {
            // Show only approvals for their department and office
            $query->where('location_id', $user->office->id)
                  ->where('department_id', $user->dept->id);
        }
        // super-admin can see all

        $approvals = $query->latest()->paginate(20);

        return view('admin.approvals.index', compact('approvals'));
    }

    /**
     * Display specific approval process detail
     */
    public function show(ApprovalProcess $approval)
    {
        $this->authorizeAccess($approval);

        $approval->load([
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

        $currentStep = $approval->getCurrentStep();
        $progress = $approval->getProgressPercentage();

        return view('admin.approvals.show', compact('approval', 'currentStep', 'progress'));
    }

    /**
     * Display my pending approvals (as approver)
     */
    public function myApprovals()
    {
        $user = Auth::user();
        $pendingSteps = $this->approvalService->getPendingApprovalsForUser($user->id);

        return view('admin.approvals.my_approvals', compact('pendingSteps'));
    }

    /**
     * Show approval action form (approve/reject)
     */
    public function showApprovalForm(ApprovalProcessStep $step)
    {
        // Check if user is the approver for this step
        if ($step->user_id !== Auth::id()) {
            abort(403, 'Anda bukan approver untuk step ini.');
        }

        // Check if step is waiting for approval
        if (!$step->isWaiting()) {
            return back()->with('error', 'Step ini tidak dalam status menunggu approval.');
        }

        $step->load(['process.company', 'process.initiator', 'approver']);

        return view('admin.approvals.action', compact('step'));
    }

    /**
     * Process approval action (approve/reject)
     */
    public function processAction(Request $request, ApprovalProcessStep $step)
    {
        // Check if user is the approver
        if ($step->user_id !== Auth::id()) {
            abort(403, 'Anda bukan approver untuk step ini.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:1000',
        ], [
            'action.required' => 'Aksi harus dipilih.',
            'action.in' => 'Aksi tidak valid.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        try {
            $this->approvalService->processApprovalAction(
                $step,
                $request->action,
                $request->notes
            );

            $message = $request->action === 'approve'
                ? 'Data berhasil disetujui!'
                : 'Data berhasil ditolak!';

            return redirect()
                ->route('admin.approvals.show', $step->approval_id)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Authorize user access to approval process
     */
    protected function authorizeAccess(ApprovalProcess $approval): void
    {
        $user = Auth::user();
        $role = $user->roles[0]->name;

        // Super admin has access to all
        if ($role === 'super-admin') {
            return;
        }

        // Admin and super-user can only access approvals from their department and office
        if (in_array($role, ['admin', 'super-user'])) {
            if ($approval->department_id !== $user->dept->id ||
                $approval->location_id !== $user->office->id) {
                abort(403, 'Anda tidak memiliki akses ke approval ini.');
            }
            return;
        }

        // Other roles not authorized
        abort(403, 'Anda tidak memiliki izin untuk mengakses resource ini.');
    }

    /**
     * Display approval history for a company
     */
    public function companyApprovalHistory($companyId)
    {
        $approval = $this->approvalService->getApprovalProcess($companyId);

        if (!$approval) {
            return back()->with('error', 'Tidak ada riwayat approval untuk data ini.');
        }

        $this->authorizeAccess($approval);

        return $this->show($approval);
    }
}
