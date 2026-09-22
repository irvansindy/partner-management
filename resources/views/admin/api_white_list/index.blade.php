@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <style>
            :root {
                --whitelist-primary: #6C5DD3;
                --whitelist-primary-soft: #EEF0FF;
                --whitelist-border: #E9E9F1;
                --whitelist-text-muted: #8A8CA5;
                --whitelist-row-hover: #F7F7FC;
            }

            .whitelist-page-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 24px;
                margin-bottom: 28px;
            }

            .whitelist-page-header h2 {
                margin: 0 0 6px;
                color: #20202D;
                font-size: 28px;
                font-weight: 600;
            }

            .whitelist-page-header p {
                margin: 0;
                color: var(--whitelist-text-muted);
            }

            .whitelist-page-header .btn {
                min-width: 122px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(108, 93, 211, .18);
            }

            .whitelist-table-card {
                border: 1px solid var(--whitelist-border);
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(20, 20, 43, .04);
                overflow: hidden;
            }

            .whitelist-table-card .card-header {
                align-items: center;
                background: #fff;
                border-bottom: 1px solid var(--whitelist-border);
                display: flex;
                justify-content: space-between;
                padding: 20px 24px;
            }

            .whitelist-table-card .card-header h5 {
                color: #20202D;
                font-size: 16px;
                font-weight: 600;
                margin: 0;
            }

            .whitelist-table-card .card-body {
                padding: 16px 24px 20px;
            }

            #ip_address_table {
                border: 1px solid var(--whitelist-border);
                border-radius: 8px;
                border-collapse: separate;
                border-spacing: 0;
                overflow: hidden;
                width: 100%;
            }

            #ip_address_table thead th {
                background: var(--whitelist-primary-soft);
                border-bottom: 2px solid var(--whitelist-primary);
                color: var(--whitelist-primary);
                font-size: 12px;
                font-weight: 700;
                padding: 14px 16px;
                text-transform: uppercase;
            }

            #ip_address_table tbody td {
                border-bottom: 1px solid var(--whitelist-border);
                color: #4A4A5A;
                font-size: 14px;
                padding: 14px 16px;
                vertical-align: middle;
            }

            #ip_address_table tbody tr:last-child td {
                border-bottom: 0;
            }

            #ip_address_table tbody tr:hover td {
                background: var(--whitelist-row-hover);
            }

            #ip_address_table .btn {
                border-radius: 6px;
                margin: 0 4px 0 0;
                min-width: 62px;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid var(--whitelist-border);
                border-radius: 6px;
                color: #4A4A5A;
                padding: 7px 10px;
            }

            .dataTables_wrapper .dataTables_filter input:focus {
                border-color: var(--whitelist-primary);
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .12);
                outline: none;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                color: var(--whitelist-text-muted);
                font-size: 13px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 6px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: var(--whitelist-primary);
                border-color: var(--whitelist-primary);
                color: #fff !important;
            }

            .toasts-top-right {
                z-index: 9999 !important;
            }

            @media (max-width: 576px) {
                .whitelist-page-header {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .whitelist-table-card .card-header,
                .whitelist-table-card .card-body {
                    padding-left: 16px;
                    padding-right: 16px;
                }
            }
        </style>
    @endpush

    <div class="whitelist-page">
        <div class="whitelist-page-header">
            <div>
                <h2>IP Address Management</h2>
                <p>Manage trusted IP addresses allowed to access the API.</p>
            </div>
            <button type="button" class="btn btn-primary waves-effect" id="for_create_ip_address"
                data-toggle="modal" data-target="#formIpAddress">
                <i class="material-icons align-middle mr-1">add</i>
                <span>IP Address</span>
            </button>
        </div>

        <div class="card whitelist-table-card">
            <div class="card-header">
                <h5>List IP Address</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="ip_address_table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>IP Address</th>
                                <th>Description</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('admin.api_white_list.modal_api_white_list')
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    @include('admin.api_white_list.api_white_list_js')
@endpush
