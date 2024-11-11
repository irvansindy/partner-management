@extends('adminlte::page')

@section('title', 'License Agreements - Term and Conditions')

@section('content_header')
    <h1>End User License Aggrement</h1>
@stop

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex bd-highlight">
                        {{-- <div class="pt-2 flex-fill bd-highlight">
                            <p>list Menu Permission</p>
                        </div> --}}
                        <div class="p-2 flex-fill bd-highlight d-flex justify-content-end" style="float: right !important;">
                            <button type="button" class="btn bg-gradient-primary" id="for_create_eula"
                                data-toggle="modal" data-target="#AddOrEditEULA"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>
                    <div class="card-body px-4 py-2" id="data_eula">
                    </div>
                    <div class="card-body px-4 py-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table table-hover align-items-center mb-0 data_tables" id="eula_tnc_table" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Name T & C
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Year
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status
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
        @include('admin.eula.add_or_edit_eula')
    </div>
@stop

@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- summernote --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }

        .toasts-top-right {
            z-index: 9999 !important;
        }
    </style>
@stop

@section('js')
    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- summernote --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
    @include('admin.eula.eula_js')
@stop
