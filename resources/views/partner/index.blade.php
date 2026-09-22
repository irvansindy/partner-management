@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link href="{{ asset('assets/plugins/select2/css/select2-material.css') }}" rel="stylesheet">
        <style>
            :root {
                --partner-primary: #6C5DD3;
                --partner-primary-soft: #EEF0FF;
                --partner-border: #E9E9F1;
                --partner-text-muted: #8A8CA5;
                --partner-row-hover: #F7F7FC;
            }

            .partner-page-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 24px;
                margin-bottom: 28px;
            }

            .partner-page-header h2 {
                margin: 0 0 6px;
                color: #20202D;
                font-size: 28px;
                font-weight: 600;
            }

            .partner-page-header p {
                margin: 0;
                color: var(--partner-text-muted);
            }

            .partner-actions {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: 8px;
            }

            .partner-actions .btn {
                align-items: center;
                border-radius: 8px;
                display: inline-flex;
                gap: 6px;
                justify-content: center;
                min-width: 42px;
            }

            .partner-table-card {
                border: 1px solid var(--partner-border);
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(20, 20, 43, .04);
                overflow: hidden;
            }

            .partner-table-card .card-header {
                align-items: center;
                background: #fff;
                border-bottom: 1px solid var(--partner-border);
                display: flex;
                justify-content: space-between;
                padding: 20px 24px;
            }

            .partner-table-card .card-header h5 {
                color: #20202D;
                font-size: 16px;
                font-weight: 600;
                margin: 0;
            }

            .partner-table-card .card-body {
                padding: 16px 24px 20px;
            }

            #partner_table {
                border: 1px solid var(--partner-border);
                border-radius: 8px;
                border-collapse: separate;
                border-spacing: 0;
                overflow: hidden;
                width: 100%;
            }

            #partner_table thead th {
                background: var(--partner-primary-soft);
                border-bottom: 2px solid var(--partner-primary);
                color: var(--partner-primary);
                font-size: 12px;
                font-weight: 700;
                padding: 14px 16px;
                text-transform: uppercase;
            }

            #partner_table tbody td {
                border-bottom: 1px solid var(--partner-border);
                color: #4A4A5A;
                font-size: 14px;
                padding: 14px 16px;
                vertical-align: middle;
            }

            #partner_table tbody tr:last-child td {
                border-bottom: 0;
            }

            #partner_table tbody tr:hover td {
                background: var(--partner-row-hover);
            }

            #partner_table .btn {
                border-radius: 6px;
                margin: 0 4px 0 0;
                min-width: 62px;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid var(--partner-border);
                border-radius: 6px;
                color: #4A4A5A;
                padding: 7px 10px;
            }

            .dataTables_wrapper .dataTables_filter input:focus {
                border-color: var(--partner-primary);
                box-shadow: 0 0 0 3px rgba(108, 93, 211, .12);
                outline: none;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                color: var(--partner-text-muted);
                font-size: 13px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 6px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: var(--partner-primary);
                border-color: var(--partner-primary);
                color: #fff !important;
            }

            .toasts-top-right {
                z-index: 9999 !important;
            }

            @media (max-width: 576px) {
                .partner-page-header {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .partner-actions {
                    justify-content: flex-start;
                }

                .partner-table-card .card-header,
                .partner-table-card .card-body {
                    padding-left: 16px;
                    padding-right: 16px;
                }
            }
        </style>
    @endpush

    <div class="partner-page">
        <div class="partner-page-header">
            <div>
                <h2>Partner Management</h2>
                <p>Review partner records, approval status, and company details.</p>
            </div>
            <div class="partner-actions">
                @if (in_array(auth()->user()->roles->pluck('name')[0], ['super-admin', 'admin', 'super-user']))
                    <a href="{{ route('export-excel') }}" class="btn btn-outline-success waves-effect" target="_blank" title="Export Excel">
                        <i class="material-icons">description</i>
                        <span class="d-none d-md-inline">Excel</span>
                    </a>
                    <a href="{{ route('export-pdf') }}" class="btn btn-outline-danger waves-effect" target="_blank" title="Export PDF">
                        <i class="material-icons">picture_as_pdf</i>
                        <span class="d-none d-md-inline">PDF</span>
                    </a>
                @endif
                @if (auth()->user()->roles->pluck('name')[0] === 'super-user')
                    <a href="{{ route('create-partner') }}" class="btn btn-primary waves-effect" target="_blank" title="Create partner">
                        <i class="material-icons">add</i>
                        <span class="d-none d-md-inline">Partner</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="card partner-table-card">
            <div class="card-header">
                <h5>Partner List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="partner_table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Company Name</th>
                                <th>Group Name</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('partner.detail_partner')
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    @include('partner.partner_management_js')
@endpush
