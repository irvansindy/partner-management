<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicFormResponsiveMarkupTest extends TestCase
{
    /** @test */
    public function dynamic_controls_expose_accessible_and_responsive_hooks(): void
    {
        $address = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/form/address.blade.php');
        $css = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/css.blade.php');
        $phone = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/partner_js.blade.php');

        $this->assertStringContainsString('data-address-row', $address);
        $this->assertStringContainsString('data-coordinate-status', $address);
        $this->assertStringContainsString('data-remove-address', $address);
        $this->assertStringContainsString('select2', $address);
        $this->assertStringContainsString('@media (max-width:', $css);
        $this->assertStringContainsString('international-phone-group', $phone);
        $this->assertStringContainsString("timer: 5000", $phone);
        $this->assertStringContainsString('window.close()', $phone);
        $this->assertStringContainsString('response.meta.message', $phone);
    }
}
