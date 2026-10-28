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
    public function phone_input_uses_the_twilio_referenced_international_input_pattern(): void
    {
        $script = $this->partnerScript();
        $layout = file_get_contents(dirname(__DIR__, 2).'/resources/views/layouts/public.blade.php');

        $this->assertStringContainsString('window.intlTelInput(input', $script);
        $this->assertStringContainsString('separateDialCode: true', $script);
        $this->assertStringContainsString("initialCountry: 'id'", $script);
        $this->assertStringContainsString('intlTelInput.css', $layout);
        $this->assertStringContainsString('intlTelInput.min.js', $layout);
    }

    /** @test */
    public function phone_selector_width_is_controlled_by_its_component_class(): void
    {
        $script = $this->partnerScript();
        $css = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/css.blade.php');

        $this->assertStringContainsString('#form_company .iti', $css);
        $this->assertStringContainsString('width: 100%', $css);
        $this->assertStringContainsString('min-height: 44px', $css);
        $this->assertStringContainsString('.iti__selected-flag', $css);
    }

    /** @test */
    public function established_year_only_accepts_viable_year_values_on_the_client(): void
    {
        $form = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/form/master_information.blade.php');
        $script = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/js.blade.php');

        $this->assertStringContainsString('data-year-min="1900"', $form);
        $this->assertStringContainsString('data-year-max="{{ date(\'Y\') }}"', $form);
        $this->assertStringContainsString('isViableYearPrefix', $script);
        $this->assertStringContainsString("on('input', '[data-year-range]'", $script);
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

    /** @test */
    public function disabled_address_phone_fields_are_not_added_to_submission(): void
    {
        $script = $this->partnerScript();

        $this->assertStringContainsString(".filter(':not(:disabled)')", $script);
        $this->assertStringContainsString('input._intlTelInput', $script);
    }
}
