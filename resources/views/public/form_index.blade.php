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
                        <div id="form_loading_overlay" class="d-none">
                            <div class="loading-box text-center p-4 rounded shadow">
                                <div class="spinner-border text-primary" role="status"></div>
                                <div class="mt-3 fw-bold">Mohon tunggu, sedang mengirim data...</div>
                            </div>
                        </div>
                        @csrf
                        <input type="hidden" name="submission_uuid" value="{{ \Illuminate\Support\Str::uuid() }}">

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
                                <div class="wizard-step-number">{{ $formLink->form_type === 'vendor' ? 5 : 6 }}</div>
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
            gap: 0.85rem;
            margin-bottom: 1.75rem;
            padding: 1.1rem 1rem;
            overflow-x: auto;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 1.5rem;
            box-shadow: 0 18px 32px rgba(15, 40, 74, 0.06);
        }

        #wizard_stepper::before {
            content: '';
            position: absolute;
            top: 32px;
            left: 1.25rem;
            right: 1.25rem;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }

        .wizard-step {
            position: relative;
            z-index: 2;
            flex: 0 0 auto;
            min-width: 150px;
            max-width: 210px;
            padding: 0.95rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            color: #6c757d;
            background: #f6f8fc;
            border: 1px solid transparent;
            border-radius: 1.25rem;
            transition: all 0.25s ease;
            white-space: normal;
        }

        .wizard-step:hover {
            transform: translateY(-1px);
            border-color: #d8e2ef;
            background: #eef4ff;
        }

        .wizard-step-number {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #ced4da;
            margin-bottom: 0.55rem;
            font-weight: 700;
            color: #495057;
            transition: all 0.25s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .wizard-step.active .wizard-step-number,
        .wizard-step.completed .wizard-step-number {
            background: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.18);
        }

        .wizard-step.active,
        .wizard-step.completed {
            color: #1b1f23;
            background: #e7f1ff;
            border-color: #cce0ff;
        }

        .wizard-step-text {
            font-size: 0.88rem;
            line-height: 1.3;
            max-width: 170px;
            word-break: break-word;
            color: inherit;
            font-weight: 600;
            text-transform: uppercase;
        }

        .step-pane {
            display: none;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .step-pane.active {
            display: block;
            opacity: 1;
        }

        .step-pane > .card {
            border: 1px solid #e7eaf3;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(15, 40, 74, 0.04);
            background: #ffffff;
        }

        .step-pane .card-body {
            padding: 1.75rem 1.5rem 1.25rem;
        }

        .step-pane .form-group label,
        .step-pane label {
            font-weight: 600;
            color: #2c3e50;
        }

        .step-pane .form-control,
        .step-pane select,
        .step-pane textarea,
        .step-pane .form-select {
            width: 100%;
            border-radius: 0.75rem;
            padding: 0.95rem 1rem;
            border: 1px solid #d8e2ef;
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
            box-sizing: border-box;
            min-height: 3.3rem;
            background: #fff;
            background-clip: padding-box;
        }

        .step-pane fieldset {
            border: 1px solid rgba(13, 110, 253, 0.15);
            background: rgba(13, 110, 253, 0.03);
            border-radius: 1rem;
            padding: 1.25rem 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .step-pane legend {
            color: #0d6efd;
            font-size: 1rem;
            font-weight: 700;
            padding: 0 0.75rem;
            width: auto;
        }

        .step-pane .form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: linear-gradient(45deg, transparent 50%, #6c757d 50%),
                linear-gradient(135deg, #6c757d 50%, transparent 50%);
            background-position: calc(100% - 1rem) center, calc(100% - 0.6rem) center;
            background-size: 8px 8px, 8px 8px;
            background-repeat: no-repeat;
        }

        .step-pane .form-select option {
            padding: 0.6rem 1rem;
        }

        .step-pane .form-control:focus,
        .step-pane select:focus,
        .step-pane textarea:focus,
        .step-pane .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.18rem rgba(13, 110, 253, 0.15);
        }

        .step-pane .form-text,
        .step-pane .text-muted {
            color: #6c757d;
        }

        .step-pane .form-row,
        .step-pane .row {
            gap: 1rem;
        }

        .step-pane .row > [class^="col-"] {
            margin-bottom: 1rem;
        }

        #wizard_actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        #wizard_actions > div,
        #wizard_actions > button {
            flex: 1 1 auto;
        }

        #wizard_actions button {
            min-width: 140px;
        }

        #wizard_actions .btn {
            width: 100%;
            border-radius: 0.85rem;
            padding: 0.95rem 1.25rem;
        }

        .card-header h3 {
            font-size: 1.32rem;
            letter-spacing: -0.01em;
        }

        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e9ecef;
        }

        .card {
            border-radius: 1.25rem;
        }

        .card .card-header {
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .alert-info {
            border-radius: 0.85rem;
            background: #eef7ff;
            border-color: #d2e5ff;
            color: #084298;
        }

        @media (max-width: 991px) {
            #wizard_stepper {
                padding-left: 0.85rem;
                padding-right: 0.85rem;
            }

            .wizard-step {
                min-width: 150px;
                max-width: 180px;
            }

            .wizard-step-text {
                font-size: 0.84rem;
            }

            .step-pane .form-control,
            .step-pane select,
            .step-pane textarea {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 768px) {
            #wizard_actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            #wizard_actions > div,
            #wizard_actions > button {
                width: 100%;
            }

            #wizard_actions .btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            #wizard_stepper {
                gap: 0.55rem;
            }

            .wizard-step {
                min-width: 140px;
                max-width: 155px;
                padding: 0.75rem 0.85rem;
            }

            .wizard-step-number {
                width: 34px;
                height: 34px;
                margin-bottom: 0.35rem;
            }

            .wizard-step-text {
                font-size: 0.76rem;
                max-width: 140px;
            }

            #wizard_actions {
                margin-top: 1rem;
            }
        }

        #survey_form_container {
            transition: all 0.3s ease-in-out;
        }

        #form_company {
            position: relative;
            background: #f8fbff;
            padding: 1.25rem;
            border-radius: 1.25rem;
        }

        #form_loading_overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.92);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        #form_loading_overlay.active {
            display: flex;
        }

        .loading-box {
            min-width: 280px;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        }

        .error-message {
            display: block;
            margin-top: 0.35rem;
            color: #dc3545;
            font-size: 0.875rem;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
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
@endsection

@section('js')
    <script>
        const formToken = "{{ $formLink->token ?? '' }}";
        const STORAGE_KEY = "partner_form_" + formToken;
        const unsafePattern = /<[^>]*>|javascript:|data:text|<script/i;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const urlPattern = /^(https?:\/\/)?(([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,})(:\d+)?(\/\S*)?$/;
        const digitsPattern = /^[0-9+\- ]+$/;

        function showLoadingOverlay(show) {
            const $overlay = $('#form_loading_overlay');
            if (show) {
                $overlay.addClass('active');
            } else {
                $overlay.removeClass('active');
            }
        }

        function clearValidationErrors(scope) {
            const $scope = scope ? $(scope) : $('#form_company');
            $scope.find('.is-invalid').removeClass('is-invalid');
            $scope.find('.message-danger').each(function() {
                $(this).text('');
            });
            $scope.find('.error-message').remove();
        }

        function getErrorMessageElement($field) {
            const $container = $field.closest('.col-md-4, .col-md-6, .col-md-9, .input-group, .form-group, fieldset, .row');
            let $message = $container.find('.message-danger').first();
            if ($message.length === 0) {
                $message = $('<span class="text-danger error-message"></span>');
                $field.after($message);
            }
            return $message;
        }

        function showFieldError($field, message) {
            $field.addClass('is-invalid');
            const $messageEl = getErrorMessageElement($field);
            $messageEl.text(message);
        }

        function isUnsafeValue(value) {
            return unsafePattern.test(String(value || ''));
        }

        function validateField($field, isRequired = false) {
            if (!$field.is(':visible') || $field.is(':disabled')) {
                return true;
            }

            const name = $field.attr('name') || '';
            const type = $field.attr('type') || $field.prop('tagName').toLowerCase();
            let value = $field.val();

            if (type === 'checkbox') {
                value = $field.is(':checked') ? 'checked' : '';
            }
            if (type === 'radio') {
                if (!$field.is(':checked')) {
                    return true;
                }
            }

            const normalizedValue = String(value || '').trim();

            if (isRequired && normalizedValue === '') {
                showFieldError($field, 'Field ini wajib diisi.');
                return false;
            }

            if (normalizedValue === '') {
                return true;
            }

            if (isUnsafeValue(normalizedValue)) {
                showFieldError($field, 'Format tidak valid atau berpotensi berbahaya.');
                return false;
            }

            if (/email/i.test(name) && !emailPattern.test(normalizedValue)) {
                showFieldError($field, 'Alamat email tidak valid.');
                return false;
            }

            if (/(website|url)/i.test(name) && !urlPattern.test(normalizedValue)) {
                showFieldError($field, 'Alamat website tidak valid.');
                return false;
            }

            if (/nik/i.test(name) && !/^\d{6,20}$/.test(normalizedValue)) {
                showFieldError($field, 'NIK harus berupa 6-20 digit angka.');
                return false;
            }

            if (/(telephone|fax|zip_code|account_number)/i.test(name) && !digitsPattern.test(normalizedValue)) {
                showFieldError($field, 'Hanya boleh berisi angka, spasi, +, dan -');
                return false;
            }

            if (normalizedValue.length > 255 && /(company_name|company_group_name|liable_person|contact_name|business_classification|address|bank_name|account_name|survey_pick_up|survey_truck|product_survey|merk_survey|distributor_survey)/i.test(name)) {
                showFieldError($field, 'Panjang maksimal 255 karakter.');
                return false;
            }

            return true;
        }

        function validateGroupRequired($panel, groupName, message) {
            const $fields = $panel.find(`[name="${groupName}"]`);
            if ($fields.length === 0) {
                return true;
            }
            if ($fields.filter(':checked').length === 0) {
                showFieldError($fields.first(), message);
                return false;
            }
            return true;
        }

        function validateRequiredGroupArray($panel, fieldName) {
            let isValid = true;
            $panel.find(`[name="${fieldName}[]"]`).each(function() {
                if (!validateField($(this), true)) {
                    isValid = false;
                }
            });
            return isValid;
        }

        function validateFileInput() {
            const $fileInput = $('#input-multiple-file');
            if ($fileInput.length === 0) {
                return true;
            }
            const files = $fileInput[0].files;
            const allowedExtensions = /\.(jpg|jpeg|png|pdf)$/i;
            let isValid = true;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!allowedExtensions.test(file.name)) {
                    showFieldError($fileInput, 'Hanya file JPG, JPEG, PNG, atau PDF yang diizinkan.');
                    isValid = false;
                }
                if (file.size > 5 * 1024 * 1024) {
                    showFieldError($fileInput, 'Ukuran file maksimal 5MB per file.');
                    isValid = false;
                }
            }

            return isValid;
        }

        function validateStep(step) {
            const $panel = $(`.step-pane[data-step="${step}"]`);
            clearValidationErrors($panel);
            let isValid = true;

            if (step === 1) {
                if (!formToken) {
                    $panel.find('#company_type').each(function() {
                        if (!validateField($(this), true)) {
                            isValid = false;
                        }
                    });
                }
                $panel.find('#company_name').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="liable_person[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="liable_position[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="nik[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('#business_classification').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('#register_number_as_in_tax_invoice').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('#website_address').each(function() {
                    if (!validateField($(this), false)) isValid = false;
                });
                $panel.find('#email_address').each(function() {
                    if (!validateField($(this), false)) isValid = false;
                });
                $panel.find('#term_of_payment').each(function() {
                    if (!validateField($(this), false)) isValid = false;
                });
                $panel.find('#other_term_of_payment').each(function() {
                    if (!validateField($(this), false)) isValid = false;
                });
                $panel.find('[name="other_position[]"]').each(function() {
                    if (!validateField($(this), false)) isValid = false;
                });
            }

            if (step === 2) {
                $panel.find('[name="contact_department[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="contact_position[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="contact_name[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="contact_email[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="contact_telephone[]"]')[0] && $panel.find('[name="contact_telephone[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
            }

            if (step === 3) {
                $panel.find('[name="address[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="country[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="province[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="city[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="zip_code[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="telephone[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="fax[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('#latitude_1, #longitude_1').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
            }

            if (step === 4) {
                $panel.find('[name="bank_name[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="account_name[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
                $panel.find('[name="account_number[]"]').each(function() {
                    if (!validateField($(this), true)) isValid = false;
                });
            }

            if (step === 5) {
                if ($('#survey_form_switch').is(':checked')) {
                    if (!validateGroupRequired($panel, 'survey_ownership_status', 'Pilih status kepemilikan.')) {
                        isValid = false;
                    }
                    $panel.find('#survey_pick_up').each(function() {
                        if (!validateField($(this), false)) isValid = false;
                    });
                    $panel.find('#survey_truck').each(function() {
                        if (!validateField($(this), false)) isValid = false;
                    });
                    $panel.find('[name="product_survey[]"]').each(function() {
                        if (!validateField($(this), false)) isValid = false;
                    });
                    $panel.find('[name="merk_survey[]"]').each(function() {
                        if (!validateField($(this), false)) isValid = false;
                    });
                    $panel.find('[name="distributor_survey[]"]').each(function() {
                        if (!validateField($(this), false)) isValid = false;
                    });
                }
            }

            if (step === 6) {
                if (!validateFileInput()) {
                    isValid = false;
                }
            }

            return isValid;
        }

        function validateAllSteps() {
            let isValid = true;
            for (let step of getVisibleWizardSteps()) {
                if (!validateStep(step)) {
                    isValid = false;
                    showStep(step);
                    break;
                }
            }
            return isValid;
        }

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

            window.isVendorCompany = function() {
                const $companyTypeSelect = $('[name="company_type"]');
                let companyType = null;

                if ($companyTypeSelect.length) {
                    companyType = $companyTypeSelect.val();
                }

                if (!companyType) {
                    const $companyTypeHidden = $('input[name="company_type"]');
                    if ($companyTypeHidden.length) {
                        companyType = $companyTypeHidden.val();
                    }
                }

                return String(companyType || '').toLowerCase() === 'vendor';
            };

            window.getVisibleWizardSteps = function() {
                return isVendorCompany() ? [1, 2, 3, 4, 6] : [1, 2, 3, 4, 5, 6];
            };

            window.getNextWizardStep = function(step) {
                const steps = getVisibleWizardSteps();
                const idx = steps.indexOf(step);
                if (idx === -1 || idx === steps.length - 1) {
                    return steps[steps.length - 1];
                }
                return steps[idx + 1];
            };

            window.getPreviousWizardStep = function(step) {
                const steps = getVisibleWizardSteps();
                const idx = steps.indexOf(step);
                if (idx <= 0) {
                    return steps[0];
                }
                return steps[idx - 1];
            };

            function updateSurveyStepVisibility() {
                const shouldHideSurveyStep = isVendorCompany();
                const $surveyStep = $('.wizard-step[data-step="5"]');
                const $surveyPane = $('.step-pane[data-step="5"]');

                if (shouldHideSurveyStep) {
                    $surveyStep.addClass('d-none');
                    $surveyPane.addClass('d-none');
                    $('#survey_form_switch').prop('checked', false);
                    $('#survey_form_container').empty().hide();
                    $('#switch_label').text('Show Survey');
                } else {
                    $surveyStep.removeClass('d-none');
                    if ($('#survey_form_switch').is(':checked') && $('#survey_form_container').is(':empty')) {
                        $('#survey_form_container').html(surveyFormHTML).show();
                        restoreSurveyData();
                    }
                }
            }

            updateSurveyStepVisibility();

            // Jika awal tidak checked, langsung hapus form
            if (!isSurveyVisible) {
                $('#survey_form_container').empty();
            }

            // Tangani perubahan tipe perusahaan
            $(document).on('change', '[name="company_type"]', function() {
                updateSurveyStepVisibility();
                if (currentWizardStep === 5 && isVendorCompany()) {
                    showStep(getNextWizardStep(currentWizardStep));
                }
            });

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

            let currentWizardStep = 1;

            function showStep(step) {
                const visibleSteps = getVisibleWizardSteps();
                if (!visibleSteps.includes(step)) {
                    step = visibleSteps[0];
                }
                if (step < visibleSteps[0]) {
                    step = visibleSteps[0];
                }
                if (step > visibleSteps[visibleSteps.length - 1]) {
                    step = visibleSteps[visibleSteps.length - 1];
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
                    if (stepIndex === 5 && isVendorCompany()) {
                        $(this).addClass('d-none');
                        return;
                    }
                    $(this).removeClass('active completed d-none');
                    if (stepIndex < currentWizardStep) {
                        $(this).addClass('completed');
                    }
                    if (stepIndex === currentWizardStep) {
                        $(this).addClass('active');
                    }
                });

                $('#step_prev').toggleClass('d-none', currentWizardStep === getVisibleWizardSteps()[0]);
                $('#step_next').toggleClass('d-none', currentWizardStep === getVisibleWizardSteps()[getVisibleWizardSteps().length - 1]);
                $('#btn_submit_data_company').toggleClass('d-none', currentWizardStep !== getVisibleWizardSteps()[getVisibleWizardSteps().length - 1]);

                $('html, body').animate({
                    scrollTop: $('#form_company').offset().top - 100
                }, 300);
            }

            $('#step_prev').on('click', function() {
                showStep(getPreviousWizardStep(currentWizardStep));
            });

            $('#step_next').on('click', function() {
                if (!validateStep(currentWizardStep)) {
                    return;
                }
                showStep(getNextWizardStep(currentWizardStep));
            });

            $('#btn_submit_data_company').on('click', function() {
                if (!validateStep(currentWizardStep)) {
                    return;
                }
                if (!validateAllSteps()) {
                    return;
                }
                showLoadingOverlay(true);
                $(this).prop('disabled', true);
                $('#step_next').prop('disabled', true);
                $('#form_company')[0].submit();
            });

            $('#wizard_stepper').on('click', '.wizard-step', function() {
                const step = parseInt($(this).data('step'));
                if (step === 5 && isVendorCompany()) {
                    return;
                }
                if (step > currentWizardStep && !validateStep(currentWizardStep)) {
                    return;
                }
                showStep(step);
            });

            // Initialize select2 on dropdowns and enforce maxlength on numeric inputs
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        try {
                            $(this).select2({ width: '100%' });
                        } catch (e) {
                            console.warn('select2 init error', e);
                        }
                    }
                });
            }

            // Enforce maxlength for numeric inputs (type=number) since maxlength isn't enforced by browsers
            $(document).on('input', 'input[type="number"][data-maxlength]', function() {
                const max = parseInt($(this).attr('data-maxlength') || 0, 10);
                if (!max) return;
                let v = String($(this).val() || '');
                if (v.length > max) {
                    $(this).val(v.substr(0, max));
                }
            });

            // Re-init select2 for dynamically added rows
            $(document).on('click', '#add_dynamic_address, #add_bank, #add_liable_person, #add_contact, #add_survey_data, .btn-add-row', function() {
                setTimeout(function() {
                    if (typeof $.fn.select2 !== 'undefined') {
                        $('.select2').each(function() {
                            if (!$(this).hasClass('select2-hidden-accessible')) {
                                try {
                                    $(this).select2({ width: '100%' });
                                } catch (e) {}
                            }
                        });
                    }
                }, 80);
            });

            showStep(1);
        });
    </script>

    @include('cs_vendor.partner_js')
@stop
