<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicAddressMarkupTest extends TestCase
{
    /** @test */
    public function address_form_exposes_role_country_and_per_row_coordinate_fields(): void
    {
        $html = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/form/address.blade.php');
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
        $this->assertStringContainsString("route('public.form.regencies'", $html);
        $this->assertStringContainsString("route('public.form.postal-codes'", $html);
    }
}
