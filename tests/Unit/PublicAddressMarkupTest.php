<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicAddressMarkupTest extends TestCase
{
    /** @test */
    public function address_form_exposes_role_country_and_per_row_coordinate_fields(): void
    {
        $html = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/form/address.blade.php');
        $publicScript = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/js.blade.php');
        $this->assertStringContainsString('name="address_role[]"', $html);
        $this->assertStringContainsString('name="country_code[]"', $html);
        $this->assertStringContainsString('name="latitude[]"', $html);
        $this->assertStringContainsString('name="longitude[]"', $html);
        $this->assertStringContainsString('operational_same_as_legal', $html);
        $this->assertStringContainsString('address-sync-control', $html);
        $this->assertStringContainsString('address-sync-control__text', $html);
        $this->assertStringContainsString('id="address_row_template"', $html);
        $this->assertStringContainsString('id="add_branch"', $html);
        $this->assertStringContainsString('data-address-country', $html);
        $this->assertStringContainsString('Memuat kota/kabupaten', $html);
        $this->assertStringContainsString('Kode pos belum tersedia untuk kota/kabupaten ini', $html);
        $this->assertStringContainsString('data-address-map', $html);
        $this->assertStringContainsString('data-map-shell', $html);
        $this->assertStringContainsString('data-map-fullscreen', $html);
        $this->assertStringContainsString('L.map(', $html);
        $this->assertStringContainsString("$(city).on('change'", $html);
        $this->assertStringContainsString("partner:address-step-shown", $html);
        $this->assertStringContainsString('marker-icon.png', $html);
        $this->assertStringContainsString('World_Street_Map/MapServer/tile', $html);
        $this->assertStringContainsString('name="geocoded_address[]"', $html);
        $this->assertStringContainsString('payload.data.display_name', $html);
        $this->assertStringContainsString("showPartnerToast('Pencarian Alamat', 'Alamat berhasil ditemukan.', 'success')", $html);
        $this->assertStringContainsString("showPartnerToast('Pencarian Alamat', 'Alamat tidak ditemukan.', 'danger')", $html);
        $this->assertStringContainsString('pendingLocationRestore', $html);
        $this->assertStringContainsString('data-pending-location-restore', $publicScript);
        $this->assertStringContainsString("closest('[data-address-row][hidden]')", $html);
        $this->assertStringContainsString("closest('[data-address-row][hidden]')", $publicScript);
        $this->assertStringContainsString('Pilih kota/kabupaten terlebih dahulu', $html);
        $this->assertStringContainsString("item.area ? item.postal_code + ' — ' + item.area : item.postal_code", $html);
        $this->assertStringNotContainsString("replaceLocationInput(row, 'zip_code', false, 'Masukkan kode pos')", $html);
        $this->assertStringContainsString("route('public.form.regencies'", $html);
        $this->assertStringContainsString("route('public.form.postal-codes'", $html);
        $this->assertStringContainsString('function countryOptionMarkup', $html);
        $this->assertStringContainsString("'iti__flag iti__'", $html);
        $this->assertStringContainsString('templateResult: countryOptionMarkup', $html);
        $this->assertStringContainsString('templateSelection: countryOptionMarkup', $html);
        $this->assertStringContainsString("route('reverse-map')", $html);
        $this->assertStringContainsString('{ draggable: true }', $html);
        $this->assertStringContainsString("marker.on('dragend'", $html);
        $this->assertStringContainsString("map.on('click'", $html);
        $this->assertStringContainsString('hasSelection: false', $html);
        $this->assertStringContainsString('Geser pin atau klik peta untuk memilih lokasi', $html);
        $this->assertStringContainsString('requestFullscreen', $html);
        $this->assertStringContainsString('document.exitFullscreen', $html);
        $this->assertStringContainsString("document.addEventListener('fullscreenchange'", $html);
        $this->assertStringContainsString("event.key === 'Escape'", $html);
        $this->assertStringContainsString('map.invalidateSize()', $html);
        $this->assertStringContainsString('function reverseGeocodePin', $html);
        $this->assertStringContainsString('reverseRequestSequence', $html);
        $this->assertStringContainsString('if (requestSequence !== state.reverseRequestSequence) return', $html);
        $this->assertStringContainsString('hasShownAddressGuidance', $html);
        $this->assertStringContainsString('Cari menggunakan nama tempat umum', $html);
        $this->assertStringContainsString("Swal.fire({", $html);
        $this->assertStringContainsString('Koordinat tetap tersimpan', $html);
    }
}
