<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicAddressLookupTest extends TestCase
{
    /** @test */
    public function scoped_address_master_routes_exist_and_are_throttled(): void
    {
        foreach (['public.form.provinces', 'public.form.regencies', 'public.form.postal-codes'] as $name) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertNotNull($route);
            $this->assertContains('throttle:60,1', $route->gatherMiddleware());
        }
    }
}
