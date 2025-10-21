@extends('adminlte::page')

@section('title', 'Partner Management')

@section('content_header')
    <h1>@lang('messages.Form Register')</h1>
@stop

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="container-fluid">
                <form action="" method="" id="form_company">
                    @include('cs_vendor.form.master_information')

                    @include('cs_vendor.form.contact')

                    @include('cs_vendor.form.address')

                    @include('cs_vendor.form.bank')

                    @include('cs_vendor.form.survey')

                    {{-- @include('cs_vendor.form.tax') --}}

                    @include('cs_vendor.form.file_upload')

                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary" id="btn_submit_data_company">
                            submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/cdn/file_input_min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
        crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{ asset('css/cdn/file_input.css') }}"> --}}
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
    </style>
@stop

@section('js')
    <!-- datatables -->
    <script src="{{ asset('js/cdn/data_table.js') }}"></script>
    <!-- select 2 -->
    <script src="{{ asset('js/cdn/select2.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input_sortable.js') }}"></script>
    @include('cs_vendor.partner_js')
@stop
