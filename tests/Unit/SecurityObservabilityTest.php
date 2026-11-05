<?php

namespace Tests\Unit;

use Tests\TestCase;

class SecurityObservabilityTest extends TestCase
{
    /** @test */
    public function security_channel_and_fail_open_sinks_are_configured(): void
    {
        $channels = config('logging.channels');
        $middleware = file_get_contents(app_path('Http/Middleware/SanitizeInputMiddleware.php'));

        $this->assertArrayHasKey('security', $channels);
        $this->assertSame('daily', $channels['security']['driver']);
        $this->assertStringContainsString("security.log", $channels['security']['path']);
        $this->assertStringContainsString('safeSecuritySink', $middleware);
        $this->assertStringContainsString("Log::channel('security')", $middleware);
    }
}
