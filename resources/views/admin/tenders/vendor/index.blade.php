@extends('adminlte::page')

@section('title', 'Tender for Vendor')

@section('content_header')
<div class="row mb-4 mt-2">
    <div class="col-md-6 text-left">
        <h4 style="font-weight: 700;">Tender for Vendor</h4>
        {{-- <p>This is the content in the left column, aligned to the left.</p> --}}
    </div>
    <!-- Right-aligned column -->
    <div class="col-md-6 text-right">
        <div class=" d-flex justify-content-end">
            <button type="button" class="btn bg-gradient-primary" id="for_create_tender_vendor" data-toggle="modal" data-target="#modal_create_tender_vendor"><i class="fas fa-plus"></i></button>
        </div>
    </div>
</div>
@stop

@section('content')
    <div class="container-fluid py-2">
        
        <div class="row" id="list_tender_vendor">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8 text-truncate">
                                <span class="number-tender-vendor-style">Number Tender for Vendor</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-sm bg-gradient-primary text-right" id="for_edit_tender_vendor" data-toggle="modal" data-target="#edit_tender_vendor"><i class="fas fa-edit"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 py-2" id="data_eula">
                    </div>
                </div>
            </div>
        </div>
        @include('admin.tenders.vendor.modal_create_tender_vendor')
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

        .number-tender-vendor-style {
            white-space: nowrap;
            overflow: hidden;
            font-weight: 600;
            font-size: 12px;
            text-overflow: ellipsis;
            display: block; /* Ensure text-truncate applies to block element */
        }
    </style>
@stop
{{-- @section('plugins.moment', true) --}}
@section('js')
    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- summernote --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
    {{-- moment js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js" integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/id.min.js" integrity="sha512-he8U4ic6kf3kustvJfiERUpojM8barHoz0WYpAUDWQVn61efpm3aVAD8RWL8OloaDDzMZ1gZiubF9OSdYBqHfQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @include('admin.tenders.vendor.tenders_vendor_js')
@stop
