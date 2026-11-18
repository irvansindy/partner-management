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
            .form-links-page .form-link-stats .stat-card { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
            .form-links-page .form-link-stats .material-icons { position: static; }
            .form-links-page .stat-card__label { font-size: 14px; font-weight: 600; }
        </style>
    @endpush

    <div class="container-fluid form-links-page">
        <div class="page-heading">
            <div>
                <h2>{{ __('admin.Form Links Management') }}</h2>
                <p>{{ __('admin.Create and manage public partner registration links.') }}</p>
            </div>
            @if (auth()->user()->hasRole('super-user'))
                <div class="page-actions">
                    <a href="{{ route('admin.form-links.create') }}" class="btn btn-primary waves-effect">
                        <i class="material-icons align-middle mr-1">add_link</i> {{ __('admin.Create Form Link') }}
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

        @php
            $expiredForms = $formLinks->filter(fn ($link) => $link->isExpired())->count();
            $activeForms = $formLinks->filter(fn ($link) => $link->is_active && !$link->isExpired())->count();
            $summaryCards = [
                ['label' => __('form_links.summary_total'), 'value' => $formLinks->count(), 'class' => 'stat-total', 'icon' => 'link'],
                ['label' => __('admin.Active'), 'value' => $activeForms, 'class' => 'stat-approved', 'icon' => 'check_circle'],
                ['label' => __('admin.Inactive'), 'value' => $formLinks->count() - $activeForms - $expiredForms, 'class' => 'stat-rejected', 'icon' => 'pause_circle_filled'],
                ['label' => __('admin.Expired'), 'value' => $expiredForms, 'class' => 'stat-progress', 'icon' => 'schedule'],
            ];
        @endphp
        <div class="form-link-stats" aria-label="{{ __('form_links.summary_label') }}">
            @foreach($summaryCards as $summary)
                <div class="stat-card {{ $summary['class'] }}">
                    <div>
                        <h3>{{ $summary['value'] }}</h3>
                        <span class="stat-card__label">{{ $summary['label'] }}</span>
                    </div>
                    <i class="material-icons" aria-hidden="true">{{ $summary['icon'] }}</i>
                </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-body">
                <table id="form_links_table" class="table table-bordered table-striped nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>{{ __('admin.Title') }}</th>
                            <th>{{ __('admin.Form Type') }}</th>
                            <th>{{ __('admin.Status') }}</th>
                            <th>{{ __('admin.Created By') }}</th>
                            <th>{{ __('admin.Created At') }}</th>
                            <th>{{ __('admin.Submissions') }}</th>
                            <th>{{ __('admin.Expires At') }}</th>
                            <th>{{ __('admin.Link') }}</th>
                            <th>{{ __('admin.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($formLinks as $formLink)
                            <tr>
                                <td class="row-number"></td>
                                <td>{{ $formLink->title }}</td>
                                <td>
                                    <span class="badge badge-{{ $formLink->form_type === 'vendor' ? 'info' : 'success' }}">
                                        {{ __('admin.' . ucfirst($formLink->form_type ?? '')) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($formLink->is_active && !$formLink->isExpired())
                                        <span class="badge badge-success">{{ __('admin.Active') }}</span>
                                    @elseif($formLink->isExpired())
                                        <span class="badge badge-warning">{{ __('admin.Expired') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('admin.Inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ $formLink->creator->name ?? '-' }}</td>
                                <td data-order="{{ $formLink->created_at->timestamp }}">
                                    {{ $formLink->created_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}
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
                                        {{ $formLink->expires_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}
                                    @else
                                        <span class="text-muted">{{ __('admin.No expiry') }}</span>
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
                                                class="table-action table-action--copy" title="{{ __('form_links.copy_link') }}"
                                                aria-label="{{ __('form_links.copy_named', ['name' => $formLink->title]) }}">
                                                <i class="fas fa-copy" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-actions" role="group"
                                        aria-label="{{ __('form_links.actions_for', ['name' => $formLink->title]) }}">
                                        <a href="{{ route('admin.form-links.show', $formLink) }}"
                                            class="table-action table-action--view" title="{{ __('form_links.view_link') }}"
                                            aria-label="{{ __('form_links.view_named', ['name' => $formLink->title]) }}">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </a>
                                        @if (auth()->user()->hasRole('super-user'))
                                        <a href="{{ route('admin.form-links.edit', $formLink) }}"
                                            class="table-action table-action--edit" title="{{ __('form_links.edit_link') }}"
                                            aria-label="{{ __('form_links.edit_named', ['name' => $formLink->title]) }}">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <form action="{{ route('admin.form-links.toggle-status', $formLink) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="table-action table-action--toggle{{ $formLink->is_active ? ' is-inactive' : '' }}"
                                                title="{{ __($formLink->is_active ? 'form_links.deactivate_named' : 'form_links.activate_named', ['name' => $formLink->title]) }}"
                                                aria-label="{{ __($formLink->is_active ? 'form_links.deactivate_named' : 'form_links.activate_named', ['name' => $formLink->title]) }}">
                                                <i
                                                    class="fas fa-{{ $formLink->is_active ? 'toggle-off' : 'toggle-on' }}"
                                                    aria-hidden="true"></i>
                                            </button>
                                        </form>
                                        @endif
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
                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, @json(__('form_links.all'))]],
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
                    search: @json(__('form_links.search')),
                    lengthMenu: @json(__('form_links.length')),
                    info: @json(__('form_links.info')),
                    infoEmpty: @json(__('form_links.info_empty')),
                    infoFiltered: @json(__('form_links.filtered')),
                    paginate: { previous: @json(__('form_links.previous')), next: @json(__('form_links.next')) },
                    emptyTable: @json(__('form_links.No form links found')),
                    zeroRecords: @json(__('form_links.No matching form links found'))
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

            toastr.success(@json(__('form_links.Link copied to clipboard!')));
        }
    </script>
@endpush
