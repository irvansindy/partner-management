@extends('adminlte::page')

@section('title', 'Company Profile')

@section('content_header')
    <h1>
        List Data Partner
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header pb-2">
                <div class="row mb-2">
                    <div class="col">
                        <h6>Partner Company</h6>
                    </div>
                    <div class="col">
                        <div class="text-right">
                            <button type="button" class="btn bg-gradient-primary pull-right" id="create_partner_data" data-bs-toggle="modal" data-bs-target="#formCreateEvent" style="">+ Data</button>
                        </div>
                    </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0 px-md-2">
                    <table class="table align-items-center mb-0 data_tables" id="list_partner" width="100%">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    No
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Name
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Company Name
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Email
                                </th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
<!-- datatables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<!-- select 2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('company.company_js')
@stop
