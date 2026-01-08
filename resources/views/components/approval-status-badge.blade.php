{{-- File: resources/views/components/approval-status-badge.blade.php --}}
@props(['status'])

@php
    $badges = [
        'pending' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Pending'],
        'in_progress' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'In Progress'],
        'approved' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
        'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
    ];

    $badge = $badges[$status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Unknown'];
@endphp

<span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge['class'] }}">
    {{ $badge['text'] }}
</span>

{{-- ============================================ --}}
{{-- File: resources/views/components/approval-timeline.blade.php --}}
@props(['approvalProcess'])

<div class="space-y-4">
    @foreach($approvalProcess->steps as $index => $step)
        <div class="flex items-start">
            {{-- Step Indicator --}}
            <div class="flex flex-col items-center mr-4">
                @if($step->isApproved())
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @elseif($step->isRejected())
                    <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @elseif($step->isWaiting())
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center animate-pulse">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600 text-sm font-semibold">{{ $step->step_ordering }}</span>
                    </div>
                @endif

                @if(!$loop->last)
                    <div class="w-0.5 h-16 bg-gray-300 my-1"></div>
                @endif
            </div>

            {{-- Step Details --}}
            <div class="flex-1 pb-8">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-900">
                            Step {{ $step->step_ordering }} - {{ $step->approver->name }}
                        </h4>
                        <span class="text-xs text-gray-500">{{ $step->approver->email }}</span>
                    </div>

                    @if($step->isApproved())
                        <div class="text-sm text-green-600 mb-1">
                            ✓ Approved at {{ $step->approved_at->format('d M Y H:i') }}
                        </div>
                    @elseif($step->isRejected())
                        <div class="text-sm text-red-600 mb-1">
                            ✗ Rejected at {{ $step->rejected_at->format('d M Y H:i') }}
                        </div>
                    @elseif($step->isWaiting())
                        <div class="text-sm text-blue-600 mb-1">
                            ⏳ Waiting for approval
                        </div>
                    @else
                        <div class="text-sm text-gray-500 mb-1">
                            ⏸ Pending
                        </div>
                    @endif

                    @if($step->notes)
                        <div class="mt-2 p-2 bg-gray-50 rounded text-sm">
                            <strong>Notes:</strong> {{ $step->notes }}
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    @if($step->isWaiting() && auth()->id() === $step->user_id)
                        <div class="mt-3 flex gap-2">
                            <button
                                onclick="openApproveModal({{ $approvalProcess->id }})"
                                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm"
                            >
                                Approve
                            </button>
                            <button
                                onclick="openRejectModal({{ $approvalProcess->id }})"
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm"
                            >
                                Reject
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- ============================================ --}}
{{-- File: resources/views/admin/form_links/show.blade.php (Add to existing view) --}}

{{-- Tambahkan ini di section submission list --}}
@foreach($formLink->companies as $company)
    <tr>
        <td>{{ $company->name }}</td>
        <td>{{ $company->created_at->format('d M Y') }}</td>

        {{-- Approval Status Column --}}
        <td>
            @if($company->hasApproval())
                <x-approval-status-badge :status="$company->getApprovalStatus()" />

                {{-- Progress Bar --}}
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div
                        class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                        style="width: {{ $company->getApprovalProgress() }}%"
                    ></div>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    {{ $company->approvalProcess->getCompletedStepsCount() }} /
                    {{ $company->approvalProcess->getTotalStepsCount() }} steps completed
                </div>
            @else
                <span class="text-gray-400 text-sm">No approval</span>

                {{-- Button to create approval manually --}}
                <form action="{{ route('admin.companies.create-approval', $company) }}" method="POST" class="mt-2">
                    @csrf
                    <button
                        type="submit"
                        class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                    >
                        Create Approval
                    </button>
                </form>
            @endif
        </td>

        <td>
            <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company]) }}"
               class="text-blue-600 hover:text-blue-800">
                View Detail
            </a>

            @if($company->hasApproval())
                <a href="{{ route('admin.companies.approval-history', $company) }}"
                   class="ml-3 text-green-600 hover:text-green-800">
                    Approval History
                </a>
            @endif
        </td>
    </tr>
@endforeach

{{-- ============================================ --}}
{{-- File: resources/views/admin/approvals/index.blade.php --}}

@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Pending Approvals</h1>
        <a href="{{ route('admin.approvals.dashboard') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Dashboard
        </a>
    </div>

    @if($pendingApprovals->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Pending Approvals</h3>
            <p class="text-gray-500">You're all caught up! There are no approvals waiting for your action.</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($pendingApprovals as $step)
                @php $process = $step->process; @endphp
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $process->company->name }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Type: <span class="font-medium">{{ ucfirst($process->company->type) }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Submitted by: <span class="font-medium">{{ $process->initiator->name }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500">
                                    Step {{ $step->step_ordering }} of {{ $process->getTotalStepsCount() }}
                                </span>
                                <div class="mt-1">
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                        Waiting Your Approval
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t">
                            <div class="text-sm text-gray-500">
                                <span>{{ $process->office->name }} - {{ $process->department->name }}</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.approvals.show', $process) }}"
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    View Details
                                </a>
                                <button
                                    onclick="quickApprove({{ $process->id }})"
                                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                    Quick Approve
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Modals --}}
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">Approve Submission</h3>
        <form id="approveForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea
                    name="notes"
                    rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    placeholder="Add any notes..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    onclick="closeModal('approveModal')"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Confirm Approve
                </button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">Reject Submission</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Rejection <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="notes"
                    rows="3"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    placeholder="Please provide a reason..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    onclick="closeModal('rejectModal')"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Confirm Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(processId) {
    document.getElementById('approveForm').action = `/admin/approvals/${processId}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function openRejectModal(processId) {
    document.getElementById('rejectForm').action = `/admin/approvals/${processId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function quickApprove(processId) {
    if (confirm('Are you sure you want to approve this submission?')) {
        openApproveModal(processId);
    }
}
</script>
@endsection