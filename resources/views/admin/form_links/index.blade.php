@extends('layouts.main')

@section('content')
    @push('styles')
        @include('admin.form_links._alpha_styles')
    @endpush

    <div class="container-fluid form-links-page">
        <div class="page-heading">
            <div>
                <h2>Form Links Management</h2>
                <p>Create and manage public partner registration links.</p>
            </div>
            @if (\Auth::user()->roles[0]->name == 'admin' || \Auth::user()->roles[0]->name == 'super-user')
                <div class="page-actions">
                    <a href="{{ route('admin.form-links.create') }}" class="btn btn-primary waves-effect">
                        <i class="material-icons align-middle mr-1">add_link</i> Create Form Link
                    </a>
                </div>
            @endif
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
                                            <button type="button"
                                                onclick="copyLink('link-{{ $formLink->id }}')"
                                                class="table-action table-action--copy" title="Copy public link"
                                                aria-label="Copy public link for {{ $formLink->title }}">
                                                <i class="fas fa-copy" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-actions" role="group"
                                        aria-label="Actions for {{ $formLink->title }}">
                                        <a href="{{ route('admin.form-links.show', $formLink) }}"
                                            class="table-action table-action--view" title="View form link"
                                            aria-label="View {{ $formLink->title }}">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('admin.form-links.edit', $formLink) }}"
                                            class="table-action table-action--edit" title="Edit form link"
                                            aria-label="Edit {{ $formLink->title }}">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <form action="{{ route('admin.form-links.toggle-status', $formLink) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="table-action table-action--toggle{{ $formLink->is_active ? ' is-inactive' : '' }}"
                                                title="{{ $formLink->is_active ? 'Deactivate' : 'Activate' }} form link"
                                                aria-label="{{ $formLink->is_active ? 'Deactivate' : 'Activate' }} {{ $formLink->title }}">
                                                <i
                                                    class="fas fa-{{ $formLink->is_active ? 'toggle-off' : 'toggle-on' }}"
                                                    aria-hidden="true"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.form-links.destroy', $formLink) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="table-action table-action--delete"
                                                title="Delete form link" aria-label="Delete {{ $formLink->title }}">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
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
@endsection

@push('scripts')
    <script>
        function copyLink(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            toastr.success('Link copied to clipboard!');
        }
    </script>
@endpush
