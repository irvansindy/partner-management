@extends('layouts.main')

@section('content')
    @push('styles')
        <style>
            .approval-index { color: #292b3a; padding: 26px 0 40px; }
            .approval-index__header { align-items: flex-end; display: flex; gap: 20px; justify-content: space-between; margin-bottom: 24px; }
            .approval-index__header h2 { font-size: 28px; font-weight: 700; margin: 0 0 5px; }
            .approval-index__header p { color: #74778a; margin: 0; }
            .approval-index__header .btn { border-radius: 7px; }
            .approval-stats { display: grid; gap: 14px; grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: 18px; }
            .approval-stat { align-items: center; background: #fff; border: 1px solid #e5e6ef; border-radius: 9px; display: flex; gap: 13px; padding: 16px; }
            .approval-stat__icon { align-items: center; background: #f0eefc; border-radius: 7px; color: #6c5dd3; display: flex; flex: 0 0 38px; height: 38px; justify-content: center; }
            .approval-stat strong { display: block; font-size: 21px; line-height: 1.1; }
            .approval-stat span { color: #737688; font-size: 12px; }
            .approval-filter, .approval-list-card { background: #fff; border: 1px solid #e5e6ef; border-radius: 10px; margin-bottom: 18px; }
            .approval-filter { padding: 16px 18px; }
            .approval-filter form { align-items: flex-end; display: flex; gap: 12px; }
            .approval-filter .form-group { margin: 0; min-width: 230px; }
            .approval-filter label { font-size: 12px; font-weight: 700; }
            .approval-filter .form-control, .approval-filter .btn { border-radius: 7px; min-height: 40px; }
            .approval-list-card__header { align-items: center; border-bottom: 1px solid #ececf3; display: flex; justify-content: space-between; padding: 17px 20px; }
            .approval-list-card__header h5 { font-size: 16px; font-weight: 700; margin: 0; }
            .approval-list-card table { margin: 0; min-width: 980px; }
            .approval-list-card th { background: #f6f6fa; border-color: #ececf3; color: #626578; font-size: 11px; text-transform: uppercase; }
            .approval-list-card td, .approval-list-card th { border-color: #ececf3; padding: 12px 14px; vertical-align: middle; }
            .approval-list-card td { color: #3a3c4b; font-size: 13px; }
            .approval-company strong { display: block; font-size: 14px; }
            .approval-company small, .approval-meta { color: #74778a; }
            .approval-table-progress { background: #e7e8ee; border-radius: 4px; height: 7px; margin-bottom: 5px; overflow: hidden; width: 120px; }
            .approval-table-progress span { background: #6c5dd3; display: block; height: 100%; }
            .approval-actions-cell { display: flex; gap: 6px; }
            .approval-actions-cell .btn { align-items: center; display: inline-flex; height: 34px; justify-content: center; min-width: 34px; padding: 0 9px; }
            .approval-pagination { border-top: 1px solid #ececf3; padding: 14px 20px; }
            .approval-empty { padding: 42px 20px !important; text-align: center; }
            .approval-empty i { color: #aaaabd; display: block; font-size: 30px; margin-bottom: 10px; }
            @media (max-width: 991.98px) { .approval-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
            @media (max-width: 575.98px) {
                .approval-index__header, .approval-filter form { align-items: stretch; flex-direction: column; }
                .approval-index__header .btn, .approval-filter .form-group { width: 100%; }
                .approval-stats { grid-template-columns: 1fr; }
            }
        </style>
    @endpush

    <div class="approval-index">
        <div class="approval-index__header">
            <div><h2>Approval Processes</h2><p>Monitor partner reviews and continue approvals assigned to you.</p></div>
            <a href="{{ route('admin.approvals.my') }}" class="btn btn-primary"><i class="fas fa-tasks mr-1"></i> My pending approvals</a>
        </div>

        <div class="approval-stats">
            @foreach([
                ['clock', $approvalStats['in_progress'], 'In progress'],
                ['check-circle', $approvalStats['approved'], 'Approved'],
                ['times-circle', $approvalStats['rejected'], 'Rejected'],
                ['list', $approvalStats['total'], 'Total processes'],
            ] as [$icon, $value, $label])
                <div class="approval-stat"><span class="approval-stat__icon"><i class="fas fa-{{ $icon }}"></i></span><div><strong>{{ $value }}</strong><span>{{ $label }}</span></div></div>
            @endforeach
        </div>

        <div class="approval-filter">
            <form method="GET" action="{{ route('admin.approvals.index') }}">
                <div class="form-group">
                    <label for="approval_status">Status</label>
                    <select id="approval_status" name="status" class="form-control">
                        <option value="">All statuses</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>In progress</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Approved</option>
                        <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Apply</button>
                @if(request()->filled('status'))<a href="{{ route('admin.approvals.index') }}" class="btn btn-light">Reset</a>@endif
            </form>
        </div>

        <section class="approval-list-card">
            <div class="approval-list-card__header"><h5>All approval processes</h5><span class="approval-meta">{{ $approvals->total() }} records</span></div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>#</th><th>Company</th><th>Department / Office</th><th>Submitted by</th><th>Progress</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($approvals as $index => $approval)
                            @php
                                $progress = $approval->getProgressPercentage();
                                $currentStep = $approval->getCurrentStep();
                            @endphp
                            <tr>
                                <td>{{ $approvals->firstItem() + $index }}</td>
                                <td class="approval-company"><strong>{{ $approval->company->name ?? '-' }}</strong><small>{{ ucfirst($approval->company->type ?? 'Partner') }}</small></td>
                                <td>{{ $approval->department->name ?? '-' }}<br><small class="approval-meta">{{ $approval->office->name ?? '-' }}</small></td>
                                <td>{{ $approval->initiator->name ?? '-' }}<br><small class="approval-meta">{{ $approval->initiator->email ?? '-' }}</small></td>
                                <td><div class="approval-table-progress"><span style="width: {{ $progress }}%"></span></div><small>{{ $approval->getCompletedStepsCount() }}/{{ $approval->getTotalStepsCount() }} steps</small></td>
                                <td>
                                    @if($approval->isApproved())<span class="badge badge-success">Approved</span>
                                    @elseif($approval->isRejected())<span class="badge badge-danger">Rejected</span>
                                    @elseif($approval->isInProgress())<span class="badge badge-warning">In progress</span>
                                    @else<span class="badge badge-secondary">Pending</span>@endif
                                </td>
                                <td>{{ optional($approval->updated_at)->format('d M Y') }}<br><small class="approval-meta">{{ optional($approval->updated_at)->format('H:i') }}</small></td>
                                <td><div class="approval-actions-cell">
                                    <a href="{{ route('admin.approvals.show', $approval) }}" class="btn btn-outline-primary btn-sm" aria-label="View {{ $approval->company->name ?? 'approval' }}"><i class="fas fa-eye"></i></a>
                                    @if($currentStep && $currentStep->user_id === auth()->id())
                                        <a href="{{ route('admin.approvals.step.action', $currentStep) }}" class="btn btn-primary btn-sm"><i class="fas fa-tasks mr-1"></i> Review</a>
                                    @endif
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="approval-empty"><i class="fas fa-inbox"></i><strong>No approval processes found</strong><div class="approval-meta mt-1">Try another status filter or wait for a new submission.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($approvals->hasPages())<div class="approval-pagination">{{ $approvals->appends(request()->query())->links() }}</div>@endif
        </section>
    </div>
@endsection
