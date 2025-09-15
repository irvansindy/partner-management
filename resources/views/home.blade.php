@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
    <div class="mb-2"></div>
@stop

@section('content')
    <div class="container mt-2">
        <!-- Company Info Accordion -->
        <div class="accordion mb-4" id="companyInfoAccordion">
            <div class="card shadow-sm">
                <div class="card-header bg-light position-relative" id="headingInfo">
                    <strong class="text-left text-dark" data-toggle="collapse" data-target="#collapseInfo" aria-expanded="true" aria-controls="collapseInfo" style="cursor: pointer;">
                        🏢 Informasi Perusahaan
                    </strong>
                    <button class="btn btn-sm btn-outline-primary position-absolute mr-1" id="detail_company_information" data-toggle="modal" data-target="#dataDetailPartner" style="right: 15px; top: 10px;" data-partner_id="">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>

                <div id="collapseInfo" class="collapse show" aria-labelledby="headingInfo"
                    data-parent="#companyInfoAccordion">
                    <div class="card-body">
                        <div class="row text-dark">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-building text-secondary"></i> Company Name:</strong>
                                    <span id="data-company-name"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-users text-secondary"></i> Group Name:</strong>
                                    <span id="data-company-group-name"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-calendar-alt text-secondary"></i> Established Year:</strong>
                                    <span id="data-company-established-year"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-user-tie text-secondary"></i> Company Type:</strong>
                                    <span id="data-company-type"></span>
                                </div>
                            </div>

                            <!-- Detail Kelengkapan Tambahan -->
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-users-cog text-secondary"></i> Total Employee:</strong>
                                    <span id="data-company-total-employee"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-user-check text-secondary"></i> Liable Person:</strong>
                                    <span id="data-company-liable-person"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-user text-secondary"></i> Owner Name:</strong>
                                    <span id="data-company-owner-name"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-users text-secondary"></i> Board of Directors:</strong>
                                    <span id="data-company-board-of-directors"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-chart-pie text-secondary"></i> Major Shareholders:</strong>
                                    <span id="data-company-major-shareholders"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-industry text-secondary"></i> Business Classification:</strong>
                                    <span id="data-company-business-classification"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-tasks text-secondary"></i> Business Detail:</strong>
                                    <span id="data-company-business-detail"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-globe text-secondary"></i> Website:</strong>
                                    <span id="data-company-website"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-cogs text-secondary"></i> System Management:</strong>
                                    <span id="data-company-system-management"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-user-circle text-secondary"></i> Contact Person:</strong>
                                    <span id="data-company-contact-person"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-language text-secondary"></i> Language:</strong>
                                    <span id="data-company-language"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-envelope text-secondary"></i> Email:</strong>
                                    <span id="data-company-email"></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fas fa-exclamation-circle text-secondary"></i> Status:</strong>
                                    <span id="data-company-status"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Data Completeness - List JSON Based -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <strong>📋 Detail Data Perusahaan</strong>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <!-- Address -->
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong><i class="fas fa-map-marker-alt text-primary"></i> Alamat:</strong>
                            <ul class="mt-2 list-data-address">
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="detail_company_address" data-toggle="modal" data-target="#dataDetailPartnerAddress" data-partner_id=""><i class="fas fa-edit"></i></button>
                    </li>

                    <!-- Bank Info -->
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong><i class="fas fa-university text-success"></i> Informasi Bank:</strong>
                            <ul class="mt-2 list-data-bank">
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="detail_company_bank" data-toggle="modal" data-target="#dataDetailPartnerBank" data-partner_id=""><i class="fas fa-edit"></i></button>
                    </li>

                    <!-- Tax Info -->
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong><i class="fas fa-receipt text-warning"></i> NPWP & Pajak:</strong>
                            <ul class="mt-2 list-data-tax">
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="detail_company_tax" data-toggle="modal" data-target="#dataDetailPartnerTax" data-partner_id=""><i class="fas fa-edit"></i></button>
                    </li>

                    <!-- Legal Document / Attachment -->
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong><i class="fas fa-file-alt text-danger"></i> Dokumen Legal/Pendukung:</strong>
                            <ul class="mt-2 list-data-attachment text-muted">
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add_data_support_document" data-toggle="modal" data-target="#modal_support_document" data-partner_id="" style="right: 15px; top: 10px;"><i class="fas fa-edit"></i></button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Recent Activity -->
        {{-- <div class="card shadow-sm">
            <div class="card-header bg-light">
                <strong><i class="fas fa-bell text-warning"></i> Aktivitas Terakhir</strong>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li>• Anda mengupdate data perusahaan (2 hari lalu)</li>
                    <li>• Dokumen SIUP disetujui (1 minggu lalu)</li>
                    <li>• Admin meminta revisi dokumen TDP</li>
                </ul>
            </div>
        </div> --}}
        <!-- Recent Activity -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <strong><i class="fas fa-bell text-warning"></i> Aktivitas Terakhir</strong>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0" id="activity-list">
                    <li class="text-muted">Memuat aktivitas...</li>
                </ul>
                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center mt-3" id="activity-pagination">
                        <!-- pagination will be injected here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    @include('cs_vendor.modal_detail_company_information')
    @include('cs_vendor.modal_detail_address')
    @include('cs_vendor.modal_detail_bank')
    @include('cs_vendor.modal_detail_tax')
    @include('cs_vendor.modal_alert_data_null')
    @include('cs_vendor.support_docx_modal')
    @include('cs_vendor.modal_detail_attachment')
    @include('loading')
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .toasts-top-right {
            z-index: 9999 !important;
            /* position: relative; */
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 32px !important;
        }
    </style>
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@stop

@section('js')
    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('users.partner_js')
@stop
