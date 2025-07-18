@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <section class="content-header">
        <h1>
            Dashboard
            <small style="font-size: 14px">PM</small>
        </h1>
    </section>
@stop

@section('content')
    <div class="container-fluid mt-2">
        <!-- Company Info Cards -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-handshake"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Partner</span>
                        <span class="info-box-number" id="text-total-partner"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Customer</span>
                        <span class="info-box-number" id="text-total-customer"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Vendor</span>
                        <span class="info-box-number" id="text-total-vendor"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-american-sign-language-interpreting"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New Members</span>
                        <span class="info-box-number" id="text-total-user"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- End Company Info Cards -->

        <!-- Partner Table -->
        <div class="row">
            <section class="col-md-7">
                @can('viewCustomer', \App\Models\CompanyInformation::class)
                    <x-adminlte-card title="List Customer" theme="info" theme-mode="outline" collapsible maximizable>
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table table-hover no-margin align-items-center mb-0" id="table_recent_list_customer"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>No. Telp</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>No. Telp</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </x-adminlte-card>
                @endcan
                @can('viewVendor', \App\Models\CompanyInformation::class)
                    <x-adminlte-card title="List Vendor" theme="info" theme-mode="outline" collapsible maximizable>
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table table-hover no-margin align-items-center mb-0" id="table_recent_list_vendor"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>No. Telp</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>No. Telp</th>
                                        <th>Status</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </x-adminlte-card>
                @endcan
            </section>
            <section class="col-md-5">
                <!-- Pie Chart -->
                <x-adminlte-card title="Diagram Partner" theme="info" theme-mode="outline" collapsible maximizable>
                    <div class="chart-responsive">
                        <canvas id="pieChart"></canvas>
                    </div>
                </x-adminlte-card>
                <!-- End Pie Chart -->

                @can('viewApproval', \App\Models\CompanyInformation::class)
                <!-- Last Approval -->
                <x-adminlte-card title="Approvals" theme="info" theme-mode="outline" collapsible maximizable>
                    <ul class="products-list product-list-in-box" id="list_recent_approvals">
                        
                    </ul>
                </x-adminlte-card>
                <!-- End Last Approval -->
                @endcan
            </section>
        </div>
        <!-- End Partner Table -->
    </div>
@stop

@section('css')
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
@section('plugins.Chartjs', true)
@section('js')
    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- chartjs -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    </script>

    @include('admin.dashboard_js')
@stop
