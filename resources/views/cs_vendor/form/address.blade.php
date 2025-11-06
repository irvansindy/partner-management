<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Address Data')
        </h3>
        {{-- <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div> --}}
    </div>
    <div class="card-body">
        <div class="row px-2 py-2" style="line-height: 1">
            <div class="company_address_additional" id="company_address_additional">
                <!-- Address 1: Company Address (according to NPWP) -->
                <fieldset class="border px-2 mb-4">
                    <legend class="float-none w-auto text-bold">@lang('messages.Address Data')</legend>
                    <div class="row">
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label>@lang('messages.Company Address (according to NPWP)') <span class="text-danger" role="alert">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="address[]" id="address_0" class="form-control">
                                <span class="text-danger mt-2 message_address" id="message_address_0"
                                    role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="country_0">@lang('messages.Country') <span class="text-danger"
                                        role="alert">*</span></label>
                                <input type="text" name="country[]" id="country_0" class="form-control"
                                    value="Indonesia" readonly>
                                <span class="text-danger mt-2 message_country" id="message_country_0"
                                    role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="province_0">@lang('messages.Province') <span class="text-danger"
                                        role="alert">*</span></label>
                                <select name="province[]" id="select_option_province_0" class="form-control"></select>
                                <span class="text-danger mt-2 message_province" id="message_province_0"
                                    role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="city_0">@lang('messages.City') <span class="text-danger"
                                        role="alert">*</span></label>
                                <select name="city[]" id="select_option_regency_0" class="form-control"></select>
                                <span class="text-danger mt-2 message_city" id="message_city_0" role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="zip_code_0">@lang('messages.Postal Code') <span class="text-danger"
                                        role="alert">*</span></label>
                                <input type="text" name="zip_code[]" id="zip_code_0" class="form-control" placeholder="@lang('messages.Placeholder Address Postal Code')">
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_0"
                                    role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone_0">@lang('messages.Telephone') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Telephone Info')</p>
                                <input type="number" name="telephone[]" id="telephone_0" class="form-control" placeholder="@lang('messages.Placeholder Address Telephone')">
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_0"
                                    role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="fax_0">@lang('messages.Fax') <span class="text-danger"
                                        role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Fax Info')</p>
                                <input type="number" name="fax[]" id="fax_0" class="form-control" placeholder="@lang('messages.Placeholder Address Fax')">
                                <span class="text-danger mt-2 message_fax" id="message_fax_0" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- Address 2: Company Address (Other) with Map Geocoder -->
                <fieldset class="border px-2 mb-4">
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
                            <div id="map_1"
                                style="width: 100%; height: 400px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            </div>
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
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label>@lang('messages.Company Address (according to Map)') <span class="text-danger" role="alert">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="address[]" id="address_1" class="form-control"
                                    placeholder="ex: Jl. HM Ashari No. 47 001/001 Cibinong">
                                <span class="text-muted mt-2">@lang('messages.Format Address')</span>
                                <span class="text-danger mt-2 message_address" id="message_address_1"
                                    role="alert"></span>
                            </div>
                        </div>

                        <!-- Hidden Latitude and Longitude Fields -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="latitude_1">Latitude  <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="latitude" id="latitude_1" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude_1">Longitude  <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="longitude" id="longitude_1" class="form-control"
                                    readonly>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="country_1">@lang('messages.Country') <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="country[]" id="country_1" class="form-control"
                                    value="Indonesia" readonly>
                                <span class="text-danger mt-2 message_country" id="message_country_1"
                                    role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="province_1">@lang('messages.Province') <span class="text-danger" role="alert">*</span></label>
                                <select name="province[]" id="select_option_province_1"
                                    class="form-control"></select>
                                <span class="text-danger mt-2 message_province" id="message_province_1"
                                    role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="city_1">@lang('messages.City') <span class="text-danger" role="alert">*</span></label>
                                <select name="city[]" id="select_option_regency_1" class="form-control"></select>
                                <span class="text-danger mt-2 message_city" id="message_city_1"
                                    role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="zip_code_1">@lang('messages.Postal Code') <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="zip_code[]" id="zip_code_1" class="form-control" placeholder="@lang('messages.Placeholder Address Postal Code')">
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_1"
                                    role="alert"></span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone_1">@lang('messages.Telephone') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Telephone Info')</p>
                                <input type="number" name="telephone[]" id="telephone_1" class="form-control" placeholder="@lang('messages.Placeholder Address Telephone')">
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_1"
                                    role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="fax_1">@lang('messages.Fax') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Fax Info')</p>
                                <input type="number" name="fax[]" id="fax_1" class="form-control" placeholder="@lang('messages.Placeholder Address Fax')">
                                <span class="text-danger mt-2 message_fax" id="message_fax_1" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                    <button type="button" class="btn btn-primary" id="add_dynamic_address">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="dynamic_company_address">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="{{ asset('css/cdn/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset('css/cdn/leaflet_geocoder.css') }}">
