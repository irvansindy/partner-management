@extends('adminlte::page')

@section('title', 'Map Geocoder')
@section('content')
<div class="container">
    <h3 class="mb-4">Pilih Lokasi</h3>

    <form>
        @csrf
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="search" class="form-label">Cari Alamat</label>
                <div class="input-group">
                    <input type="text" id="search" class="form-control" placeholder="Masukkan alamat...">
                    {{-- Tambahkan tombol search untuk trigger pencarian --}}
                    <button class="btn btn-outline-secondary" type="button" id="search-button">Cari</button>
                </div>
            </div>
        </div>

        <div id="map" class="mb-3"></div>

        <div class="row mb-3">
            <div class="col-md-12 col-lg-12">
                <label for="address" class="form-label">Pilih Alamat Lengkap</label>
                {{-- DIUBAH DARI INPUT MENJADI SELECT --}}
                <select id="address" name="address" class="form-control" disabled>
                    <option value="" data-lat="" data-lng="">-- Pilih Alamat --</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" id="latitude" name="latitude" class="form-control" readonly>
            </div>
            <div class="col-md-6">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" id="longitude" name="longitude" class="form-control" readonly>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Lokasi</button>
    </form>
</div>

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="{{ asset('css/cdn/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset('css/cdn/leaflet_geocoder.css') }}">

{{-- Tambahkan CSS responsif --}}
<style>
    #map {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        #map {
            height: 300px;
        }
    }

    @media (max-width: 576px) {
        #map {
            height: 250px;
        }
    }
</style>

{{-- Leaflet JS --}}
<script src="{{ asset('js/cdn/leaflet.js') }}"></script>
<script>
    // Fix default icon
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png",
        iconUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png",
        shadowUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png"
    });
</script>
<script src="{{ asset('js/cdn/leaflet_geocoder.js') }}"></script>

<script>
    // Inisialisasi map
    var map = L.map('map').setView([-6.200000, 106.816666], 12);

    // Tambah tile layer OSM
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    var marker;
    var selectAddress = document.getElementById("address");
    var inputLatitude = document.getElementById("latitude");
    var inputLongitude = document.getElementById("longitude");
    var inputSearch = document.getElementById("search");
    var searchButton = document.getElementById("search-button");

    // Fungsi untuk menghandle penempatan marker dan pengisian lat/lng
    function setMarkerAndFields(lat, lng, addressText = "") {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 15);

        inputLatitude.value = lat;
        inputLongitude.value = lng;

        // Hanya set address jika diberikan (dari select), jika dari klik map, biarkan select kosong
        if (addressText) {
            // Coba cari option yang sesuai untuk menjaga konsistensi UI
            var found = false;
            for (var i = 0; i < selectAddress.options.length; i++) {
                if (selectAddress.options[i].text === addressText) {
                    selectAddress.selectedIndex = i;
                    found = true;
                    break;
                }
            }
            if (!found) {
                 // Jika addressText dari Geocoder (bukan dari select), set kembali ke default
                selectAddress.selectedIndex = 0;
            }
        } else {
             // Jika dari klik map, pastikan select direset
            selectAddress.selectedIndex = 0;
        }
    }

    // Event klik map
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // **TIDAK ADA LAGI REVERSE GEOCODING OTOMATIS DI SINI**
        // Hanya set marker dan koordinat
        setMarkerAndFields(lat, lng);
    });

    // Event change pada select option
    selectAddress.addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var lat = selectedOption.getAttribute('data-lat');
        var lng = selectedOption.getAttribute('data-lng');
        var addressText = selectedOption.text;

        if (lat && lng) {
            setMarkerAndFields(lat, lng, addressText);
        } else {
            // Opsi "-- Pilih Alamat --" dipilih
            if (marker) {
                map.removeLayer(marker);
            }
            inputLatitude.value = "";
            inputLongitude.value = "";
            map.setView([-6.200000, 106.816666], 12); // Kembali ke view awal
        }
    });

    // Fungsi Geocoding (Pencarian Alamat)
    function performSearch(query) {
        if (!query) {
            alert("Harap masukkan alamat untuk dicari!");
            return;
        }

        // Menggunakan limit=5 untuk mendapatkan maksimal 5 hasil
        fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=5&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                // Bersihkan select option yang lama
                selectAddress.innerHTML = '<option value="" data-lat="" data-lng="">-- Pilih Alamat --</option>';
                selectAddress.disabled = true; // Non-aktifkan dulu

                if (data.length > 0) {
                    data.forEach(item => {
                        var option = document.createElement('option');
                        option.value = item.display_name;
                        option.textContent = item.display_name;
                        option.setAttribute('data-lat', item.lat);
                        option.setAttribute('data-lng', item.lon);
                        selectAddress.appendChild(option);
                    });

                    selectAddress.disabled = false; // Aktifkan select
                    alert(`Ditemukan ${data.length} alamat. Silakan pilih salah satu.`);

                } else {
                    alert("Alamat tidak ditemukan");
                    // Pastikan input lat/lng dikosongkan jika tidak ada hasil
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    inputLatitude.value = "";
                    inputLongitude.value = "";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat mencari alamat.");
            });
    }

    // Event listener untuk tombol Cari
    searchButton.addEventListener("click", function() {
        var query = inputSearch.value;
        performSearch(query);
    });

    // Event listener untuk tombol Enter pada input Cari Alamat
    inputSearch.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            e.preventDefault(); // Mencegah submit form
            var query = inputSearch.value;
            performSearch(query);
        }
    });

    // **Geocoder bawaan Leaflet**
    // Tetap dipertahankan, namun akan menimpa lat/lng saat digunakan
    var geocoder = L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        var latlng = e.geocode.center;
        var addressText = e.geocode.name; // Alamat dari geocoder

        setMarkerAndFields(latlng.lat, latlng.lng, addressText);

        // Opsional: Isi input search dengan alamat yang ditemukan geocoder
        inputSearch.value = addressText;
    })
    .addTo(map);

</script>
@endsection