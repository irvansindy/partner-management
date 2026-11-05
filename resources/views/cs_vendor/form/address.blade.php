<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.Address Data') }}</h3>
    </div>
    <div class="card-body">
        <div class="address-sync-wrapper mb-4">
            <label class="address-sync-control" for="operational_same_as_legal">
                <input class="form-check-input" type="checkbox" id="operational_same_as_legal" checked>
                <span class="address-sync-control__text">{{ __('messages.Public Operational Same As Legal') }}</span>
            </label>
        </div>
        <div id="address_rows"></div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="add_branch">
                <i class="fas fa-plus" aria-hidden="true"></i> {{ __('messages.Public Add Branch Address') }}
            </button>
        </div>
    </div>
</div>

<template id="address_row_template">
    <section class="address-row mb-4" data-address-row>
        <fieldset class="partner-fieldset">
            <legend class="float-none w-auto text-bold" data-address-title></legend>
            <input type="hidden" name="address_role[]" data-field="address_role">
            <input type="hidden" name="country[]" data-field="country">
            <input type="hidden" name="latitude[]" data-field="latitude">
            <input type="hidden" name="longitude[]" data-field="longitude">

            <div class="row">
                <div class="col-12 mb-3">
                    <label data-label="address">{{ __('messages.Company Address (Other)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="address[]" class="form-control" maxlength="1000" data-field="address" required>
                    <span class="text-danger mt-2 d-block" data-message="address" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3">
                    <label data-label="country_code">{{ __('messages.Country') }} <span class="text-danger">*</span></label>
                    <select name="country_code[]" class="form-control" data-field="country_code" data-address-country required>
                        @foreach(($countries ?? \App\Support\CountryCatalog::all()) as $country)
                            <option value="{{ $country['code'] }}" data-name="{{ $country['name'] }}" @if($country['code'] === 'ID') selected @endif>
                                {{ $country['code'] }} — {{ $country['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger mt-2 d-block" data-message="country_code" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3" data-location-field="province">
                    <label data-label="province">{{ __('messages.Province') }} <span class="text-danger">*</span></label>
                    <select name="province[]" class="form-control" data-field="province" required>
                        <option value="">{{ __('messages.Public Loading Provinces') }}</option>
                    </select>
                    <span class="text-danger mt-2 d-block" data-message="province" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3" data-location-field="city">
                    <label data-label="city">{{ __('messages.City') }} <span class="text-danger">*</span></label>
                    <select name="city[]" class="form-control" data-field="city" disabled required>
                        <option value="">{{ __('messages.Public Select Province First') }}</option>
                    </select>
                    <span class="text-danger mt-2 d-block" data-message="city" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3" data-location-field="zip_code">
                    <label data-label="zip_code">{{ __('messages.Postal Code') }} <span class="text-danger">*</span></label>
                    <select name="zip_code[]" class="form-control" data-field="zip_code" disabled required>
                        <option value="">{{ __('messages.Public Select Regency First') }}</option>
                    </select>
                    <span class="text-danger mt-2 d-block" data-message="zip_code" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3">
                    <label data-label="telephone">{{ __('messages.Telephone') }} <span class="text-danger">*</span></label>
                    <input type="tel" name="telephone[]" class="form-control international-phone" inputmode="numeric" autocomplete="tel-national" maxlength="14" pattern="[0-9]{2,14}" required>
                    <span class="text-danger mt-2 d-block" data-message="telephone" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3">
                    <label data-label="fax">Fax</label>
                    <input type="tel" name="fax[]" class="form-control international-phone" inputmode="numeric" maxlength="14" pattern="[0-9]{2,14}">
                    <span class="text-danger mt-2 d-block" data-message="fax" role="alert"></span>
                </div>
                <div class="col-12 mb-3">
                    <label data-label="coordinate_search">{{ __('messages.Public Map Coordinate') }}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" data-coordinate-search maxlength="500" placeholder="{{ __('messages.Public Search Coordinate Placeholder') }}">
                        <button type="button" class="btn btn-outline-secondary" data-find-coordinate>{{ __('messages.Public Search') }}</button>
                    </div>
                    <div class="mt-2">
                        <label data-label="geocoded_address">{{ __('messages.Public Geocoded Address') }}</label>
                        <input type="text" name="geocoded_address[]" class="form-control" data-field="geocoded_address" placeholder="{{ __('messages.Public Geocoded Address Placeholder') }}" readonly>
                    </div>
                    <small class="text-muted" data-coordinate-status>{{ __('messages.Public Map Optional') }}</small>
                    <div class="partner-map-shell mt-3" data-map-shell>
                        <button type="button" class="partner-map-fullscreen" data-map-fullscreen
                            aria-label="{{ __('messages.Public Open Fullscreen Map') }}" title="{{ __('messages.Public Open Fullscreen Map') }}">
                            <i class="fas fa-expand" aria-hidden="true"></i>
                            <span>{{ __('messages.Public Fullscreen') }}</span>
                        </button>
                        <div class="partner-map" data-address-map aria-label="{{ __('messages.Public Address Map') }}"></div>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="d-flex justify-content-end mt-2">
            <button type="button" class="btn btn-danger" data-remove-address hidden>
                <i class="fas fa-minus" aria-hidden="true"></i> {{ __('messages.Public Remove Address') }}
            </button>
        </div>
    </section>
</template>

@php
    $addressCopy = [
        'searchTitle' => __('messages.Public Address Search'),
        'pinUpdated' => __('messages.Public Pin Updated'),
        'addressUpdateFailed' => __('messages.Public Address Update Failed'),
        'enterSearch' => __('messages.Public Enter Search Address'),
        'addressFound' => __('messages.Public Address Found'),
        'addressNotFound' => __('messages.Public Address Not Found'),
        'coordinateFound' => __('messages.Public Coordinate Found'),
        'coordinateNotFound' => __('messages.Public Coordinate Not Found'),
        'selectProvinceFirst' => __('messages.Public Select Province First'),
        'selectRegencyFirst' => __('messages.Public Select Regency First'),
        'selectProvince' => __('messages.Public Select Province'),
        'selectRegency' => __('messages.Public Select Regency'),
        'selectPostalCode' => __('messages.Public Select Postal Code'),
        'loadingRegencies' => __('messages.Public Loading Regencies'),
        'loadingPostalCodes' => __('messages.Public Loading Postal Codes'),
        'provinceLoadFailed' => __('messages.Public Province Load Failed'),
        'regencyLoadFailed' => __('messages.Public Regency Load Failed'),
        'selectCountry' => __('messages.Public Select Country'),
        'branchAddress' => __('messages.Public Branch Address'),
        'legalAddress' => __('messages.Public Legal Address'),
        'operationalAddress' => __('messages.Public Operational Address'),
        'mapGuidance' => __('messages.Public Map Guidance'),
        'mapGuidanceTitle' => __('messages.Public Map Guidance Title'),
        'understand' => __('messages.Public Understand'),
        'exitFullscreenMap' => __('messages.Public Exit Fullscreen Map'),
        'openFullscreenMap' => __('messages.Public Open Fullscreen Map Short'),
        'exitFullscreen' => __('messages.Public Exit Fullscreen'),
        'fullscreen' => __('messages.Public Fullscreen'),
        'loadingPinAddress' => __('messages.Public Loading Pin Address'),
        'selectedCoordinate' => __('messages.Public Selected Coordinate'),
        'loadingProvinces' => __('messages.Public Loading Provinces'),
        'pinInstruction' => __('messages.Public Pin Instruction'),
        'reverseAddressFailed' => __('messages.Public Reverse Address Failed'),
        'postalCodeUnavailable' => __('messages.Public Postal Code Unavailable'),
    ];
@endphp
<script>
window.addEventListener('load', function () {
    const addressCopy = @json($addressCopy);
    const rows = document.getElementById('address_rows');
    const template = document.getElementById('address_row_template');
    const sameAsLegal = document.getElementById('operational_same_as_legal');
    const provincesUrl = @json(route('public.form.provinces'));
    const regenciesUrl = @json(route('public.form.regencies', ['province' => '__PROVINCE__']));
    const postalCodesUrl = @json(route('public.form.postal-codes', ['regency' => '__REGENCY__']));
    const geocodeUrl = @json(route('forward-map'));
    const reverseGeocodeUrl = @json(route('reverse-map'));
    const maps = new WeakMap();
    let nextIndex = 0;
    let hasShownAddressGuidance = false;

    if (typeof L !== 'undefined') {
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'
        });
    }

    function field(row, name) {
        return row.querySelector('[data-field="' + name + '"]');
    }

    function setIdentity(row, role, title) {
        const index = nextIndex++;
        row.dataset.role = role;
        field(row, 'address_role').value = role;
        row.querySelector('[data-address-title]').textContent = title;
        row.querySelectorAll('[data-field], input[name="telephone[]"], input[name="fax[]"]').forEach(function (input) {
            const name = input.dataset.field || input.name.replace('[]', '');
            input.id = name + '_' + index;
            const label = row.querySelector('[data-label="' + name + '"]');
            if (label) label.setAttribute('for', input.id);
            const message = row.querySelector('[data-message="' + name + '"]');
            if (message) message.id = 'message_' + name + '_' + index;
        });
    }

    function replaceLocationInput(row, name, asSelect, placeholder) {
        const old = field(row, name);
        const pendingValue = old.dataset.pendingLocationRestore || old.value;
        if ($(old).hasClass('select2-hidden-accessible')) $(old).select2('destroy');
        const control = document.createElement(asSelect ? 'select' : 'input');
        control.name = name + '[]';
        control.id = old.id;
        control.className = 'form-control';
        control.dataset.field = name;
        control.required = true;
        if (asSelect) control.add(new Option(placeholder, ''));
        else {
            control.type = 'text';
            control.maxLength = name === 'zip_code' ? 20 : 100;
            control.placeholder = placeholder;
        }
        if (pendingValue) control.dataset.pendingLocationRestore = pendingValue;
        old.replaceWith(control);
        return control;
    }

    function select2(control, placeholder) {
        $(control).select2({ width: '100%', placeholder: placeholder });
    }

    function countryOptionMarkup(option) {
        if (!option.id) return option.text;

        const code = String(option.id).toLowerCase();
        const optionRow = $('<span class="country-option"></span>');
        $('<span></span>', {
            class: 'iti__flag iti__' + code,
            'aria-hidden': 'true'
        }).appendTo(optionRow);
        $('<span class="country-option__label"></span>').text(option.text).appendTo(optionRow);

        return optionRow;
    }

    function populate(control, items, valueKey, label) {
        control.innerHTML = '<option value=""></option>';
        items.forEach(function (item) {
            control.add(new Option(label(item), item[valueKey]));
        });
        control.disabled = Boolean(control.closest('[data-address-row][hidden]'));
        $(control).trigger('change.select2');
    }

    function resetLocationSelect(control, message, disabled) {
        control.replaceChildren(new Option(message, ''));
        control.disabled = disabled;
        $(control).trigger('change.select2');
    }

    function loadJson(url) {
        return fetch(url, { headers: { Accept: 'application/json' } }).then(function (response) {
            if (!response.ok) throw new Error('lookup failed');
            return response.json();
        });
    }

    function restorePendingLocation(control, dispatchChange) {
        const value = control.dataset.pendingLocationRestore;
        if (!value) return false;
        if (control.tagName === 'SELECT' && !Array.from(control.options).some(function (option) { return option.value == value; })) {
            return false;
        }
        control.value = value;
        delete control.dataset.pendingLocationRestore;
        $(control).trigger('change.select2');
        if (dispatchChange) control.dispatchEvent(new Event('change', { bubbles: true }));
        return true;
    }

    function initializeMap(row) {
        const canvas = row.querySelector('[data-address-map]');
        if (!canvas || typeof L === 'undefined') return;
        const initialPosition = [-2.5, 118];
        const map = L.map(canvas).setView(initialPosition, 5);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 19
        }).addTo(map);
        maps.set(row, { map: map, marker: null, reverseRequestSequence: 0, hasSelection: false });
        ensureDraggableMarker(row, initialPosition[0], initialPosition[1], false);
        map.on('click', function (event) {
            updatePinFromSelection(row, event.latlng.lat, event.latlng.lng);
        });

        const fullscreenButton = row.querySelector('[data-map-fullscreen]');
        if (fullscreenButton) {
            fullscreenButton.addEventListener('click', function (event) {
                event.stopPropagation();
                toggleMapFullscreen(row);
            });
        }
    }

    function setCoordinateValues(row, latitude, longitude) {
        field(row, 'latitude').value = Number(latitude).toFixed(7);
        field(row, 'longitude').value = Number(longitude).toFixed(7);
        field(row, 'latitude').dispatchEvent(new Event('input', { bubbles: true }));
        field(row, 'longitude').dispatchEvent(new Event('input', { bubbles: true }));
    }

    function setMarkerSelectionState(state, selected) {
        state.hasSelection = selected;
        if (state.marker) state.marker.setOpacity(selected ? 1 : 0.65);
    }

    function ensureDraggableMarker(row, lat, lng, selected) {
        const state = maps.get(row);
        if (!state) return null;
        if (state.marker) {
            state.marker.setLatLng([lat, lng]);
            setMarkerSelectionState(state, selected !== false);
            return state.marker;
        }

        const marker = L.marker([lat, lng], { draggable: true }).addTo(state.map);
        marker.bindTooltip(addressCopy.pinInstruction, { direction: 'top' });
        marker.on('dragend', function () {
            const position = marker.getLatLng();
            updatePinFromSelection(row, position.lat, position.lng);
        });
        state.marker = marker;
        setMarkerSelectionState(state, selected !== false);
        return marker;
    }

    function showCoordinates(row, latitude, longitude) {
        const state = maps.get(row);
        const lat = Number(latitude);
        const lng = Number(longitude);
        if (!state || !Number.isFinite(lat) || !Number.isFinite(lng)) return;
        ensureDraggableMarker(row, lat, lng, true);
        state.map.setView([lat, lng], 16);
        setTimeout(function () { state.map.invalidateSize(); }, 0);
    }

    function setFullscreenButtonState(shell, active) {
        const button = shell.querySelector('[data-map-fullscreen]');
        if (!button) return;
        const label = active ? addressCopy.exitFullscreenMap : addressCopy.openFullscreenMap;
        button.setAttribute('aria-label', label);
        button.setAttribute('title', label);
        button.querySelector('span').textContent = active ? addressCopy.exitFullscreen : addressCopy.fullscreen;
        button.querySelector('i').className = active ? 'fas fa-compress' : 'fas fa-expand';
    }

    function resizeFullscreenMap(shell) {
        const row = shell.closest('[data-address-row]');
        const state = row ? maps.get(row) : null;
        if (state) setTimeout(function () { state.map.invalidateSize(); }, 0);
    }

    function setFallbackFullscreen(shell, active) {
        shell.classList.toggle('is-map-fullscreen', active);
        document.body.classList.toggle('map-fullscreen-open', active);
        setFullscreenButtonState(shell, active);
        resizeFullscreenMap(shell);
    }

    function toggleMapFullscreen(row) {
        const shell = row.querySelector('[data-map-shell]');
        if (!shell) return;

        if (document.fullscreenElement === shell) {
            document.exitFullscreen();
            return;
        }
        if (shell.classList.contains('is-map-fullscreen')) {
            setFallbackFullscreen(shell, false);
            return;
        }
        if (shell.requestFullscreen) {
            const request = shell.requestFullscreen();
            if (request && typeof request.catch === 'function') {
                request.catch(function () { setFallbackFullscreen(shell, true); });
            }
            return;
        }
        setFallbackFullscreen(shell, true);
    }

    function reverseGeocodePin(row, latitude, longitude) {
        const state = maps.get(row);
        if (!state) return;
        const requestSequence = ++state.reverseRequestSequence;
        const status = row.querySelector('[data-coordinate-status]');
        const address = field(row, 'geocoded_address');
        address.value = '';
        address.dispatchEvent(new Event('input', { bubbles: true }));
        status.textContent = addressCopy.loadingPinAddress;

        fetch(reverseGeocodeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ latitude: latitude, longitude: longitude })
        }).then(function (response) {
            if (!response.ok) throw new Error('reverse lookup failed');
            return response.json();
        }).then(function (payload) {
            if (requestSequence !== state.reverseRequestSequence) return;
            address.value = payload.data.display_name || '';
            address.dispatchEvent(new Event('input', { bubbles: true }));
            const coordinate = field(row, 'latitude').value + ', ' + field(row, 'longitude').value;
            status.textContent = addressCopy.selectedCoordinate.replace(':coordinate', coordinate);
            showPartnerToast(addressCopy.searchTitle, addressCopy.pinUpdated, 'success');
        }).catch(function () {
            if (requestSequence !== state.reverseRequestSequence) return;
            status.textContent = addressCopy.reverseAddressFailed;
            showPartnerToast(addressCopy.searchTitle, addressCopy.addressUpdateFailed, 'warning');
        });
    }

    function updatePinFromSelection(row, latitude, longitude) {
        setCoordinateValues(row, latitude, longitude);
        showCoordinates(row, latitude, longitude);
        reverseGeocodePin(row, Number(latitude), Number(longitude));
    }

    function clearCoordinates(row) {
        field(row, 'latitude').value = '';
        field(row, 'longitude').value = '';
        field(row, 'geocoded_address').value = '';
        const state = maps.get(row);
        if (state) state.reverseRequestSequence++;
        if (state && state.marker) {
            const center = state.map.getCenter();
            state.marker.setLatLng(center);
            setMarkerSelectionState(state, false);
        }
    }

    function configureIndonesia(row) {
        const province = replaceLocationInput(row, 'province', true, addressCopy.loadingProvinces);
        const city = replaceLocationInput(row, 'city', true, addressCopy.selectProvinceFirst);
        let postal = replaceLocationInput(row, 'zip_code', true, addressCopy.selectRegencyFirst);
        city.disabled = true;
        postal.disabled = true;
        select2(province, addressCopy.selectProvince);
        select2(city, addressCopy.selectRegency);
        select2(postal, addressCopy.selectPostalCode);

        loadJson(provincesUrl).then(function (payload) {
            populate(province, payload.data || [], 'id', function (item) { return item.name; });
            restorePendingLocation(province, true);
        }).catch(function () {
            province.replaceChildren(new Option(addressCopy.provinceLoadFailed, ''));
        });

        $(province).on('change', function () {
            resetLocationSelect(city, province.value ? addressCopy.loadingRegencies : addressCopy.selectProvinceFirst, true);
            resetLocationSelect(postal, addressCopy.selectRegencyFirst, true);
            if (!province.value) return;
            loadJson(regenciesUrl.replace('__PROVINCE__', encodeURIComponent(province.value))).then(function (payload) {
                populate(city, payload.data || [], 'id', function (item) { return item.name; });
                restorePendingLocation(city, true);
            }).catch(function () {
            resetLocationSelect(city, addressCopy.regencyLoadFailed, true);
            });
        });

        $(city).on('change', function () {
            resetLocationSelect(postal, city.value ? addressCopy.loadingPostalCodes : addressCopy.selectRegencyFirst, true);
            if (!city.value) return;
            loadJson(postalCodesUrl.replace('__REGENCY__', encodeURIComponent(city.value))).then(function (payload) {
                const items = payload.data || [];
                if (!items.length) {
                    resetLocationSelect(postal, addressCopy.postalCodeUnavailable, true);
                    return;
                }
                populate(postal, items, 'postal_code', function (item) {
                    return item.area ? item.postal_code + ' — ' + item.area : item.postal_code;
                });
                restorePendingLocation(postal, false);
            }).catch(function () {
                resetLocationSelect(postal, 'Kode pos tidak dapat dimuat', true);
            });
        });
    }

    function configureForeign(row) {
        replaceLocationInput(row, 'province', false, 'State / province');
        replaceLocationInput(row, 'city', false, 'City');
        replaceLocationInput(row, 'zip_code', false, 'Postal code');
    }

    function configureCountry(row) {
        const country = field(row, 'country_code');
        const selected = country.options[country.selectedIndex];
        field(row, 'country').value = selected ? selected.dataset.name : '';
        if (country.value === 'ID') configureIndonesia(row);
        else configureForeign(row);
    }

    function addAddress(role, title) {
        const row = template.content.firstElementChild.cloneNode(true);
        setIdentity(row, role, title);
        rows.appendChild(row);
        const country = field(row, 'country_code');
        $(country).select2({
            width: '100%',
            placeholder: addressCopy.selectCountry,
            templateResult: countryOptionMarkup,
            templateSelection: countryOptionMarkup
        });
        $(country).on('change', function () { configureCountry(row); });
        configureCountry(row);
        initializeInternationalPhoneInputs(row);
        initializeMap(row);
        if (role === 'branch') row.querySelector('[data-remove-address]').hidden = false;
        return row;
    }

    function setOperationalVisibility() {
        const row = rows.querySelector('[data-role="operational"]');
        const hidden = sameAsLegal.checked;
        row.hidden = hidden;
        row.querySelectorAll('input, select, button').forEach(function (control) { control.disabled = hidden; });
        if (!hidden && maps.has(row)) setTimeout(function () { maps.get(row).map.invalidateSize(); }, 0);
    }

    rows.addEventListener('click', function (event) {
        const remove = event.target.closest('[data-remove-address]');
        if (remove) remove.closest('[data-address-row]').remove();

        const search = event.target.closest('[data-find-coordinate]');
        if (!search) return;
        const row = search.closest('[data-address-row]');
        const mapState = maps.get(row);
        if (mapState) mapState.reverseRequestSequence++;
        const query = row.querySelector('[data-coordinate-search]').value.trim();
        const status = row.querySelector('[data-coordinate-status]');
        if (!query) {
            status.textContent = 'Masukkan alamat yang akan dicari.';
            showPartnerToast(addressCopy.searchTitle, addressCopy.enterSearch, 'warning');
            return;
        }
        search.disabled = true;
        status.textContent = 'Mencari koordinat...';
        fetch(geocodeUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ address: query, country_code: field(row, 'country_code').value })
        }).then(function (response) {
            if (!response.ok) throw new Error('not found');
            return response.json();
        }).then(function (payload) {
            setCoordinateValues(row, payload.data.latitude, payload.data.longitude);
            field(row, 'geocoded_address').value = payload.data.display_name || query;
            field(row, 'geocoded_address').dispatchEvent(new Event('input', { bubbles: true }));
            status.textContent = addressCopy.coordinateFound.replace(':coordinate', payload.data.latitude + ', ' + payload.data.longitude);
            showCoordinates(row, payload.data.latitude, payload.data.longitude);
            showPartnerToast(addressCopy.searchTitle, addressCopy.addressFound, 'success');
        }).catch(function () {
            clearCoordinates(row);
            status.textContent = addressCopy.coordinateNotFound;
            showPartnerToast(addressCopy.searchTitle, addressCopy.addressNotFound, 'danger');
        }).finally(function () { search.disabled = false; });
    });

    document.getElementById('add_branch').addEventListener('click', function () {
        addAddress('branch', addressCopy.branchAddress);
    });
    sameAsLegal.addEventListener('change', setOperationalVisibility);
    rows.addEventListener('partner:draft-location-ready', function (event) {
        const row = event.target.closest('[data-address-row]');
        if (!row || field(row, 'country_code').value !== 'ID') return;
        restorePendingLocation(field(row, 'province'), true);
        restorePendingLocation(field(row, 'city'), true);
        restorePendingLocation(field(row, 'zip_code'), false);
    });
    document.addEventListener('partner:draft-restored', function () {
        rows.querySelectorAll('[data-address-row]').forEach(function (row) {
            showCoordinates(row, field(row, 'latitude').value, field(row, 'longitude').value);
        });
    });
    document.addEventListener('fullscreenchange', function () {
        rows.querySelectorAll('[data-map-shell]').forEach(function (shell) {
            const active = document.fullscreenElement === shell;
            setFullscreenButtonState(shell, active);
            if (active || !document.fullscreenElement) resizeFullscreenMap(shell);
        });
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            const shell = rows.querySelector('[data-map-shell].is-map-fullscreen');
            if (shell) setFallbackFullscreen(shell, false);
        }
    });
    document.addEventListener('partner:address-step-shown', function () {
        rows.querySelectorAll('[data-address-row]:not([hidden])').forEach(function (row) {
            const state = maps.get(row);
            if (state) setTimeout(function () { state.map.invalidateSize(); }, 0);
        });
        if (!hasShownAddressGuidance && typeof Swal !== 'undefined') {
            hasShownAddressGuidance = true;
            Swal.fire({
                icon: 'info',
                title: addressCopy.mapGuidanceTitle,
                text: addressCopy.mapGuidance,
                confirmButtonText: addressCopy.understand,
                confirmButtonColor: '#6558d3'
            });
        }
    });
    addAddress('legal', addressCopy.legalAddress);
    addAddress('operational', addressCopy.operationalAddress);
    setOperationalVisibility();
});
</script>
