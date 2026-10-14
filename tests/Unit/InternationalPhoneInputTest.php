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

        $this->assertStringContainsString("{ country: 'Indonesia', iso: 'ID', code: '+62' }", $script);
        $this->assertStringContainsString(".text(item.iso + ' ' + item.code)", $script);
        $this->assertStringNotContainsString(".text(item.code + ' ' + item.country)", $script);
    }

    /** @test */
    public function phone_selector_width_is_controlled_by_its_component_class(): void
    {
        $script = $this->partnerScript();

        $this->assertStringNotContainsString("\$countryCode.css({ flex: '0 0 140px'", $script);
    }
}
