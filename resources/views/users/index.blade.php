@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link href="{{ asset('assets/plugins/select2/css/select2-material.css') }}" rel="stylesheet">
        <style>
            :root {
                --user-primary: #6C5DD3;
                --user-primary-soft: #EEF0FF;
                --user-border: #E9E9F1;
                --user-text-muted: #8A8CA5;
                --user-row-hover: #F7F7FC;
            }

            .user-page-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 24px;
                margin-bottom: 28px;
            }

            .user-page-header h2 {
                margin: 0 0 6px;
                color: #20202D;
                font-size: 28px;
                font-weight: 600;
            }

            .user-page-header p {
                margin: 0;
                color: var(--user-text-muted);
            }

            .user-page-header .btn {
                min-width: 132px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(108, 93, 211, .18);
            }

            .user-table-card {
                border: 1px solid var(--user-border);
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(20, 20, 43, .04);
                overflow: hidden;
            }

            .user-table-card .card-header {
                align-items: center;
                background: #fff;
                border-bottom: 1px solid var(--user-border);
                display: flex;
                justify-content: space-between;
                padding: 20px 24px;
            }

            .user-table-card .card-header h5 {
                color: #20202D;
                font-size: 16px;
                font-weight: 600;
                margin: 0;
            }

            .user-table-card .card-body {
                padding: 16px 24px 20px;
            }

            #user_table {
                border: 1px solid var(--user-border);
                border-radius: 8px;
                border-collapse: separate;
                border-spacing: 0;
                overflow: hidden;
            }

            #user_table thead th {
                background: var(--user-primary-soft);
                border-bottom: 2px solid var(--user-primary);
                color: var(--user-primary);
                font-size: 12px;
                font-weight: 700;
                padding: 14px 16px;
                text-transform: uppercase;
            }

            #user_table tbody td {
                border-bottom: 1px solid var(--user-border);
                color: #4A4A5A;
                font-size: 14px;
                padding: 14px 16px;
                vertical-align: middle;
            }

            #user_table tbody tr:last-child td {
                border-bottom: 0;
            }

            #user_table tbody tr:hover td {
                background: var(--user-row-hover);
            }

            #user_table .btn {
                border-radius: 6px;
                margin: 0 4px 0 0;
                min-width: 62px;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid var(--user-border);
                border-radius: 6px;
                color: #4A4A5A;
                padding: 7px 10px;
            }

            .dataTables_wrapper .dataTables_filter input:focus {
                border-color: var(--user-primary);
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .12);
                outline: none;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                color: var(--user-text-muted);
                font-size: 13px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 6px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: var(--user-primary);
                border-color: var(--user-primary);
                color: #fff !important;
            }

            .user-management-modal .modal-dialog {
                max-width: 920px;
            }

            .user-form-modal {
                overflow: hidden !important;
            }

            .user-form-modal .modal-dialog {
                display: flex;
                height: calc(100vh - 32px);
                margin: 16px auto;
                min-height: 0;
            }

            .user-management-modal .modal-content {
                border: 0;
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                max-height: calc(100vh - 48px);
                box-shadow: 0 18px 48px rgba(32, 32, 45, .18);
                overflow: hidden;
            }

            .user-form-modal .modal-content {
                height: 100%;
                max-height: 100%;
                min-height: 0;
            }

            .user-form-modal .modal-content > form {
                display: flex;
                flex: 1 1 auto;
                flex-direction: column;
                min-height: 0;
                overflow: hidden;
            }

            .user-management-modal .modal-header {
                align-items: flex-start;
                background: #fff;
                border-bottom: 1px solid var(--user-border);
                flex: 0 0 auto;
                padding: 22px 28px 20px;
            }

            .user-management-modal .modal-header .close {
                align-items: center;
                border-radius: 6px;
                color: #5f6175;
                display: inline-flex;
                height: 36px;
                justify-content: center;
                margin: -4px -6px 0 16px;
                opacity: 1;
                padding: 0;
                width: 36px;
            }

            .user-management-modal .modal-header .close:hover,
            .user-management-modal .modal-header .close:focus {
                background: #f2f2f7;
                color: #20202d;
                outline: none;
            }

            .user-modal-kicker {
                color: var(--user-primary);
                display: block;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .08em;
                margin-bottom: 5px;
            }

            .user-management-modal .modal-title {
                color: #20202d;
                font-size: 22px;
                font-weight: 650;
                line-height: 1.25;
                margin: 0;
            }

            .user-modal-description {
                color: #73758a;
                font-size: 13px;
                margin: 6px 0 0;
            }

            .user-management-modal .modal-body {
                background: #f8f8fb;
                flex: 1 1 auto;
                min-height: 0;
                overscroll-behavior: contain;
                overflow-y: scroll;
                padding: 24px 28px;
                -webkit-overflow-scrolling: touch;
            }

            .user-form-section {
                background: #fff;
                border: 1px solid var(--user-border);
                border-radius: 10px;
                padding: 20px;
            }

            .user-form-section + .user-form-section {
                margin-top: 18px;
            }

            .user-form-section-heading {
                border-bottom: 1px solid #eeeef4;
                margin-bottom: 18px;
                padding-bottom: 14px;
            }

            .user-form-section-heading h3 {
                color: #292a3a;
                font-size: 15px;
                font-weight: 650;
                margin: 0 0 4px;
            }

            .user-form-section-heading p {
                color: #85879b;
                font-size: 12px;
                margin: 0;
            }

            .user-form-grid {
                display: grid;
                gap: 16px 20px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .user-management-modal .form-group {
                margin: 0;
                min-width: 0;
            }

            .user-management-modal label {
                color: #444657;
                display: block;
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 7px;
            }

            .user-management-modal .required-mark {
                color: #dc3545;
            }

            .user-management-modal .optional-label {
                color: #8b8da0;
                float: right;
                font-size: 11px;
                font-weight: 500;
            }

            .user-management-modal .form-control,
            .user-management-modal .select2-container .select2-selection--single {
                background: #fff;
                border: 1px solid #d9dae5;
                border-radius: 7px;
                color: #303142;
                font-size: 14px;
                min-height: 44px;
            }

            .user-management-modal .form-control {
                padding: 9px 12px;
            }

            .user-management-modal .form-control::placeholder {
                color: #a1a3b1;
            }

            .user-management-modal .form-control:focus,
            .user-management-modal .select2-container--focus .select2-selection--single,
            .user-management-modal .select2-container--open .select2-selection--single {
                border-color: var(--user-primary);
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .12);
                outline: 0;
            }

            .user-management-modal .select2-container {
                display: block;
                width: 100% !important;
            }

            .user-management-modal .select2-selection__rendered {
                line-height: 42px !important;
                padding-left: 12px !important;
                padding-right: 36px !important;
            }

            .user-management-modal .select2-selection__arrow {
                height: 42px !important;
                right: 8px !important;
            }

            .user-field-error {
                color: #dc3545;
                display: block;
                font-size: 12px;
                line-height: 1.35;
                min-height: 16px;
                padding-top: 4px;
            }

            .user-management-modal .is-invalid,
            .user-management-modal .select2-selection.is-invalid {
                border-color: #dc3545 !important;
            }

            .user-management-modal .modal-footer {
                background: #fff;
                border-top: 1px solid var(--user-border);
                flex: 0 0 auto;
                gap: 10px;
                padding: 16px 28px;
            }

            .user-management-modal .modal-footer .btn {
                align-items: center;
                border-radius: 7px;
                display: inline-flex;
                font-size: 13px;
                font-weight: 600;
                justify-content: center;
                min-height: 42px;
                min-width: 112px;
                padding: 9px 16px;
            }

            .user-management-modal .modal-footer .material-icons {
                font-size: 17px;
                margin-right: 7px;
            }

            .user-delete-modal .modal-dialog {
                max-width: 480px;
            }

            .user-delete-modal .modal-body {
                background: #fff;
                padding: 28px;
                text-align: center;
            }

            .delete-user-warning {
                align-items: center;
                background: #fff0f1;
                border-radius: 50%;
                color: #dc3545;
                display: inline-flex;
                height: 52px;
                justify-content: center;
                margin-bottom: 16px;
                width: 52px;
            }

            .delete-user-title {
                color: #292a3a;
                font-size: 16px;
                font-weight: 650;
                margin: 0 0 8px;
            }

            .delete-user-description {
                color: #77798c;
                font-size: 13px;
                line-height: 1.5;
                margin: 0 auto;
                max-width: 360px;
            }

            @media (max-width: 576px) {
                .user-page-header {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .user-table-card .card-header,
                .user-table-card .card-body {
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .user-management-modal .modal-dialog {
                    margin: 12px;
                }

                .user-form-modal .modal-dialog {
                    height: calc(100vh - 24px);
                }

                .user-management-modal .modal-content {
                    max-height: calc(100vh - 24px);
                }

                .user-management-modal .modal-header,
                .user-management-modal .modal-body,
                .user-management-modal .modal-footer {
                    padding-left: 18px;
                    padding-right: 18px;
                }

                .user-form-section {
                    padding: 16px;
                }

                .user-form-grid {
                    grid-template-columns: 1fr;
                }

                .user-management-modal .modal-footer .btn {
                    flex: 1 1 0;
                    min-width: 0;
                }
            }
        </style>
    @endpush

    <div class="user-page">
        <div class="user-page-header">
            <div>
                <h2>{{ __('messages.User Management Title') }}</h2>
                <p>{{ __('messages.User Management Subtitle') }}</p>
            </div>
            <button type="button" class="btn btn-primary waves-effect" id="for_create_user" data-toggle="modal"
                data-target="#formCreateUser">
                <i class="material-icons align-middle mr-1">person_add</i>
                <span>{{ __('messages.User Management New User') }}</span>
            </button>
        </div>

        <div class="card user-table-card">
            <div class="card-header">
                <h5>{{ __('messages.User Management User List') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="user_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('messages.User Management Number') }}</th>
                                <th>{{ __('messages.User Management Name') }}</th>
                                <th>{{ __('messages.User Management Role') }}</th>
                                <th>{{ __('messages.User Management Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('users.create_user')
        @include('users.detail_user')
        @include('users.delete_user')
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    @include('users.user_js')
@endpush
