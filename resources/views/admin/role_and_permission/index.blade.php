@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link href="{{ asset('assets/plugins/select2/css/select2-material.css') }}" rel="stylesheet">
        <style>
            :root {
                --rp-primary: #6C5DD3;
                --rp-primary-soft: #EEF0FF;
                --rp-border: #E9E9F1;
                --rp-text-muted: #8A8CA5;
                --rp-row-hover: #F7F7FC;
            }

            .rp-page-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 24px;
                margin-bottom: 28px;
            }

            .rp-page-header h2 {
                margin: 0 0 6px;
                color: #20202D;
                font-size: 28px;
                font-weight: 600;
            }

            .rp-page-header p {
                margin: 0;
                color: var(--rp-text-muted);
            }

            .rp-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 24px;
            }

            .rp-card {
                border: 1px solid var(--rp-border);
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
                overflow: hidden;
                background: #fff;
            }

            .rp-card .card-header {
                align-items: center;
                background: #fff;
                border-bottom: 1px solid var(--rp-border);
                display: flex;
                justify-content: space-between;
                padding: 20px 24px;
            }

            .rp-card .card-header h5 {
                color: #20202D;
                font-size: 16px;
                font-weight: 600;
                margin: 0;
            }

            .rp-card .card-body {
                padding: 16px 24px 20px;
            }

            .rp-card .btn {
                min-width: 120px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(108, 93, 211, .18);
            }

            #role_table,
            #permission_table {
                border: 1px solid var(--rp-border);
                border-radius: 8px;
                border-collapse: separate;
                border-spacing: 0;
                overflow: hidden;
                width: 100%;
            }

            #role_table thead th,
            #permission_table thead th {
                background: var(--rp-primary-soft);
                border-bottom: 2px solid var(--rp-primary);
                color: var(--rp-primary);
                font-size: 12px;
                font-weight: 700;
                padding: 14px 16px;
                text-transform: uppercase;
            }

            #role_table tbody td,
            #permission_table tbody td {
                border-bottom: 1px solid var(--rp-border);
                color: #4A4A5A;
                font-size: 14px;
                padding: 14px 16px;
                vertical-align: middle;
            }

            #role_table tbody tr:last-child td,
            #permission_table tbody tr:last-child td {
                border-bottom: 0;
            }

            #role_table tbody tr:hover td,
            #permission_table tbody tr:hover td {
                background: var(--rp-row-hover);
            }

            #role_table .btn,
            #permission_table .btn {
                border-radius: 6px;
                margin: 0 4px 0 0;
                min-width: 62px;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid var(--rp-border);
                border-radius: 6px;
                color: #4A4A5A;
                padding: 7px 10px;
            }

            .dataTables_wrapper .dataTables_filter input:focus {
                border-color: var(--rp-primary);
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .12);
                outline: none;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                color: var(--rp-text-muted);
                font-size: 13px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 6px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: var(--rp-primary);
                border-color: var(--rp-primary);
                color: #fff !important;
            }

            @media (max-width: 991px) {
                .rp-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 576px) {
                .rp-page-header {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .rp-card .card-header,
                .rp-card .card-body {
                    padding-left: 16px;
                    padding-right: 16px;
                }
            }
        </style>
    @endpush

    <div class="rp-page">
        <div class="rp-page-header">
            <div>
                <h2>Role &amp; Permission</h2>
                <p>Manage access roles and permission assignments across the application.</p>
            </div>
        </div>

        <div class="rp-grid">
            <div class="rp-card">
                <div class="card-header">
                    <h5>Role Management</h5>
                    <button type="button" class="btn btn-primary waves-effect" id="for_create_role" data-toggle="modal" data-target="#formCreateRole">
                        <i class="material-icons align-middle mr-1">add</i>
                        <span>Role</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="role_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="rp-card">
                <div class="card-header">
                    <h5>Permission Management</h5>
                    <button type="button" class="btn btn-primary waves-effect" id="for_create_permission" data-toggle="modal" data-target="#formCreatePermission">
                        <i class="material-icons align-middle mr-1">vpn_key</i>
                        <span>Permission</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="permission_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.role_and_permission.create_role')
        @include('admin.role_and_permission.detail_role')
        @include('admin.role_and_permission.add_permission')
        @include('admin.role_and_permission.create_permission')
        @include('admin.role_and_permission.detail_permission')
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    @include('admin.role_and_permission.role_permission_js')
@endpush
