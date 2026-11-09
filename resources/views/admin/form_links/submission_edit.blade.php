@extends('layouts.main')

@push('styles')
    @include('admin.form_links._alpha_styles')
    <link rel="stylesheet" href="{{ asset('vendor/flag-icon-css/css/flag-icons.min.css') }}">
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
        .submission-edit-page .phone-input-group { display: flex; width: 100%; }
        .submission-edit-page .form-group > .static-field-label {
            color: var(--form-link-text-muted);
            display: block;
            font-size: 12px;
            left: auto;
            line-height: 1.25;
            margin: 0 0 7px;
            position: static;
            top: auto;
            transform: none;
        }
        .submission-edit-page .phone-input-group > .select2-container { flex: 0 0 128px; width: 128px !important; }
        .submission-edit-page .phone-input-group > .select2-container .select2-selection--single { background: #fbfbfd; border: 1px solid var(--form-link-border); }
        .submission-edit-page .phone-input-group .phone-number { border-bottom-left-radius: 0; border-top-left-radius: 0; min-width: 0; }
        .submission-edit-page .phone-input-group .select2-selection { border-bottom-right-radius: 0 !important; border-top-right-radius: 0 !important; }
        .submission-edit-page .select2-container { width: 100% !important; }
        .submission-edit-page .select2-container--default .select2-selection--single { align-items: center; display: flex; min-height: 44px; }
        .submission-edit-page .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 42px; padding-left: 12px; }
        .submission-edit-page .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px; }
        .submission-edit-page .country-option { align-items: center; display: inline-flex; gap: 8px; }
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
                <h2>Edit Submission</h2>
                <p>{{ $company->name }} · Perubahan masih memungkinkan sebelum tahap approval terakhir aktif.</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}" class="btn btn-outline-secondary">
                    <i class="material-icons align-middle mr-1" aria-hidden="true">arrow_back</i> Detail
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Periksa kembali data berikut:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.form-links.submission-update', [$formLink, $company->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header"><h3 class="card-title">Company Information</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name">Company Name <span class="text-danger">*</span></label>
                            <input id="name" name="name" class="form-control" value="{{ old('name', $company->name) }}" maxlength="255" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="group_name">Company Group Name</label>
                            <input id="group_name" name="group_name" class="form-control" value="{{ old('group_name', $company->group_name) }}" maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="established_year">Established Year</label>
                            <input id="established_year" name="established_year" type="number" class="form-control" value="{{ old('established_year', $company->established_year) }}" min="1900" max="{{ date('Y') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="total_employee">Total Employee</label>
                            <select id="total_employee" name="total_employee" class="form-control">
                                <option value="">Select employee range</option>
                                @foreach (\App\Support\EmployeeRange::values() as $range)
                                    <option value="{{ $range }}" @selected(old('total_employee', $company->total_employee) === $range)>{{ $range }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="business_classification">Business Classification <span class="text-danger">*</span></label>
                            <input id="business_classification" name="business_classification" class="form-control" value="{{ old('business_classification', $company->business_classification) }}" maxlength="255" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="business_classification_detail">Classification Detail</label>
                            <input id="business_classification_detail" name="business_classification_detail" class="form-control" value="{{ old('business_classification_detail', $company->business_classification_detail) }}" maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="register_number_as_in_tax_invoice">Tax Register Number</label>
                            <input id="register_number_as_in_tax_invoice" name="register_number_as_in_tax_invoice" class="form-control" value="{{ old('register_number_as_in_tax_invoice', $company->register_number_as_in_tax_invoice) }}" maxlength="100">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="website_address">Website Address</label>
                            <input id="website_address" name="website_address" type="url" class="form-control" value="{{ old('website_address', $company->website_address) }}" maxlength="255" placeholder="https://example.com">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="system_management">Certificate Type</label>
                            <input id="system_management" name="system_management" class="form-control" value="{{ old('system_management', $company->system_management) }}" maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email_address">Correspondence Email</label>
                            <input id="email_address" name="email_address" type="email" class="form-control" value="{{ old('email_address', $company->email_address) }}" maxlength="255">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="credit_limit">Credit Limit</label>
                            <input id="credit_limit" name="credit_limit" type="number" class="form-control" value="{{ old('credit_limit', $company->credit_limit) }}" min="0" step="0.01">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="term_of_payment">Term of Payment</label>
                            <input id="term_of_payment" name="term_of_payment" class="form-control" value="{{ old('term_of_payment', $company->term_of_payment) }}" maxlength="100">
                        </div>
                    </div>
                </div>
            </div>

            <x-submission-repeatable-section title="Liable Persons" container="liable-people" add-label="Add liable person">
                @foreach ($liablePeople as $index => $person)
                    @include('admin.form_links._submission_edit_row', ['kind' => 'liable_people', 'index' => $index, 'data' => $person])
                @endforeach
            </x-submission-repeatable-section>

            <x-submission-repeatable-section title="Contact Persons" container="contacts" add-label="Add contact person">
                @foreach ($contacts as $index => $contact)
                    @include('admin.form_links._submission_edit_row', ['kind' => 'contacts', 'index' => $index, 'data' => $contact])
                @endforeach
            </x-submission-repeatable-section>

            <x-submission-repeatable-section title="Addresses" container="addresses" add-label="Add address">
                @foreach ($addresses as $index => $address)
                    @include('admin.form_links._submission_edit_row', ['kind' => 'addresses', 'index' => $index, 'data' => $address])
                @endforeach
            </x-submission-repeatable-section>

            <x-submission-repeatable-section title="Bank Information" container="banks" add-label="Add bank account">
                @foreach ($banks as $index => $bank)
                    @include('admin.form_links._submission_edit_row', ['kind' => 'banks', 'index' => $index, 'data' => $bank])
                @endforeach
            </x-submission-repeatable-section>

            <div class="card">
                <div class="card-header"><h3 class="card-title">File Attachments</h3></div>
                <div class="card-body">
                    @forelse ($company->attachment as $attachment)
                        <div class="attachment-row">
                            <div class="attachment-row__file">
                                <i class="fas fa-paperclip" aria-hidden="true"></i>
                                <span class="attachment-row__name">{{ $attachment->original_name ?? $attachment->filename }}</span>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="delete_attachment_{{ $attachment->id }}" name="delete_attachment_ids[]" value="{{ $attachment->id }}">
                                <label class="custom-control-label text-danger" for="delete_attachment_{{ $attachment->id }}">Remove</label>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No current attachments.</p>
                    @endforelse
                    <div class="attachment-picker" data-attachment-picker>
                        <div class="attachment-picker__copy">
                            <strong>Add attachments</strong>
                            <small class="text-muted">PDF, JPG, JPEG, or PNG. Maximum 10 MB per file.</small>
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
                    <a href="{{ route('admin.form-links.submission-detail', [$formLink, $company->id]) }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1" aria-hidden="true"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const templates = @json($rowTemplates);
            const provincesUrl = @json(route('public.form.provinces'));
            const regenciesUrl = @json(route('public.form.regencies', ['province' => '__PROVINCE__']));

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
                const name = $old.attr('name');
                const id = $old.attr('id');
                const current = $old.data('current') || $old.val() || '';
                if ($old.hasClass('select2-hidden-accessible')) $old.select2('destroy');
                const $control = useSelect
                    ? $('<select class="form-control address-' + type + '-select" required><option value="">Select ' + type + '</option></select>')
                    : $('<input class="form-control address-' + type + '-input" maxlength="100" required>');
                $control.attr('id', id).attr('name', name).attr('data-current', current);
                $old.replaceWith($control);
                if (useSelect) $control.select2({ width: '100%' });
                else $control.val(current);
                return $control;
            }

            function loadCities($row, provinceId) {
                const $city = $row.find('.address-city-select');
                if (!$city.length || !provinceId) return;
                $city.prop('disabled', true).html('<option value="">Loading cities...</option>').trigger('change.select2');
                $.getJSON(regenciesUrl.replace('__PROVINCE__', provinceId)).done(function (response) {
                    const current = $city.data('current');
                    $city.html('<option value="">Select city / regency</option>');
                    (response.data || []).forEach(function (item) { $city.append(new Option(item.name, item.id)); });
                    $city.prop('disabled', false);
                    setCurrentOption($city, current);
                    $city.trigger('change.select2');
                }).fail(function () {
                    $city.html('<option value="">Unable to load cities</option>').prop('disabled', true).trigger('change.select2');
                });
            }

            function loadProvinces($row) {
                const $province = $row.find('.address-province-select');
                if (!$province.length) return;
                $province.prop('disabled', true).html('<option value="">Loading provinces...</option>').trigger('change.select2');
                $.getJSON(provincesUrl).done(function (response) {
                    const current = $province.data('current');
                    $province.html('<option value="">Select province</option>');
                    (response.data || []).forEach(function (item) { $province.append(new Option(item.name, item.id)); });
                    $province.prop('disabled', false);
                    if (setCurrentOption($province, current)) loadCities($row, $province.val());
                    $province.trigger('change.select2');
                }).fail(function () {
                    $province.html('<option value="">Unable to load provinces</option>').prop('disabled', true).trigger('change.select2');
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

            $(document).on('click', '[data-remove-row]', function () {
                $(this).closest('.repeatable-item').remove();
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
