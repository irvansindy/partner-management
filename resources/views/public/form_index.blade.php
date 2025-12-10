@extends('layouts.public')

@section('title', isset($formLink) ? $formLink->title : 'Partner Registration')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-file-alt"></i>
                        @if (isset($formLink))
                            {{ $formLink->title }}
                        @else
                            @lang('messages.Form Register')
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    @if (isset($formLink) && $formLink->description)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> {{ $formLink->description }}
                        </div>
                    @endif

                    <form action="{{ isset($formLink) ? route('public.form.submit', $formLink->token) : '' }}" method="POST"
                        id="form_company" enctype="multipart/form-data">
                        @csrf

                        @if (isset($formLink))
                        <!-- field hidden untuk dikirim ke backend -->
                            <input type="hidden" name="company_type" value="{{ $formLink->form_type }}">

                        @endif
                        <input type="hidden" id="public_key" value="{{ env('PUBLIC_FORM_SECRET') }}">

                        @include('cs_vendor.form.master_information')

                        @include('cs_vendor.form.contact')

                        @include('cs_vendor.form.address')

                        @include('cs_vendor.form.bank')

                        @if (!isset($formLink) || $formLink->form_type === 'customer')
                            @include('cs_vendor.form.survey')
                        @endif

                        @include('cs_vendor.form.file_upload')

                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn-primary btn-lg" id="btn_submit_data_company">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Ambil token form dari Laravel
        const formToken = "{{ $formLink->token ?? '' }}";

        // Buat session unik per tab (agar tab 1 & tab 2 tidak konflik)
        let sessionId = sessionStorage.getItem("form_session_id");

        if (!sessionId) {
            sessionId = Math.random().toString(36).substring(2, 15);
            sessionStorage.setItem("form_session_id", sessionId);
        }

        // STORAGE KEY unik per user, per tab, per form
        const STORAGE_KEY = "partner_form_" + formToken + "_" + sessionId;

        // =========================================================
        // 1) AUTO SAVE (setiap input berubah)
        // =========================================================
        function autoSaveForm() {
            let formData = {};

            $('#form_company')
                .find('input, textarea, select')
                .each(function() {

                    if (this.type === "file") return;

                    // FIX: jangan simpan company_type
                    if (this.name === "company_type") return;

                    formData[this.name] = $(this).val();
                });

            localStorage.setItem(STORAGE_KEY, JSON.stringify(formData));
        }


        $(document).on('input change', '#form_company input, #form_company textarea, #form_company select', function() {
            autoSaveForm();
        });

        // =========================================================
        // 2) LOAD DATA KETIKA HALAMAN DIBUKA
        // =========================================================
        function loadSavedForm() {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (!saved) return;

            const data = JSON.parse(saved);

            $('#form_company')
                .find('input, textarea, select')
                .each(function() {

                    if (this.type === "file") return;

                    // FIX: jangan timpa company_type
                    if (this.name === "company_type") return;

                    if (data[this.name] !== undefined) {
                        $(this).val(data[this.name]);
                    }
                });
        }


        $(document).ready(function() {
            loadSavedForm();
        });
    </script>
    @include('cs_vendor.partner_js')
@stop
