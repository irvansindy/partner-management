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

                    <form
                        action="{{ isset($formLink) ? route('public.form.submit', $formLink->token) : route('submit-partner') }}"
                        method="POST" id="form_company" enctype="multipart/form-data"
                        data-storage-key="partner_form_{{ $formLink->token ?? 'default' }}">
                        @csrf

                        @if (isset($formLink))
                            <input type="hidden" name="company_type" value="{{ $formLink->form_type }}">
                        @endif
                        <input type="hidden" id="public_key" value="{{ env('PUBLIC_FORM_SECRET') }}">

                        @include('cs_vendor.form.master_information')
                        @include('cs_vendor.form.contact')
                        @include('cs_vendor.form.address')
                        @include('cs_vendor.form.bank')

                        {{-- Survey Toggle Switch --}}
                        <div class="card card-outline card-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="fas fa-clipboard-check"></i> @lang('messages.Survey Result')
                                        </h5>
                                        <small class="text-muted">
                                            Survey data membantu kami memahami kebutuhan bisnis Anda lebih baik
                                        </small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="survey_form_switch"
                                            {{ !isset($formLink) || $formLink->form_type === 'customer' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="survey_form_switch">
                                            <span id="switch_label">
                                                {{ !isset($formLink) || $formLink->form_type === 'customer' ? 'Hide Survey' : 'Show Survey' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Survey Form Container --}}
                        <div id="survey_form_container"
                            style="display: {{ !isset($formLink) || $formLink->form_type === 'customer' ? 'block' : 'none' }};">
                            @include('cs_vendor.form.survey')
                        </div>

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

@push('css')
    <style>
        #survey_form_container {
            transition: all 0.3s ease-in-out;
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-check-input:not(:checked) {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }

        .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }
    </style>
@endpush

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

                let selector = `[name="${escapeName(baseName + '[]')}"]`;
                let currentCount = $(selector).length;

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

        /* SAVE FORM - EXCLUDE SURVEY FIELDS */
        function saveForm() {
            let data = {};

            $("#form_company")
                .find("input, textarea, select")
                .each(function() {
                    if (!this.name) return;
                    if (this.type === "file") return;
                    if (this.name === "company_type") return;

                    // ❌ SKIP survey fields - akan disimpan terpisah
                    if (this.name.includes('survey') ||
                        this.name.includes('ownership_status') ||
                        this.name.includes('product_survey') ||
                        this.name.includes('merk_survey') ||
                        this.name.includes('distributor_survey')) {
                        return;
                    }

                    let fieldName = this.name;
                    let fieldValue = null;

                    if (this.type === "checkbox") {
                        fieldValue = $(this).is(":checked");
                    } else if (this.type === "radio") {
                        if (!$(this).is(":checked")) return;
                        fieldValue = $(this).val();
                    } else {
                        fieldValue = $(this).val();
                    }

                    if (fieldName.includes('[]')) {
                        let cleanName = fieldName.replace('[]', '');
                        if (!data[cleanName]) {
                            data[cleanName] = [];
                        }
                        data[cleanName].push(fieldValue);
                    } else {
                        data[fieldName] = fieldValue;
                    }
                });

            data['survey_form_switch'] = $('#survey_form_switch').is(':checked');

            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            console.log("📝 Form saved (excluding survey):", data);
        }

        /* RESTORE FORM - EXCLUDE SURVEY FIELDS */
        function restoreForm() {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (!saved) return;

            let data = JSON.parse(saved);
            console.log("🔄 Restoring form:", data);

            for (let key in data) {
                const value = data[key];

                if (key === "company_type") continue;

                // Handle survey switch
                if (key === "survey_form_switch") {
                    $('#survey_form_switch').prop('checked', value);

                    if (value) {
                        $('#survey_form_container').show();
                        $('#switch_label').text('Hide Survey');
                    } else {
                        $('#survey_form_container').empty().hide();
                        $('#switch_label').text('Show Survey');
                    }
                    continue;
                }

                if (Array.isArray(value)) {
                    value.forEach((val, index) => {
                        let selector = `[name="${escapeName(key + '[]')}"]`;
                        let $els = $(selector);

                        if (index > 0) {
                            ensureRowExists(key, index);
                            $els = $(selector);
                        }

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

                            if ($el.hasClass("select2-hidden-accessible")) {
                                $el.trigger("change.select2");
                            }
                        }
                    });
                } else {
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

                        if ($el.hasClass("select2-hidden-accessible")) {
                            $el.trigger("change.select2");
                        }
                    });
                }
            }
        }

        // Event listener untuk auto-save (EXCLUDE survey fields)
        $(document).on("input change", "#form_company input, #form_company textarea, #form_company select", function() {
            // Skip jika field dari survey
            if ($(this).closest('#survey_form_container').length > 0) {
                return;
            }
            saveForm();
        });

        $(document).on("change", "#survey_form_switch", function() {
            saveForm();
        });

        /* Clear setelah submit */
        function clearSavedForm() {
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(STORAGE_KEY + '_survey');
            console.log("✅ Form data cleared from localStorage");
        }

        // ============================================
        // DYNAMIC SURVEY TOGGLE - REMOVE & APPEND
        // ============================================
        $(document).ready(function() {
            // Simpan HTML survey form saat pertama kali load
            const surveyFormHTML = $('#survey_form_container').html();
            let isSurveyVisible = $('#survey_form_switch').is(':checked');

            // Jika awal tidak checked, langsung hapus form
            if (!isSurveyVisible) {
                $('#survey_form_container').empty();
            }

            // Handler untuk switch button
            $('#survey_form_switch').on('change', function() {
                const isChecked = $(this).is(':checked');

                if (isChecked) {
                    // SHOW: Append kembali survey form
                    $('#survey_form_container').html(surveyFormHTML).hide().slideDown(300);
                    $('#switch_label').text('Hide Survey');

                    // Restore data survey dari localStorage jika ada
                    restoreSurveyData();

                    console.log('✅ Survey form added to DOM');
                } else {
                    // HIDE: Konfirmasi dan remove survey form
                    Swal.fire({
                        title: 'Hide Survey?',
                        text: "Survey data will not be submitted to the server",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, hide it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Simpan data survey sebelum dihapus
                            saveSurveyData();

                            // Remove dari DOM
                            $('#survey_form_container').slideUp(300, function() {
                                $(this).empty();
                                console.log('❌ Survey form removed from DOM');
                            });

                            $('#switch_label').text('Show Survey');

                            Swal.fire({
                                icon: 'success',
                                title: 'Survey Hidden',
                                text: 'Survey fields will not be submitted',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            // User cancel, kembalikan switch ke checked
                            $('#survey_form_switch').prop('checked', true);
                        }
                    });
                }

                saveForm();
            });

            // Fungsi untuk save data survey ke localStorage terpisah
            function saveSurveyData() {
                let surveyData = {};

                $('#survey_form_container').find('input, textarea, select').each(function() {
                    if (!this.name) return;

                    let fieldName = this.name;
                    let fieldValue = null;

                    if (this.type === "checkbox") {
                        fieldValue = $(this).is(":checked");
                    } else if (this.type === "radio") {
                        if (!$(this).is(":checked")) return;
                        fieldValue = $(this).val();
                    } else {
                        fieldValue = $(this).val();
                    }

                    if (fieldName.includes('[]')) {
                        let cleanName = fieldName.replace('[]', '');
                        if (!surveyData[cleanName]) {
                            surveyData[cleanName] = [];
                        }
                        surveyData[cleanName].push(fieldValue);
                    } else {
                        surveyData[fieldName] = fieldValue;
                    }
                });

                localStorage.setItem(STORAGE_KEY + '_survey', JSON.stringify(surveyData));
                console.log('💾 Survey data saved separately:', surveyData);
            }

            // Fungsi untuk restore data survey
            function restoreSurveyData() {
                const savedSurvey = localStorage.getItem(STORAGE_KEY + '_survey');
                if (!savedSurvey) return;

                let surveyData = JSON.parse(savedSurvey);
                console.log('🔄 Restoring survey data:', surveyData);

                setTimeout(function() {
                    for (let key in surveyData) {
                        const value = surveyData[key];

                        if (Array.isArray(value)) {
                            value.forEach((val, index) => {
                                let selector = `[name="${escapeName(key + '[]')}"]`;
                                let $els = $(selector);

                                if (index > 0 && key.includes('survey')) {
                                    ensureRowExists(key, index);
                                    $els = $(selector);
                                }

                                if ($els.length > index) {
                                    let $el = $els.eq(index);
                                    const type = $el.attr("type");

                                    if (type === "checkbox") {
                                        $el.prop("checked", !!val);
                                    } else if (type === "radio") {
                                        if ($el.val() == val) {
                                            $el.prop("checked", true);
                                        }
                                    } else {
                                        $el.val(val);
                                    }
                                }
                            });
                        } else {
                            let $els = $(`[name="${escapeName(key)}"]`);

                            $els.each(function() {
                                const type = $(this).attr("type");

                                if (type === "checkbox") {
                                    $(this).prop("checked", !!value);
                                } else if (type === "radio") {
                                    if ($(this).val() == value) {
                                        $(this).prop("checked", true);
                                    }
                                } else {
                                    $(this).val(value);
                                }
                            });
                        }
                    }
                }, 300);
            }

            // Restore form setelah halaman dimuat
            setTimeout(restoreForm, 500);
        });
    </script>

    @include('cs_vendor.partner_js')
@stop