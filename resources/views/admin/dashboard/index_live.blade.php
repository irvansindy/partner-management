@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cdn/leaflet.css') }}">
        <style>
            .dashboard-page {
                --dashboard-ink: #202638;
                --dashboard-muted: #70798b;
                --dashboard-line: #e3e8ef;
                --dashboard-primary: #6558d3;
                color: var(--dashboard-ink);
            }

            .dashboard-heading {
                align-items: flex-end;
                display: flex;
                justify-content: space-between;
                gap: 20px;
                margin: 0 0 24px;
            }

            .dashboard-heading h1 {
                color: var(--dashboard-ink);
                font-size: 26px;
                font-weight: 700;
                margin: 0 0 5px;
            }

            .dashboard-heading p {
                color: var(--dashboard-muted);
                margin: 0;
            }

            .dashboard-metric-card,
            .dashboard-panel {
                background: #fff;
                border: 1px solid var(--dashboard-line);
                border-radius: 8px;
                box-shadow: 0 3px 14px rgba(32, 38, 56, .035);
                height: 100%;
            }

            .dashboard-metric-card {
                align-items: center;
                display: flex;
                gap: 16px;
                min-height: 112px;
                padding: 20px;
            }

            .dashboard-metric-icon {
                align-items: center;
                background: #eef0ff;
                border-radius: 8px;
                color: var(--dashboard-primary);
                display: inline-flex;
                flex: 0 0 48px;
                height: 48px;
                justify-content: center;
                width: 48px;
            }

            .dashboard-metric-icon .material-icons { font-size: 24px; }

            .dashboard-metric-label {
                color: var(--dashboard-muted);
                display: block;
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 3px;
            }

            .dashboard-metric-value {
                color: var(--dashboard-ink);
                display: block;
                font-size: 27px;
                font-weight: 700;
                line-height: 1.15;
            }

            .dashboard-panel { overflow: hidden; }

            .dashboard-panel-header {
                align-items: center;
                border-bottom: 1px solid #edf0f4;
                display: flex;
                justify-content: space-between;
                min-height: 58px;
                padding: 16px 20px;
            }

            .dashboard-panel-header h2 {
                color: var(--dashboard-ink);
                font-size: 16px;
                font-weight: 700;
                margin: 0;
            }

            .dashboard-panel-body { padding: 18px 20px; }
            .dashboard-chart-box { min-height: 320px; position: relative; }

            #dashboard-map {
                border: 1px solid var(--dashboard-line);
                border-radius: 6px;
                height: 390px;
                width: 100%;
                z-index: 0;
            }

            .dashboard-map-marker {
                align-items: center;
                border: 2px solid #fff;
                border-radius: 50%;
                box-shadow: 0 1px 4px rgba(0, 0, 0, .3);
                color: #fff;
                display: flex;
                font-size: 14px;
                height: 34px;
                justify-content: center;
                width: 34px;
            }

            .dashboard-map-marker.customer { background: #df7180; }
            .dashboard-map-marker.vendor { background: #2f80a8; }

            .dashboard-empty-state {
                align-items: center;
                color: var(--dashboard-muted);
                display: flex;
                justify-content: center;
                min-height: 260px;
                text-align: center;
            }

            .dashboard-table { margin-bottom: 0; width: 100% !important; }

            .dashboard-table thead th {
                background: #f5f6fb;
                border-bottom: 1px solid var(--dashboard-line);
                color: #596273;
                font-size: 11px;
                text-transform: uppercase;
            }

            .dashboard-table tbody td { vertical-align: middle; }

            .dashboard-page .dataTables_wrapper {
                color: var(--dashboard-muted);
                font-size: 13px;
            }

            .dashboard-page .dataTables_wrapper .dataTables_filter input,
            .dashboard-page .dataTables_wrapper .dataTables_length select {
                border: 1px solid var(--dashboard-line);
                border-radius: 5px;
                padding: 5px 8px;
            }

            @media (max-width: 767px) {
                .dashboard-heading { align-items: flex-start; flex-direction: column; }
                .dashboard-panel-header,
                .dashboard-panel-body { padding-left: 15px; padding-right: 15px; }
                #dashboard-map { height: 320px; }
            }
        </style>
    @endpush

    <main class="dashboard-page">
        <header class="dashboard-heading">
            <div>
                <h1>Partner Dashboard</h1>
                <p>Current partner and approval overview for your access scope.</p>
            </div>
        </header>

        <div id="dashboard-status" class="alert alert-warning d-none" role="status"></div>

        <section class="row mb-4" aria-label="Partner summary">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="dashboard-metric-card">
                    <span class="dashboard-metric-icon"><i class="material-icons">handshake</i></span>
                    <div><span class="dashboard-metric-label">Approved Partners</span><strong id="text-total-partner" class="dashboard-metric-value">—</strong></div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="dashboard-metric-card">
                    <span class="dashboard-metric-icon"><i class="material-icons">groups</i></span>
                    <div><span class="dashboard-metric-label">Approved Customers</span><strong id="text-total-customer" class="dashboard-metric-value">—</strong></div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="dashboard-metric-card">
                    <span class="dashboard-metric-icon"><i class="material-icons">storefront</i></span>
                    <div><span class="dashboard-metric-label">Approved Vendors</span><strong id="text-total-vendor" class="dashboard-metric-value">—</strong></div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="dashboard-metric-card">
                    <span class="dashboard-metric-icon"><i class="material-icons">schedule</i></span>
                    <div><span class="dashboard-metric-label">Pending Approvals</span><strong id="pending-approval" class="dashboard-metric-value">—</strong></div>
                </div>
            </div>
        </section>

        <section class="row mb-4">
            <div class="col-lg-7 mb-3">
                <article class="dashboard-panel">
                    <header class="dashboard-panel-header"><h2>Approved Partners by Province</h2></header>
                    <div class="dashboard-panel-body">
                        <div id="province-chart" class="dashboard-chart-box"></div>
                        <div id="province-chart-empty" class="dashboard-empty-state d-none">No province distribution is available for this access scope.</div>
                    </div>
                </article>
            </div>
            <div class="col-lg-5 mb-3">
                <article class="dashboard-panel">
                    <header class="dashboard-panel-header"><h2>Approved Partner Types</h2></header>
                    <div class="dashboard-panel-body">
                        <div id="partner-type-chart"></div>
                        <div id="partner-type-empty" class="dashboard-empty-state d-none">No approved partner data.</div>
                    </div>
                </article>
            </div>
        </section>

        <section class="row mb-4">
            @can('viewCustomer', \App\Models\CompanyInformation::class)
                <div class="col-lg-6 mb-3">
                    <article class="dashboard-panel">
                        <header class="dashboard-panel-header"><h2>Recent Customers</h2></header>
                        <div class="dashboard-panel-body table-responsive">
                            <table id="table_recent_list_customer" class="table table-hover dashboard-table">
                                <thead><tr><th>Name</th><th>Email</th><th>Contact</th><th>Status</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </article>
                </div>
            @endcan
            @can('viewVendor', \App\Models\CompanyInformation::class)
                <div class="col-lg-6 mb-3">
                    <article class="dashboard-panel">
                        <header class="dashboard-panel-header"><h2>Recent Vendors</h2></header>
                        <div class="dashboard-panel-body table-responsive">
                            <table id="table_recent_list_vendor" class="table table-hover dashboard-table">
                                <thead><tr><th>Name</th><th>Email</th><th>Contact</th><th>Status</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </article>
                </div>
            @endcan
        </section>

        <section class="row mb-4">
            <div class="col-12">
                <article class="dashboard-panel">
                    <header class="dashboard-panel-header"><h2>Partner Locations</h2></header>
                    <div class="dashboard-panel-body">
                        <div id="dashboard-map"></div>
                        <div id="dashboard-map-empty" class="dashboard-empty-state d-none">No partner locations with valid coordinates.</div>
                    </div>
                </article>
            </div>
        </section>

        <div class="modal fade" id="regencyModal" tabindex="-1" role="dialog" aria-labelledby="regencyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="regencyModalLabel">Partner distribution</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="regency-chart"></div>
                        <div id="regency-chart-empty" class="dashboard-empty-state d-none">No city distribution is available.</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/cdn/leaflet.js') }}"></script>
    @include('admin.dashboard.live_js')
@endpush
