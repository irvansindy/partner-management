@extends('adminlte::page')

@section('title', 'Approval Detail')

@section('content_header')
    <h1>Approval Detail</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>

                <a href="{{ route('admin.form-links.submission-detail', [$approval->company->form_link_id, $approval->company->id]) }}"
                   class="btn btn-info"
                   target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Company Details
                </a>

                @if($currentStep && $currentStep->user_id === auth()->id())
                    <a href="{{ route('admin.approvals.step.action', $currentStep) }}"
                       class="btn btn-warning">
                        <i class="fas fa-tasks"></i> Take Action
                    </a>
                @endif
            </div>
        </div>

        <!-- Status & Progress Card -->
        <div class="card card-outline card-{{ $approval->isApproved() ? 'success' : ($approval->isRejected() ? 'danger' : 'warning') }}">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Approval Status & Progress
                </h3>
                <div class="card-tools">
                    @if($approval->isPending() || $approval->isInProgress())
                        <span class="badge badge-warning badge-lg">
                            <i class="fas fa-clock"></i> In Progress
                        </span>
                    @elseif($approval->isApproved())
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-check"></i> Approved
                        </span>
                    @elseif($approval->isRejected())
                        <span class="badge badge-danger badge-lg">
                            <i class="fas fa-times"></i> Rejected
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Progress Overview</h5>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Completed Steps: <strong>{{ $approval->getCompletedStepsCount() }}</strong> of <strong>{{ $approval->getTotalStepsCount() }}</strong></span>
                                <span class="text-bold">{{ number_format($progress, 0) }}%</span>
                            </div>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-{{ $approval->isApproved() ? 'success' : ($approval->isRejected() ? 'danger' : 'warning') }}"
                                     role="progressbar"
                                     style="width: {{ $progress }}%">
                                    {{ number_format($progress, 0) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Information</h5>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Current Step:</th>
                                <td>
                                    @if($currentStep)
                                        Step {{ $currentStep->step_ordering }} - {{ $currentStep->approver->name }}
                                    @else
                                        {{ $approval->isApproved() ? 'All Steps Completed' : 'N/A' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Started At:</th>
                                <td>{{ $approval->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $approval->updated_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Duration:</th>
                                <td>{{ $approval->created_at->diffForHumans($approval->updated_at, true) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Company Information -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Company Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Company Name:</th>
                                <td>{{ $approval->company->name }}</td>
                            </tr>
                            <tr>
                                <th>Company Type:</th>
                                <td>
                                    <span class="badge badge-{{ $approval->company->type === 'vendor' ? 'info' : 'success' }}">
                                        {{ ucfirst($approval->company->type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Business Classification:</th>
                                <td>{{ $approval->company->business_classification ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $approval->company->email_address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Submitted At:</th>
                                <td>{{ $approval->company->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Submission Information -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Submission Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Submitted By:</th>
                                <td>{{ $approval->initiator->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $approval->initiator->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Department:</th>
                                <td>{{ $approval->department->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Office:</th>
                                <td>{{ $approval->office->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Form Link:</th>
                                <td>
                                    @if($approval->company->formLink)
                                        <a href="{{ route('admin.form-links.show', $approval->company->form_link_id) }}">
                                            {{ $approval->company->formLink->title }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Timeline -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i> Approval Timeline
                </h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($approval->steps as $step)
                        <div class="time-label">
                            <span class="bg-{{ $step->isApproved() ? 'success' : ($step->isRejected() ? 'danger' : ($step->isWaiting() ? 'warning' : 'secondary')) }}">
                                Step {{ $step->step_ordering }}
                            </span>
                        </div>
                        <div>
                            <i class="fas fa-{{ $step->isApproved() ? 'check' : ($step->isRejected() ? 'times' : ($step->isWaiting() ? 'clock' : 'circle')) }}
                               bg-{{ $step->isApproved() ? 'success' : ($step->isRejected() ? 'danger' : ($step->isWaiting() ? 'warning' : 'secondary')) }}"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    @if($step->isApproved() && $step->approved_at)
                                        <i class="fas fa-clock"></i> {{ $step->approved_at->format('d M Y H:i') }}
                                    @elseif($step->isRejected() && $step->rejected_at)
                                        <i class="fas fa-clock"></i> {{ $step->rejected_at->format('d M Y H:i') }}
                                    @else
                                        <i class="fas fa-clock"></i> {{ $step->created_at->format('d M Y H:i') }}
                                    @endif
                                </span>
                                <h3 class="timeline-header">
                                    <strong>{{ $step->approver->name ?? 'Unknown' }}</strong>
                                    @if($step->isApproved())
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($step->isRejected())
                                        <span class="badge badge-danger">Rejected</span>
                                    @elseif($step->isWaiting())
                                        <span class="badge badge-warning">Waiting for Approval</span>
                                    @else
                                        <span class="badge badge-secondary">Pending</span>
                                    @endif
                                </h3>
                                <div class="timeline-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1">
                                                <i class="fas fa-user"></i> <strong>Approver:</strong> {{ $step->approver->name ?? 'Unknown' }}
                                            </p>
                                            <p class="mb-1">
                                                <i class="fas fa-envelope"></i> <strong>Email:</strong> {{ $step->approver->email ?? '-' }}
                                            </p>
                                            @if($step->isWaiting())
                                                <p class="mb-1">
                                                    <i class="fas fa-hourglass-half"></i> <strong>Waiting since:</strong> {{ $step->created_at->diffForHumans() }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($step->notes)
                                                <div class="alert alert-{{ $step->isApproved() ? 'success' : 'danger' }} mb-0">
                                                    <strong><i class="fas fa-comment"></i> Notes:</strong>
                                                    <p class="mb-0 mt-1">{{ $step->notes }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($step->isWaiting() && auth()->id() === $step->user_id)
                                        <div class="mt-3">
                                            <a href="{{ route('admin.approvals.step.action', $step) }}"
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-tasks"></i> Take Action Now
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    /* Timeline Styles */
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }

    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }

    .timeline > div {
        margin-bottom: 15px;
        position: relative;
    }

    .timeline > div > .time-label > span {
        font-weight: 600;
        padding: 5px;
        display: inline-block;
        background-color: #fff;
        border-radius: 4px;
    }

    .timeline > div > .timeline-item {
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        border-radius: 3px;
        margin-top: 0;
        background: #fff;
        color: #495057;
        margin-left: 60px;
        margin-right: 15px;
        padding: 0;
        position: relative;
    }

    .timeline > div > .fa,
    .timeline > div > .fas {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #666;
        background: #d2d6de;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }

    .timeline > div > .timeline-item > .time {
        color: #999;
        float: right;
        padding: 10px;
        font-size: 12px;
    }

    .timeline > div > .timeline-item > .timeline-header {
        margin: 0;
        color: #555;
        border-bottom: 1px solid #f4f4f4;
        padding: 10px;
        font-size: 16px;
        line-height: 1.1;
    }

    .timeline > div > .timeline-item > .timeline-body {
        padding: 10px;
    }

    .bg-success { background-color: #28a745 !important; color: white; }
    .bg-danger { background-color: #dc3545 !important; color: white; }
    .bg-warning { background-color: #ffc107 !important; color: #212529; }
    .bg-secondary { background-color: #6c757d !important; color: white; }
    .bg-gray { background-color: #d2d6de !important; }
    .badge-lg { font-size: 1rem; padding: 0.5rem 0.75rem; }
</style>
@stop