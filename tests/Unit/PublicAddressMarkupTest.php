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
        $this->assertStringContainsString('id="address_row_template"', $html);
        $this->assertStringContainsString('id="add_branch"', $html);
        $this->assertStringContainsString('data-address-country', $html);
        $this->assertStringContainsString('Memuat kota/kabupaten', $html);
        $this->assertStringContainsString('Kode pos tidak tersedia', $html);
        $this->assertStringContainsString('data-address-map', $html);
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
        $this->assertStringContainsString('Masukkan kode pos', $html);
        $this->assertStringContainsString("route('public.form.regencies'", $html);
        $this->assertStringContainsString("route('public.form.postal-codes'", $html);
    }
}
