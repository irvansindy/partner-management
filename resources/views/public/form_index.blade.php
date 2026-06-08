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

                        <div class="wizard-stepper mb-4" id="wizard_stepper">
                            <div class="wizard-step active" data-step="1">
                                <div class="wizard-step-number">1</div>
                                <div class="wizard-step-text">Master Information</div>
                            </div>
                            <div class="wizard-step" data-step="2">
                                <div class="wizard-step-number">2</div>
                                <div class="wizard-step-text">Contact</div>
                            </div>
                            <div class="wizard-step" data-step="3">
                                <div class="wizard-step-number">3</div>
                                <div class="wizard-step-text">Address</div>
                            </div>
                            <div class="wizard-step" data-step="4">
                                <div class="wizard-step-number">4</div>
                                <div class="wizard-step-text">Bank</div>
                            </div>
                            <div class="wizard-step" data-step="5">
                                <div class="wizard-step-number">5</div>
                                <div class="wizard-step-text">Form Survey</div>
                            </div>
                            <div class="wizard-step" data-step="6">
                                <div class="wizard-step-number">6</div>
                                <div class="wizard-step-text">Form Upload</div>
                            </div>
                        </div>

                        <div class="step-pane" data-step="1">
                            @include('cs_vendor.form.master_information')
                        </div>
                        <div class="step-pane d-none" data-step="2">
                            @include('cs_vendor.form.contact')
                        </div>
                        <div class="step-pane d-none" data-step="3">
                            @include('cs_vendor.form.address')
                        </div>
                        <div class="step-pane d-none" data-step="4">
                            @include('cs_vendor.form.bank')
                        </div>
                        <div class="step-pane d-none" data-step="5">
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

                            <div id="survey_form_container"
                                style="display: {{ !isset($formLink) || $formLink->form_type === 'customer' ? 'block' : 'none' }};">
                                @include('cs_vendor.form.survey')
                            </div>
                        </div>
                        <div class="step-pane d-none" data-step="6">
                            @include('cs_vendor.form.file_upload')
                        </div>

                        <div class="d-flex justify-content-between mt-4 align-items-center" id="wizard_actions">
                            <button type="button" class="btn btn-secondary btn-lg d-none" id="step_prev">
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <div>
                                <button type="button" class="btn btn-primary btn-lg" id="step_next">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                                <button type="button" class="btn btn-success btn-lg d-none" id="btn_submit_data_company">
                                    <i class="fas fa-paper-plane"></i> Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        #wizard_stepper {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
        }

        #wizard_stepper::before {
            content: '';
            position: absolute;
            top: calc(1rem + 21px);
            left: calc(0.5rem + 21px);
            right: calc(0.5rem + 21px);
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }

        .wizard-step {
            position: relative;
            z-index: 2;
            flex: 1 1 0;
            min-width: 120px;
            max-width: 170px;
            padding: 0.25rem 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            color: #6c757d;
            transition: transform 0.2s ease, color 0.2s ease;
            white-space: nowrap;
        }

        .wizard-step:hover {
            transform: translateY(-2px);
        }

        .wizard-step-number {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f3f5;
            border: 2px solid #dee2e6;
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #495057;
            transition: all 0.25s ease;
        }

        .wizard-step.active .wizard-step-number,
        .wizard-step.completed .wizard-step-number {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .wizard-step.active,
        .wizard-step.completed {
            color: #212529;
        }

        .wizard-step-text {
            font-size: 0.9rem;
            line-height: 1.2;
            max-width: 120px;
            word-break: break-word;
        }

        .step-pane {
            display: none;
        }

        .step-pane.active {
            display: block;
        }

        .wizard-actions button {
            min-width: 130px;
        }

        @media (max-width: 991px) {
            #wizard_stepper {
                overflow-x: auto;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            #wizard_stepper::before {
                left: 2rem;
                right: 2rem;
            }

            .wizard-step {
                min-width: 140px;
            }
        }

        @media (max-width: 576px) {
            #wizard_stepper {
                gap: 0.75rem;
            }

            .wizard-step {
                min-width: 120px;
            }

            .wizard-step-text {
                font-size: 0.8rem;
            }
        }

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

        .select2-selection.is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endsection

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

            const totalWizardSteps = 6;
            let currentWizardStep = 1;

            function getFieldLabel($field) {
                let fieldId = $field.attr('id');
                let label = fieldId ? $('label[for="' + fieldId + '"]').first().text().trim() : '';

                if (!label) {
                    label = $field.closest('.col-md-3, .col-md-4, .col-md-6, .col-md-9, .col-md-auto, .input-group, .row')
                        .find('label').first().text().trim();
                }

                return (label || ($field.attr('name') || 'Field')).replace(/\*/g, '').replace(/\s+/g, ' ').trim();
            }

            function getMessageContainer($field) {
                let fieldName = ($field.attr('name') || '').replace('[]', '');
                let fieldId = $field.attr('id') || '';
                let index = null;

                if (fieldId.match(/_(\d+)$/)) {
                    index = fieldId.match(/_(\d+)$/)[1];
                }

                let candidates = [];

                if (fieldName === 'province') {
                    candidates.push('#message_province_' + index);
                } else if (fieldName === 'city') {
                    candidates.push('#message_city_' + index);
                } else if (fieldName) {
                    if (index !== null) {
                        candidates.push('#message_' + fieldName + '_' + index);
                    }
                    candidates.push('#message_' + fieldName);
                }

                if (fieldId) {
                    candidates.push('#message_' + fieldId);
                }

                for (let i = 0; i < candidates.length; i++) {
                    let $message = $(candidates[i]);
                    if ($message.length) {
                        return $message.first();
                    }
                }

                return $();
            }

            function isRequiredFieldEmpty($field) {
                if ($field.is(':disabled')) {
                    return false;
                }

                if ($field.is(':radio')) {
                    return $('[name="' + $field.attr('name') + '"]:checked').length === 0;
                }

                if ($field.is(':checkbox')) {
                    return !$field.is(':checked');
                }

                return $.trim($field.val() || '') === '';
            }

            function validateStep(step) {
                let $pane = $('.step-pane[data-step="' + step + '"]');
                let errors = [];

                $pane.find('[id^="message_"]').text('');
                $pane.find('.is-invalid').removeClass('is-invalid');
                $pane.find('.select2-selection.is-invalid').removeClass('is-invalid');

                $pane.find('[required]').each(function() {
                    let $field = $(this);
                    if (!isRequiredFieldEmpty($field)) {
                        return;
                    }

                    let label = getFieldLabel($field);
                    let message = label + ' wajib diisi.';
                    errors.push({
                        field: $field,
                        label: label,
                        message: message
                    });

                    $field.addClass('is-invalid');
                    if ($field.hasClass('select2-hidden-accessible')) {
                        $field.next('.select2').find('.select2-selection').addClass('is-invalid');
                    }
                    getMessageContainer($field).text(message);
                });

                if (!errors.length) {
                    return true;
                }

                let toastBody = errors.slice(0, 6).map(function(error) {
                    return '<div><strong>' + error.label + '</strong>: ' + error.message + '</div>';
                }).join('');

                if (errors.length > 6) {
                    toastBody += '<div>+' + (errors.length - 6) + ' error lainnya.</div>';
                }

                if (typeof showPartnerToast === 'function') {
                    showPartnerToast('Mandatory Field', toastBody, 'danger');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Mandatory Field',
                        html: toastBody
                    });
                }

                let $firstError = errors[0].field;
                let $scrollTarget = $firstError.hasClass('select2-hidden-accessible') ? $firstError.next('.select2') : $firstError;
                if ($scrollTarget.length) {
                    $('html, body').animate({
                        scrollTop: $scrollTarget.offset().top - 150
                    }, 400);
                }

                return false;
            }

            function canMoveToStep(targetStep) {
                if (targetStep <= currentWizardStep) {
                    return true;
                }

                for (let step = currentWizardStep; step < targetStep; step++) {
                    if (step !== currentWizardStep) {
                        showStep(step);
                    }

                    if (!validateStep(step)) {
                        return false;
                    }
                }

                return true;
            }

            function showStep(step) {
                if (step < 1) {
                    step = 1;
                }
                if (step > totalWizardSteps) {
                    step = totalWizardSteps;
                }
                currentWizardStep = step;

                $('.step-pane').each(function() {
                    const stepIndex = parseInt($(this).data('step'));
                    if (stepIndex === currentWizardStep) {
                        $(this).removeClass('d-none').addClass('active');
                    } else {
                        $(this).addClass('d-none').removeClass('active');
                    }
                });

                $('.wizard-step').each(function() {
                    const stepIndex = parseInt($(this).data('step'));
                    $(this).removeClass('active completed');
                    if (stepIndex < currentWizardStep) {
                        $(this).addClass('completed');
                    }
                    if (stepIndex === currentWizardStep) {
                        $(this).addClass('active');
                    }
                });

                $('#step_prev').toggleClass('d-none', currentWizardStep === 1);
                $('#step_next').toggleClass('d-none', currentWizardStep === totalWizardSteps);
                $('#btn_submit_data_company').toggleClass('d-none', currentWizardStep !== totalWizardSteps);

                $('html, body').animate({
                    scrollTop: $('#form_company').offset().top - 100
                }, 300);
            }

            $('#step_prev').on('click', function() {
                showStep(currentWizardStep - 1);
            });

            $('#step_next').on('click', function() {
                if (canMoveToStep(currentWizardStep + 1)) {
                    showStep(currentWizardStep + 1);
                }
            });

            $('#wizard_stepper').on('click', '.wizard-step', function() {
                const step = parseInt($(this).data('step'));
                if (canMoveToStep(step)) {
                    showStep(step);
                }
            });

            showStep(1);
        });
    </script>

    @include('cs_vendor.partner_js')
@stop
