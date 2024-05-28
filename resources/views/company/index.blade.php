@extends('adminlte::page')

@section('title', 'Company Profile')

@section('content_header')
    <h1>List Data Partner
        <br> Seleksi Customer
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header pb-2">
                <div class="row">
                    <div class="col">
                        <h6>Partner Company</h6>
                    </div>
                    <div class="col">
                        <div class="text-right">
                            <button type="button" class="btn bg-gradient-primary pull-right" id="create_partner_data" data-bs-toggle="modal" data-bs-target="#formCreateEvent" style="">+ Data</button>
                        </div>
                    </div>
                <div class="align-items-center">
                {{-- <div class="d-flex align-items-center"> --}}
                    </div>

                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0 px-md-2">
                    <table class="table align-items-center mb-0 data_tables" id="list_partner">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    No
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Name
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Quota
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Date
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

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css') }}"> --}}
@stop

@section('js')
    @include('company.company_js')
@stop
