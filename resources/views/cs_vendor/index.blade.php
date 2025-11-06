{{-- @extends('adminlte::page')

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/cdn/file_input_min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
        crossorigin="anonymous">
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
    <script src="{{ asset('js/cdn/data_table.js') }}"></script>
    <script src="{{ asset('js/cdn/select2.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input_sortable.js') }}"></script>
    @include('cs_vendor.partner_js')
@stop --}}
@extends('adminlte::page')

@section('title', isset($formLink) ? $formLink->title : 'Partner Management')

@section('content_header')
    <h1>
        @if(isset($formLink))
            {{ $formLink->title }}
        @else
            @lang('messages.Form Register')
        @endif
    </h1>
@stop

@section('content')
    <div class="container-fluid py-4">
        @if(isset($formLink))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                @if($formLink->description)
                    {{ $formLink->description }}
                @else
                    Please fill out the form below completely and accurately.
                @endif
            </div>
        @endif

        <div class="row">
            <div class="container-fluid">
                <form action="{{ isset($formLink) ? route('public.form.submit', $formLink->token) : '' }}"
                    method="POST"
                    id="form_company"
                    enctype="multipart/form-data">
                    @csrf

                    @if(isset($formLink))
                        <input type="hidden" name="company_type" value="{{ $formLink->form_type }}">
                    @endif

                    @include('cs_vendor.form.master_information')

                    @include('cs_vendor.form.contact')

                    @include('cs_vendor.form.address')

                    @include('cs_vendor.form.bank')

                    @if(!isset($formLink) || $formLink->form_type === 'customer')
                        @include('cs_vendor.form.survey')
                    @endif

                    @include('cs_vendor.form.file_upload')

                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary btn-lg" id="btn_submit_data_company">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/cdn/file_input_min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
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
    <script src="{{ asset('js/cdn/data_table.js') }}"></script>
    <script src="{{ asset('js/cdn/select2.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input_sortable.js') }}"></script>
    @include('cs_vendor.partner_js')
@stop