@extends('adminlte::page')

@section('title', 'Approval Action')

@section('content_header')
    <h1>Approval Action - Step {{ $step->step_ordering }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.approvals.show', $step->approval_id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Approval Detail
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Company Summary -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Company Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Company Name:</th>
                                <td>{{ $step->process->company->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Company Type:</th>
                                <td>
                                    <span
                                        class="badge badge-{{ $step->process->company->type === 'vendor' ? 'info' : 'success' }}">
                                        {{ ucfirst($step->process->company->type ?? '-') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Business Classification:</th>
                                <td>{{ $step->process->company->business_classification ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Submitted By:</th>
                                <td>{{ $step->process->initiator->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Submitted At:</th>
                                <td>{{ $step->process->company->created_at->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                        </table>

                        <a href="{{ route('admin.form-links.submission-detail', [$step->process->company->form_link_id, $step->process->company->id]) }}"
                            class="btn btn-sm btn-info" target="_blank">
                            <i class="fas fa-external-link-alt"></i> View Full Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step Information -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Approval Step Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Current Step:</th>
                                <td>Step {{ $step->step_ordering }}</td>
                            </tr>
                            <tr>
                                <th>Your Role:</th>
                                <td>Approver</td>
                            </tr>
                            <tr>
                                <th>Total Steps:</th>
                                <td>{{ $step->process->getTotalStepsCount() }}</td>
                            </tr>
                            <tr>
                                <th>Completed Steps:</th>
                                <td>{{ $step->process->getCompletedStepsCount() }}</td>
                            </tr>
                            <tr>
                                <th>Progress:</th>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $step->process->getProgressPercentage() }}%">
                                            {{ number_format($step->process->getProgressPercentage(), 0) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Form -->
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tasks"></i> Take Action
                        </h3>
                    </div>
                    <form action="{{ route('admin.approvals.step.process', $step) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Important:</strong> Please review the company information carefully before making
                                your decision.
                                Your action cannot be undone.
                            </div>

                            <!-- Action Selection -->
                            <div class="form-group">
                                <label>Action <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="action_approve" name="action" value="approve"
                                                class="custom-control-input" required
                                                {{ old('action') === 'approve' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="action_approve">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <strong>Approve</strong>
                                                <br>
                                                <small class="text-muted">I approve this submission and it can proceed to
                                                    the next step</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="action_reject" name="action" value="reject"
                                                class="custom-control-input" required
                                                {{ old('action') === 'reject' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="action_reject">
                                                <i class="fas fa-times-circle text-danger"></i>
                                                <strong>Reject</strong>
                                                <br>
                                                <small class="text-muted">I reject this submission and the process will be
                                                    terminated</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('action')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="form-group">
                                <label for="notes">
                                    Notes / Comments
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="5"
                                    placeholder="Add your comments or reasons for this action...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Maximum 1000 characters
                                </small>
                            </div>

                            <!-- Confirmation Checkbox -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="confirmation" required>
                                    <label class="custom-control-label" for="confirmation">
                                        I confirm that I have reviewed all the information and my decision is final
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('admin.approvals.show', $step->approval_id) }}"
                                        class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-paper-plane"></i> Submit Decision
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Previous Steps -->
        @if ($step->step_ordering > 1)
            <div class="row">
                <div class="col-12">
                    <div class="card card-secondary collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Previous Approval Steps</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Step</th>
                                        <th>Approver</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($step->process->steps as $previousStep)
                                        @if ($previousStep->step_ordering < $step->step_ordering)
                                            <tr>
                                                <td>{{ $previousStep->step_ordering }}</td>
                                                <td>{{ $previousStep->approver->name ?? '-' }}</td>
                                                <td>
                                                    @if ($previousStep->isApproved())
                                                        <span class="badge badge-success">Approved</span>
                                                    @elseif($previousStep->isRejected())
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @else
                                                        <span class="badge badge-secondary">Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ $previousStep->notes ?? '-' }}</td>
                                                <td>
                                                    @if ($previousStep->approved_at)
                                                        {{ $previousStep->approved_at->format('d M Y H:i') }}
                                                    @elseif($previousStep->rejected_at)
                                                        {{ $previousStep->rejected_at->format('d M Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Change button color based on action selected
            $('input[name="action"]').on('change', function() {
                const submitBtn = $('#submitBtn');
                const action = $(this).val();

                if (action === 'approve') {
                    submitBtn.removeClass('btn-danger').addClass('btn-success');
                    submitBtn.html('<i class="fas fa-check"></i> Approve');
                } else {
                    submitBtn.removeClass('btn-success').addClass('btn-danger');
                    submitBtn.html('<i class="fas fa-times"></i> Reject');
                }
            });

            // Confirm before submit
            $('form').on('submit', function(e) {
                const action = $('input[name="action"]:checked').val();
                const actionText = action === 'approve' ? 'approve' : 'reject';

                if (!confirm(`Are you sure you want to ${actionText} this submission?`)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@stop
