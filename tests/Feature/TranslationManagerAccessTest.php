<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class TranslationManagerAccessTest extends TestCase
{
    /** @test */
    public function every_translation_manager_route_is_restricted_to_super_admin(): void
    {
        $routes = collect(Route::getRoutes()->getRoutes())
            ->filter(function ($route) {
                return strpos($route->uri(), 'translations') === 0;
            });

        $this->assertNotEmpty($routes);

        foreach ($routes as $route) {
            $this->assertContains('web', $route->gatherMiddleware(), $route->uri());
            $this->assertContains('auth', $route->gatherMiddleware(), $route->uri());
            $this->assertContains('role:super-admin', $route->gatherMiddleware(), $route->uri());
            $this->assertStringStartsWith(
                'Barryvdh\\TranslationManager\\Controller@',
                $route->getActionName(),
                $route->uri()
            );
        }
    }
}
