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
        const formToken = "{{ $formLink->token ?? '' }}";
        const STORAGE_KEY = "partner_form_" + formToken;

        /* Escape untuk name selector dengan bracket */
        function escapeName(name) {
            return name.replace(/([\[\]])/g, "\\$1");
        }

        /* Generate row dinamis jika belum ada */
        function ensureRowExists(baseName, index) {
            const container = $('[data-repeat="' + baseName + '"]');
            if (!container.length) {
                // Coba cari button add berdasarkan ID yang sesuai
                let addButton = null;

                if (baseName === 'address') {
                    addButton = $('#add_dynamic_address');
                } else if (baseName === 'bank_name' || baseName === 'account_name' || baseName === 'account_number') {
                    addButton = $('#add_bank');
                } else if (baseName.includes('contact_')) {
                    addButton = $('#add_contact');
                } else if (baseName === 'liable_person' || baseName === 'liable_position' || baseName === 'nik') {
                    addButton = $('#add_liable_person');
                } else if (baseName.includes('survey')) {
                    addButton = $('#add_survey_data');
                }

                if (!addButton || !addButton.length) return;

                // Hitung jumlah field yang ada
                let selector = `[name="${escapeName(baseName + '[]')}"]`;
                let currentCount = $(selector).length;

                // Tambahkan row sampai mencapai index yang dibutuhkan
                while (currentCount <= index) {
                    addButton.trigger("click");
                    currentCount++;
                }
                return;
            }

            let rows = container.find(".repeat-row");
            let currentCount = rows.length;

            while (currentCount <= index) {
                container.find(".btn-add-row").trigger("click");
                currentCount++;
            }
        }

        /* SAVE FORM SECARA OTOMATIS */
        function saveForm() {
            let data = {};

            $("#form_company")
                .find("input, textarea, select")
                .each(function() {
                    if (!this.name) return;
                    if (this.type === "file") return;
                    if (this.name === "company_type") return;

                    let fieldName = this.name;
                    let fieldValue = null;

                    // Handle berbagai tipe input
                    if (this.type === "checkbox") {
                        fieldValue = $(this).is(":checked");
                    } else if (this.type === "radio") {
                        if (!$(this).is(":checked")) return; // Skip radio yang tidak terpilih
                        fieldValue = $(this).val();
                    } else {
                        fieldValue = $(this).val();
                    }

                    // Cek apakah field adalah array (mengandung [])
                    if (fieldName.includes('[]')) {
                        // Bersihkan nama untuk key
                        let cleanName = fieldName.replace('[]', '');

                        // Inisialisasi array jika belum ada
                        if (!data[cleanName]) {
                            data[cleanName] = [];
                        }

                        // Push value ke array
                        data[cleanName].push(fieldValue);
                    } else {
                        // Non-array field, simpan langsung
                        data[fieldName] = fieldValue;
                    }
                });

            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            console.log("Form saved:", data); // Debug
        }

        /* RESTORE FORM */
        function restoreForm() {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (!saved) return;

            let data = JSON.parse(saved);
            console.log("Restoring form:", data); // Debug

            for (let key in data) {
                const value = data[key];

                if (key === "company_type") continue;

                // Jika value adalah array
                if (Array.isArray(value)) {
                    value.forEach((val, index) => {
                        // Generate selector untuk field array dengan index
                        let selector = `[name="${escapeName(key + '[]')}"]`;
                        let $els = $(selector);

                        // Pastikan row tersedia (untuk dynamic fields)
                        if (index > 0) {
                            ensureRowExists(key, index);
                            // Refresh selector setelah row dibuat
                            $els = $(selector);
                        }

                        // Set value ke elemen dengan index yang sesuai
                        if ($els.length > index) {
                            let $el = $els.eq(index);
                            const type = $el.attr("type");

                            if (type === "checkbox") {
                                $el.prop("checked", !!val).trigger("change");
                            } else if (type === "radio") {
                                if ($el.val() == val) {
                                    $el.prop("checked", true).trigger("change");
                                }
                            } else {
                                $el.val(val).trigger("change");
                            }

                            // select2 fix
                            if ($el.hasClass("select2-hidden-accessible")) {
                                $el.trigger("change.select2");
                            }
                        }
                    });
                } else {
                    // Non-array field
                    let escaped = escapeName(key);
                    let $els = $(`[name="${escaped}"]`);

                    if ($els.length === 0) continue;

                    $els.each(function() {
                        const $el = $(this);
                        const type = $el.attr("type");

                        if (type === "checkbox") {
                            $el.prop("checked", !!value).trigger("change");
                        } else if (type === "radio") {
                            if ($el.val() == value) {
                                $el.prop("checked", true).trigger("change");
                            }
                        } else {
                            $el.val(value).trigger("change");
                        }

                        // select2 fix
                        if ($el.hasClass("select2-hidden-accessible")) {
                            $el.trigger("change.select2");
                        }
                    });
                }
            }
        }

        // Event listener untuk auto-save
        $(document).on("input change", "#form_company input, #form_company textarea, #form_company select", function() {
            saveForm();
        });

        // Restore saat halaman dimuat
        $(document).ready(function() {
            // Tunggu sebentar agar semua element sudah terinisialisasi
            setTimeout(function() {
                restoreForm();
            }, 500);
        });

        /* Clear setelah submit */
        function clearSavedForm() {
            localStorage.removeItem(STORAGE_KEY);
            console.log("Form data cleared from localStorage");
        }
    </script>

    @include('cs_vendor.partner_js')
@stop
