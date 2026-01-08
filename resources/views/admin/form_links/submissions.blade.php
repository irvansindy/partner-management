@extends('adminlte::page')

@section('title', 'Form Submissions')

@section('content_header')
    <h1>Form Submissions: {{ $formLink->title }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.form-links.show', $formLink) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Form Link
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $companies->total() }}</h3>
                        <p>Total Submissions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $companies->where('approvalProcess.status', 2)->count() }}</h3>
                        <p>Approved</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $companies->whereIn('approvalProcess.status', [0, 1])->count() }}</h3>
                        <p>In Progress</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $companies->where('approvalProcess.status', 3)->count() }}</h3>
                        <p>Rejected</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Submissions</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search company...">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="submissionsTable">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Company Name</th>
                            <th width="10%">Type</th>
                            <th width="15%">Business Classification</th>
                            <th width="15%">Email</th>
                            <th width="10%">Submitted At</th>
                            <th width="15%">Approval Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $index => $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $company->name }}</strong>
                                    @if($company->group_name)
                                        <br><small class="text-muted">{{ $company->group_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $company->type === 'vendor' ? 'info' : 'success' }}">
                                        {{ ucfirst($company->type) }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($company->business_classification, 30) }}</td>
                                <td>{{ $company->email_address ?? '-' }}</td>
                                <td>
                                    <small>
                                        {{ $company->created_at->format('d M Y') }}
                                        <br>
                                        {{ $company->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    @if($company->hasApproval())
                                        @php
                                            $approval = $company->approvalProcess;
                                            $progress = $approval->getProgressPercentage();
                                        @endphp

                                        @if($approval->isApproved())
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Approved
                                            </span>
                                        @elseif($approval->isRejected())
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times"></i> Rejected
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> In Progress
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                Step {{ $approval->step_ordering }}/{{ $approval->getTotalStepsCount() }}
                                            </small>
                                            <div class="progress progress-xs mt-1">
                                                <div class="progress-bar bg-warning" style="width: {{ $progress }}%"></div>
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-minus"></i> No Approval
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}"
                                       class="btn btn-sm btn-info"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($company->hasApproval())
                                        <a href="{{ route('admin.approvals.company.history', $company->id) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Approval History">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No submissions yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Simple search functionality
        $('#searchInput').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#submissionsTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
@stop

@section('css')
<style>
    .progress-xs {
        height: 7px;
    }
    .small-box .icon {
        opacity: 0.15;
    }
</style>
@stop