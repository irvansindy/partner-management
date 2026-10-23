<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class InternationalPhoneInputTest extends TestCase
{
    private function partnerScript(): string
    {
        return file_get_contents(
            dirname(__DIR__, 2).'/resources/views/cs_vendor/partner_js.blade.php'
        );
    }

    /** @test */
    public function country_selector_uses_compact_iso_and_calling_code_labels(): void
    {
        $script = $this->partnerScript();

        $this->assertStringContainsString('window.partnerCountries', $script);
        $this->assertStringContainsString('templateResult: formatCountryOption', $script);
        $this->assertStringContainsString("country.code + ' ' + country.calling_code", $script);
    }

    /** @test */
    public function phone_selector_width_is_controlled_by_its_component_class(): void
    {
        $script = $this->partnerScript();

        $this->assertStringNotContainsString("\$countryCode.css({ flex: '0 0 140px'", $script);
    }
}
