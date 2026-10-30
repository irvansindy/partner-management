<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class InternalPartnerFormUiTest extends TestCase
{
    /** @test */
    public function internal_partner_form_reuses_the_public_wizard_experience(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/index.blade.php');
        $controller = file_get_contents(dirname(__DIR__, 2).'/app/Http/Controllers/PartnerController.php');
        $routes = file_get_contents(dirname(__DIR__, 2).'/routes/web.php');

        $this->assertStringContainsString("@include('public.form.css')", $view);
        $this->assertStringContainsString('class="wizard-shell"', $view);
        $this->assertStringContainsString('class="step-pane" data-step="1"', $view);
        $this->assertStringContainsString("route('submit-partner')", $view);
        $this->assertStringContainsString('id="step_prev"', $view);
        $this->assertStringContainsString('id="step_next"', $view);
        $this->assertStringContainsString('appearance: none;', $view);
        $this->assertStringContainsString('CountryCatalog::all()', $controller);
        $this->assertSame(1, substr_count($routes, "Route::get('/create-partner'"));
        $this->assertSame(1, substr_count($routes, "Route::post('/submit-partner'"));
    }
}
