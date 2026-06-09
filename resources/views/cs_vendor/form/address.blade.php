<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Address Data')
        </h3>
    </div>
    <div class="card-body">
        <div class="row partner-form-section">
            <div class="company_address_additional" id="company_address_additional">
                <!-- Address 1: Company Address (according to NPWP) -->
                <fieldset class="partner-fieldset mb-4">
                    <legend class="float-none w-auto text-bold">@lang('messages.Address Data')</legend>
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label for="address_0">@lang('messages.Company Address (according to NPWP)') <span class="text-danger" role="alert">*</span></label>
                            <input type="text" name="address[]" id="address_0" class="form-control" required>
                            <span class="text-danger mt-2 message_address" id="message_address_0" role="alert"></span>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="country_0">@lang('messages.Country') <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="country[]" id="country_0" class="form-control" value="Indonesia" readonly required>
                                <span class="text-danger mt-2 message_country" id="message_country_0" role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="select_option_province_0">@lang('messages.Province') <span class="text-danger" role="alert">*</span></label>
                                <select name="province[]" id="select_option_province_0" class="form-control" required></select>
                                <span class="text-danger mt-2 message_province" id="message_province_0" role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="select_option_regency_0">@lang('messages.City') <span class="text-danger" role="alert">*</span></label>
                                <select name="city[]" id="select_option_regency_0" class="form-control" required></select>
                                <span class="text-danger mt-2 message_city" id="message_city_0" role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="zip_code_0">@lang('messages.Postal Code') <span class="text-danger" role="alert">*</span></label>
                                <input type="text"
                                    name="zip_code[]"
                                    id="zip_code_0"
                                    class="form-control"
                                    placeholder="@lang('messages.Placeholder Address Postal Code')"
                                    maxlength="5"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,5)"
                                    required>
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_0" role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone_0">@lang('messages.Telephone') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Telephone Info')</p>
                                <input type="text"
                                    name="telephone[]"
                                    id="telephone_0"
                                    class="form-control"
                                    placeholder="@lang('messages.Placeholder Address Telephone')"
                                    maxlength="13"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,13)"
                                    required>
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_0" role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="fax_0">@lang('messages.Fax') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Fax Info')</p>
                                <input type="text"
                                    name="fax[]"
                                    id="fax_0"
                                    class="form-control"
                                    placeholder="@lang('messages.Placeholder Address Fax')"
                                    maxlength="15"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,15)"
                                    required>
                                <span class="text-danger mt-2 message_fax" id="message_fax_0" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- Address 2: Company Address (Other) with Map Geocoder -->
                <fieldset class="partner-fieldset mb-4">
                    <legend class="float-none w-auto text-bold">@lang('messages.Address Data')</legend>

                    <!-- Search Address Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="search_address_1" class="form-label">@lang('messages.Search Address')</label>
                            <div class="input-group">
                                <input type="text" id="search_address_1" class="form-control"
                                    placeholder="Masukkan alamat untuk dicari...">
                                <button class="btn btn-outline-secondary" type="button" id="search_button_1">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div id="map_1" class="partner-map"></div>
                        </div>
                    </div>

                    <!-- Address Selection Dropdown -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="select_searched_address_1" class="form-label">@lang('messages.Select Address from Search Results')</label>
                            <select id="select_searched_address_1" class="form-control" disabled>
                                <option value="" data-lat="" data-lng="">-- Pilih Alamat --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-4">
                            <label for="address_1">@lang('messages.Company Address (according to Map)') <span class="text-danger" role="alert">*</span></label>
                            <input type="text" name="address[]" id="address_1" class="form-control"
                                placeholder="ex: Jl. HM Ashari No. 47 001/001 Cibinong" required>
                            <span class="text-muted mt-2 d-block">@lang('messages.Format Address')</span>
                            <span class="text-danger mt-2 message_address" id="message_address_1" role="alert"></span>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="latitude_1">Latitude <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="latitude" id="latitude_1" class="form-control" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude_1">Longitude <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="longitude" id="longitude_1" class="form-control" readonly required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="country_1">@lang('messages.Country') <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="country[]" id="country_1" class="form-control" value="Indonesia" readonly required>
                                <span class="text-danger mt-2 message_country" id="message_country_1" role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="select_option_province_1">@lang('messages.Province') <span class="text-danger" role="alert">*</span></label>
                                <select name="province[]" id="select_option_province_1" class="form-control" required></select>
                                <span class="text-danger mt-2 message_province" id="message_province_1" role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="select_option_regency_1">@lang('messages.City') <span class="text-danger" role="alert">*</span></label>
                                <select name="city[]" id="select_option_regency_1" class="form-control" required></select>
                                <span class="text-danger mt-2 message_city" id="message_city_1" role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="zip_code_1">@lang('messages.Postal Code') <span class="text-danger" role="alert">*</span></label>
                                <input type="text"
                                    name="zip_code[]"
                                    id="zip_code_1"
                                    class="form-control"
                                    placeholder="@lang('messages.Placeholder Address Postal Code')"
                                    maxlength="5"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,5)"
                                    required>
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_1" role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone_1">@lang('messages.Telephone') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Telephone Info')</p>
                                <input type="text"
                                    name="telephone[]"
                                    id="telephone_1"
                                    class="form-control"
                                    placeholder="@lang('messages.Placeholder Address Telephone')"
                                    maxlength="13"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,13)"
                                    required>
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_1" role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="fax_1">@lang('messages.Fax') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Fax Info')</p>
                                <input type="text"
                                    name="fax[]"
                                    id="fax_1"
                                    class="form-control"
                                    placeholder="@lang('messages.Placeholder Address Fax')"
                                    maxlength="15"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,15)"
                                    required>
                                <span class="text-danger mt-2 message_fax" id="message_fax_1" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="d-flex justify-content-end mb-4 mt-4">
                    <button type="button" class="btn btn-primary" id="add_dynamic_address">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="dynamic_company_address"></div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="{{ asset('css/cdn/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset('css/cdn/leaflet_geocoder.css') }}">

