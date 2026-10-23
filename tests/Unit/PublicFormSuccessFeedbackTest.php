<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PublicFormSuccessFeedbackTest extends TestCase
{
    /** @test */
    public function successful_submission_shows_a_countdown_then_closes_the_tab(): void
    {
        $script = file_get_contents(
            dirname(__DIR__, 2).'/resources/views/cs_vendor/partner_js.blade.php'
        );

        $this->assertStringContainsString('timer: 5000', $script);
        $this->assertStringContainsString('timerProgressBar: true', $script);
        $this->assertStringContainsString('window.close()', $script);
        $this->assertStringContainsString('setTimeout(attemptCloseTab, 5000)', $script);
        $this->assertStringContainsString("window.open('', '_self')", $script);
        $this->assertStringContainsString("route('public.form.success')", $script);
        $this->assertStringContainsString('window.location.replace(successPageUrl)', $script);
    }
}
