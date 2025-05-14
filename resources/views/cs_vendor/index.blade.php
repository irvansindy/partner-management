@extends('adminlte::page')

@section('title', 'Partner Management')

@section('content_header')
    <h1>Form Register</h1>
@stop

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="container-fluid">
                <form action="" method="" id="form_company">
                    @include('cs_vendor.form.master_information')
                    {{-- end master --}}
                    {{-- dynamic form income statement --}}
                    <div class="dynamic-form-income-statement">
                        
                    </div>
                    {{-- end dynamic form income statement --}}
                    {{-- company bank --}}
                    @include('cs_vendor.form.bank')
                    {{-- end company bank --}}

                    {{-- company tax --}}
                    @include('cs_vendor.form.tax')
                    {{-- end company tax --}}
                </form>
            </div>
        </div>
        {{-- @include('cs_vendor.detail_partner') --}}
    </div>
@stop

@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('cs_vendor.partner_js')
@stop
