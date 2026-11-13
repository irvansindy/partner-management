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

            .form-links-page .submission-detail-actions {
                align-items: center;
                display: flex;
                flex-wrap: nowrap;
                gap: 10px;
            }

            .form-links-page .submission-header-action {
                align-items: center;
                border-radius: 8px;
                box-shadow: 0 3px 7px rgba(32, 32, 45, .18);
                display: inline-flex;
                font-size: 14px;
                font-weight: 700;
                height: 48px;
                justify-content: center;
                letter-spacing: .04em;
                line-height: 1;
                padding: 0 22px;
                text-transform: uppercase;
                white-space: nowrap;
            }

            .form-links-page .submission-header-action .material-icons {
                font-size: 22px;
                line-height: 1;
                margin-right: 7px;
            }

            .form-links-page .submission-header-action.btn-outline-secondary {
                box-shadow: none;
            }

            .form-links-page .submission-tabs-shell {
                background: #fff;
                border: 1px solid var(--form-link-border);
                border-radius: 8px;
                margin-bottom: 20px;
                overflow-x: auto;
                scrollbar-width: thin;
            }

            .form-links-page .submission-tabs {
                border: 0;
                display: flex;
                flex-wrap: nowrap;
                min-width: max-content;
                padding: 0 12px;
            }

            .form-links-page .submission-tabs .nav-link {
                border: 0;
                border-bottom: 3px solid transparent;
                border-radius: 0;
                color: #5f6677;
                font-weight: 700;
                min-height: 50px;
                padding: 15px 16px 12px;
                white-space: nowrap;
            }

            .form-links-page .submission-tabs .nav-link:hover {
                color: #3f35a8;
            }

            .form-links-page .submission-tabs .nav-link.active {
                background: transparent;
                border-bottom-color: var(--form-link-primary);
                color: #3f35a8;
            }

            .form-links-page .submission-tabs .nav-link:focus-visible {
                outline: 3px solid #8c82eb;
                outline-offset: -3px;
            }

            .form-links-page .submission-tab-pane > .card {
                margin-bottom: 0;
            }

            .form-links-page .attachment-column {
                margin-bottom: 16px;
            }

            .form-links-page .attachment-card {
                border: 1px solid var(--form-link-border);
                box-shadow: none;
                margin: 0;
            }

            .form-links-page .attachment-card-body {
                display: flex;
                flex-direction: column;
                height: 100%;
                padding: 18px;
                text-align: center;
            }

            .form-links-page .attachment-preview {
                align-items: center;
                display: flex;
                height: 180px;
                justify-content: center;
                margin-bottom: 14px;
                overflow: hidden;
                width: 100%;
            }

            .form-links-page .attachment-preview img {
                height: 100%;
                object-fit: contain;
                width: 100%;
            }

            .form-links-page .attachment-preview .fas {
                font-size: 64px;
                margin: 0;
            }

            .form-links-page .attachment-name {
                display: -webkit-box;
                min-height: 42px;
                overflow: hidden;
                overflow-wrap: anywhere;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }

            .form-links-page .attachment-download {
                align-self: center;
                margin-top: auto;
            }

            @media (max-width: 576px) {
                .form-links-page .approval-step-header,
                .form-links-page .approval-detail-list {
                    display: block;
                }

                .form-links-page .approval-state {
                    margin-top: 8px;
                }

                .form-links-page .submission-detail-actions {
                    align-items: stretch;
                    flex-direction: column;
                    width: 100%;
                }

                .form-links-page .submission-header-action {
                    width: 100%;
                }

                .form-links-page .submission-tabs .nav-link {
                    padding-left: 13px;
                    padding-right: 13px;
                }
            }
        </style>
    @endpush

    <div class="container-fluid form-links-page">
        <div class="page-heading">
            <div>
                <h2>{{ __('admin.Submission Detail') }}</h2>
                <p>{{ $company->name }}</p>
            </div>
            <div class="page-actions submission-detail-actions">
                @if ($canManageFormLink)
                    <a href="{{ route('admin.form-links.submissions', $formLink) }}" class="btn btn-outline-secondary submission-header-action">
                        <i class="material-icons" aria-hidden="true">arrow_back</i> {{ __('admin.Submissions') }}
                    </a>
                @elseif ($company->approvalProcess)
                    <a href="{{ route('admin.approvals.show', $company->approvalProcess) }}" class="btn btn-outline-secondary submission-header-action">
                        <i class="material-icons" aria-hidden="true">arrow_back</i> {{ __('admin.Approval') }}
                    </a>
                @else
                    <a href="{{ route('admin.approvals.my') }}" class="btn btn-outline-secondary submission-header-action">
                        <i class="material-icons" aria-hidden="true">arrow_back</i> {{ __('form_links_audit.My Approvals') }}
                    </a>
                @endif
                @if ($canEditSubmission)
                    <a href="{{ route('admin.form-links.submission-edit', [$formLink, $company->id]) }}"
                        class="btn btn-primary submission-header-action">
                        <i class="material-icons" aria-hidden="true">edit</i> {{ __('admin.Edit Submission') }}
                    </a>
                @endif
                <a href="{{ route('admin.form-links.submission-pdf', [$formLink, $company->id]) }}" target="_blank" rel="noopener noreferrer" class="btn btn-danger submission-header-action">
                    <i class="material-icons" aria-hidden="true">picture_as_pdf</i> {{ __('tools.Export PDF') }}
                </a>
            </div>
        </div>

        @include('admin.form_links._submission_detail_panels')
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

@if (session('success'))
    @push('scripts')
        <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script>
            $(function () {
                Swal.fire({
                    icon: 'success',
                    title: @json(__('form_links.Perubahan berhasil disimpan')),
                    text: @json(session('success')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6558d3'
                });
            });
        </script>
    @endpush
@endif
