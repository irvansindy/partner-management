@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link href="{{ asset('assets/plugins/select2/css/select2-material.css') }}" rel="stylesheet">
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
                <span>Approval</span>
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
                                <th>#</th>
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
