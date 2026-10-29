@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
        <style>
            :root {
                --approval-primary: #6C5DD3;
                --approval-primary-soft: #EEF0FF;
                --approval-border: #E9E9F1;
                --approval-text-muted: #8A8CA5;
                --approval-row-hover: #F7F7FC;
            }

            .approval-page-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 24px;
                margin-bottom: 28px;
            }

            .approval-page-header h2 {
                margin: 0 0 6px;
                color: #20202D;
                font-size: 28px;
                font-weight: 600;
            }

            .approval-page-header p {
                margin: 0;
                color: var(--approval-text-muted);
            }

            .approval-page-header .btn {
                min-width: 138px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(108, 93, 211, .18);
            }

            .approval-table-card {
                border: 1px solid var(--approval-border);
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(20, 20, 43, .04);
                overflow: hidden;
            }

            .approval-table-card .card-header {
                align-items: center;
                background: #fff;
                border-bottom: 1px solid var(--approval-border);
                display: flex;
                justify-content: space-between;
                padding: 20px 24px;
            }

            .approval-table-card .card-header h5 {
                color: #20202D;
                font-size: 16px;
                font-weight: 600;
                margin: 0;
            }

            .approval-table-card .card-body {
                padding: 16px 24px 20px;
            }

            #approval_table {
                border: 1px solid var(--approval-border);
                border-radius: 8px;
                border-collapse: separate;
                border-spacing: 0;
                overflow: hidden;
                width: 100%;
            }

            #approval_table thead th {
                background: var(--approval-primary-soft);
                border-bottom: 2px solid var(--approval-primary);
                color: var(--approval-primary);
                font-size: 12px;
                font-weight: 700;
                padding: 14px 16px;
                text-transform: uppercase;
            }

            #approval_table tbody td {
                border-bottom: 1px solid var(--approval-border);
                color: #4A4A5A;
                font-size: 14px;
                padding: 14px 16px;
                vertical-align: middle;
            }

            #approval_table tbody tr:last-child td {
                border-bottom: 0;
            }

            #approval_table tbody tr:hover td {
                background: var(--approval-row-hover);
            }

            #approval_table .btn {
                border-radius: 6px;
                margin: 0 4px 0 0;
                min-width: 62px;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid var(--approval-border);
                border-radius: 6px;
                color: #4A4A5A;
                padding: 7px 10px;
            }

            .dataTables_wrapper .dataTables_filter input:focus {
                border-color: var(--approval-primary);
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .12);
                outline: none;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                color: var(--approval-text-muted);
                font-size: 13px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 6px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: var(--approval-primary);
                border-color: var(--approval-primary);
                color: #fff !important;
            }

            .select2-container {
                z-index: 9999 !important;
            }

            .toasts-top-right {
                z-index: 9999 !important;
            }

            .approval-modal .modal-content {
                border: 0;
                border-radius: 14px;
                box-shadow: 0 24px 70px rgba(24, 28, 48, .22);
                overflow: hidden;
            }

            .approval-modal .modal-header {
                align-items: flex-start;
                background: #fff;
                border-bottom: 1px solid var(--approval-border);
                padding: 22px 26px 18px;
            }

            .approval-modal .modal-title {
                color: #20202D;
                font-size: 21px;
                font-weight: 700;
                margin-top: 2px;
            }

            .approval-modal__eyebrow {
                color: var(--approval-primary);
                display: block;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: .08em;
                text-transform: uppercase;
            }

            .approval-modal__subtitle {
                color: #66697c;
                font-size: 13px;
                margin: 6px 36px 0 0;
                max-width: 590px;
            }

            .approval-modal .close {
                border-radius: 6px;
                margin: 0;
                padding: 5px 9px;
            }

            .approval-modal .close:focus-visible,
            .approval-modal .btn:focus-visible {
                outline: 3px solid rgba(108, 93, 211, .32);
                outline-offset: 2px;
            }

            .approval-modal .modal-body {
                background: #f8f8fb;
                max-height: min(68vh, 640px);
                overflow-y: auto;
                padding: 24px 26px;
            }

            .approval-modal .modal-footer {
                background: #fff;
                border-top: 1px solid var(--approval-border);
                gap: 8px;
                padding: 16px 26px;
            }

            .approval-modal .modal-footer .btn {
                border-radius: 7px;
                min-height: 40px;
                min-width: 112px;
            }

            .approval-form-section {
                background: #fff;
                border: 1px solid var(--approval-border);
                border-radius: 10px;
                margin-bottom: 16px;
                padding: 18px;
            }

            .approval-form-section__title {
                align-items: flex-start;
                display: flex;
                gap: 11px;
                margin-bottom: 18px;
            }

            .approval-form-section__number,
            .approver-step-card__number {
                align-items: center;
                background: var(--approval-primary-soft);
                border-radius: 50%;
                color: var(--approval-primary);
                display: inline-flex;
                flex: 0 0 30px;
                font-size: 13px;
                font-weight: 800;
                height: 30px;
                justify-content: center;
            }

            .approval-form-section__title strong,
            .approval-form-section__title small {
                display: block;
            }

            .approval-form-section__title small {
                color: #74778a;
                margin-top: 2px;
            }

            .approval-modal label {
                color: #343646;
                font-size: 13px;
                font-weight: 700;
            }

            .approval-modal .form-control,
            .approval-modal .select2-container--default .select2-selection--single {
                border-color: #cfd2df;
                border-radius: 7px;
                min-height: 43px;
            }

            .approval-modal .select2-container {
                display: block;
                max-width: 100%;
                width: 100% !important;
            }

            .approval-modal .select2-selection--single {
                align-items: center;
                background: #fff;
                border: 1px solid #cfd2df !important;
                box-shadow: none;
                display: flex;
                height: 44px !important;
                padding: 0;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            .approval-modal .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #2f3242;
                line-height: normal;
                padding: 0 42px 0 15px;
                text-overflow: ellipsis;
                width: 100%;
            }

            .approval-modal .select2-container--default .select2-selection--single .select2-selection__arrow {
                align-items: center;
                border-left: 1px solid #e2e4ec;
                display: flex;
                height: 42px;
                justify-content: center;
                right: 0;
                top: 0;
                width: 38px;
            }

            .approval-modal .select2-container--default .select2-selection--single .select2-selection__arrow b {
                border-color: #626679 transparent transparent;
                margin: 3px 0 0;
                position: static;
            }

            .approval-modal .select2-container--open .select2-selection--single,
            .approval-modal .select2-container--focus .select2-selection--single {
                border-color: var(--approval-primary) !important;
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .14);
            }

            .approval-modal .select2-container--disabled .select2-selection--single {
                background: #f0f1f5;
                color: #777a8c;
                cursor: not-allowed;
            }

            .approval-modal select.is-invalid + .select2-container .select2-selection--single {
                border-color: #dc3545 !important;
                box-shadow: 0 0 0 3px rgba(220, 53, 69, .1);
            }

            .approval-select2-dropdown.select2-dropdown {
                border: 1px solid #bfc2d0;
                border-radius: 0;
                box-shadow: none;
                margin-top: 0;
                overflow: hidden;
                z-index: 10060;
            }

            .approval-select2-dropdown .select2-search--dropdown {
                background: #fff;
                padding: 4px;
            }

            .approval-select2-dropdown .select2-search__field {
                border: 1px solid #aaa !important;
                border-radius: 0;
                box-sizing: border-box;
                color: #2f3242;
                font-size: 16px;
                height: 42px !important;
                line-height: 22px;
                min-height: 42px;
                padding: 9px 12px;
                width: 100% !important;
            }

            .approval-select2-dropdown .select2-search__field:focus {
                border-color: var(--approval-primary) !important;
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .13);
                outline: 0;
            }

            .approval-select2-dropdown .select2-results__options {
                max-height: 240px;
                padding: 0;
            }

            .approval-select2-dropdown .select2-results__option {
                border-radius: 0;
                color: #343747;
                font-size: 16px;
                line-height: 22px;
                margin: 0;
                padding: 10px 14px;
            }

            .approval-select2-dropdown .select2-results__option--highlighted[aria-selected] {
                background: #5897fb;
                color: #fff;
            }

            .approval-select2-dropdown .select2-results__option[aria-selected="true"] {
                background: #ddd;
                color: #343747;
                font-weight: 400;
            }

            .approval-select2-dropdown .select2-results__option--highlighted[aria-selected="true"] {
                background: #5897fb;
                color: #fff;
            }

            .approval-modal-context {
                align-items: stretch;
                background: #fff;
                border: 1px solid var(--approval-border);
                border-radius: 10px;
                display: grid;
                gap: 0;
                grid-template-columns: 1.4fr 1fr 1fr;
                margin-bottom: 22px;
            }

            .approval-modal-context > div {
                border-left: 1px solid var(--approval-border);
                min-width: 0;
                padding: 14px 16px;
            }

            .approval-modal-context > div:first-child { border-left: 0; }
            .approval-modal-context span,
            .approval-modal-context strong { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .approval-modal-context span { color: #777a8d; font-size: 11px; font-weight: 700; text-transform: uppercase; }
            .approval-modal-context strong { color: #2d3040; font-size: 14px; margin-top: 3px; }

            .approval-list-header {
                align-items: center;
                display: flex;
                gap: 16px;
                justify-content: space-between;
                margin-bottom: 12px;
            }

            .approval-list-header h6 { color: #292b3a; font-weight: 700; margin: 0; }
            .approval-list-header p { color: #74778a; font-size: 12px; margin: 3px 0 0; }
            .approval-list-header .btn { border-radius: 7px; white-space: nowrap; }

            .approver-list { display: grid; gap: 10px; }
            .approver-step-card {
                align-items: center;
                background: #fff;
                border: 1px solid var(--approval-border);
                border-radius: 9px;
                display: grid;
                gap: 12px;
                grid-template-columns: 34px minmax(0, 1fr) 38px;
                padding: 12px;
            }
            .approver-step-card > * { min-width: 0; }
            .approver-step-card .form-group { margin: 0; min-width: 0; }
            .approver-step-card__remove {
                align-items: center;
                box-sizing: border-box;
                display: inline-flex;
                height: 36px;
                justify-content: center;
                justify-self: end;
                max-width: 36px;
                min-width: 36px !important;
                padding: 0;
                width: 36px !important;
            }

            .approval-modal-state {
                background: #fff;
                border: 1px dashed #c9cbd8;
                border-radius: 9px;
                color: #616477;
                padding: 18px;
                text-align: center;
            }
            .approval-modal-state--error { background: #fff5f5; border-color: #e7a4aa; color: #9d2934; }
            .approval-modal-footer-note { color: #6d7082; font-size: 12px; margin-right: auto; }

            @media (max-width: 576px) {
                .approval-page-header {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .approval-table-card .card-header,
                .approval-table-card .card-body {
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .approval-modal .modal-dialog { margin: 8px; }
                .approval-modal .modal-header,
                .approval-modal .modal-body,
                .approval-modal .modal-footer { padding-left: 18px; padding-right: 18px; }
                .approval-modal-context { grid-template-columns: 1fr; }
                .approval-modal-context > div { border-left: 0; border-top: 1px solid var(--approval-border); }
                .approval-modal-context > div:first-child { border-top: 0; }
                .approval-list-header { align-items: stretch; flex-direction: column; }
                .approval-list-header .btn { width: 100%; }
                .approval-modal-footer-note { flex-basis: 100%; margin-bottom: 4px; }
            }
        </style>
    @endpush

    <div class="approval-page">
        <div class="approval-page-header">
            <div>
                <h2>Master Approval</h2>
                <p>Set up approval templates and approver flows by office and department.</p>
            </div>
            <button type="button" class="btn btn-primary waves-effect" id="for_create_approval"
                data-toggle="modal" data-target="#formCreateApproval">
                <i class="material-icons align-middle mr-1">playlist_add</i>
                <span>Create Template</span>
            </button>
        </div>

        <div class="card approval-table-card">
            <div class="card-header">
                <h5>Approval List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="approval_table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Approval Name</th>
                                <th>Lokasi</th>
                                <th>Department</th>
                                <th>Steps</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('admin.approval_setting.create_approval_master')
        @include('admin.approval_setting.add_view_approval_detail_modal')
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    @include('admin.approval_setting.approval_js')
@endpush
