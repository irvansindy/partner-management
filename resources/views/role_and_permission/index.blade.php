@extends('adminlte::page')

@section('title', 'Role and Permission')

@section('content_header')
    <h1>Role and Permission</h1>
@stop

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex bd-highlight">
                        <div class="pt-2 flex-fill bd-highlight">
                            <p>Role Management</p>
                        </div>
                        <div class="p-2 flex-fill bd-highlight d-flex justify-content-end" style="float: right !important;">
                            <button type="button" class="btn bg-gradient-primary" id="for_create_role" data-toggle="modal" data-target="#formCreateRole">+ Role</button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-2 pb-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table table-hover align-items-center mb-0 data_tables" id="role_table">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Name
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
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex bd-highlight">
                        <div class="pt-2 flex-fill bd-highlight">
                            <p>Permission Management</p>
                        </div>
                        <div class="p-2 flex-fill bd-highlight d-flex justify-content-end" style="float: right !important;">
                            <button type="button" class="btn bg-gradient-primary" id="for_create_permission" data-toggle="modal" data-target="#formCreatePermission">+ Permission</button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-2 pb-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table table-hover align-items-center mb-0 data_tables" id="permission_table">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Name
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
        @include('role_and_permission.create_role')
        @include('role_and_permission.detail_role')
        @include('role_and_permission.add_permission')
        @include('role_and_permission.create_permission')
        @include('role_and_permission.detail_permission')
    </div>
@stop

@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('role_and_permission.role_permission_js')
@stop
