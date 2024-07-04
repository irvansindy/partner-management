@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
    <h1>Home</h1>
@stop

@section('content')
    {{-- <p>Welcome to this beautiful admin panel.</p> --}}
    <div class="data-partner"></div>
    <p>Kamu belum mendaftakan diri sebagai Partner <br> silahkan klik tombol di bawah untuk registrasi</p>
    <button type="button" class="btn btn-primary" id="create_partner" data-toggle="modal" data-target="#modalCreatePartner">
        Registrasi
    </button>
    @include('users.modal_create_partner')
    @include('loading')
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        .toasts-top-right {
            z-index: 9999 !important;
            /* position: relative; */
        }
        .select2-container .select2-selection--single {
            height: 38px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 32px !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@include('users.partner_js')
@stop