<style>
    .partner-map {
        min-height: 400px;
        width: 100%;
        z-index: 0;
    }

    @media (max-width: 768px) {
        .partner-map { min-height: 320px; }
    }

    @media (max-width: 576px) {
        .partner-map { min-height: 280px; }
    }
</style>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/cdn/leaflet_geocoder.js') }}"></script>

<script>
(function () {
    // ============================================================
    // FIX: Jalankan setelah DOM + semua script (termasuk Select2)
    //      sudah selesai load
    // ============================================================

    function initMap1() {

        // ----------------------------------------------------------
        // 1. Fix default icon Leaflet
        // ----------------------------------------------------------
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png",
            iconUrl:        "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png",
            shadowUrl:      "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png"
        });

        // ----------------------------------------------------------
        // 2. Referensi elemen DOM
        // ----------------------------------------------------------
        var selectEl     = document.getElementById("select_searched_address_1");
        var inputLat     = document.getElementById("latitude_1");
        var inputLng     = document.getElementById("longitude_1");
        var inputAddress = document.getElementById("address_1");
        var inputSearch  = document.getElementById("search_address_1");
        var btnSearch    = document.getElementById("search_button_1");

        // Guard: pastikan semua elemen ada
        if (!selectEl || !inputLat || !inputLng || !inputAddress || !inputSearch || !btnSearch) {
            console.error('[Map1] Satu atau lebih elemen tidak ditemukan.');
            return;
        }

        // ----------------------------------------------------------
        // 3. Inisialisasi Leaflet Map
        // ----------------------------------------------------------
        var map1    = L.map('map_1').setView([-6.200000, 106.816666], 12);
        var marker1 = null;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map1);

        // ----------------------------------------------------------
        // 4. Fungsi utama: set marker + isi semua field
        // ----------------------------------------------------------
        function setMarkerAndFields(lat, lng, addressText) {
            var normalizedLat = parseFloat(lat);
            var normalizedLng = parseFloat(lng);

            if (isNaN(normalizedLat) || isNaN(normalizedLng)) {
                console.warn('[Map1] lat/lng tidak valid:', lat, lng);
                return;
            }

            // Pasang / pindahkan marker
            if (marker1) {
                map1.removeLayer(marker1);
            }
            marker1 = L.marker([normalizedLat, normalizedLng]).addTo(map1);
            map1.setView([normalizedLat, normalizedLng], 15);

            // ✅ Isi field latitude & longitude
            inputLat.value = normalizedLat;
            inputLng.value = normalizedLng;

            // ✅ Isi field address
            if (addressText && addressText.trim() !== '') {
                inputAddress.value = addressText.trim();
            }

            // Trigger input event agar framework / validasi lain tahu nilainya berubah
            ['input', 'change'].forEach(function (evtName) {
                inputLat.dispatchEvent(new Event(evtName, { bubbles: true }));
                inputLng.dispatchEvent(new Event(evtName, { bubbles: true }));
                inputAddress.dispatchEvent(new Event(evtName, { bubbles: true }));
            });

            console.log('[Map1] Field diisi → lat:', normalizedLat, '| lng:', normalizedLng, '| address:', addressText);
        }

        // ----------------------------------------------------------
        // 5. Fungsi reset semua field
        // ----------------------------------------------------------
        function resetFields() {
            if (marker1) {
                map1.removeLayer(marker1);
                marker1 = null;
            }
            inputLat.value     = '';
            inputLng.value     = '';
            inputAddress.value = '';
            map1.setView([-6.200000, 106.816666], 12);

            ['input', 'change'].forEach(function (evtName) {
                inputLat.dispatchEvent(new Event(evtName, { bubbles: true }));
                inputLng.dispatchEvent(new Event(evtName, { bubbles: true }));
                inputAddress.dispatchEvent(new Event(evtName, { bubbles: true }));
            });
        }

        // ----------------------------------------------------------
        // 6. Handler saat user memilih dari dropdown
        // ----------------------------------------------------------
        function handleAddressSelection() {
            var idx    = selectEl.selectedIndex;
            var opt    = selectEl.options[idx];

            if (!opt) return;

            var lat         = opt.getAttribute('data-lat') || '';
            var lng         = opt.getAttribute('data-lng') || '';
            var addressText = opt.getAttribute('data-display') || opt.value || opt.textContent || '';

            console.log('[Map1] handleAddressSelection → idx:', idx, '| lat:', lat, '| lng:', lng, '| address:', addressText);

            if (lat.trim() !== '' && lng.trim() !== '') {
                setMarkerAndFields(lat, lng, addressText);
            } else {
                resetFields();
            }
        }

        // ----------------------------------------------------------
        // 7. Pasang event listener
        //    - Native change  → untuk select biasa
        //    - select2:select → untuk Select2 (jika aktif)
        //    Keduanya memanggil handleAddressSelection()
        // ----------------------------------------------------------

        // Native change
        selectEl.addEventListener('change', handleAddressSelection);

        // Select2 — gunakan $(document).on agar tidak peduli kapan Select2 di-init
        if (window.jQuery) {
            jQuery(document)
                .off('select2:select.map1addr select2:unselect.map1addr')
                .on('select2:select.map1addr', '#select_searched_address_1', function () {
                    // Saat Select2 memilih, sync selectedIndex ke native select lalu panggil handler
                    var data = jQuery(this).select2('data');
                    if (data && data.length > 0) {
                        var val = data[0].id; // value dari option yang dipilih
                        for (var i = 0; i < selectEl.options.length; i++) {
                            if (selectEl.options[i].value === val) {
                                selectEl.selectedIndex = i;
                                break;
                            }
                        }
                    }
                    handleAddressSelection();
                })
                .on('select2:unselect.map1addr', '#select_searched_address_1', function () {
                    selectEl.selectedIndex = 0;
                    handleAddressSelection();
                });

            console.log('[Map1] Select2 event handler terpasang');
        }

        // ----------------------------------------------------------
        // 8. Klik pada map → set marker & isi lat/lng (tanpa address)
        // ----------------------------------------------------------
        map1.on('click', function (e) {
            setMarkerAndFields(e.latlng.lat, e.latlng.lng, inputAddress.value);
        });

        // ----------------------------------------------------------
        // 9. Fungsi pencarian alamat via Nominatim
        // ----------------------------------------------------------
        function performSearch(query) {
            if (!query || query.trim() === '') {
                alert('Harap masukkan alamat untuk dicari!');
                return;
            }

            // Loading state
            btnSearch.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
            btnSearch.disabled   = true;

            fetch('https://nominatim.openstreetmap.org/search?format=json&limit=5&q=' + encodeURIComponent(query))
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    // Reset button
                    btnSearch.innerHTML = '<i class="fas fa-search"></i> Cari';
                    btnSearch.disabled  = false;

                    // Bersihkan dropdown
                    selectEl.innerHTML = '<option value="" data-lat="" data-lng="" data-display="">-- Pilih Alamat --</option>';

                    // Destroy Select2 sementara agar kita bisa manipulasi DOM-nya
                    if (window.jQuery && jQuery.fn.select2 && jQuery(selectEl).data('select2')) {
                        jQuery(selectEl).select2('destroy');
                    }

                    selectEl.disabled = true;

                    if (data && data.length > 0) {
                        data.forEach(function (item) {
                            var opt = document.createElement('option');
                            opt.value       = item.display_name;
                            opt.textContent = item.display_name;
                            opt.setAttribute('data-lat',     item.lat);
                            opt.setAttribute('data-lng',     item.lon);
                            opt.setAttribute('data-display', item.display_name);
                            selectEl.appendChild(opt);
                        });

                        selectEl.disabled = false;

                        // Re-init Select2 jika sebelumnya aktif
                        if (window.jQuery && jQuery.fn.select2) {
                            jQuery(selectEl).select2();

                            // Re-pasang event select2 setelah re-init
                            jQuery(document)
                                .off('select2:select.map1addr select2:unselect.map1addr')
                                .on('select2:select.map1addr', '#select_searched_address_1', function () {
                                    var selData = jQuery(this).select2('data');
                                    if (selData && selData.length > 0) {
                                        var val = selData[0].id;
                                        for (var i = 0; i < selectEl.options.length; i++) {
                                            if (selectEl.options[i].value === val) {
                                                selectEl.selectedIndex = i;
                                                break;
                                            }
                                        }
                                    }
                                    handleAddressSelection();
                                })
                                .on('select2:unselect.map1addr', '#select_searched_address_1', function () {
                                    selectEl.selectedIndex = 0;
                                    handleAddressSelection();
                                });
                        }

                        console.log('[Map1] Ditemukan', data.length, 'alamat');
                    } else {
                        alert('Alamat tidak ditemukan. Coba kata kunci lain.');
                        resetFields();
                    }
                })
                .catch(function (err) {
                    console.error('[Map1] Error fetch Nominatim:', err);
                    btnSearch.innerHTML = '<i class="fas fa-search"></i> Cari';
                    btnSearch.disabled  = false;
                    alert('Terjadi kesalahan saat mencari alamat.');
                });
        }

        // ----------------------------------------------------------
        // 10. Event listener tombol Cari & Enter
        // ----------------------------------------------------------
        btnSearch.addEventListener('click', function () {
            performSearch(inputSearch.value);
        });

        inputSearch.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch(inputSearch.value);
            }
        });

    } // end initMap1()

    // ============================================================
    // Jalankan initMap1 setelah semua script selesai load
    // (window load lebih aman daripada DOMContentLoaded
    //  karena Select2 biasanya di-init di akhir body)
    // ============================================================
    if (document.readyState === 'complete') {
        // Halaman sudah fully loaded
        initMap1();
    } else {
        window.addEventListener('load', initMap1);
    }

})();
</script>