@extends('layouts.main')

@section('content')
    @push('styles')
        <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet">
        <style>
            .division-page {
                --division-primary: #6558d3;
                --division-primary-soft: #efedff;
                --division-line: #e3e8ef;
                --division-ink: #202638;
                --division-muted: #70798b;
            }

            .division-heading {
                align-items: flex-end;
                display: flex;
                justify-content: space-between;
                gap: 20px;
                margin-bottom: 24px;
            }

            .division-heading h1 {
                color: var(--division-ink);
                font-size: 26px;
                font-weight: 700;
                margin: 0 0 5px;
            }

            .division-heading p {
                color: var(--division-muted);
                margin: 0;
            }

            .division-refresh {
                align-items: center;
                display: inline-flex;
                gap: 8px;
                white-space: nowrap;
            }

            .division-heading-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .division-list-panel {
                background: #fff;
                border: 1px solid var(--division-line);
                border-radius: 8px;
                box-shadow: 0 3px 14px rgba(32, 38, 56, .035);
                overflow: hidden;
            }

            .division-list-header {
                align-items: center;
                border-bottom: 1px solid var(--division-line);
                display: flex;
                justify-content: space-between;
                min-height: 58px;
                padding: 16px 20px;
            }

            .division-list-header h2 {
                color: var(--division-ink);
                font-size: 16px;
                font-weight: 700;
                margin: 0;
            }

            .division-list-body {
                padding: 18px 20px 20px;
            }

            #division_table .gridjs-wrapper {
                border: 1px solid var(--division-line);
                border-radius: 7px;
                box-shadow: none;
                overflow: hidden;
            }

            #division_table .gridjs-head {
                margin-bottom: 8px;
                padding: 0;
            }

            #division_table .gridjs-search {
                margin-bottom: 4px;
            }

            #division_table .gridjs-search-input {
                border: 1px solid var(--division-line);
                border-radius: 6px;
                color: var(--division-ink);
                font-size: 14px;
                min-width: min(320px, 100%);
                padding: 8px 12px;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            #division_table .gridjs-search-input:focus {
                border-color: var(--division-primary);
                box-shadow: 0 0 0 3px rgba(101, 88, 211, .12);
                outline: none;
            }

            #division_table th.gridjs-th {
                background: var(--division-primary-soft);
                border-color: var(--division-line);
                border-bottom: 2px solid var(--division-primary);
                color: var(--division-primary);
                font-size: 11px;
                font-weight: 700;
                padding: 13px 15px;
                text-transform: uppercase;
            }

            #division_table td.gridjs-td {
                border-color: var(--division-line);
                color: #4a5260;
                font-size: 14px;
                padding: 13px 15px;
                vertical-align: middle;
            }

            #division_table tr.gridjs-tr:hover td {
                background: #f7f8fc;
            }

            #division_table .gridjs-footer {
                border-top: 1px solid var(--division-line);
                box-shadow: none;
                color: var(--division-muted);
                padding: 13px 15px;
            }

            #division_table .gridjs-pagination .gridjs-pages button {
                border-color: var(--division-line);
                border-radius: 5px;
                color: var(--division-muted);
                margin: 0 2px;
            }

            #division_table .gridjs-pagination .gridjs-pages button.gridjs-currentPage {
                background: var(--division-primary);
                border-color: var(--division-primary);
                color: #fff;
            }

            #division_table .table-actions {
                align-items: center;
                display: inline-flex;
                gap: 6px;
                justify-content: flex-end;
            }

            #division_table .table-actions .table-action {
                align-items: center;
                border-radius: 8px;
                display: inline-flex;
                height: 44px;
                justify-content: center;
                min-width: 44px;
                padding: 8px 12px;
            }

            #division_table .table-actions .table-action--edit {
                background: #2563eb;
                border-color: #2563eb;
                color: #fff;
            }

            #division_table .table-actions .table-action--edit:hover {
                background: #1d4ed8;
                border-color: #1d4ed8;
                color: #fff;
            }

            @media (max-width: 576px) {
                .division-heading {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .division-list-header,
                .division-list-body {
                    padding-left: 14px;
                    padding-right: 14px;
                }

                #division_table .gridjs-search-input {
                    min-width: 0;
                    width: 100%;
                }
            }
        </style>
    @endpush

    <main class="container-fluid division-page">
        <header class="division-heading">
            <div>
                <h1>{{ __('settings.Division Management') }}</h1>
                <p>{{ __('settings.Browse and search the registered divisions.') }}</p>
            </div>
            <div class="division-heading-actions">
                <button type="button" class="btn btn-primary division-refresh waves-effect" id="create_division" data-toggle="modal" data-target="#divisionModal">
                    <i class="material-icons" aria-hidden="true">add</i>
                    <span>{{ __('settings.Division') }}</span>
                </button>
                <button type="button" class="btn btn-outline-secondary division-refresh waves-effect" id="refresh_divisions" title="{{ __('settings.Refresh divisions') }}">
                    <i class="material-icons" aria-hidden="true">refresh</i>
                    <span>{{ __('settings.Refresh') }}</span>
                </button>
            </div>
        </header>

        <section class="division-list-panel" aria-labelledby="division-list-title">
            <header class="division-list-header">
                <h2 id="division-list-title">{{ __('settings.Division List') }}</h2>
            </header>
            <div class="division-list-body">
                <div id="division_status" class="alert alert-info py-2 d-none" role="status" aria-live="polite"></div>
                <div id="division_table"></div>
            </div>
        </section>
    </main>

    <div class="modal fade" id="divisionModal" tabindex="-1" role="dialog" aria-labelledby="divisionModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="division_form">
                    @csrf
                    <input type="hidden" id="division_id" name="division_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="divisionModalTitle">{{ __('admin.Create Division') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('admin.Close') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="division_name">{{ __('settings.Division Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="division_name" name="name" maxlength="255" required>
                            <span class="text-danger small" id="division_name_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="division_gm_user_id">{{ __('admin.General Manager') }}</label>
                            <select class="form-control" id="division_gm_user_id" name="gm_user_id">
                                <option value="">{{ __('settings.Not assigned') }}</option>
                                @foreach ($generalManagers as $generalManager)
                                    <option value="{{ $generalManager->id }}">{{ $generalManager->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger small" id="division_gm_user_id_error"></span>
                        </div>
                        <div class="form-group mb-0">
                            <label for="division_office_id">{{ __('admin.Office') }}</label>
                            <select class="form-control" id="division_office_id" name="office_id">
                                <option value="">{{ __('settings.Not assigned') }}</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger small" id="division_office_id_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('admin.Cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="save_division">
                            <i class="material-icons align-middle mr-1">save</i> {{ __('settings.Save Division') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @include('admin.division.division_js')
    @endpush
@endsection
