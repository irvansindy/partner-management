@extends('layouts.main')

@section('content')
    @push('styles')
        @include('admin.form_links._alpha_styles')
        <style>
            .form-links-page .public-link-field label,
            .form-links-page .qr-code-field label {
                display: block;
                margin: 0 0 8px;
                position: static;
                transform: none;
            }

            .form-links-page .public-link-field .input-group {
                align-items: stretch;
            }

            .form-links-page .public-link-field .form-control {
                min-width: 0;
                text-overflow: ellipsis;
            }

            .form-links-page .public-link-field .btn {
                white-space: nowrap;
            }

            .form-links-page .qr-code-field #qrcode {
                display: inline-block;
                padding: 12px;
                border: 1px solid var(--form-link-border);
                border-radius: 8px;
                background: #fff;
                line-height: 0;
            }

            .form-links-page .form-link-actions {
                display: grid;
                gap: 10px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .form-links-page .form-link-actions form {
                margin: 0;
            }

            .form-links-page .form-detail-action {
                align-items: center;
                border-radius: 7px;
                display: inline-flex;
                font-weight: 600;
                gap: 7px;
                justify-content: center;
                min-height: 44px;
                padding: 10px 14px;
                width: 100%;
            }

            .form-links-page .form-detail-action:focus-visible {
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .18);
                outline: 2px solid var(--form-link-primary);
                outline-offset: 2px;
            }

            @media (max-width: 575.98px) {
                .form-links-page .form-link-actions {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    <div class="container-fluid form-links-page">
        <div class="page-heading">
            <div>
                <h2>{{ __('form_links.Form Link Details') }}</h2>
                <p>{{ __('submission.Review configuration, public access, and recent submissions.') }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.form-links.index') }}" class="btn btn-outline-secondary">
                    <i class="material-icons align-middle mr-1">arrow_back</i> Form Links
                </a>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('submission.Form Information') }}</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">{{ __('admin.Title') }}</th>
                                <td>{{ $formLink->title }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.Form Type') }}</th>
                                <td>
                                    <span class="badge badge-{{ $formLink->form_type === 'vendor' ? 'info' : 'success' }}">
                                        {{ ucfirst($formLink->form_type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.Status') }}</th>
                                <td>
                                    @if ($formLink->is_active && !$formLink->isExpired())
                                        <span class="badge badge-success">{{ __('admin.Active') }}</span>
                                    @elseif($formLink->isExpired())
                                        <span class="badge badge-warning">{{ __('admin.Expired') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('admin.Inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.Description') }}</th>
                                <td>{{ $formLink->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.Submissions') }}</th>
                                <td>
                                    <strong>{{ $formLink->submission_count }}</strong>
                                    @if ($formLink->max_submissions)
                                        / {{ $formLink->max_submissions }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.Expires At') }}</th>
                                <td>
                                    @if ($formLink->expires_at)
                                        {{ $formLink->expires_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}
                                    @else
                                        <span class="text-muted">{{ __('submission.No expiration') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('admin.Created At') }}</th>
                                <td>{{ $formLink->created_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('submission.Public Link') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group public-link-field">
                            <label for="public-link">{{ __('submission.Share this link with recipients:') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $formLink->getPublicUrl() }}"
                                    id="public-link" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyPublicLink()">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group qr-code-field">
                            <label>{{ __('submission.QR Code:') }}</label>
                            <div class="text-center">
                                <div id="qrcode"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('admin.Actions') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-link-actions">
                            <a href="{{ route('admin.form-links.submissions', $formLink) }}"
                                class="btn btn-primary form-detail-action">
                                <i class="fas fa-list" aria-hidden="true"></i>
                                <span>{{ __('admin.Submissions') }} ({{ $formLink->submission_count }})</span>
                            </a>
                            <a href="{{ route('admin.form-links.edit', $formLink) }}"
                                class="btn btn-outline-secondary form-detail-action">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                                <span>Edit form</span>
                            </a>
                            <a href="{{ $formLink->getPublicUrl() }}" target="_blank" rel="noopener noreferrer"
                                class="btn btn-outline-secondary form-detail-action">
                                <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                <span>Open public form</span>
                            </a>
                            <form action="{{ route('admin.form-links.toggle-status', $formLink) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary form-detail-action">
                                    <i class="fas fa-{{ $formLink->is_active ? 'toggle-off' : 'toggle-on' }}" aria-hidden="true"></i>
                                    <span>{{ __($formLink->is_active ? 'form_links.deactivate_named' : 'form_links.activate_named', ['name' => $formLink->title]) }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('submission.Recent Submissions') }}</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.Company Name') }}</th>
                                    <th>{{ __('admin.Type') }}</th>
                                    <th>Email</th>
                                    <th>{{ __('admin.Submitted At') }}</th>
                                    <th>{{ __('admin.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($formLink->companies->take(10) as $company)
                                    <tr>
                                        <td>{{ $company->name }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $company->type === 'vendor' ? 'info' : 'success' }}">
                                                {{ ucfirst($company->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $company->email_address }}</td>
                                        <td>{{ $company->created_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('submission.No submissions yet') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script> --}}
    <script src="{{ asset('js/cdn/qrcode.js') }}"></script>
    <script>
        function copyPublicLink() {
            var copyText = document.getElementById("public-link");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            toastr.success(@json(__('form_links.Link copied to clipboard!')));
        }

        // Generate QR Code
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $formLink->getPublicUrl() }}",
            width: 200,
            height: 200
        });
    </script>
@endpush