<!-- Responsive Map CSS -->
<style>
    @media (max-width: 768px) {
        #map_1 {
            height: 300px !important;
        }
    }

    @media (max-width: 576px) {
        #map_1 {
            height: 250px !important;
        }
    }
</style>

<!-- Leaflet JS (tambahkan sebelum closing body tag) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/cdn/leaflet_geocoder.js') }}"></script>
<script>
    // Fix default icon Leaflet
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png",
        iconUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png",
        shadowUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png"
    });

    // ============================================
    // MAP GEOCODER IMPLEMENTATION FOR ADDRESS 1
    // ============================================

    // Inisialisasi map untuk Address 1
    var map1 = L.map('map_1').setView([-6.200000, 106.816666], 12);

    // Tambah tile layer OSM
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map1);

    var marker1;
    var selectSearchedAddress = document.getElementById("select_searched_address_1");
    var inputLatitude1 = document.getElementById("latitude_1");
    var inputLongitude1 = document.getElementById("longitude_1");
    var inputAddress1 = document.getElementById("address_1");
    var inputSearch1 = document.getElementById("search_address_1");
    var searchButton1 = document.getElementById("search_button_1");

    // Fungsi untuk set marker dan mengisi field
    function setMarkerAndFields1(lat, lng, addressText = "") {
        if (marker1) {
            map1.removeLayer(marker1);
        }
        marker1 = L.marker([lat, lng]).addTo(map1);
        map1.setView([lat, lng], 15);

        inputLatitude1.value = lat;
        inputLongitude1.value = lng;

        if (addressText) {
            inputAddress1.value = addressText;
            // Cari option yang sesuai
            var found = false;
            for (var i = 0; i < selectSearchedAddress.options.length; i++) {
                if (selectSearchedAddress.options[i].text === addressText) {
                    selectSearchedAddress.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found) {
                selectSearchedAddress.selectedIndex = 0;
            }
        } else {
            selectSearchedAddress.selectedIndex = 0;
        }
    }

    // Event klik pada map
    map1.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        setMarkerAndFields1(lat, lng);
    });

    // Event change pada select searched address
    selectSearchedAddress.addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var lat = selectedOption.getAttribute('data-lat');
        var lng = selectedOption.getAttribute('data-lng');
        var addressText = selectedOption.text;

        if (lat && lng) {
            setMarkerAndFields1(lat, lng, addressText);
        } else {
            if (marker1) {
                map1.removeLayer(marker1);
            }
            inputLatitude1.value = "";
            inputLongitude1.value = "";
            inputAddress1.value = "";
            map1.setView([-6.200000, 106.816666], 12);
        }
    });

    // Fungsi pencarian alamat
    function performSearch1(query) {
        if (!query) {
            alert("Harap masukkan alamat untuk dicari!");
            return;
        }

        // Tampilkan loading
        searchButton1.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        searchButton1.disabled = true;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=5&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                // Reset button
                searchButton1.innerHTML = '<i class="fas fa-search"></i> Cari';
                searchButton1.disabled = false;

                // Bersihkan select option
                selectSearchedAddress.innerHTML =
                    '<option value="" data-lat="" data-lng="">-- Pilih Alamat --</option>';
                selectSearchedAddress.disabled = true;

                if (data.length > 0) {
                    data.forEach(item => {
                        var option = document.createElement('option');
                        option.value = item.display_name;
                        option.textContent = item.display_name;
                        option.setAttribute('data-lat', item.lat);
                        option.setAttribute('data-lng', item.lon);
                        selectSearchedAddress.appendChild(option);
                    });

                    selectSearchedAddress.disabled = false;
                    alert(`Ditemukan ${data.length} alamat. Silakan pilih salah satu dari dropdown.`);
                } else {
                    alert("Alamat tidak ditemukan. Coba kata kunci lain.");
                    if (marker1) {
                        map1.removeLayer(marker1);
                    }
                    inputLatitude1.value = "";
                    inputLongitude1.value = "";
                    inputAddress1.value = "";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                searchButton1.innerHTML = '<i class="fas fa-search"></i> Cari';
                searchButton1.disabled = false;
                alert("Terjadi kesalahan saat mencari alamat.");
            });
    }

    // Event listener tombol Cari
    searchButton1.addEventListener("click", function() {
        var query = inputSearch1.value;
        performSearch1(query);
    });

    // Event listener Enter pada input search
    inputSearch1.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            var query = inputSearch1.value;
            performSearch1(query);
        }
    });
</script>
