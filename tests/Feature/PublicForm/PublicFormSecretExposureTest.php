<?php

namespace Tests\Feature\PublicForm;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicFormSecretExposureTest extends TestCase
{
    /** @test */
    public function public_form_markup_does_not_expose_a_server_secret(): void
    {
        $html = file_get_contents(resource_path('views/public/form_index.blade.php'));
        $javascript = file_get_contents(resource_path('views/cs_vendor/partner_js.blade.php'));

        $this->assertStringNotContainsString("env('PUBLIC_FORM_SECRET')", $html);
        $this->assertStringNotContainsString('public_key', $html . $javascript);
        $this->assertStringNotContainsString('X-APP-KEY', $javascript);
        $this->assertStringNotContainsString("route('submit-partner')", $html);
    }

    /** @test */
    public function every_legacy_submit_partner_route_requires_authentication(): void
    {
        $routes = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($route) => $route->uri() === 'submit-partner' && in_array('POST', $route->methods(), true));

        $this->assertNotEmpty($routes);

        foreach ($routes as $route) {
            $this->assertContains('auth', $route->gatherMiddleware());
        }
    }
}
