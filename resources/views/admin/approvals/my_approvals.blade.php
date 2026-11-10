@extends('layouts.main')

@push('styles')
    @include('admin.form_links._alpha_styles')
    <style>
        .approval-inbox-page .stat-card { align-items: center; display: flex; justify-content: space-between; }
        .approval-inbox-page .stat-card__label { font-size: 14px; font-weight: 600; opacity: .92; }
        .approval-inbox-page .stat-card .material-icons { position: static; }
        .approval-inbox-page .inbox-card-header { gap: 20px; }
        .approval-inbox-page .inbox-search { max-width: 320px; width: 100%; }
        .approval-inbox-page .inbox-table-wrap { overflow-x: auto; }
        .approval-inbox-page #pendingApprovalsTable { margin-bottom: 0; min-width: 1040px; }
        .approval-inbox-page .company-name { color: #20202d; font-weight: 700; }
        .approval-inbox-page .approval-progress { max-width: 130px; }
        .approval-inbox-page .approval-actions { display: flex; gap: 8px; }
        .approval-inbox-page .approval-actions .btn {
            align-items: center;
            border-radius: 7px;
            display: inline-flex;
            justify-content: center;
            min-height: 38px;
            white-space: nowrap;
        }
        .approval-inbox-page .approval-actions .btn-icon { width: 40px; }
        .approval-inbox-page .empty-inbox { padding: 56px 24px !important; }
        .approval-inbox-page .empty-inbox .material-icons { color: #31b77a; font-size: 48px; }
        .approval-inbox-page .stat-companies { background: #6c5dd3; }

        @media (max-width: 991.98px) {
            .approval-inbox-page .form-link-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 767px) {
            .approval-inbox-page .inbox-card-header { align-items: stretch; flex-direction: column; }
            .approval-inbox-page .inbox-search { max-width: none; }
            .approval-inbox-page .approval-actions .btn { min-height: 44px; }
        }

        @media (max-width: 575.98px) {
            .approval-inbox-page .form-link-stats { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    @php
        $customerCount = $pendingSteps->filter(fn ($step) => optional($step->process->company)->type === 'customer')->count();
        $vendorCount = $pendingSteps->filter(fn ($step) => optional($step->process->company)->type === 'vendor')->count();
        $companyCount = $pendingSteps->pluck('process.company.id')->filter()->unique()->count();
    @endphp

    <div class="container-fluid form-links-page submissions-page approval-inbox-page">
        <div class="page-heading">
            <div>
                <h2>My Pending Approvals</h2>
                <p>Review partner submissions currently waiting for your decision.</p>
            </div>
        </div>

        <div class="form-link-stats" aria-label="Pending approval summary">
            <div class="stat-card stat-total">
                <div><h3>{{ $pendingSteps->count() }}</h3><span class="stat-card__label">Pending Actions</span></div>
                <i class="material-icons" aria-hidden="true">assignment_late</i>
            </div>
            <div class="stat-card stat-approved">
                <div><h3>{{ $customerCount }}</h3><span class="stat-card__label">Customer</span></div>
                <i class="material-icons" aria-hidden="true">groups</i>
            </div>
            <div class="stat-card stat-progress">
                <div><h3>{{ $vendorCount }}</h3><span class="stat-card__label">Vendor</span></div>
                <i class="material-icons" aria-hidden="true">storefront</i>
            </div>
            <div class="stat-card stat-companies">
                <div><h3>{{ $companyCount }}</h3><span class="stat-card__label">Companies</span></div>
                <i class="material-icons" aria-hidden="true">business</i>
            </div>
        </div>

        <div class="card">
            <div class="card-header inbox-card-header">
                <h3 class="card-title">Assigned Submissions</h3>
                <div class="input-group input-group-sm inbox-search">
                    <input type="search" id="approvalSearchInput" class="form-control"
                        placeholder="Search company or type" aria-label="Search pending approvals">
                    <div class="input-group-append" aria-hidden="true">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>

            <div class="inbox-table-wrap">
                <table class="table table-hover" id="pendingApprovalsTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Company Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Business Classification</th>
                            <th scope="col">Submitted At</th>
                            <th scope="col">Approval Status</th>
                            <th scope="col">Waiting Since</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingSteps as $index => $step)
                            @php
                                $process = $step->process;
                                $company = $process->company;
                                $progress = $process->getProgressPercentage();
                                $completedSteps = $process->getCompletedStepsCount();
                                $totalSteps = $process->getTotalStepsCount();
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="company-name">{{ $company->name }}</span>
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
                                <td>
                                    {{ optional($company->created_at)->format('d M Y') ?? '-' }}
                                    @if($company->created_at)
                                        <br><small class="text-muted">{{ $company->created_at->format('H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-warning"><i class="fas fa-clock" aria-hidden="true"></i> Waiting for You</span>
                                    <small class="d-block text-muted mt-1">Progress {{ $completedSteps }}/{{ $totalSteps }}</small>
                                    <div class="progress progress-xs mt-1 approval-progress" aria-label="Approval progress {{ round($progress) }}%">
                                        <div class="progress-bar bg-warning" style="width: {{ $progress }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    {{ optional($step->created_at)->diffForHumans() ?? '-' }}
                                    @if($step->created_at)
                                        <br><small class="text-muted">{{ $step->created_at->format('d M Y H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="approval-actions">
                                        <a href="{{ route('admin.form-links.submission-detail', [$company->form_link_id, $company->id]) }}"
                                            class="btn btn-info btn-icon form-link-action"
                                            aria-label="View submission details for {{ $company->name }}" title="View details">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('admin.approvals.step.action', $step) }}"
                                            class="btn btn-primary form-link-action"
                                            aria-label="Review submission for {{ $company->name }}">
                                            <i class="fas fa-tasks mr-1" aria-hidden="true"></i> Review
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted empty-inbox">
                                    <i class="material-icons d-block mb-2" aria-hidden="true">task_alt</i>
                                    <strong>No pending approvals</strong>
                                    <p class="mb-0 mt-1">New submissions assigned to you will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#approvalSearchInput').on('input', function () {
                const value = $(this).val().toLowerCase();
                $('#pendingApprovalsTable tbody tr').each(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) !== -1);
                });
            });
        });
    </script>
@endpush
