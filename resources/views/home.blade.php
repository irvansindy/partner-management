@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
    <div class="mb-2"></div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- main-data-company --}}
        <div class="row" style="cursor: pointer;">
            <div class="col-md-3 col-sm-6 col-xs-12" id="card-info-company-name"
                data-title="Double click untuk melihat detail data">
                <div class="info-box">
                    <span class="info-box-icon bg-primary">
                        <i class="fas fa-fw fa-user"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold">Company Name</span>
                        <span class="info-box-text" id="data-company-name">90<small>%</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12" id="card-info-company-group-name">
                <div class="info-box">
                    <span class="info-box-icon bg-teal">
                        <i class="fas fa-fw fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold">Group Name</span>
                        <span class="info-box-text" id="data-company-group-name">90<small>%</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12" id="card-info-company-established-year">
                <div class="info-box">
                    <span class="info-box-icon bg-orange">
                        <i class="fas fa-fw fa-user"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold">Established Year</span>
                        <span class="info-box-text" id="data-company-established-year">90<small>%</small></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12" id="card-info-company-type">
                <div class="info-box">
                    <span class="info-box-icon bg-maroon">
                        <i class="fas fa-fw fa-user"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold">Company Type</span>
                        <span class="info-box-text" id="data-company-type">90<small>%</small></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tooltip container -->
        <div id="tooltip"
            style="display:none; position:absolute; background-color:#333; color:#fff; padding:5px; border-radius:5px; font-size:14px;">
        </div>

    </div>

    {{-- table attachment --}}
    <x-adminlte-card title="Supporting Document" theme="info" icon="fas fa-lg fa-file" collapsible maximizable>
        <button class="btn btn-sm btn-outline-primary mr-2 mb-2" id="add_data_support_document" data-toggle="modal"
            data-target="#modal_support_document" style="float: right;">+ <i class="fas fa-file"></i></button>
        <div class="table-responsive p-2 px-md-2 fluid">
            <table class="table table-hover align-items-center mb-0 data_tables" id="company_support_document"
                style="width: 100%">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            No
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Document Name
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Document Company Type
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Link
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        No
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Document Name
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Document Company Type
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Link
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Action
                    </th>
                </tfoot>
            </table>
        </div>
    </x-adminlte-card>
    {{-- <p>Kamu belum mendaftakan diri sebagai Partner <br> silahkan klik tombol di bawah untuk registrasi</p>
    <button type="button" class="btn btn-primary" id="create_partner" data-toggle="modal"
        data-target="#modalCreatePartner">
        Registrasi

    </button> --}}
    @include('cs_vendor.detail_data')
    @include('cs_vendor.modal_alert_data_null')
    @include('cs_vendor.support_docx_modal')
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
