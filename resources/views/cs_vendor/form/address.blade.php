<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">@lang('messages.Address Data')</h3>
    </div>
    <div class="card-body">
        <div class="address-sync-wrapper mb-4">
            <label class="address-sync-control" for="operational_same_as_legal">
                <input class="form-check-input" type="checkbox" id="operational_same_as_legal" checked>
                <span class="address-sync-control__text">Alamat operasional sama dengan alamat legal</span>
            </label>
        </div>
        <div id="address_rows"></div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="add_branch">
                <i class="fas fa-plus" aria-hidden="true"></i> Tambah alamat cabang
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
                    <label data-label="address">Alamat <span class="text-danger">*</span></label>
                    <input type="text" name="address[]" class="form-control" maxlength="1000" data-field="address" required>
                    <span class="text-danger mt-2 d-block" data-message="address" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3">
                    <label data-label="country_code">Negara <span class="text-danger">*</span></label>
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
                    <label data-label="province">Provinsi / State <span class="text-danger">*</span></label>
                    <select name="province[]" class="form-control" data-field="province" required>
                        <option value="">Memuat provinsi...</option>
                    </select>
                    <span class="text-danger mt-2 d-block" data-message="province" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3" data-location-field="city">
                    <label data-label="city">Kota / Kabupaten <span class="text-danger">*</span></label>
                    <select name="city[]" class="form-control" data-field="city" disabled required>
                        <option value="">Pilih provinsi terlebih dahulu</option>
                    </select>
                    <span class="text-danger mt-2 d-block" data-message="city" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3" data-location-field="zip_code">
                    <label data-label="zip_code">Kode Pos <span class="text-danger">*</span></label>
                    <select name="zip_code[]" class="form-control" data-field="zip_code" disabled required>
                        <option value="">Pilih kota/kabupaten terlebih dahulu</option>
                    </select>
                    <span class="text-danger mt-2 d-block" data-message="zip_code" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3">
                    <label data-label="telephone">Telepon <span class="text-danger">*</span></label>
                    <input type="tel" name="telephone[]" class="form-control international-phone" inputmode="numeric" autocomplete="tel-national" maxlength="14" pattern="[0-9]{2,14}" required>
                    <span class="text-danger mt-2 d-block" data-message="telephone" role="alert"></span>
                </div>
                <div class="col-md-4 mb-3">
                    <label data-label="fax">Fax <span class="text-danger">*</span></label>
                    <input type="tel" name="fax[]" class="form-control international-phone" inputmode="numeric" maxlength="14" pattern="[0-9]{2,14}" required>
                    <span class="text-danger mt-2 d-block" data-message="fax" role="alert"></span>
                </div>
                <div class="col-12 mb-3">
                    <label data-label="coordinate_search">Koordinat peta (opsional)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" data-coordinate-search maxlength="500" placeholder="Cari alamat untuk mendapatkan koordinat">
                        <button type="button" class="btn btn-outline-secondary" data-find-coordinate>Cari</button>
                    </div>
                    <div class="mt-2">
                        <label data-label="geocoded_address">Alamat ditemukan</label>
                        <input type="text" name="geocoded_address[]" class="form-control" data-field="geocoded_address" placeholder="Hasil pencarian alamat akan tampil di sini" readonly>
                    </div>
                    <small class="text-muted" data-coordinate-status>Pencarian peta tidak wajib untuk melanjutkan.</small>
                    <div class="partner-map-shell mt-3" data-map-shell>
                        <button type="button" class="partner-map-fullscreen" data-map-fullscreen
                            aria-label="Buka peta layar penuh" title="Buka peta layar penuh">
                            <i class="fas fa-expand" aria-hidden="true"></i>
                            <span>Layar penuh</span>
                        </button>
                        <div class="partner-map" data-address-map aria-label="Peta lokasi alamat"></div>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="d-flex justify-content-end mt-2">
            <button type="button" class="btn btn-danger" data-remove-address hidden>
                <i class="fas fa-minus" aria-hidden="true"></i> Hapus alamat
            </button>
        </div>
    </section>
</template>

