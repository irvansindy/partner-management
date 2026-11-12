@extends('layouts.main')

@push('styles')
    @include('admin.form_links._alpha_styles')
    <link rel="stylesheet" href="{{ asset('vendor/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cdn/leaflet.css') }}">
    <style>
        .submission-edit-page .section-help { color: var(--form-link-text-muted); margin: 4px 0 0; }
        .submission-edit-page .repeatable-item {
            background: #fbfbfd;
            border: 1px solid var(--form-link-border);
            border-radius: 10px;
            margin-bottom: 14px;
            padding: 18px 76px 18px 18px;
            position: relative;
        }
        .submission-edit-page .repeatable-item__remove {
            align-items: center;
            appearance: none;
            background: #fff;
            border: 1px solid #dc3545;
            border-radius: 8px;
            color: #dc3545;
            cursor: pointer;
            display: inline-flex;
            height: 44px;
            justify-content: center;
            line-height: 1;
            min-width: 44px;
            padding: 0;
            position: absolute;
            right: 16px;
            top: 16px;
            transition: background-color .16s ease, color .16s ease, border-color .16s ease;
            width: 44px;
            z-index: 1;
        }
        .submission-edit-page .repeatable-item__remove:hover {
            background: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }
        .submission-edit-page .repeatable-item__remove:focus-visible {
            outline: 3px solid rgba(220, 53, 69, .28);
            outline-offset: 2px;
        }
        .submission-edit-page .repeatable-item__remove .fas {
            font-size: 16px;
            line-height: 1;
            margin: 0;
        }
        .submission-edit-page .attachment-row {
            align-items: center;
            background: #fbfbfd;
            border: 1px solid var(--form-link-border);
            border-radius: 8px;
            display: flex;
            gap: 12px;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 14px 16px;
        }
        .submission-edit-page .attachment-row__file { align-items: center; display: flex; gap: 10px; min-width: 0; }
        .submission-edit-page .attachment-row__file i { color: var(--form-link-primary); }
        .submission-edit-page .attachment-row__name { overflow-wrap: anywhere; }
        .submission-edit-page .attachment-picker {
            align-items: center;
            background: #fbfbfd;
            border: 1px dashed #b7bfcc;
            border-radius: 8px;
            display: flex;
            gap: 14px;
            justify-content: space-between;
            margin-top: 18px;
            padding: 16px;
        }
        .submission-edit-page .attachment-picker__copy { min-width: 0; }
        .submission-edit-page .attachment-picker__copy strong { display: block; margin-bottom: 3px; }
        .submission-edit-page .attachment-input { height: 1px; overflow: hidden; position: absolute; width: 1px; clip: rect(0 0 0 0); }
        .submission-edit-page .attachment-input:focus + .attachment-picker__button { outline: 3px solid rgba(101, 88, 211, .25); outline-offset: 2px; }
        .submission-edit-page .selected-attachment-list { color: var(--form-link-text-muted); margin: 10px 0 0; padding-left: 20px; }
        .submission-edit-page .selected-attachment-list:empty { display: none; }
        .submission-edit-page .edit-field { margin-bottom: 20px; }
        .submission-edit-page .edit-field > label,
        .submission-edit-page .edit-field > .edit-field__label {
            color: #374151;
            display: block;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.25;
            margin: 0 0 8px;
            min-height: 17px;
            position: static !important;
            transform: none !important;
        }
        .submission-edit-page .edit-field > .form-control {
            background: #fff;
            border: 1px solid var(--form-link-border);
            border-radius: 7px;
            height: 46px;
            min-height: 46px;
            padding: 10px 12px;
        }
        .submission-edit-page .edit-field > textarea.form-control {
            height: 86px;
            min-height: 86px;
            resize: vertical;
        }
        .submission-edit-page .phone-input-group { align-items: stretch; display: flex; width: 100%; }
        .submission-edit-page .phone-input-group > .select2-container { flex: 0 0 148px; width: 148px !important; }
        .submission-edit-page .phone-input-group > .select2-container .select2-selection--single {
            background: #fff;
            border: 1px solid var(--form-link-border);
            height: 46px !important;
            min-height: 46px !important;
        }
        .submission-edit-page .phone-input-group .phone-number {
            background: #fff;
            border: 1px solid var(--form-link-border);
            border-bottom-left-radius: 0;
            border-top-left-radius: 0;
            height: 46px;
            min-height: 46px;
            min-width: 0;
            padding: 10px 12px;
        }
        .submission-edit-page .phone-input-group .select2-selection { border-bottom-right-radius: 0 !important; border-top-right-radius: 0 !important; }
        .submission-edit-page .select2-container { width: 100% !important; }
        .submission-edit-page .edit-field > .select2-container .select2-selection--single,
        .submission-edit-page .select2-container--default .select2-selection--single { align-items: center; display: flex; height: 46px !important; min-height: 46px !important; }
        .submission-edit-page .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 44px !important; padding-left: 12px; }
        .submission-edit-page .select2-container--default .select2-selection--single .select2-selection__arrow { height: 44px !important; }
        .submission-edit-page .country-option { align-items: center; display: inline-flex; gap: 8px; }
        .submission-edit-page .address-map-search > .form-control { height: 46px; }
        .submission-edit-page .address-map-search .btn { height: 46px; min-width: 108px; }
        .submission-edit-page .address-map-status { display: block; margin: 8px 0 10px; }
        .submission-edit-page .submission-map-shell {
            background: #eef1f5;
            border: 1px solid var(--form-link-border);
            border-radius: 8px;
            min-height: 380px;
            overflow: hidden;
            position: relative;
        }
        .submission-edit-page .submission-address-map { height: 380px; width: 100%; }
        .submission-edit-page .submission-map-fullscreen {
            align-items: center;
            background: #fff;
            border: 1px solid #cbd2dc;
            border-radius: 6px;
            color: #303746;
            display: inline-flex;
            gap: 7px;
            height: 40px;
            padding: 0 12px;
            position: absolute;
            right: 12px;
            top: 12px;
            z-index: 500;
        }
        .submission-edit-page .submission-map-fullscreen:hover { background: #f7f7fa; }
        .submission-edit-page .submission-map-fullscreen:focus-visible { outline: 3px solid rgba(101, 88, 211, .25); outline-offset: 2px; }
        .submission-edit-page .submission-map-shell:fullscreen,
        .submission-edit-page .submission-map-shell.is-map-fullscreen {
            background: #fff;
            border: 0;
            border-radius: 0;
            height: 100vh;
            inset: 0;
            position: fixed;
            width: 100vw;
            z-index: 9999;
        }
        .submission-edit-page .submission-map-shell:fullscreen .submission-address-map,
        .submission-edit-page .submission-map-shell.is-map-fullscreen .submission-address-map { height: 100vh; }
        body.submission-map-fullscreen-open { overflow: hidden; }
        .submission-map-marker { background: transparent; border: 0; color: #3f78b5; font-size: 38px; text-align: center; text-shadow: 0 2px 3px rgba(0, 0, 0, .25); }
        .submission-edit-page .sticky-actions {
            align-items: center;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        @media (max-width: 576px) {
            .submission-edit-page .repeatable-item { padding: 68px 14px 14px; }
            .submission-edit-page .attachment-picker { align-items: stretch; flex-direction: column; }
            .submission-edit-page .attachment-picker__button { width: 100%; }
            .submission-edit-page .sticky-actions { align-items: stretch; flex-direction: column-reverse; }
            .submission-edit-page .sticky-actions .btn { width: 100%; }
        }
    </style>
@endpush

@section('content')
    @php
        $liablePeople = old('liable_people', $company->liablePeople->map(fn ($item) => $item->only(['id', 'name', 'position', 'nik']))->values()->all());
        $contacts = old('contacts', $company->contact->map(fn ($item) => $item->only(['id', 'department', 'position', 'name', 'email', 'telephone']))->values()->all());
        $addresses = old('addresses', $company->address->map(fn ($item) => $item->only(['id', 'address', 'country', 'province', 'city', 'zip_code', 'telephone', 'fax', 'latitude', 'longitude']))->values()->all());
        $banks = old('banks', $company->bank->map(fn ($item) => $item->only(['id', 'name', 'account_name', 'account_number']))->values()->all());
        $rowTemplates = [
            'liable-people' => view('admin.form_links._submission_edit_row', ['kind' => 'liable_people', 'index' => '__INDEX__', 'data' => []])->render(),
            'contacts' => view('admin.form_links._submission_edit_row', ['kind' => 'contacts', 'index' => '__INDEX__', 'data' => []])->render(),
            'addresses' => view('admin.form_links._submission_edit_row', ['kind' => 'addresses', 'index' => '__INDEX__', 'data' => []])->render(),
            'banks' => view('admin.form_links._submission_edit_row', ['kind' => 'banks', 'index' => '__INDEX__', 'data' => []])->render(),
        ];
    @endphp

    <div class="container-fluid form-links-page submission-edit-page">
        <div class="page-heading">
            <div>
                <h2>{{ __('admin.Edit Submission') }}</h2>
                <p>{{ $company->name }} · {{ __('submission.editable_notice') }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}" class="btn btn-outline-secondary">
                    <i class="material-icons align-middle mr-1" aria-hidden="true">arrow_back</i> Detail
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>{{ __('form_links.Periksa kembali data berikut:') }}</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <form id="submission_edit_form" method="POST" action="{{ route('admin.form-links.submission-update', [$formLink, $company->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header"><h3 class="card-title">{{ __('admin.Company Information') }}</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">{{ __('admin.Company Name') }} <span class="text-danger">*</span></label>
                            <input id="name" name="name" class="form-control" value="{{ old('name', $company->name) }}" maxlength="255" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="group_name">{{ __('admin.Company Group Name') }}</label>
                            <input id="group_name" name="group_name" class="form-control" value="{{ old('group_name', $company->group_name) }}" maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="established_year">{{ __('admin.Established Year') }}</label>
                            <input id="established_year" name="established_year" type="number" class="form-control" value="{{ old('established_year', $company->established_year) }}" min="1900" max="{{ date('Y') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total_employee">{{ __('admin.Total Employee') }}</label>
                            <select id="total_employee" name="total_employee" class="form-control">
                                <option value="">{{ __('submission.Select employee range') }}</option>
                                @foreach (\App\Support\EmployeeRange::values() as $range)
                                    <option value="{{ $range }}" @selected(old('total_employee', $company->total_employee) === $range)>{{ $range }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="business_classification">{{ __('admin.Business Classification') }} <span class="text-danger">*</span></label>
                            <input id="business_classification" name="business_classification" class="form-control" value="{{ old('business_classification', $company->business_classification) }}" maxlength="255" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="business_classification_detail">{{ __('submission.Classification Detail') }}</label>
                            <input id="business_classification_detail" name="business_classification_detail" class="form-control" value="{{ old('business_classification_detail', $company->business_classification_detail) }}" maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="register_number_as_in_tax_invoice">{{ __('admin.Tax Register Number') }}</label>
                            <input id="register_number_as_in_tax_invoice" name="register_number_as_in_tax_invoice" class="form-control" value="{{ old('register_number_as_in_tax_invoice', $company->npwp) }}" maxlength="100">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="website_address">{{ __('admin.Website Address') }}</label>
                            <input id="website_address" name="website_address" type="url" class="form-control" value="{{ old('website_address', $company->website_address) }}" maxlength="255" placeholder="https://example.com">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="system_management">{{ __('admin.Certificate Type') }}</label>
                            <input id="system_management" name="system_management" class="form-control" value="{{ old('system_management', $company->system_management) }}" maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email_address">{{ __('submission.Correspondence Email') }}</label>
                            <input id="email_address" name="email_address" type="email" class="form-control" value="{{ old('email_address', $company->email_address) }}" maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="credit_limit">{{ __('admin.Credit Limit') }}</label>
                            <input id="credit_limit" name="credit_limit" type="number" class="form-control" value="{{ old('credit_limit', $company->credit_limit) }}" min="0" step="0.01">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="term_of_payment">{{ __('admin.Term of Payment') }}</label>
                            <input id="term_of_payment" name="term_of_payment" class="form-control" value="{{ old('term_of_payment', $company->term_of_payment) }}" maxlength="100">
                        </div>
                    </div>
                </div>
            </div>

            <x-submission-repeatable-section title="{{ __('admin.Liable Persons') }}" container="liable-people" add-label="{{ __('form_links_remaining.Add liable person') }}">
                @foreach ($liablePeople as $index => $person)
                    @include('admin.form_links._submission_edit_row', ['kind' => 'liable_people', 'index' => $index, 'data' => $person])
                @endforeach
            </x-submission-repeatable-section>

            <x-submission-repeatable-section title="{{ __('admin.Contact Persons') }}" container="contacts" add-label="{{ __('form_links_remaining.Add contact person') }}">
                @foreach ($contacts as $index => $contact)
                    @include('admin.form_links._submission_edit_row', ['kind' => 'contacts', 'index' => $index, 'data' => $contact])
                @endforeach
            </x-submission-repeatable-section>

            <x-submission-repeatable-section title="{{ __('admin.Addresses') }}" container="addresses" add-label="{{ __('form_links_remaining.Add address') }}">
                @foreach ($addresses as $index => $address)
                    @include('admin.form_links._submission_edit_row', ['kind' => 'addresses', 'index' => $index, 'data' => $address])
                @endforeach
            </x-submission-repeatable-section>

            <x-submission-repeatable-section title="{{ __('admin.Bank Information') }}" container="banks" add-label="{{ __('form_links_remaining.Add bank account') }}">
                @foreach ($banks as $index => $bank)
                    @include('admin.form_links._submission_edit_row', ['kind' => 'banks', 'index' => $index, 'data' => $bank])
                @endforeach
            </x-submission-repeatable-section>

            <div class="card">
                <div class="card-header"><h3 class="card-title">{{ __('admin.File Attachments') }}</h3></div>
                <div class="card-body">
                    @forelse ($company->attachment as $attachment)
                        <div class="attachment-row">
                            <div class="attachment-row__file">
                                <i class="fas fa-paperclip" aria-hidden="true"></i>
                                <span class="attachment-row__name">{{ $attachment->original_name ?? $attachment->filename }}</span>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="delete_attachment_{{ $attachment->id }}" name="delete_attachment_ids[]" value="{{ $attachment->id }}">
                                <label class="custom-control-label text-danger" for="delete_attachment_{{ $attachment->id }}">{{ __('submission.Remove') }}</label>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">{{ __('submission.No current attachments.') }}</p>
                    @endforelse
                    <div class="attachment-picker" data-attachment-picker>
                        <div class="attachment-picker__copy">
                            <strong>{{ __('submission.Add attachments') }}</strong>
                            <small class="text-muted">{{ __('submission.PDF, JPG, JPEG, or PNG. Maximum 10 MB per file.') }}</small>
                            <ul class="selected-attachment-list" data-selected-attachments aria-live="polite"></ul>
                        </div>
                        <div>
                            <input id="new_attachments" type="file" name="new_attachments[]" class="attachment-input" accept=".pdf,.jpg,.jpeg,.png" multiple>
                            <label for="new_attachments" class="btn btn-outline-primary attachment-picker__button mb-0">
                                <i class="fas fa-upload mr-1" aria-hidden="true"></i> Choose files
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-footer sticky-actions">
                    <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}" class="btn btn-outline-secondary">{{ __('admin.Cancel') }}</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1" aria-hidden="true"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/cdn/leaflet.js') }}"></script>
    <script>
        $(function () {
            const validationError = @json($errors->first());
            const updateError = @json(session('error'));
            const feedbackMessage = validationError || updateError;

            if (feedbackMessage) {
                Swal.fire({
                    icon: 'error',
                    title: @json(__('form_links.Data belum dapat disimpan')),
                    text: feedbackMessage,
                    confirmButtonText: @json(__('submission.recheck')),
                    confirmButtonColor: '#6558d3'
                });
            }

            $('#submission_edit_form').on('submit', function (event) {
                if (this.dataset.submitting === 'true') {
                    event.preventDefault();
                    return;
                }

                this.dataset.submitting = 'true';
                $(this).find('button[type="submit"]')
                    .prop('disabled', true)
                    .attr('aria-busy', 'true');

                Swal.fire({
                    title: @json(__('form_links.Menyimpan perubahan')),
                    text: @json(__('form_links.Mohon tunggu, data submission sedang diperbarui.')),
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: function () {
                        Swal.showLoading();
                    }
                });
            });

            const templates = @json($rowTemplates);
            const provincesUrl = @json(route('public.form.provinces'));
            const regenciesUrl = @json(route('public.form.regencies', ['province' => '__PROVINCE__']));
            const forwardGeocodeUrl = @json(route('forward-map'));
            const reverseGeocodeUrl = @json(route('reverse-map'));
            const addressMaps = new WeakMap();

            function mapFields($row) {
                return {
                    address: $row.find('textarea[name$="[address]"]').first(),
                    latitude: $row.find('input[name$="[latitude]"]').first(),
                    longitude: $row.find('input[name$="[longitude]"]').first(),
                    search: $row.find('[data-coordinate-search]').first(),
                    status: $row.find('[data-coordinate-status]').first()
                };
            }

            function updateMapFullscreenButton(shell, active) {
                const button = shell.querySelector('[data-map-fullscreen]');
                if (!button) return;
                button.querySelector('i').className = active ? 'fas fa-compress' : 'fas fa-expand';
                button.querySelector('span').textContent = active ? 'Exit fullscreen' : 'Fullscreen';
                button.setAttribute('aria-label', active ? 'Exit fullscreen map' : 'Open fullscreen map');
                button.setAttribute('title', active ? 'Exit fullscreen map' : 'Open fullscreen map');
            }

            function resizeAddressMap(row) {
                const state = addressMaps.get(row);
                if (state) setTimeout(function () { state.map.invalidateSize(); }, 0);
            }

            function toggleAddressMapFullscreen(row) {
                const shell = row.querySelector('[data-map-shell]');
                if (!shell) return;
                if (document.fullscreenElement === shell) return document.exitFullscreen();
                if (shell.classList.contains('is-map-fullscreen')) {
                    shell.classList.remove('is-map-fullscreen');
                    document.body.classList.remove('submission-map-fullscreen-open');
                    updateMapFullscreenButton(shell, false);
                    return resizeAddressMap(row);
                }
                if (shell.requestFullscreen) {
                    const request = shell.requestFullscreen();
                    if (request && typeof request.catch === 'function') {
                        request.catch(function () {
                            shell.classList.add('is-map-fullscreen');
                            document.body.classList.add('submission-map-fullscreen-open');
                            updateMapFullscreenButton(shell, true);
                            resizeAddressMap(row);
                        });
                    }
                    return;
                }
                shell.classList.add('is-map-fullscreen');
                document.body.classList.add('submission-map-fullscreen-open');
                updateMapFullscreenButton(shell, true);
                resizeAddressMap(row);
            }

            function setMapSelection(row, latitude, longitude, reverseLookup) {
                const state = addressMaps.get(row);
                const fields = mapFields($(row));
                const lat = Number(latitude);
                const lng = Number(longitude);
                if (!state || !Number.isFinite(lat) || !Number.isFinite(lng)) return;
                fields.latitude.val(lat.toFixed(7));
                fields.longitude.val(lng.toFixed(7));
                state.marker.setLatLng([lat, lng]);
                state.map.setView([lat, lng], 16);
                resizeAddressMap(row);
                if (reverseLookup) reverseGeocodeAddress(row, lat, lng);
            }

            function reverseGeocodeAddress(row, latitude, longitude) {
                const fields = mapFields($(row));
                fields.status.text(@json(__('submission.loading_pin')));
                fetch(reverseGeocodeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ latitude: latitude, longitude: longitude })
                }).then(function (response) {
                    if (!response.ok) throw new Error('Reverse geocoding failed');
                    return response.json();
                }).then(function (payload) {
                    const address = payload.data && payload.data.display_name ? payload.data.display_name : '';
                    if (address) {
                        fields.address.val(address);
                        fields.search.val(address);
                    }
                    fields.status.text(@json(__('submission.selected_coordinate')) + fields.latitude.val() + ', ' + fields.longitude.val());
                }).catch(function () {
                    fields.status.text(@json(__('submission.pin_address_failed')));
                });
            }

            function searchAddressMap(row) {
                const $row = $(row);
                const fields = mapFields($row);
                const query = String(fields.search.val() || '').trim();
                if (!query) {
                    fields.status.text(@json(__('submission.enter_address')));
                    fields.search.trigger('focus');
                    return;
                }
                fields.status.text(@json(__('submission.searching_address')));
                fetch(forwardGeocodeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        address: query,
                        country_code: String($row.find('.address-country-select').val() || '').toLowerCase()
                    })
                }).then(function (response) {
                    if (!response.ok) throw new Error('Address not found');
                    return response.json();
                }).then(function (payload) {
                    const result = payload.data;
                    fields.address.val(result.display_name || query);
                    fields.search.val(result.display_name || query);
                    setMapSelection(row, result.latitude, result.longitude, false);
                    fields.status.text(@json(__('submission.coordinate_found')) + fields.latitude.val() + ', ' + fields.longitude.val());
                }).catch(function () {
                    fields.status.text(@json(__('submission.address_not_found')));
                });
            }

            function initializeAddressMap(row) {
                const canvas = row.querySelector('[data-address-map]');
                if (!canvas || typeof L === 'undefined' || addressMaps.has(row)) return;
                const fields = mapFields($(row));
                const storedLatitude = Number(fields.latitude.val());
                const storedLongitude = Number(fields.longitude.val());
                const hasStoredCoordinate = Number.isFinite(storedLatitude) && Number.isFinite(storedLongitude)
                    && fields.latitude.val() !== '' && fields.longitude.val() !== '';
                const initial = hasStoredCoordinate ? [storedLatitude, storedLongitude] : [-2.5, 118];
                const map = L.map(canvas).setView(initial, hasStoredCoordinate ? 16 : 5);
                L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles &copy; Esri',
                    maxZoom: 19
                }).addTo(map);
                const icon = L.divIcon({
                    className: 'submission-map-marker',
                    html: '<i class="fas fa-map-marker-alt" aria-hidden="true"></i>',
                    iconSize: [30, 40],
                    iconAnchor: [15, 38]
                });
                const marker = L.marker(initial, { draggable: true, icon: icon }).addTo(map);
                addressMaps.set(row, { map: map, marker: marker });
                marker.on('dragend', function () {
                    const point = marker.getLatLng();
                    setMapSelection(row, point.lat, point.lng, true);
                });
                map.on('click', function (event) {
                    setMapSelection(row, event.latlng.lat, event.latlng.lng, true);
                });
                resizeAddressMap(row);
            }

            function countryMarkup(option) {
                if (!option.id) return option.text;
                const code = $(option.element).data('country');
                const $content = $('<span class="country-option"></span>');
                if (code) $('<span></span>', { class: 'flag-icon flag-icon-' + code, 'aria-hidden': 'true' }).appendTo($content);
                $('<span></span>').text(option.text).appendTo($content);
                return $content;
            }

            function initializeSelects(scope) {
                $(scope).find('.phone-country-code, .address-country-select').each(function () {
                    if ($(this).hasClass('select2-hidden-accessible')) return;
                    $(this).select2({ width: '100%', templateResult: countryMarkup, templateSelection: countryMarkup });
                });
                $(scope).find('.address-province-select, .address-city-select').each(function () {
                    if (!$(this).hasClass('select2-hidden-accessible')) $(this).select2({ width: '100%' });
                });
            }

            function setCurrentOption($select, current) {
                if (!current) return false;
                const option = $select.find('option').filter(function () {
                    return String(this.value) === String(current) || $(this).text().trim() === String(current).trim();
                }).first();
                if (!option.length) return false;
                $select.val(option.val()).trigger('change.select2');
                return true;
            }

            function replaceLocationControl($row, type, useSelect) {
                const selector = '.address-' + type + '-select, .address-' + type + '-input';
                const $old = $row.find(selector).first();
                const alreadyCorrectType = useSelect
                    ? $old.is('select.address-' + type + '-select')
                    : $old.is('input.address-' + type + '-input');

                if (alreadyCorrectType) return $old;

                const name = $old.attr('name');
                const id = $old.attr('id');
                const current = $old.data('current') || $old.val() || '';
                if ($old.hasClass('select2-hidden-accessible')) $old.select2('destroy');
                const $control = useSelect
                    ? $('<select class="form-control address-' + type + '-select" required></select>').append($('<option>', { value: '', text: type === 'province' ? @json(__('submission.select_province')) : @json(__('submission.select_city')) }))
                    : $('<input class="form-control address-' + type + '-input" maxlength="100" required>');
                $control.attr('id', id)
                    .attr('name', name)
                    .attr('aria-label', type === 'city' ? 'City or regency' : 'Province')
                    .attr('data-current', current);
                $old.replaceWith($control);
                if (useSelect) $control.select2({ width: '100%' });
                else $control.val(current);
                return $control;
            }

            function loadCities($row, provinceId) {
                const $city = $row.find('.address-city-select');
                if (!$city.length || !provinceId) return;
                $city.prop('disabled', false);
                $.getJSON(regenciesUrl.replace('__PROVINCE__', provinceId)).done(function (response) {
                    const current = $city.data('current');
                    $city.empty().append($('<option>', { value: '', text: @json(__('submission.select_city')) }));
                    (response.data || []).forEach(function (item) { $city.append(new Option(item.name, item.id)); });
                    $city.prop('disabled', false);
                    setCurrentOption($city, current);
                    $city.trigger('change.select2');
                }).fail(function () {
                    $city.prop('disabled', false).trigger('change.select2');
                });
            }

            function loadProvinces($row) {
                const $province = $row.find('.address-province-select');
                if (!$province.length) return;
                $province.prop('disabled', false);
                $.getJSON(provincesUrl).done(function (response) {
                    const current = $province.data('current');
                    $province.empty().append($('<option>', { value: '', text: @json(__('submission.select_province')) }));
                    (response.data || []).forEach(function (item) { $province.append(new Option(item.name, item.id)); });
                    $province.prop('disabled', false);
                    if (setCurrentOption($province, current)) loadCities($row, $province.val());
                    $province.trigger('change.select2');
                }).fail(function () {
                    $province.prop('disabled', false).trigger('change.select2');
                });
            }

            function applyCountryMode($row) {
                const $country = $row.find('.address-country-select');
                const isIndonesia = $country.val() === 'ID';
                $row.find('[data-country-name]').val($country.find(':selected').data('name') || '');
                const $province = replaceLocationControl($row, 'province', isIndonesia);
                replaceLocationControl($row, 'city', isIndonesia);
                if (isIndonesia) loadProvinces($row);
            }

            function initializeRow(row) {
                const $row = $(row);
                initializeSelects($row);
                if ($row.find('.address-country-select').length) applyCountryMode($row);
                initializeAddressMap(row instanceof HTMLElement ? row : $row.get(0));
            }

            $('.repeatable-item').each(function () { initializeRow(this); });

            $('[data-add-row]').on('click', function () {
                const target = $(this).data('add-row');
                const $container = $('#' + target);
                const index = Number($container.data('next-index') || $container.children().length);
                const $row = $(templates[target].replaceAll('__INDEX__', index));
                $container.append($row);
                initializeRow($row);
                $container.data('next-index', index + 1);
            });

            $(document).on('change', '.address-country-select', function () { applyCountryMode($(this).closest('.repeatable-item')); });
            $(document).on('change', '.address-province-select', function () { loadCities($(this).closest('.repeatable-item'), this.value); });
            $(document).on('click', '[data-find-coordinate]', function () { searchAddressMap($(this).closest('.repeatable-item').get(0)); });
            $(document).on('keydown', '[data-coordinate-search]', function (event) {
                if (event.key !== 'Enter') return;
                event.preventDefault();
                searchAddressMap($(this).closest('.repeatable-item').get(0));
            });
            $(document).on('click', '[data-map-fullscreen]', function () { toggleAddressMapFullscreen($(this).closest('.repeatable-item').get(0)); });

            document.addEventListener('fullscreenchange', function () {
                document.querySelectorAll('[data-map-shell]').forEach(function (shell) {
                    const active = document.fullscreenElement === shell;
                    updateMapFullscreenButton(shell, active);
                    const row = shell.closest('.repeatable-item');
                    if (row) resizeAddressMap(row);
                });
            });

            $(document).on('click', '[data-remove-row]', function () {
                const row = $(this).closest('.repeatable-item').get(0);
                const mapState = row ? addressMaps.get(row) : null;
                if (mapState) mapState.map.remove();
                $(row).remove();
            });

            $('#new_attachments').on('change', function () {
                const $list = $('[data-selected-attachments]').empty();
                Array.from(this.files || []).forEach(function (file) {
                    $('<li></li>').text(file.name).appendTo($list);
                });
            });
        });
    </script>
@endpush
