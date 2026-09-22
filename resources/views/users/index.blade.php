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
            }
        </style>
    @endpush

    <div class="user-page">
        <div class="user-page-header">
            <div>
                <h2>User Management</h2>
                <p>Manage application users, roles, and organizational assignments.</p>
            </div>
            <button type="button" class="btn btn-primary waves-effect" id="for_create_user" data-toggle="modal"
                data-target="#formCreateUser">
                <i class="material-icons align-middle mr-1">person_add</i>
                <span>New User</span>
            </button>
        </div>

        <div class="card user-table-card">
            <div class="card-header">
                <h5>User List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="user_table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>#</th>
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
