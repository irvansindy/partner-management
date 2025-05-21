@extends('adminlte::page')

@section('title', 'IP Address Management')

@section('content_header')
    <h1>IP Address Management</h1>
@stop

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex bd-highlight">
                        <div class="pt-2 flex-fill bd-highlight">
                            <p>List IP Address</p>
                        </div>
                        <div class="p-2 flex-fill bd-highlight d-flex justify-content-end" style="float: right !important;">
                            <button type="button" class="btn bg-gradient-primary" id="for_create_ip_address" data-toggle="modal" data-target="#formIpAddress">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-2 pb-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table table-hover align-items-center mb-0 data_tables" id="ip_address_table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            IP Address
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Description
                                        </th>
                                        <th class="text-secondary opacity-7">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.api_white_list.modal_api_white_list')
    </div>
@stop

@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        .toasts-top-right {
            z-index: 9999 !important;
        }
    </style>
@stop

@section('js')
    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    @include('admin.api_white_list.api_white_list_js')
@stop
