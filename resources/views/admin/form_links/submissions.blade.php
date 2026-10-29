@extends('layouts.main')

@push('styles')
    @include('admin.form_links._alpha_styles')
    <style>
        .submissions-page .stat-card { align-items: center; display: flex; justify-content: space-between; }
        .submissions-page .stat-card__label { font-size: 14px; font-weight: 600; opacity: .92; }
        .submissions-page .stat-card .material-icons { position: static; }
        .submissions-page .submissions-card-header { gap: 20px; }
        .submissions-page .submission-search { max-width: 320px; width: 100%; }
        .submissions-page .submissions-table-wrap { overflow-x: auto; }
        .submissions-page #submissionsTable { margin-bottom: 0; min-width: 1040px; }
        .submissions-page .company-name { color: #20202d; font-weight: 700; }
        .submissions-page .approval-progress { max-width: 130px; }
        .submissions-page .submission-actions { display: flex; gap: 8px; }
        .submissions-page .submission-actions .btn {
            align-items: center;
            border-radius: 7px;
            display: inline-flex;
            height: 36px;
            justify-content: center;
            width: 38px;
        }
        .submissions-page .empty-submissions { padding: 56px 24px !important; }
        .submissions-page .empty-submissions .material-icons { color: #707386; font-size: 48px; }

        @media (max-width: 767px) {
            .submissions-page .submissions-card-header { align-items: stretch; flex-direction: column; }
            .submissions-page .submission-search { max-width: none; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid form-links-page submissions-page">
        <div class="page-heading">
            <div>
                <h2>Form Submissions</h2>
                <p>{{ $formLink->title }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.form-links.show', $formLink) }}" class="btn btn-outline-secondary">
                    <i class="material-icons align-middle mr-1" aria-hidden="true">arrow_back</i> Form Link
                </a>
            </div>
        </div>

        <div class="form-link-stats" aria-label="Submission summary">
            <div class="stat-card stat-total">
                <div><h3>{{ $submissionStats['total'] }}</h3><span class="stat-card__label">Total Submissions</span></div>
                <i class="material-icons" aria-hidden="true">description</i>
            </div>
            <div class="stat-card stat-approved">
                <div><h3>{{ $submissionStats['approved'] }}</h3><span class="stat-card__label">Approved</span></div>
                <i class="material-icons" aria-hidden="true">check_circle</i>
            </div>
            <div class="stat-card stat-progress">
                <div><h3>{{ $submissionStats['in_progress'] }}</h3><span class="stat-card__label">In Progress</span></div>
                <i class="material-icons" aria-hidden="true">schedule</i>
            </div>
            <div class="stat-card stat-rejected">
                <div><h3>{{ $submissionStats['rejected'] }}</h3><span class="stat-card__label">Rejected</span></div>
                <i class="material-icons" aria-hidden="true">cancel</i>
            </div>
        </div>

        <div class="card">
            <div class="card-header submissions-card-header">
                <h3 class="card-title">All Submissions</h3>
                <div class="input-group input-group-sm submission-search">
                    <input type="search" id="searchInput" class="form-control" placeholder="Search company or email" aria-label="Search submissions">
                    <div class="input-group-append" aria-hidden="true"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                </div>
            </div>

            <div class="submissions-table-wrap">
                <table class="table table-hover" id="submissionsTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Company Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Business Classification</th>
                            <th scope="col">Email</th>
                            <th scope="col">Submitted At</th>
                            <th scope="col">Approval Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $index => $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $index }}</td>
                                <td>
                                    <span class="company-name">{{ $company->name }}</span>
                                    @if($company->group_name)<br><small class="text-muted">{{ $company->group_name }}</small>@endif
                                </td>
                                <td><span class="badge badge-{{ $company->type === 'vendor' ? 'info' : 'success' }}">{{ ucfirst($company->type) }}</span></td>
                                <td>{{ Str::limit($company->business_classification, 30) }}</td>
                                <td>{{ $company->email_address ?? '-' }}</td>
                                <td>{{ $company->created_at->format('d M Y') }}<br><small class="text-muted">{{ $company->created_at->format('H:i') }}</small></td>
                                <td>
                                    @if($company->hasApproval())
                                        @php
                                            $approval = $company->approvalProcess;
                                            $progress = $approval->getProgressPercentage();
                                        @endphp
                                        @if($approval->isApproved())
                                            <span class="badge badge-success"><i class="fas fa-check" aria-hidden="true"></i> Approved</span>
                                        @elseif($approval->isRejected())
                                            <span class="badge badge-danger"><i class="fas fa-times" aria-hidden="true"></i> Rejected</span>
                                        @else
                                            <span class="badge badge-warning"><i class="fas fa-clock" aria-hidden="true"></i> In Progress</span>
                                            <small class="d-block text-muted mt-1">Step {{ $approval->step_ordering }}/{{ $approval->getTotalStepsCount() }}</small>
                                            <div class="progress progress-xs mt-1 approval-progress" aria-label="Approval progress {{ round($progress) }}%">
                                                <div class="progress-bar bg-warning" style="width: {{ $progress }}%"></div>
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-minus" aria-hidden="true"></i> No Approval</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="submission-actions">
                                        <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}"
                                            class="btn btn-info form-link-action" aria-label="View submission details for {{ $company->name }}" title="View details">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </a>
                                        @if($company->hasApproval())
                                            <a href="{{ route('admin.approvals.company.history', $company->id) }}"
                                                class="btn btn-primary form-link-action" aria-label="View approval history for {{ $company->name }}" title="Approval history">
                                                <i class="fas fa-history" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted empty-submissions">
                                    <i class="material-icons d-block mb-2" aria-hidden="true">inbox</i>
                                    <strong>No submissions yet</strong>
                                    <p class="mb-0 mt-1">Submitted partner registrations will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($companies->hasPages())
                <div class="card-footer clearfix">{{ $companies->links() }}</div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#searchInput').on('input', function () {
                const value = $(this).val().toLowerCase();
                $('#submissionsTable tbody tr').each(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) !== -1);
                });
            });
        });
    </script>
@endpush
