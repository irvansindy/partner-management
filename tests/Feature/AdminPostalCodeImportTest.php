<?php

namespace Tests\Feature;

use App\Http\Middleware\SanitizeInputMiddleware;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminPostalCodeImportTest extends TestCase
{
    /** @test */
    public function postal_import_routes_require_authentication_and_super_admin_role(): void
    {
        foreach (['admin.postal-codes.import.form', 'admin.postal-codes.import.store', 'admin.postal-codes.import.errors'] as $name) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertNotNull($route);
            $this->assertContains('auth', $route->gatherMiddleware());
            $this->assertContains('role:super-admin', $route->gatherMiddleware());
        }
    }

    /** @test */
    public function guests_are_redirected_from_the_import_page(): void
    {
        $this->withoutMiddleware(SanitizeInputMiddleware::class);
        $this->get(route('admin.postal-codes.import.form'))->assertRedirect(route('login'));
    }
}
