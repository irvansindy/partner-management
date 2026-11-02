<?php

namespace Tests\Unit;

use Tests\TestCase;

class PublicFormLocalizedValidationTest extends TestCase
{
    /** @test */
    public function validation_alerts_select_the_message_for_the_active_locale(): void
    {
        $script = file_get_contents(resource_path('views/cs_vendor/partner_js.blade.php'));

        $this->assertStringContainsString('var partnerFormLocale = @json(app()->getLocale());', $script);
        $this->assertStringContainsString('function localizeValidationMessage(message)', $script);
        $this->assertStringContainsString('message = localizeValidationMessage(message);', $script);
        $this->assertStringContainsString("title: @json(__('messages.Validation Error'))", $script);
        $this->assertStringContainsString("title: partnerValidationCopy.title", $script);
        $this->assertStringNotContainsString("title: 'Validation Error'", $script);
    }
}
