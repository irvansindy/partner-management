<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class LoginFloatingLabelTest extends TestCase
{
    private function resource(string $path): string
    {
        return dirname(__DIR__, 2)."/resources/".$path;
    }

    /** @test */
    public function both_login_views_initialize_floating_labels(): void
    {
        $views = [
            $this->resource('views/auth/login.blade.php'),
            $this->resource('views/login.blade.php'),
        ];

        foreach ($views as $view) {
            $contents = file_get_contents($view);

            $this->assertStringContainsString(
                "@include('auth.partials.login-floating-labels')",
                $contents,
                basename($view).' must initialize its floating labels.'
            );
        }
    }

    /** @test */
    public function floating_label_handler_tracks_focus_and_input_values(): void
    {
        $contents = file_get_contents(
            $this->resource('views/auth/partials/login-floating-labels.blade.php')
        );

        $this->assertStringContainsString("input.matches(':focus') || input.value.length > 0", $contents);
        $this->assertStringContainsString("input.addEventListener('focus'", $contents);
        $this->assertStringContainsString("input.addEventListener('blur'", $contents);
        $this->assertStringContainsString("input.addEventListener('input'", $contents);
    }
}
