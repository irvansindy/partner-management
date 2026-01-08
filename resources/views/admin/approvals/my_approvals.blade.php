@extends('adminlte::page')

@section('title', 'My Pending Approvals')

@section('content_header')
    <h1>
        <i class="fas fa-tasks"></i> My Pending Approvals
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Statistics -->
        <div class="row mb-3">
            <div class="col-lg-4">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Actions</span>
                        <span class="info-box-number">{{ $pendingSteps->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($pendingSteps->isEmpty())
            <!-- No Pending Approvals -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                    <h4>All Caught Up!</h4>
                    <p class="text-muted">You don't have any pending approvals at the moment.</p>
                </div>
            </div>
        @else
            <!-- Pending Approvals List -->
            @foreach($pendingSteps as $step)
                @php
                    $company = $step->process->company;
                    $progress = $step->process->getProgressPercentage();
                @endphp

                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <strong>{{ $company->name }}</strong>
                            <span class="badge badge-{{ $company->type === 'vendor' ? 'info' : 'success' }} ml-2">
                                {{ ucfirst($company->type) }}
                            </span>
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i> Waiting for Your Approval
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Company Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Company Information</h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Company Name:</th>
                                        <td>{{ $company->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Business Classification:</th>
                                        <td>{{ $company->business_classification ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $company->email_address ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Submitted By:</th>
                                        <td>{{ $step->process->initiator->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Submitted At:</th>
                                        <td>{{ $company->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Waiting Since:</th>
                                        <td>
                                            {{ $step->created_at->diffForHumans() }}
                                            <br>
                                            <small class="text-muted">{{ $step->created_at->format('d M Y H:i') }}</small>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Approval Progress -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Approval Progress</h5>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Current Step: <strong>{{ $step->step_ordering }}</strong> of <strong>{{ $step->process->getTotalStepsCount() }}</strong></span>
                                        <span class="text-bold">{{ number_format($progress, 0) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $progress }}%">
                                            {{ number_format($progress, 0) }}%
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mt-4">Previous Steps:</h6>
                                <ul class="list-unstyled">
                                    @foreach($step->process->steps->where('step_ordering', '<', $step->step_ordering) as $prevStep)
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <strong>Step {{ $prevStep->step_ordering }}</strong> - {{ $prevStep->approver->name }}
                                            <br>
                                            <small class="text-muted ml-4">
                                                Approved at {{ $prevStep->approved_at->format('d M Y H:i') }}
                                            </small>
                                            @if($prevStep->notes)
                                                <br>
                                                <small class="text-muted ml-4">
                                                    <em>"{{ Str::limit($prevStep->notes, 50) }}"</em>
                                                </small>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('admin.form-links.submission-detail', [$company->form_link_id, $company->id]) }}"
                                   class="btn btn-info"
                                   target="_blank">
                                    <i class="fas fa-eye"></i> View Full Details
                                </a>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('admin.approvals.step.action', $step) }}"
                                   class="btn btn-warning">
                                    <i class="fas fa-tasks"></i> Take Action
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@stop

@section('css')
<style>
    .info-box {
        min-height: 90px;
    }
    .info-box-icon {
        width: 90px;
    }
    .info-box-number {
        font-size: 2rem;
    }
</style>
@stop