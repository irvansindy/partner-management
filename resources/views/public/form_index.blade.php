@extends('layouts.public')

@section('title', isset($formLink) ? $formLink->title : 'Partner Registration')

@section('content')
    <div class="public-form-page">
        <div class="public-form-heading">
            <div>
                <div class="public-eyebrow">PARTNER REGISTRATION</div>
                <h1>
                    @if (isset($formLink))
                        {{ $formLink->title }}
                    @else
                        @lang('messages.Form Register')
                    @endif
                </h1>
                <p>Complete the company details below to submit your registration.</p>
            </div>
            @if (isset($formLink))
                <span class="public-form-type">{{ ucfirst($formLink->form_type) }} application</span>
            @endif
        </div>

        @if (isset($formLink) && $formLink->description)
            <div class="public-form-description">
                <i class="fas fa-info-circle" aria-hidden="true"></i>
                <span>{{ $formLink->description }}</span>
            </div>
        @endif

        <div class="public-form-card">
            <form
                action="{{ route('public.form.submit', $formLink->token) }}"
                method="POST" id="form_company" enctype="multipart/form-data"
                data-storage-key="partner_form_{{ $formLink->token ?? 'default' }}">
                @csrf
                <input type="hidden" name="idempotency_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}">

                @if (isset($formLink))
                    <input type="hidden" name="company_type" value="{{ $formLink->form_type }}">
                @endif
                <div class="wizard-shell">
                    <div class="wizard-topline">
                        <div>
                            <span class="wizard-kicker">REGISTRATION STEPS</span>
                            <span class="wizard-current-label" id="wizard_current_label">Master Information</span>
                        </div>
                        <span class="wizard-count"><span id="wizard_current_number">01</span> / {{ isset($formLink) ? '05' : '06' }}</span>
                    </div>
                    <div class="wizard-stepper" id="wizard_stepper">
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
                            @unless (isset($formLink))
                                <div class="wizard-step" data-step="5">
                                    <div class="wizard-step-number">5</div>
                                    <div class="wizard-step-text">Form Survey</div>
                                </div>
                            @endunless
                            <div class="wizard-step" data-step="{{ isset($formLink) ? 5 : 6 }}">
                                <div class="wizard-step-number">{{ isset($formLink) ? 5 : 6 }}</div>
                                <div class="wizard-step-text">Form Upload</div>
                            </div>
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
                        @unless (isset($formLink))
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
                            @endunless
                            <div class="step-pane d-none" data-step="{{ isset($formLink) ? 5 : 6 }}">
                            @include('cs_vendor.form.file_upload')
                        </div>

                <div class="wizard-actions d-flex justify-content-between align-items-center" id="wizard_actions">
                    <button type="button" class="btn btn-outline-secondary d-none" id="step_prev">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i> Previous
                    </button>
                    <div>
                        <button type="button" class="btn btn-primary" id="step_next">
                            Continue <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-success d-none" id="btn_submit_data_company">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Submit Registration
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    @include('public.form.css')
@endsection

@section('js')
    @include('public.form.js')
@stop
