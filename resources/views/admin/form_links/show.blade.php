@extends('adminlte::page')

@section('title', 'Form Link Details')

@section('content_header')
    <h1>Form Link Details</h1>
@stop

@section('content')
    <div class="container-fluid">
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
                        <h3 class="card-title">Form Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Title</th>
                                <td>{{ $formLink->title }}</td>
                            </tr>
                            <tr>
                                <th>Form Type</th>
                                <td>
                                    <span class="badge badge-{{ $formLink->form_type === 'vendor' ? 'info' : 'success' }}">
                                        {{ ucfirst($formLink->form_type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($formLink->is_active && !$formLink->isExpired())
                                        <span class="badge badge-success">Active</span>
                                    @elseif($formLink->isExpired())
                                        <span class="badge badge-warning">Expired</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $formLink->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Submissions</th>
                                <td>
                                    <strong>{{ $formLink->submission_count }}</strong>
                                    @if ($formLink->max_submissions)
                                        / {{ $formLink->max_submissions }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Expires At</th>
                                <td>
                                    @if ($formLink->expires_at)
                                        {{ $formLink->expires_at->format('d M Y H:i') }}
                                    @else
                                        <span class="text-muted">No expiration</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $formLink->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Public Link</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Share this link with recipients:</label>
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

                        <div class="form-group">
                            <label>QR Code:</label>
                            <div class="text-center">
                                <div id="qrcode"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Actions</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('admin.form-links.edit', $formLink) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Edit Form Link
                        </a>
                        <a href="{{ route('admin.form-links.submissions', $formLink) }}" class="btn btn-info btn-block">
                            <i class="fas fa-list"></i> View Submissions ({{ $formLink->submission_count }})
                        </a>
                        <form action="{{ route('admin.form-links.toggle-status', $formLink) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-{{ $formLink->is_active ? 'secondary' : 'success' }} btn-block">
                                <i class="fas fa-{{ $formLink->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                {{ $formLink->is_active ? 'Deactivate' : 'Activate' }} Form
                            </button>
                        </form>
                        <a href="{{ $formLink->getPublicUrl() }}" target="_blank" class="btn btn-primary btn-block">
                            <i class="fas fa-external-link-alt"></i> Open Public Form
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Submissions</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Type</th>
                                    <th>Email</th>
                                    <th>Submitted At</th>
                                    <th>Actions</th>
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
                                        <td>{{ $company->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No submissions yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        #qrcode {
            display: inline-block;
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
@stop

@section('js')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script> --}}
    <script src="{{ asset('js/cdn/qrcode.js') }}"></script>
    <script>
        function copyPublicLink() {
            var copyText = document.getElementById("public-link");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            toastr.success('Link copied to clipboard!');
        }

        // Generate QR Code
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $formLink->getPublicUrl() }}",
            width: 200,
            height: 200
        });
    </script>
@stop
