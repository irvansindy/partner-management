<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SystemMaintenanceAccessTest extends TestCase
{
    /** @test */
    public function maintenance_routes_are_super_admin_only_and_execution_is_post_only(): void
    {
        $index = Route::getRoutes()->getByName('admin.system-maintenance.index');
        $execute = Route::getRoutes()->getByName('admin.system-maintenance.execute');

        $this->assertNotNull($index);
        $this->assertNotNull($execute);
        $this->assertSame(['GET', 'HEAD'], $index->methods());
        $this->assertSame(['POST'], $execute->methods());
        $this->assertContains('auth', $index->gatherMiddleware());
        $this->assertContains('role:super-admin', $index->gatherMiddleware());
        $this->assertContains('role:super-admin', $execute->gatherMiddleware());
    }

    /** @test */
    public function controller_validates_an_allowlisted_identifier_and_never_accepts_command_text(): void
    {
        $controller = file_get_contents(base_path('app/Http/Controllers/Admin/SystemMaintenanceController.php'));

        $this->assertStringContainsString('Rule::in(array_keys($this->maintenance->commands()))', $controller);
        $this->assertStringContainsString('$request->validate', $controller);
        $this->assertStringNotContainsString('Artisan::call', $controller);
    }

    /** @test */
    public function maintenance_view_uses_csrf_confirmation_and_escaped_output(): void
    {
        $view = file_get_contents(resource_path('views/admin/system_maintenance/index.blade.php'));

        $this->assertStringContainsString('@csrf', $view);
        $this->assertStringContainsString('maintenance-command-form', $view);
        $this->assertStringContainsString('data-confirm', $view);
        $this->assertStringContainsString('{{ $result[\'output\'] }}', $view);
        $this->assertStringNotContainsString('{!! $result[\'output\'] !!}', $view);
        $this->assertStringContainsString('aria-live="polite"', $view);
    }
}
