@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
        @include('admin.form_links._alpha_styles')
        <style>
            .form-links-page .dataTables_wrapper,
            .form-links-page .dataTables_scroll {
                width: 100%;
            }

            .form-links-page .dataTables_scrollBody {
                scrollbar-width: thin;
            }

            .form-links-page #form_links_table {
                width: 100% !important;
            }
        </style>
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
                <table id="form_links_table" class="table table-bordered table-striped nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Form Type</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Submissions</th>
                            <th>Expires At</th>
                            <th>Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($formLinks as $formLink)
                            <tr>
                                <td class="row-number"></td>
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
                                <td>{{ $formLink->creator->name ?? '-' }}</td>
                                <td data-order="{{ $formLink->created_at->timestamp }}">
                                    {{ $formLink->created_at->format('d M Y H:i') }}
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            const formLinksTable = $('#form_links_table').DataTable({
                scrollX: true,
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                order: [[5, 'desc']],
                columnDefs: [
                    {
                        targets: [0, 8, 9],
                        orderable: false
                    },
                    {
                        targets: [0, 9],
                        searchable: false
                    }
                ],
                drawCallback: function () {
                    const api = this.api();
                    const pageInfo = api.page.info();

                    api.column(0, { page: 'current' }).nodes().each(function (cell, index) {
                        cell.textContent = pageInfo.start + index + 1;
                    });
                },
                language: {
                    emptyTable: 'No form links found',
                    zeroRecords: 'No matching form links found'
                }
            });

            let adjustTimer;

            function adjustFormLinksTable() {
                window.requestAnimationFrame(function () {
                    formLinksTable.columns.adjust();
                });

                window.clearTimeout(adjustTimer);
                adjustTimer = window.setTimeout(function () {
                    formLinksTable.columns.adjust();
                }, 350);
            }

            $('.navigation-toggle').on('click.formLinksTable', adjustFormLinksTable);
            $('.page-content, .page-sidebar').on('transitionend.formLinksTable', adjustFormLinksTable);
            $(window).on('resize.formLinksTable', adjustFormLinksTable);
        });

        function copyLink(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            toastr.success('Link copied to clipboard!');
        }
    </script>
@endpush