<script>
window.addEventListener('load', function () {
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
        if (asSelect) control.innerHTML = '<option value="">' + placeholder + '</option>';
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
        control.innerHTML = '<option value="">' + message + '</option>';
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
        marker.bindTooltip('Geser pin atau klik peta untuk memilih lokasi', { direction: 'top' });
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
        const label = active ? 'Keluar dari layar penuh' : 'Buka peta layar penuh';
        button.setAttribute('aria-label', label);
        button.setAttribute('title', label);
        button.querySelector('span').textContent = active ? 'Keluar layar penuh' : 'Layar penuh';
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
        status.textContent = 'Memuat alamat untuk posisi pin...';

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
            status.textContent = 'Koordinat dipilih: ' + field(row, 'latitude').value + ', ' + field(row, 'longitude').value;
            showPartnerToast('Pencarian Alamat', 'Posisi pin dan alamat berhasil diperbarui.', 'success');
        }).catch(function () {
            if (requestSequence !== state.reverseRequestSequence) return;
            status.textContent = 'Koordinat tetap tersimpan, tetapi alamat untuk posisi pin tidak dapat dimuat.';
            showPartnerToast('Pencarian Alamat', 'Koordinat tetap tersimpan. Alamat tidak dapat diperbarui.', 'warning');
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
        const province = replaceLocationInput(row, 'province', true, 'Memuat provinsi...');
        const city = replaceLocationInput(row, 'city', true, 'Pilih provinsi terlebih dahulu');
        let postal = replaceLocationInput(row, 'zip_code', true, 'Pilih kota/kabupaten terlebih dahulu');
        city.disabled = true;
        postal.disabled = true;
        select2(province, 'Pilih provinsi');
        select2(city, 'Pilih kota/kabupaten');
        select2(postal, 'Pilih kode pos');

        loadJson(provincesUrl).then(function (payload) {
            populate(province, payload.data || [], 'id', function (item) { return item.name; });
            restorePendingLocation(province, true);
        }).catch(function () {
            province.innerHTML = '<option value="">Provinsi tidak dapat dimuat</option>';
        });

        $(province).on('change', function () {
            resetLocationSelect(city, province.value ? 'Memuat kota/kabupaten...' : 'Pilih provinsi terlebih dahulu', true);
            resetLocationSelect(postal, 'Pilih kota/kabupaten terlebih dahulu', true);
            if (!province.value) return;
            loadJson(regenciesUrl.replace('__PROVINCE__', encodeURIComponent(province.value))).then(function (payload) {
                populate(city, payload.data || [], 'id', function (item) { return item.name; });
                restorePendingLocation(city, true);
            }).catch(function () {
                resetLocationSelect(city, 'Kota/kabupaten tidak dapat dimuat', true);
            });
        });

        $(city).on('change', function () {
            resetLocationSelect(postal, city.value ? 'Memuat kode pos...' : 'Pilih kota/kabupaten terlebih dahulu', true);
            if (!city.value) return;
            loadJson(postalCodesUrl.replace('__REGENCY__', encodeURIComponent(city.value))).then(function (payload) {
                const items = payload.data || [];
                if (!items.length) {
                    resetLocationSelect(postal, 'Kode pos belum tersedia untuk kota/kabupaten ini', true);
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
            placeholder: 'Pilih negara',
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
            showPartnerToast('Pencarian Alamat', 'Masukkan alamat yang akan dicari.', 'warning');
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
            status.textContent = 'Koordinat ditemukan: ' + payload.data.latitude + ', ' + payload.data.longitude;
            showCoordinates(row, payload.data.latitude, payload.data.longitude);
            showPartnerToast('Pencarian Alamat', 'Alamat berhasil ditemukan.', 'success');
        }).catch(function () {
            clearCoordinates(row);
            status.textContent = 'Koordinat tidak ditemukan. Form tetap dapat dilanjutkan.';
            showPartnerToast('Pencarian Alamat', 'Alamat tidak ditemukan.', 'danger');
        }).finally(function () { search.disabled = false; });
    });

    document.getElementById('add_branch').addEventListener('click', function () {
        addAddress('branch', 'Alamat Cabang');
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
                title: 'Tips mencari titik alamat',
                html: 'Cari menggunakan nama tempat umum atau alamat terdekat, lalu geser pin atau klik peta untuk menentukan lokasi yang lebih tepat.',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#6558d3'
            });
        }
    });
    addAddress('legal', 'Alamat Legal (sesuai NPWP)');
    addAddress('operational', 'Alamat Operasional');
    setOperationalVisibility();
});
</script>
