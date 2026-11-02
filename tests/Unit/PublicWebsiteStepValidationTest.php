<?php

namespace Tests\Unit;

use Tests\TestCase;

class PublicWebsiteStepValidationTest extends TestCase
{
    /** @test */
    public function continue_waits_for_server_side_website_reachability_validation(): void
    {
        $routes = file_get_contents(base_path('routes/web.php'));
        $script = file_get_contents(resource_path('views/public/form/js.blade.php'));
        $controller = file_get_contents(app_path('Http/Controllers/PublicFormController.php'));

        $this->assertStringContainsString("Route::post('/{token}/validate-website'", $routes);
        $this->assertStringContainsString('public function validateWebsite(Request $request, $token)', $controller);
        $this->assertStringContainsString('async function validateWebsiteReachability()', $script);
        $this->assertStringContainsString('await validateWebsiteReachability()', $script);
        $this->assertStringContainsString("url: @json(route('public.form.validate-website', ['token' => \$formLink->token]))", $script);
        $this->assertStringNotContainsString('Alamat Situs Web Tidak Valid | Invalid Website Address', $script);
    }
}
