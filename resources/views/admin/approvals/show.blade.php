@extends('layouts.main')

@section('content')
    @php
        $company = $approval->company;
        $completedSteps = $approval->getCompletedStepsCount();
        $totalSteps = $approval->getTotalStepsCount();
        $statusLabel = $approval->isApproved() ? 'Approved' : ($approval->isRejected() ? 'Rejected' : 'In progress');
        $statusClass = $approval->isApproved() ? 'success' : ($approval->isRejected() ? 'danger' : 'warning');
    @endphp

    @push('styles')
        <style>
            .approval-detail-shell { color: #292b3a; padding: 26px 0 40px; }
            .approval-detail-header { align-items: flex-end; display: flex; gap: 18px; justify-content: space-between; margin-bottom: 24px; }
            .approval-detail-header h2 { font-size: 28px; font-weight: 700; margin: 0 0 5px; }
            .approval-detail-header p { color: #74778a; margin: 0; }
            .approval-detail-actions { display: flex; flex-wrap: wrap; gap: 8px; }
            .approval-detail-actions .btn { border-radius: 7px; }
            .approval-detail-card { background: #fff; border: 1px solid #e5e6ef; border-radius: 11px; margin-bottom: 18px; overflow: hidden; }
            .approval-detail-card__header { align-items: center; border-bottom: 1px solid #ececf3; display: flex; justify-content: space-between; padding: 17px 20px; }
            .approval-detail-card__header h5 { font-size: 16px; font-weight: 700; margin: 0; }
            .approval-detail-card__body { padding: 20px; }
            .approval-status { border-radius: 5px; font-size: 12px; font-weight: 700; padding: 6px 10px; }
            .approval-status--success { background: #e7f6ec; color: #257642; }
            .approval-status--danger { background: #fdeaea; color: #a83232; }
            .approval-status--warning { background: #fff4d6; color: #815d00; }
            .approval-progress-grid { display: grid; gap: 28px; grid-template-columns: minmax(0, 1.25fr) minmax(300px, .75fr); }
            .approval-progress-meta { align-items: center; display: flex; justify-content: space-between; margin-bottom: 9px; }
            .approval-progress-meta span { color: #6f7284; font-size: 13px; }
            .approval-progress-track { background: #e7e8ee; border-radius: 5px; height: 11px; overflow: hidden; }
            .approval-progress-track span { background: #6c5dd3; display: block; height: 100%; }
            .approval-facts { display: grid; gap: 14px; grid-template-columns: repeat(2, minmax(0, 1fr)); margin: 0; }
            .approval-facts div { min-width: 0; }
            .approval-facts dt { color: #74778a; font-size: 11px; font-weight: 700; letter-spacing: .04em; margin-bottom: 4px; text-transform: uppercase; }
            .approval-facts dd { color: #2f3140; font-size: 14px; font-weight: 600; margin: 0; overflow-wrap: anywhere; }
            .approval-info-grid { display: grid; gap: 18px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .approval-timeline { display: grid; gap: 12px; }
            .approval-step { align-items: flex-start; border: 1px solid #e5e6ef; border-radius: 9px; display: grid; gap: 14px; grid-template-columns: 38px minmax(0, 1fr) auto; padding: 15px; }
            .approval-step__number { align-items: center; background: #efedfc; border-radius: 50%; color: #6c5dd3; display: flex; font-weight: 700; height: 36px; justify-content: center; width: 36px; }
            .approval-step__main strong { display: block; margin-bottom: 3px; }
            .approval-step__main small { color: #74778a; }
            .approval-step__notes { background: #f7f7fa; border-radius: 6px; color: #555869; margin-top: 10px; padding: 10px 12px; }
            .approval-step__aside { min-width: 135px; text-align: right; }
            .approval-step__aside small { color: #74778a; display: block; margin-top: 6px; }
            @media (max-width: 991.98px) { .approval-progress-grid, .approval-info-grid { grid-template-columns: 1fr; } }
            @media (max-width: 575.98px) {
                .approval-detail-header { align-items: stretch; flex-direction: column; }
                .approval-detail-actions .btn { flex: 1 1 100%; }
                .approval-facts { grid-template-columns: 1fr; }
                .approval-step { grid-template-columns: 38px minmax(0, 1fr); }
                .approval-step__aside { grid-column: 2; min-width: 0; text-align: left; }
            }
        </style>
    @endpush

    <div class="approval-detail-shell">
        <header class="approval-detail-header">
            <div><h2>Approval Detail</h2><p>{{ $company->name ?? 'Partner submission' }} · Review status and approval history.</p></div>
            <div class="approval-detail-actions">
                <a href="{{ route('admin.approvals.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back to list</a>
                @if($company && $company->form_link_id)
                    <a href="{{ route('admin.form-links.submission-detail', [$company->form_link_id, $company->id]) }}" class="btn btn-outline-primary" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-1"></i> View submission</a>
                @endif
                @if($currentStep && $currentStep->user_id === auth()->id())
                    <a href="{{ route('admin.approvals.step.action', $currentStep) }}" class="btn btn-primary"><i class="fas fa-tasks mr-1"></i> Review now</a>
                @endif
            </div>
        </header>

        <section class="approval-detail-card">
            <div class="approval-detail-card__header">
                <h5>Status and progress</h5>
                <span class="approval-status approval-status--{{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
            <div class="approval-detail-card__body approval-progress-grid">
                <div>
                    <div class="approval-progress-meta"><span>{{ $completedSteps }} of {{ $totalSteps }} steps completed</span><strong>{{ number_format($progress, 0) }}%</strong></div>
                    <div class="approval-progress-track" role="progressbar" aria-label="Approval progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ number_format($progress, 0) }}"><span style="width: {{ $progress }}%"></span></div>
                </div>
                <dl class="approval-facts">
                    <div><dt>Current step</dt><dd>{{ $currentStep ? 'Step '.$currentStep->step_ordering.' · '.($currentStep->approver->name ?? 'Approver') : ($approval->isApproved() ? 'Completed' : 'Not available') }}</dd></div>
                    <div><dt>Duration</dt><dd>{{ $approval->created_at->diffForHumans($approval->updated_at, true) }}</dd></div>
                    <div><dt>Started</dt><dd>{{ $approval->created_at->format('d M Y, H:i') }}</dd></div>
                    <div><dt>Updated</dt><dd>{{ $approval->updated_at->format('d M Y, H:i') }}</dd></div>
                </dl>
            </div>
        </section>

        <div class="approval-info-grid">
            <section class="approval-detail-card">
                <div class="approval-detail-card__header"><h5>Partner information</h5></div>
                <div class="approval-detail-card__body"><dl class="approval-facts">
                    <div><dt>Company</dt><dd>{{ $company->name ?? '-' }}</dd></div>
                    <div><dt>Type</dt><dd>{{ ucfirst($company->type ?? 'Partner') }}</dd></div>
                    <div><dt>Business classification</dt><dd>{{ $company->business_classification ?? '-' }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $company->email_address ?? '-' }}</dd></div>
                    <div><dt>Submitted at</dt><dd>{{ optional($company->created_at)->format('d M Y, H:i') ?? '-' }}</dd></div>
                </dl></div>
            </section>
            <section class="approval-detail-card">
                <div class="approval-detail-card__header"><h5>Submission information</h5></div>
                <div class="approval-detail-card__body"><dl class="approval-facts">
                    <div><dt>Submitted by</dt><dd>{{ $approval->initiator->name ?? '-' }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $approval->initiator->email ?? '-' }}</dd></div>
                    <div><dt>Department</dt><dd>{{ $approval->department->name ?? '-' }}</dd></div>
                    <div><dt>Office</dt><dd>{{ $approval->office->name ?? '-' }}</dd></div>
                    <div><dt>Form</dt><dd>{{ $company->formLink->title ?? '-' }}</dd></div>
                </dl></div>
            </section>
        </div>

        <section class="approval-detail-card">
            <div class="approval-detail-card__header"><h5>Approval timeline</h5><span class="text-muted small">{{ $totalSteps }} steps</span></div>
            <div class="approval-detail-card__body">
                <div class="approval-timeline">
                    @foreach($approval->steps as $step)
                        @php
                            $stepStatus = $step->isApproved() ? 'Approved' : ($step->isRejected() ? 'Rejected' : ($step->isWaiting() ? 'Waiting' : 'Pending'));
                            $stepClass = $step->isApproved() ? 'success' : ($step->isRejected() ? 'danger' : ($step->isWaiting() ? 'warning' : 'secondary'));
                            $actedAt = $step->approved_at ?? $step->rejected_at;
                        @endphp
                        <article class="approval-step">
                            <span class="approval-step__number">{{ $step->step_ordering }}</span>
                            <div class="approval-step__main">
                                <strong>{{ $step->approver->name ?? 'Unknown approver' }}</strong>
                                <small>{{ $step->approver->email ?? '-' }}</small>
                                @if($step->notes)<div class="approval-step__notes">{!! $step->notes !! }</div>@endif
                                @if($step->isWaiting() && auth()->id() === $step->user_id)
                                    <a href="{{ route('admin.approvals.step.action', $step) }}" class="btn btn-primary btn-sm mt-3">Review this step</a>
                                @endif
                            </div>
                            <div class="approval-step__aside">
                                <span class="badge badge-{{ $stepClass }}">{{ $stepStatus }}</span>
                                <small>{{ $actedAt ? $actedAt->format('d M Y, H:i') : 'Awaiting action' }}</small>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
