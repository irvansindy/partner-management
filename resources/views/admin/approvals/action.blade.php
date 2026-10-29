@extends('layouts.main')

@section('content')
    @php
        $process = $step->process;
        $company = $process->company;
        $progress = $process->getProgressPercentage();
        $totalSteps = $process->getTotalStepsCount();
        $completedSteps = $process->getCompletedStepsCount();
    @endphp

    @push('styles')
        <style>
            .approval-action-shell { color: #292b3a; padding: 26px 0 40px; }
            .approval-action-header { align-items: flex-end; display: flex; gap: 20px; justify-content: space-between; margin-bottom: 24px; }
            .approval-action-header h2 { font-size: 28px; font-weight: 700; margin: 0 0 5px; }
            .approval-action-header p { color: #74778a; margin: 0; }
            .approval-action-header .btn, .approval-actions .btn { border-radius: 7px; }
            .approval-action-grid { display: grid; gap: 18px; grid-template-columns: minmax(0, 1.15fr) minmax(320px, .85fr); }
            .approval-panel { background: #fff; border: 1px solid #e5e6ef; border-radius: 11px; margin-bottom: 18px; overflow: hidden; }
            .approval-panel__header { align-items: center; border-bottom: 1px solid #ececf3; display: flex; justify-content: space-between; padding: 17px 20px; }
            .approval-panel__header h5 { font-size: 16px; font-weight: 700; margin: 0; }
            .approval-panel__body { padding: 20px; }
            .approval-summary { display: grid; gap: 15px 22px; grid-template-columns: repeat(2, minmax(0, 1fr)); margin: 0; }
            .approval-summary div { min-width: 0; }
            .approval-summary dt { color: #74778a; font-size: 11px; font-weight: 700; letter-spacing: .04em; margin-bottom: 4px; text-transform: uppercase; }
            .approval-summary dd { color: #2f3140; font-size: 14px; font-weight: 600; margin: 0; overflow-wrap: anywhere; }
            .approval-type { background: #e8f6f8; border-radius: 4px; color: #16798a; display: inline-block; font-size: 12px; padding: 4px 8px; }
            .approval-progress__meta { align-items: center; display: flex; justify-content: space-between; margin-bottom: 8px; }
            .approval-progress__meta span { color: #686b7d; font-size: 12px; }
            .approval-progress { background: #e7e8ee; border-radius: 4px; height: 9px; overflow: hidden; }
            .approval-progress span { background: #6c5dd3; display: block; height: 100%; }
            .approval-guidance { align-items: flex-start; background: #f1f0fc; border: 1px solid #dcd8f7; border-radius: 8px; color: #4c456f; display: flex; gap: 10px; margin-bottom: 20px; padding: 13px 15px; }
            .approval-guidance p { font-size: 13px; line-height: 1.5; margin: 0; }
            .decision-options { display: grid; gap: 12px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .decision-option { border: 1px solid #d9dbe6; border-radius: 9px; cursor: pointer; display: block; margin: 0; min-height: 105px; padding: 15px 15px 15px 44px; position: relative; }
            .decision-option input { left: 16px; position: absolute; top: 18px; }
            .decision-option strong { display: block; font-size: 14px; margin-bottom: 5px; }
            .decision-option small { color: #6f7284; display: block; line-height: 1.45; }
            .decision-option:has(input:checked) { background: #f4f2ff; border-color: #6c5dd3; }
            .decision-option--reject:has(input:checked) { background: #fff5f5; border-color: #dc3545; }
            .approval-field { margin-bottom: 22px; }
            .approval-field > label { color: #383a49; display: block; font-size: 13px; font-weight: 700; margin: 0 0 9px; position: static; transform: none; }
            .approval-form .form-control { border-color: #cfd2df; border-radius: 7px; }
            .approval-form .form-control:focus { border-color: #6c5dd3; box-shadow: 0 0 0 3px rgba(108, 93, 211, .13); }
            .approval-confirmation { align-items: flex-start; display: flex; gap: 9px; }
            .approval-confirmation input { margin-top: 3px; }
            .approval-actions { align-items: center; border-top: 1px solid #ececf3; display: flex; gap: 10px; justify-content: flex-end; margin: 22px -20px -20px; padding: 16px 20px; }
            .approval-actions .btn { min-width: 130px; }
            .approval-history { margin: 0; }
            .approval-history th { background: #f6f6fa; color: #626578; font-size: 11px; text-transform: uppercase; }
            .approval-history td, .approval-history th { border-color: #ececf3; padding: 11px 12px; vertical-align: middle; }
            @media (max-width: 991.98px) { .approval-action-grid { grid-template-columns: 1fr; } }
            @media (max-width: 575.98px) {
                .approval-action-header { align-items: stretch; flex-direction: column; }
                .approval-action-header .btn, .approval-actions .btn { width: 100%; }
                .approval-summary, .decision-options { grid-template-columns: 1fr; }
                .approval-actions { align-items: stretch; flex-direction: column-reverse; }
            }
        </style>
    @endpush

    <div class="approval-action-shell">
        <div class="approval-action-header">
            <div>
                <h2>Review Approval</h2>
                <p>Step {{ $step->step_ordering }} of {{ $totalSteps }} · Review the partner submission before deciding.</p>
            </div>
            <a href="{{ route('admin.approvals.show', $step->approval_id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1" aria-hidden="true"></i> Approval Detail
            </a>
        </div>

        <div class="approval-action-grid">
            <main>
                <section class="approval-panel">
                    <div class="approval-panel__header">
                        <h5>Partner information</h5>
                        <span class="approval-type">{{ ucfirst($company->type ?? 'Partner') }}</span>
                    </div>
                    <div class="approval-panel__body">
                        <dl class="approval-summary">
                            <div><dt>Company</dt><dd>{{ $company->name ?? '-' }}</dd></div>
                            <div><dt>Business classification</dt><dd>{{ $company->business_classification ?? '-' }}</dd></div>
                            <div><dt>Submitted by</dt><dd>{{ $process->initiator->name ?? '-' }}</dd></div>
                            <div><dt>Submitted at</dt><dd>{{ optional($company->created_at)->format('d M Y, H:i') ?? '-' }}</dd></div>
                        </dl>
                        @if($company && $company->form_link_id)
                            <a href="{{ route('admin.form-links.submission-detail', [$company->form_link_id, $company->id]) }}"
                               class="btn btn-outline-primary btn-sm mt-4" target="_blank" rel="noopener noreferrer">
                                <i class="fas fa-external-link-alt mr-1" aria-hidden="true"></i> View full submission
                            </a>
                        @endif
                    </div>
                </section>

                <section class="approval-panel">
                    <div class="approval-panel__header"><h5>Decision</h5></div>
                    <form class="approval-form" action="{{ route('admin.approvals.step.process', $step) }}" method="POST" id="approvalDecisionForm">
                        @csrf
                        <div class="approval-panel__body">
                            <div class="approval-guidance" role="note">
                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                <p>Review the full submission carefully. Once submitted, this decision cannot be undone.</p>
                            </div>
                            <div class="approval-field">
                                <label>Decision <span class="text-danger">*</span></label>
                                <div class="decision-options">
                                    <label class="decision-option">
                                        <input type="radio" name="action" value="approve" required {{ old('action') === 'approve' ? 'checked' : '' }}>
                                        <strong><i class="fas fa-check-circle text-success mr-1"></i>Approve</strong>
                                        <small>Approve this submission and continue it to the next step.</small>
                                    </label>
                                    <label class="decision-option decision-option--reject">
                                        <input type="radio" name="action" value="reject" required {{ old('action') === 'reject' ? 'checked' : '' }}>
                                        <strong><i class="fas fa-times-circle text-danger mr-1"></i>Reject</strong>
                                        <small>Reject this submission and terminate its approval process.</small>
                                    </label>
                                </div>
                                @error('action')<span class="text-danger small d-block mt-2">{{ $message }}</span>@enderror
                            </div>
                            <div class="approval-field">
                                <label for="notes">Notes / Comments <span class="text-muted font-weight-normal">(Wajib jika memilih Reject)</span></label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4"
                                    maxlength="1000" placeholder="Add supporting notes or a rejection reason">{{ old('notes') }}</textarea>
                                @error('notes')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                <small class="form-text text-muted"><span id="notesCount">0</span>/1000 characters</small>
                            </div>
                            <label class="approval-confirmation" for="confirmation">
                                <input type="checkbox" id="confirmation" required>
                                <span>I confirm that I have reviewed the submission and this decision is final.</span>
                            </label>
                            <div class="approval-actions">
                                <a href="{{ route('admin.approvals.show', $step->approval_id) }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary" id="submitBtn"><i class="fas fa-paper-plane mr-1"></i> Submit decision</button>
                            </div>
                        </div>
                    </form>
                </section>
            </main>

            <aside>
                <section class="approval-panel">
                    <div class="approval-panel__header"><h5>Workflow progress</h5></div>
                    <div class="approval-panel__body">
                        <div class="approval-summary mb-4">
                            <div><dt>Current step</dt><dd>Step {{ $step->step_ordering }}</dd></div>
                            <div><dt>Your role</dt><dd>Approver</dd></div>
                            <div><dt>Completed</dt><dd>{{ $completedSteps }} steps</dd></div>
                            <div><dt>Total</dt><dd>{{ $totalSteps }} steps</dd></div>
                        </div>
                        <div class="approval-progress__meta"><strong>Progress</strong><span>{{ number_format($progress, 0) }}%</span></div>
                        <div class="approval-progress" role="progressbar" aria-label="Approval progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ number_format($progress, 0) }}">
                            <span style="width: {{ $progress }}%"></span>
                        </div>
                    </div>
                </section>
            </aside>
        </div>

        @if($step->step_ordering > 1)
            <section class="approval-panel">
                <div class="approval-panel__header"><h5>Previous approval steps</h5></div>
                <div class="table-responsive">
                    <table class="table approval-history">
                        <thead><tr><th>Step</th><th>Approver</th><th>Status</th><th>Notes</th><th>Date</th></tr></thead>
                        <tbody>
                            @foreach($process->steps->where('step_ordering', '<', $step->step_ordering) as $previousStep)
                                <tr>
                                    <td>{{ $previousStep->step_ordering }}</td>
                                    <td>{{ $previousStep->approver->name ?? '-' }}</td>
                                    <td>
                                        @if($previousStep->isApproved())<span class="badge badge-success">Approved</span>
                                        @elseif($previousStep->isRejected())<span class="badge badge-danger">Rejected</span>
                                        @else<span class="badge badge-secondary">Pending</span>@endif
                                    </td>
                                    <td>{{ $previousStep->notes }}</td>
                                    <td>{{ optional($previousStep->approved_at ?? $previousStep->rejected_at)->format('d M Y, H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const $form = $('#approvalDecisionForm');
            const $notes = $('#notes');
            const $submit = $('#submitBtn');
            function syncDecision() {
                const action = $form.find('input[name="action"]:checked').val();
                $notes.prop('required', action === 'reject');
                $submit.toggleClass('btn-danger', action === 'reject').toggleClass('btn-primary', action !== 'reject');
            }
            function syncCount() { $('#notesCount').text($notes.val().length); }
            $form.find('input[name="action"]').on('change', syncDecision);
            $notes.on('input', syncCount);
            $form.on('submit', function (event) {
                const action = $form.find('input[name="action"]:checked').val();
                if (!window.confirm('Submit this ' + action + ' decision? This action cannot be undone.')) event.preventDefault();
            });
            syncDecision();
            syncCount();
        });
    </script>
@endpush
