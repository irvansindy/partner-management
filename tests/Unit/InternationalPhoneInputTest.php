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
    public function country_selector_uses_flag_and_calling_code_without_iso_label(): void
    {
        $script = $this->partnerScript();

        $this->assertStringContainsString('window.partnerCountries', $script);
        $this->assertStringContainsString('templateResult: formatCountryOption', $script);
        $this->assertStringContainsString("countryFlag(country.code) + ' ' + country.calling_code", $script);
        $this->assertStringNotContainsString("countryFlag(country.code) + ' ' + country.code + ' ' + country.calling_code", $script);
    }

    /** @test */
    public function phone_selector_width_is_controlled_by_its_component_class(): void
    {
        $script = $this->partnerScript();
        $css = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/css.blade.php');

        $this->assertStringNotContainsString("\$countryCode.css({ flex: '0 0 140px'", $script);
        $this->assertStringContainsString('.international-phone-group > .select2-container', $css);
        $this->assertStringContainsString('flex: 0 0 96px', $css);
    }

    /** @test */
    public function contact_phone_has_room_for_the_number_and_filters_non_digits(): void
    {
        $form = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/form/contact.blade.php');
        $script = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/js.blade.php');

        $this->assertStringContainsString('contact-phone-column', $form);
        $this->assertStringContainsString('contact-action-column', $form);
        $this->assertStringContainsString("on('input', '.international-phone'", $script);
        $this->assertStringContainsString("replace(/\\D/g, '')", $script);
    }
}
