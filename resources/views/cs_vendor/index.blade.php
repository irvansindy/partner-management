@extends('layouts.main')

@section('content')
    @push('styles')
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
        <link rel="stylesheet" href="{{ asset('css/cdn/file_input_min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cdn/leaflet.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cdn/leaflet_geocoder.css') }}">
        <style>
            .internal-partner-form {
                --public-ink: #202638;
                --public-muted: #70798b;
                --public-line: #e3e8ef;
                --public-paper: #fff;
                --public-canvas: #f3f5f8;
                --public-primary: #6558d3;
                --public-primary-dark: #5043bd;
                --public-primary-soft: #efedff;
                --public-success: #238768;
                margin: 0 auto;
                max-width: 1420px;
                padding: 26px 4px 44px;
            }

            .internal-partner-form .public-form-card {
                background: transparent;
                border: 0;
                box-shadow: none;
                padding: 0;
            }

            .internal-partner-form .wizard-shell {
                background: #fff;
                border: 1px solid var(--public-line);
                border-radius: 10px;
                margin-bottom: 20px;
            }

            .internal-partner-form .wizard-step {
                appearance: none;
                background: transparent;
                border: 0;
                font: inherit;
            }

            .internal-partner-form .wizard-step:focus-visible {
                outline: 3px solid rgba(101, 88, 211, .35);
                outline-offset: 3px;
            }
        </style>
        @include('public.form.css')
    @endpush

    <div class="internal-partner-form public-form-page">
        <div class="public-form-heading">
            <div>
                <div class="public-eyebrow">PARTNER REGISTRATION</div>
                <h1>Formulir Pendaftaran Partner</h1>
                <p>Lengkapi informasi perusahaan secara bertahap agar data lebih mudah diperiksa.</p>
            </div>
            <span class="public-form-type">Internal registration</span>
        </div>

        <div class="public-form-description">
            <i class="fas fa-info-circle" aria-hidden="true"></i>
            <span>Data bertanda bintang wajib diisi. Informasi yang sudah lengkap akan dikirim untuk proses peninjauan.</span>
        </div>

        <div class="public-form-card">
            <form action="{{ route('submit-partner') }}" method="POST" id="form_company" enctype="multipart/form-data">
                @csrf

                <div class="wizard-shell">
                    <div class="wizard-topline">
                        <div>
                            <span class="wizard-kicker">REGISTRATION STEPS</span>
                            <span class="wizard-current-label" id="wizard_current_label">Master Information</span>
                        </div>
                        <span class="wizard-count"><span id="wizard_current_number">01</span> / 06</span>
                    </div>
                    <div class="wizard-stepper" id="wizard_stepper">
                        @foreach([
                            1 => 'Master Information',
                            2 => 'Contact',
                            3 => 'Address',
                            4 => 'Bank',
                            5 => 'Form Survey',
                            6 => 'Form Upload',
                        ] as $stepNumber => $stepLabel)
                            <button type="button" class="wizard-step {{ $stepNumber === 1 ? 'active' : '' }}" data-step="{{ $stepNumber }}" aria-label="Open {{ $stepLabel }} step">
                                <span class="wizard-step-number">{{ $stepNumber }}</span>
                                <span class="wizard-step-text">{{ $stepLabel }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="step-pane" data-step="1">@include('cs_vendor.form.master_information')</div>
                <div class="step-pane d-none" data-step="2">@include('cs_vendor.form.contact')</div>
                <div class="step-pane d-none" data-step="3">@include('cs_vendor.form.address')</div>
                <div class="step-pane d-none" data-step="4">@include('cs_vendor.form.bank')</div>
                <div class="step-pane d-none" data-step="5">@include('cs_vendor.form.survey')</div>
                <div class="step-pane d-none" data-step="6">@include('cs_vendor.form.file_upload')</div>

                <div class="wizard-actions d-flex justify-content-between align-items-center" id="wizard_actions">
                    <button type="button" class="btn btn-outline-secondary d-none" id="step_prev">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Previous
                    </button>
                    <div>
                        <button type="button" class="btn btn-primary" id="step_next">
                            Continue <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-success d-none" id="btn_submit_data_company">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Submit Partner
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="{{ asset('js/cdn/file_input.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input_sortable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/cdn/leaflet_geocoder.js') }}"></script>
    <script>
        $(function () {
            const stepLabels = ['Master Information', 'Contact', 'Address', 'Bank', 'Form Survey', 'Form Upload'];
            const totalSteps = stepLabels.length;
            let currentStep = 1;

            function requiredFieldIsEmpty(field) {
                if (field.disabled || $(field).closest('[hidden]').length) return false;
                if (field.type === 'radio') return !$('[name="' + field.name + '"]:checked').length;
                if (field.type === 'checkbox') return !field.checked;
                return !String($(field).val() || '').trim();
            }

            function validateStep(step) {
                const $pane = $('.step-pane[data-step="' + step + '"]');
                let firstInvalid = null;

                $pane.find('[required]').each(function () {
                    const invalid = requiredFieldIsEmpty(this) || !this.checkValidity();
                    $(this).toggleClass('is-invalid', invalid);
                    if (invalid && !firstInvalid) firstInvalid = this;
                });

                if (!firstInvalid) return true;

                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Data belum lengkap', text: 'Lengkapi field wajib pada tahap ini sebelum melanjutkan.' });
                }
                firstInvalid.reportValidity();
                const $target = $(firstInvalid).hasClass('select2-hidden-accessible') ? $(firstInvalid).next('.select2') : $(firstInvalid);
                $('html, body').animate({ scrollTop: $target.offset().top - 145 }, 300);
                return false;
            }

            function showStep(step) {
                currentStep = Math.max(1, Math.min(totalSteps, step));
                $('.step-pane').addClass('d-none').removeClass('active');
                $('.step-pane[data-step="' + currentStep + '"]').removeClass('d-none').addClass('active');
                $('#wizard_current_label').text(stepLabels[currentStep - 1]);
                $('#wizard_current_number').text(String(currentStep).padStart(2, '0'));
                $('#wizard_stepper .wizard-step').each(function () {
                    const itemStep = Number($(this).data('step'));
                    $(this).toggleClass('active', itemStep === currentStep).toggleClass('completed', itemStep < currentStep);
                });
                $('#step_prev').toggleClass('d-none', currentStep === 1);
                $('#step_next').toggleClass('d-none', currentStep === totalSteps);
                $('#btn_submit_data_company').toggleClass('d-none', currentStep !== totalSteps);
                if (currentStep === 3) document.dispatchEvent(new Event('partner:address-step-shown'));
                $('html, body').animate({ scrollTop: $('#form_company').offset().top - 95 }, 250);
            }

            $('#step_prev').on('click', function () { showStep(currentStep - 1); });
            $('#step_next').on('click', function () { if (validateStep(currentStep)) showStep(currentStep + 1); });
            $('#wizard_stepper').on('click', '.wizard-step', function () {
                const target = Number($(this).data('step'));
                if (target <= currentStep || validateStep(currentStep)) showStep(target);
            });

            showStep(1);
        });
    </script>
    @include('cs_vendor.partner_js')
@endpush
