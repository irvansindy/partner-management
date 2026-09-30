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
@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/cdn/file_input_min.css') }}">
        <style>
            .partner-entry-page {
                --entry-ink: #202638;
                --entry-muted: #70798b;
                --entry-line: #e3e8ef;
                --entry-primary: #6558d3;
                color: var(--entry-ink);
            }

            .partner-entry-heading {
                align-items: flex-end;
                display: flex;
                justify-content: space-between;
                gap: 24px;
                margin-bottom: 24px;
            }

            .partner-entry-heading h1 {
                color: var(--entry-ink);
                font-size: 28px;
                font-weight: 600;
                line-height: 1.2;
                margin: 6px 0;
                overflow-wrap: anywhere;
            }

            .partner-entry-heading p {
                color: var(--entry-muted);
                margin: 0;
            }

            .partner-entry-eyebrow {
                color: var(--entry-primary);
                font-size: 11px;
                font-weight: 700;
            }

            .partner-entry-type {
                background: #e8f4ef;
                border-radius: 999px;
                color: #238768;
                flex: 0 0 auto;
                font-size: 12px;
                font-weight: 700;
                padding: 9px 13px;
            }

            .partner-entry-description {
                align-items: flex-start;
                background: #fff;
                border: 1px solid var(--entry-line);
                border-left: 3px solid var(--entry-primary);
                border-radius: 8px;
                color: var(--entry-muted);
                display: flex;
                gap: 10px;
                line-height: 1.5;
                margin-bottom: 20px;
                padding: 14px 17px;
            }

            .partner-entry-description i {
                color: var(--entry-primary);
                margin-top: 3px;
            }

            .partner-entry-page #form_company > .card,
            .partner-entry-page #form_company .card {
                background: #fff;
                border: 1px solid var(--entry-line);
                border-radius: 10px;
                box-shadow: 0 3px 14px rgba(32, 38, 56, .035);
                margin-bottom: 20px;
                overflow: hidden;
            }

            .partner-entry-page #form_company .card-header {
                background: #fff;
                border-bottom: 1px solid #edf0f4;
                color: var(--entry-ink);
                padding: 17px 24px;
            }

            .partner-entry-page #form_company .card-title,
            .partner-entry-page #form_company .card-header h3,
            .partner-entry-page #form_company .card-header h5 {
                color: var(--entry-ink);
                font-size: 17px;
                font-weight: 700;
                margin: 0;
            }

            .partner-entry-page #form_company .card-header .fas,
            .partner-entry-page #form_company .card-header .fa {
                color: var(--entry-primary);
                margin-right: 7px;
            }

            .partner-entry-page #form_company .card-body {
                padding: 25px;
            }

            .partner-entry-page #form_company label {
                color: #3b4352;
                font-size: 13px;
                font-weight: 600;
                line-height: 1.35;
            }

            .partner-entry-page #form_company .form-control,
            .partner-entry-page #form_company .form-select,
            .partner-entry-page #form_company .select2-container--default .select2-selection--single {
                background-color: #fbfcfe;
                border: 1px solid #dce2eb;
                border-radius: 6px;
                color: var(--entry-ink);
                min-height: 44px;
                transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
            }

            .partner-entry-page #form_company textarea.form-control {
                min-height: 92px;
            }

            .partner-entry-page #form_company .form-control:focus,
            .partner-entry-page #form_company .form-select:focus {
                background: #fff;
                border-color: var(--entry-primary);
                box-shadow: 0 0 0 3px rgba(101, 88, 211, .12);
            }

            .partner-entry-page #form_company .partner-fieldset,
            .partner-entry-page #form_company fieldset.border {
                background: #fcfdff;
                border: 1px solid var(--entry-line) !important;
                border-radius: 9px;
                padding: 1.25rem !important;
            }

            .partner-entry-page #form_company legend {
                background: #fff;
                color: var(--entry-ink);
                font-weight: 700;
                line-height: 1.3;
                padding: 0 .5rem;
            }

            .partner-entry-page #form_company .text-muted,
            .partner-entry-page #form_company small {
                color: var(--entry-muted) !important;
                line-height: 1.4;
            }

            .partner-entry-page #form_company .btn {
                border-radius: 7px;
                font-weight: 600;
                min-height: 40px;
                white-space: nowrap;
            }

            .partner-entry-page #form_company .btn-primary {
                background: var(--entry-primary);
                border-color: var(--entry-primary);
            }

            .partner-entry-page #form_company .btn-primary:hover {
                background: #5043bd;
                border-color: #5043bd;
            }

            .partner-entry-page #form_company .form-check-input:checked {
                background-color: var(--entry-primary);
                border-color: var(--entry-primary);
            }

            .partner-entry-page #form_company .form-check-input:not(:checked) {
                background-color: #fff;
                border-color: #b8c1ce;
            }

            .partner-entry-submit {
                display: flex;
                justify-content: flex-end;
                padding: 6px 0 22px;
            }

            .partner-entry-submit .btn {
                min-width: 190px;
                padding: 11px 22px;
            }

            .partner-entry-page #form_company .partner-map {
                border: 1px solid var(--entry-line);
                box-shadow: 0 4px 14px rgba(32, 38, 56, .08);
            }

            @media (max-width: 767px) {
                .partner-entry-heading {
                    align-items: flex-start;
                    flex-direction: column;
                    gap: 12px;
                }

                .partner-entry-heading h1 {
                    font-size: 24px;
                }

                .partner-entry-page #form_company .card-body {
                    padding: 18px 16px;
                }

                .partner-entry-page #form_company .card-header {
                    padding: 15px 16px;
                }

                .partner-entry-page #form_company .partner-fieldset,
                .partner-entry-page #form_company fieldset.border {
                    padding: .9rem !important;
                }

                .partner-entry-submit {
                    justify-content: stretch;
                }

                .partner-entry-submit .btn {
                    width: 100%;
                }
            }
        </style>
    @endpush

    <div class="container-fluid partner-entry-page">
        <div class="partner-entry-heading">
            <div>
                <div class="partner-entry-eyebrow">PARTNER REGISTRATION</div>
                <h1>
                    @if (isset($formLink))
                        {{ $formLink->title }}
                    @else
                        @lang('messages.Form Register')
                    @endif
                </h1>
                <p>Enter partner and company information using the sections below.</p>
            </div>
            @if(isset($formLink))
                <span class="partner-entry-type">{{ ucfirst($formLink->form_type) }} application</span>
            @endif
        </div>

        @if(isset($formLink))
            <div class="partner-entry-description">
                <i class="fas fa-info-circle" aria-hidden="true"></i>
                <span>{{ $formLink->description ?: 'Please fill out the form below completely and accurately.' }}</span>
            </div>
        @endif

        <form action="{{ isset($formLink) ? route('public.form.submit', $formLink->token) : '' }}"
            method="POST" id="form_company" enctype="multipart/form-data">
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

            <div class="partner-entry-submit">
                <button type="button" class="btn btn-primary" id="btn_submit_data_company">
                    <i class="fas fa-paper-plane mr-1" aria-hidden="true"></i> Submit Partner
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/cdn/file_input.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input_sortable.js') }}"></script>
    @include('cs_vendor.partner_js')
@endpush