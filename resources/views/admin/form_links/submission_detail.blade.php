@extends('layouts.main')

@section('content')
    @push('styles')
        @include('admin.form_links._alpha_styles')
        <style>
            .form-links-page .approval-summary {
                border-top: 3px solid var(--form-link-primary);
            }

            .form-links-page .approval-progress {
                height: 10px;
                overflow: hidden;
            }

            .form-links-page .approval-progress .progress-bar {
                background: var(--form-link-primary);
            }

            .form-links-page .approval-timeline {
                margin: 0;
                padding: 0;
                position: relative;
            }

            .form-links-page .approval-timeline:before {
                background: var(--form-link-border);
                bottom: 24px;
                content: '';
                left: 17px;
                position: absolute;
                top: 24px;
                width: 2px;
            }

            .form-links-page .approval-step {
                display: grid;
                gap: 16px;
                grid-template-columns: 36px minmax(0, 1fr);
                margin-bottom: 16px;
                position: relative;
            }

            .form-links-page .approval-status-dot {
                align-items: center;
                background: #fff;
                border: 2px solid #a9afbd;
                border-radius: 50%;
                color: #73798a;
                display: flex;
                height: 36px;
                justify-content: center;
                position: relative;
                width: 36px;
                z-index: 1;
            }

            .form-links-page .approval-status-dot.is-approved {
                border-color: #26845f;
                color: #26845f;
            }

            .form-links-page .approval-status-dot.is-rejected {
                border-color: #b84450;
                color: #b84450;
            }

            .form-links-page .approval-status-dot.is-waiting {
                border-color: #9a7413;
                color: #7d5d0d;
            }

            .form-links-page .approval-step-card {
                border: 1px solid var(--form-link-border);
                border-radius: 8px;
                min-width: 0;
                padding: 18px;
            }

            .form-links-page .approval-step-header {
                align-items: flex-start;
                display: flex;
                gap: 12px;
                justify-content: space-between;
                margin-bottom: 14px;
            }

            .form-links-page .approval-step-title {
                color: #20202d;
                font-size: 16px;
                font-weight: 700;
                margin: 0 0 4px;
            }

            .form-links-page .approval-step-meta {
                color: var(--form-link-text-muted);
                font-size: 13px;
            }

            .form-links-page .approval-state {
                background: #f1f2f6;
                border: 1px solid #dfe1e8;
                border-radius: 6px;
                color: #565d6d;
                display: inline-flex;
                font-size: 12px;
                font-weight: 700;
                padding: 5px 8px;
                white-space: nowrap;
            }

            .form-links-page .approval-note {
                background: #f7f7fa;
                border-left: 3px solid #c9cbd5;
                color: #363b49;
                margin-top: 14px;
                padding: 12px 14px;
            }

            .form-links-page .approval-detail-list {
                display: grid;
                gap: 8px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .form-links-page .approval-detail-list p {
                margin: 0;
                overflow-wrap: anywhere;
            }

            @media (max-width: 576px) {
                .form-links-page .approval-step-header,
                .form-links-page .approval-detail-list {
                    display: block;
                }

                .form-links-page .approval-state {
                    margin-top: 8px;
                }
            }
        </style>
    @endpush

    <div class="container-fluid form-links-page">
        <div class="page-heading">
            <div>
                <h2>Submission Detail</h2>
                <p>{{ $company->name }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.form-links.submissions', $formLink) }}" class="btn btn-outline-secondary">
                    <i class="material-icons align-middle mr-1">arrow_back</i> Submissions
                </a>
                <a href="{{ route('admin.form-links.submission-pdf', [$formLink, $company->id]) }}" target="_blank" class="btn btn-danger">
                    <i class="material-icons align-middle mr-1">picture_as_pdf</i> Export PDF
                </a>
            </div>
        </div>

        @if ($company->hasApproval())
            @php
                $approval = $company->approvalProcess;
                $currentStep = $approval->getCurrentStep();
                $progress = $approval->getProgressPercentage();
                $completedSteps = $approval->getCompletedStepsCount();
                $totalSteps = $approval->getTotalStepsCount();
            @endphp

            <div class="card approval-summary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle mr-1"></i> Approval Status
                    </h3>
                    <div class="card-tools">
                        @if ($approval->isPending() || $approval->isInProgress())
                            <span class="approval-state">
                                <i class="fas fa-clock"></i> In Progress
                            </span>
                        @elseif($approval->isApproved())
                            <span class="approval-state">
                                <i class="fas fa-check"></i> Approved
                            </span>
                        @elseif($approval->isRejected())
                            <span class="approval-state">
                                <i class="fas fa-times"></i> Rejected
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Progress: {{ $completedSteps }}/{{ $totalSteps }} steps</span>
                            <span class="text-bold">{{ number_format($progress, 0) }}%</span>
                        </div>
                        <div class="progress approval-progress" aria-label="Approval progress">
                            <div class="progress-bar"
                                role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <h5 class="mb-3">Approval Timeline</h5>
                    <div class="approval-timeline">
                        @foreach ($approval->steps as $step)
                            <div class="approval-step">
                                <span class="approval-status-dot {{ $step->isApproved() ? 'is-approved' : ($step->isRejected() ? 'is-rejected' : ($step->isWaiting() ? 'is-waiting' : '')) }}">
                                    <i class="fas fa-{{ $step->isApproved() ? 'check' : ($step->isRejected() ? 'times' : ($step->isWaiting() ? 'clock' : 'circle')) }}"></i>
                                </span>
                                <div class="approval-step-card">
                                    <div class="approval-step-header">
                                        <div>
                                            <h3 class="approval-step-title">Step {{ $step->step_ordering }} · {{ $step->approver->name ?? 'Unknown' }}</h3>
                                            <span class="approval-step-meta">
                                        @if ($step->isApproved() && $step->approved_at)
                                            <i class="fas fa-clock"></i> {{ $step->approved_at->format('d M Y H:i') }}
                                        @elseif($step->isRejected() && $step->rejected_at)
                                            <i class="fas fa-clock"></i> {{ $step->rejected_at->format('d M Y H:i') }}
                                        @else
                                            <i class="fas fa-clock"></i> {{ $step->created_at->format('d M Y H:i') }}
                                        @endif
                                            </span>
                                        </div>
                                        @if ($step->isApproved())
                                            <span class="approval-state">Approved</span>
                                        @elseif($step->isRejected())
                                            <span class="approval-state">Rejected</span>
                                        @elseif($step->isWaiting())
                                            <span class="approval-state">Waiting for Approval</span>
                                        @else
                                            <span class="approval-state">Pending</span>
                                        @endif
                                    </div>
                                    <div class="approval-detail-list">
                                        <p class="mb-1">
                                            <i class="fas fa-user"></i> <strong>Approver:</strong>
                                            {{ $step->approver->name ?? 'Unknown' }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-envelope"></i> <strong>Email:</strong>
                                            {{ $step->approver->email ?? '-' }}
                                        </p>
                                    </div>

                                    @if ($step->notes)
                                        <div class="approval-note">
                                            <strong><i class="fas fa-comment mr-1"></i> Notes</strong>
                                            <div class="mt-1">{!! $step->notes !!}</div>
                                        </div>
                                    @endif

                                    @if ($step->isWaiting() && auth()->id() === $step->user_id)
                                        <div class="mt-3">
                                            <a href="{{ route('admin.approvals.step.action', $step) }}"
                                                class="btn btn-primary btn-sm form-link-action">
                                                <i class="fas fa-tasks"></i> Take Action
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Submitted By:</th>
                                    <td>{{ $approval->initiator->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{ $approval->department->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $approval->office->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Current Step:</th>
                                    <td>
                                        @if ($currentStep)
                                            Step {{ $currentStep->step_ordering }} - {{ $currentStep->approver->name }}
                                        @else
                                            {{ $approval->isApproved() ? 'Completed' : 'N/A' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Started At:</th>
                                    <td>{{ $approval->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $approval->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- No Approval Process --}}
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>No Approval Process</strong> - This submission does not have an approval process yet.
                @if (auth()->user()->hasRole('super-admin'))
                    This might be because there's no approval template configured for this office and department.
                @endif
            </div>
        @endif

        <!-- Company Information -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Company Information</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Company Type</th>
                        <td>
                            <span class="badge badge-{{ $company->type === 'vendor' ? 'info' : 'success' }}">
                                {{ ucfirst($company->type) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Company Name</th>
                        <td>{{ $company->name }}</td>
                    </tr>
                    <tr>
                        <th>Company Group Name</th>
                        <td>{{ $company->group_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Established Year</th>
                        <td>{{ $company->established_year ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Total Employee</th>
                        <td>{{ $company->total_employee ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Business Classification</th>
                        <td>{{ $company->business_classification }}</td>
                    </tr>
                    <tr>
                        <th>Business Classification Detail</th>
                        <td>{{ $company->business_classification_detail ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tax Register Number</th>
                        <td>{{ $company->register_number_as_in_tax_invoice ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Website Address</th>
                        <td>
                            @if ($company->website_address)
                                <a href="{{ $company->website_address }}"
                                    target="_blank">{{ $company->website_address }}</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Certificate Type</th>
                        <td>{{ $company->system_management ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email Address</th>
                        <td>{{ $company->email_address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Credit Limit</th>
                        <td>{{ $company->credit_limit ? number_format($company->credit_limit, 2) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Term of Payment</th>
                        <td>{{ $company->term_of_payment ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Submitted At</th>
                        <td>{{ $company->created_at->format('d M Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Liable Persons -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Liable Persons</h3>
            </div>
            <div class="card-body">
                @if ($company->liablePeople->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>NIK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->liablePeople as $liable)
                                <tr>
                                    <td>{{ $liable->name }}</td>
                                    <td>{{ $liable->position }}</td>
                                    <td>{{ $liable->nik }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No liable persons recorded</p>
                @endif
            </div>
        </div>

        <!-- Contact Persons -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Contact Persons</h3>
            </div>
            <div class="card-body">
                @if ($company->contact->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Telephone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->contact as $contact)
                                <tr>
                                    <td>{{ $contact->department }}</td>
                                    <td>{{ $contact->position }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->telephone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No contacts recorded</p>
                @endif
            </div>
        </div>

        <!-- Addresses -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Addresses</h3>
            </div>
            <div class="card-body">
                @if ($company->address->isNotEmpty())
                    @foreach ($company->address as $index => $address)
                        <div class="mb-4">
                            <h5>Address {{ $index + 1 }}</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Address</th>
                                    <td>{{ $address->address }}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $address->country }}</td>
                                </tr>
                                <tr>
                                    <th>Province</th>
                                    <td>{{ $address->province }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $address->city }}</td>
                                </tr>
                                <tr>
                                    <th>Postal Code</th>
                                    <td>{{ $address->zip_code }}</td>
                                </tr>
                                <tr>
                                    <th>Telephone</th>
                                    <td>{{ $address->telephone }}</td>
                                </tr>
                                <tr>
                                    <th>Fax</th>
                                    <td>{{ $address->fax ?? '-' }}</td>
                                </tr>
                                @if ($address->latitude && $address->longitude)
                                    <tr>
                                        <th>Coordinates</th>
                                        <td>
                                            Lat: {{ $address->latitude }}, Long: {{ $address->longitude }}
                                            <br>
                                            <a href="https://www.google.com/maps?q={{ $address->latitude }},{{ $address->longitude }}"
                                                target="_blank" class="btn btn-sm btn-primary mt-2">
                                                <i class="fas fa-map-marker-alt"></i> View on Map
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No addresses recorded</p>
                @endif
            </div>
        </div>

        <!-- Banks -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Bank Information</h3>
            </div>
            <div class="card-body">
                @if ($company->bank->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bank Name</th>
                                <th>Account Name</th>
                                <th>Account Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->bank as $bank)
                                <tr>
                                    <td>{{ $bank->name }}</td>
                                    <td>{{ $bank->account_name }}</td>
                                    <td>{{ $bank->account_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No bank information recorded</p>
                @endif
            </div>
        </div>

        <!-- Survey Data (only for customer) -->
        @if ($company->type === 'customer' && $company->salesSurvey)
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Survey Data</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Ownership Status</th>
                            <td>
                                @if ($company->salesSurvey->ownership_status)
                                    <span
                                        class="badge badge-{{ $company->salesSurvey->ownership_status === 'own' ? 'success' : 'info' }}">
                                        {{ ucfirst($company->salesSurvey->ownership_status) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @if ($company->salesSurvey->rental_year)
                            <tr>
                                <th>Rental Year</th>
                                <td>{{ $company->salesSurvey->rental_year }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Pick Up</th>
                            <td>{{ $company->salesSurvey->pick_up ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Truck</th>
                            <td>{{ $company->salesSurvey->truck ?? '-' }}</td>
                        </tr>
                    </table>

                    @if ($company->productCustomers->isNotEmpty())
                        <h5 class="mt-4">Products</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Merk</th>
                                    <th>Distributor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->productCustomers as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->merk ?? '-' }}</td>
                                        <td>{{ $product->distributor ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif

        <!-- Attachments -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">File Attachments</h3>
            </div>
            <div class="card-body">
                @if ($company->attachment->isNotEmpty())
                    <div class="row">
                        @foreach ($company->attachment as $file)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        @if (strpos($file->mime_type ?? $file->filetype, 'image/') === 0)
                                            <img src="{{ route('admin.company-attachments.show', $file) }}"
                                                alt="{{ $file->original_name ?? $file->filename }}" class="img-fluid mb-2"
                                                style="max-height: 150px;">
                                        @else
                                            <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                                        @endif
                                        <p class="small mb-2">{{ $file->original_name ?? $file->filename }}</p>
                                        <p class="small text-muted">{{ number_format($file->filesize / 1024, 2) }} KB</p>
                                        <a href="{{ route('admin.company-attachments.download', $file) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No attachments</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
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

        .timeline>div {
            margin-bottom: 15px;
            position: relative;
        }

        .timeline>div>.time-label>span {
            font-weight: 600;
            padding: 5px;
            display: inline-block;
            background-color: #fff;
            border-radius: 4px;
        }

        .timeline>div>.timeline-item {
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
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

        .timeline>div>.fa,
        .timeline>div>.fas,
        .timeline>div>.far,
        .timeline>div>.fab,
        .timeline>div>.fal,
        .timeline>div>.fad,
        .timeline>div>.ion {
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

        .timeline>div>.timeline-item>.time {
            color: #999;
            float: right;
            padding: 10px;
            font-size: 12px;
        }

        .timeline>div>.timeline-item>.timeline-header {
            margin: 0;
            color: #555;
            border-bottom: 1px solid #f4f4f4;
            padding: 10px;
            font-size: 16px;
            line-height: 1.1;
        }

        .timeline>div>.timeline-item>.timeline-body {
            padding: 10px;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
        }

        .bg-gray {
            background-color: #d2d6de !important;
        }
    </style>
@endpush
