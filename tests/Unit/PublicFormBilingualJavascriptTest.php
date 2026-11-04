<?php

namespace Tests\Unit;

use Tests\TestCase;

class PublicFormBilingualJavascriptTest extends TestCase
{
    /** @test */
    public function dynamic_form_copy_is_rendered_from_laravel_translations(): void
    {
        $publicScript = file_get_contents(resource_path('views/public/form/js.blade.php'));
        $partnerScript = file_get_contents(resource_path('views/cs_vendor/partner_js.blade.php'));
        $address = file_get_contents(resource_path('views/cs_vendor/form/address.blade.php'));

        $this->assertStringContainsString('var partnerFormCopy = {', $partnerScript);
        $this->assertStringContainsString("@json(__('messages.Public Required Field'))", $partnerScript);
        $this->assertStringContainsString('partnerFormCopy.requiredField', $publicScript);
        $this->assertStringContainsString('const addressCopy = @json($addressCopy);', $address);
        $this->assertStringContainsString('addressCopy.provinceLoadFailed', $address);
        $this->assertStringContainsString('addressCopy.regencyLoadFailed', $address);
        $this->assertStringContainsString('partnerFormCopy.errorTitle', $partnerScript);
        $this->assertStringContainsString('partnerFormCopy.sessionExpired', $partnerScript);
        $this->assertStringNotContainsString("'Master Information',", $publicScript);
        $this->assertStringNotContainsString("'Alamat website harus berupa URL yang valid.'", $publicScript);
        $this->assertStringNotContainsString("'Pencarian Alamat'", $address);
        $this->assertStringNotContainsString("title: 'Error!'", $partnerScript);
        $this->assertStringNotContainsString("title: 'Server Error'", $partnerScript);
        $this->assertStringNotContainsString('Provinsi tidak dapat dimuat', $address);
        $this->assertStringNotContainsString('Kota/kabupaten tidak dapat dimuat', $address);
        $this->assertStringNotContainsString("'Hide Survey'", $publicScript);
        $this->assertStringNotContainsString("'Format alamat email tidak valid.'", $publicScript);
        $this->assertStringNotContainsString("'Hanya boleh berisi huruf dan spasi.'", $publicScript);
        $this->assertStringNotContainsString("'Session Error'", $partnerScript);
        $this->assertStringNotContainsString("'Submit Berhasil'", $partnerScript);
        $this->assertStringNotContainsString("'Keluar dari layar penuh'", $address);
    }

    /** @test */
    public function javascript_translation_keys_have_different_id_and_en_values(): void
    {
        $id = require resource_path('lang/id/messages.php');
        $en = require resource_path('lang/en/messages.php');

        foreach (['Public Required Field', 'Public Address Search', 'Public Website Checking'] as $key) {
            $this->assertArrayHasKey($key, $id);
            $this->assertArrayHasKey($key, $en);
            $this->assertNotSame($id[$key], $en[$key]);
        }
    }
}
