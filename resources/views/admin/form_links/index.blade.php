@extends('adminlte::page')

@section('title', 'Form Links Management')

@section('content_header')
    <h1>Form Links Management</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.form-links.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Form Link
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Form Type</th>
                            <th>Status</th>
                            <th>Submissions</th>
                            <th>Expires At</th>
                            <th>Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($formLinks as $formLink)
                            <tr>
                                <td>{{ $formLink->id }}</td>
                                <td>{{ $formLink->title }}</td>
                                <td>
                                    <span class="badge badge-{{ $formLink->form_type === 'vendor' ? 'info' : 'success' }}">
                                        {{ ucfirst($formLink->form_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($formLink->is_active && !$formLink->isExpired())
                                        <span class="badge badge-success">Active</span>
                                    @elseif($formLink->isExpired())
                                        <span class="badge badge-warning">Expired</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $formLink->submission_count }}
                                        @if ($formLink->max_submissions)
                                            / {{ $formLink->max_submissions }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if ($formLink->expires_at)
                                        {{ $formLink->expires_at->format('d M Y H:i') }}
                                    @else
                                        <span class="text-muted">No expiry</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $formLink->getPublicUrl() }}" id="link-{{ $formLink->id }}"
                                            readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="copyLink('link-{{ $formLink->id }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.form-links.show', $formLink) }}" class="mx-1 btn btn-info"
                                            title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.form-links.edit', $formLink) }}" class="mx-1 btn btn-warning"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.form-links.toggle-status', $formLink) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="mx-1 btn btn-{{ $formLink->is_active ? 'secondary' : 'success' }}"
                                                title="{{ $formLink->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i
                                                    class="fas fa-{{ $formLink->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.form-links.destroy', $formLink) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-1 btn btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No form links found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $formLinks->links() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        function copyLink(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            toastr.success('Link copied to clipboard!');
        }
    </script>
@stop
