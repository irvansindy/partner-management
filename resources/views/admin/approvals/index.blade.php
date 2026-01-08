@extends('adminlte::page')

@section('title', 'Approval Processes')

@section('content_header')
    <h1>
        <i class="fas fa-clipboard-check"></i> Approval Processes
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Statistics -->
        <div class="row mb-3">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $approvals->where('status', 0)->count() + $approvals->where('status', 1)->count() }}</h3>
                        <p>In Progress</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $approvals->where('status', 2)->count() }}</h3>
                        <p>Approved</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $approvals->where('status', 3)->count() }}</h3>
                        <p>Rejected</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $approvals->total() }}</h3>
                        <p>Total Processes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card card-default collapsed-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter"></i> Filters
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.approvals.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>In Progress</option>
                                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Approved</option>
                                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Apply Filter
                                </button>
                                <a href="{{ route('admin.approvals.index') }}" class="btn btn-default">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Approvals Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Approval Processes</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.approvals.my') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-tasks"></i> My Pending Approvals
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Company</th>
                            <th width="15%">Department / Office</th>
                            <th width="15%">Submitted By</th>
                            <th width="10%">Progress</th>
                            <th width="10%">Status</th>
                            <th width="15%">Date</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvals as $index => $approval)
                            <tr>
                                <td>{{ $approvals->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $approval->company->name ?? '-' }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <span class="badge badge-{{ $approval->company->type === 'vendor' ? 'info' : 'success' }}">
                                            {{ ucfirst($approval->company->type ?? '-') }}
                                        </span>
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <i class="fas fa-building"></i> {{ $approval->department->name ?? '-' }}
                                        <br>
                                        <i class="fas fa-map-marker-alt"></i> {{ $approval->office->name ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    {{ $approval->initiator->name ?? '-' }}
                                    <br>
                                    <small class="text-muted">{{ $approval->initiator->email ?? '-' }}</small>
                                </td>
                                <td>
                                    @php
                                        $progress = $approval->getProgressPercentage();
                                        $completed = $approval->getCompletedStepsCount();
                                        $total = $approval->getTotalStepsCount();
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $approval->isApproved() ? 'success' : ($approval->isRejected() ? 'danger' : 'warning') }}"
                                             style="width: {{ $progress }}%">
                                            {{ number_format($progress, 0) }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $completed }}/{{ $total }} steps</small>
                                </td>
                                <td>
                                    @if($approval->isPending())
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @elseif($approval->isInProgress())
                                        <span class="badge badge-warning">
                                            <i class="fas fa-spinner"></i> In Progress
                                        </span>
                                        <br>
                                        <small class="text-muted">Step {{ $approval->step_ordering }}</small>
                                    @elseif($approval->isApproved())
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Approved
                                        </span>
                                    @elseif($approval->isRejected())
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        <strong>Created:</strong><br>
                                        {{ $approval->created_at->format('d M Y H:i') }}
                                        <br>
                                        <strong>Updated:</strong><br>
                                        {{ $approval->updated_at->format('d M Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.approvals.show', $approval) }}"
                                       class="btn btn-sm btn-info"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @php
                                        $currentStep = $approval->getCurrentStep();
                                    @endphp

                                    @if($currentStep && $currentStep->user_id === auth()->id())
                                        <a href="{{ route('admin.approvals.step.action', $currentStep) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Take Action">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No approval processes found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $approvals->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .small-box .icon {
        opacity: 0.15;
    }
</style>
@stop