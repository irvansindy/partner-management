{{-- HAPUS @extends('adminlte::master') - hanya pakai satu extend! --}}

@extends('adminlte::page')

@section('title', 'Menu Management')

@section('content_header')
    <h1>Menu Management</h1>
@stop

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex bd-highlight">
                        <div class="pt-2 flex-fill bd-highlight">
                            <p>List Menu Permission</p>
                        </div>
                        <div class="p-2 flex-fill bd-highlight d-flex justify-content-end" style="float: right !important;">
                            <button type="button" class="btn bg-gradient-primary" id="for_create_menu" data-toggle="modal" data-target="#formCreateMenu">+ Menu</button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-2 pb-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table table-hover align-items-center mb-0 data_tables" id="menu_table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Url</th>
                                        <th class="text-secondary opacity-7">#</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.menus.create')
        @include('admin.menus.modal_list_submenu')
    </div>

    {{-- Floating Locale Button --}}
    <div class="dropdown show floating-locale-btn">
        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ strtoupper(app()->getLocale()) }}
            <i class="fa fas-language"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @foreach (config('locales.supported') as $code => $label)
                <a class="dropdown-item" href="{{ route('locale.switch', $code) }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
@stop

@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
        .toasts-top-right {
            z-index: 9999 !important;
        }

        /* Floating Locale Button */
        .floating-locale-btn {
            position: fixed;
            right: 30px;
            bottom: 30px;
            z-index: 1050;
        }

        .floating-locale-btn .dropdown-toggle {
            border-radius: 50%;
            width: 56px;
            height: 56px;
            padding: 0;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@stop
@push('js')
    <!-- jQuery -->
    <script src="{{ asset('vendor/jquery/jquery.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@endpush
@section('js')
    {{-- JANGAN load jQuery lagi! AdminLTE sudah include jQuery --}}
    {{-- <script src="{{ asset('vendor/jquery/jquery.js') }}"></script> HAPUS INI! --}}

    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @include('admin.menus.menu_js')
@stop