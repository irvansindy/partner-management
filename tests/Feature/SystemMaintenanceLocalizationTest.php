<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\SystemMaintenanceController;
use App\Models\User;
use App\Services\MenuService;
use App\Services\SystemMaintenanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class SystemMaintenanceLocalizationTest extends TestCase
{
    public function test_catalogue_translates_display_fields_without_changing_commands(): void
    {
        $id = Arr::dot(require resource_path('lang/id/maintenance.php'));
        $en = Arr::dot(require resource_path('lang/en/maintenance.php'));
        $this->assertEqualsCanonicalizing(array_keys($id), array_keys($en));
        foreach ($id as $key => $text) {
            preg_match_all('/:[a-z_]+/', $text, $idTokens);
            preg_match_all('/:[a-z_]+/', $en[$key], $enTokens);
            $this->assertEqualsCanonicalizing($idTokens[0], $enTokens[0], $key);
        }
        $catalogues = [];
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $catalogues[$locale] = (new SystemMaintenanceService())->commands();
        }
        $this->assertCount(22, $catalogues['id']);
        $this->assertSame(array_keys($catalogues['id']), array_keys($catalogues['en']));
        foreach ($catalogues['id'] as $key => $command) {
            $this->assertSame($command['command'], $catalogues['en'][$key]['command']);
            $this->assertSame($command['confirmation'], $catalogues['en'][$key]['confirmation']);
            $this->assertSame($command['long_running'], $catalogues['en'][$key]['long_running']);
            $this->assertNotSame($command['label'], $catalogues['en'][$key]['label']);
            $this->assertNotSame($command['description'], $catalogues['en'][$key]['description']);
        }
    }

    public function test_no_output_messages_are_localized_and_artisan_output_is_preserved(): void
    {
        $lock = Mockery::mock();
        $lock->shouldReceive('get')->andReturnUsing(fn ($callback) => $callback());
        Cache::shouldReceive('lock')->with('system-maintenance:cache-clear', 120)->andReturn($lock);
        Artisan::shouldReceive('call')->with('cache:clear')->times(4)->andReturn(0);
        Artisan::shouldReceive('output')->andReturn('', "\033[32mTechnical output: unmodified\033[0m", '', "\033[32mTechnical output: unmodified\033[0m");
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $result = (new SystemMaintenanceService())->run('cache-clear', 1);
            $this->assertTrue($result['success']);
            $this->assertSame(__('maintenance.no_output'), $result['output']);
            $this->assertSame('no_output', $result['output_key']);
            $result = (new SystemMaintenanceService())->run('cache-clear', 1);
            $this->assertSame('Technical output: unmodified', $result['output']);
            $this->assertNull($result['output_key']);
        }
    }

    public function test_busy_and_exception_messages_are_localized_without_exception_details(): void
    {
        $busyLock = Mockery::mock();
        $busyLock->shouldReceive('get')->twice()->andReturn(false);
        Cache::shouldReceive('lock')->with('system-maintenance:cache-clear', 120)->andReturn($busyLock);
        $activeLock = Mockery::mock();
        $activeLock->shouldReceive('get')->twice()->andReturnUsing(fn ($callback) => $callback());
        Cache::shouldReceive('lock')->with('system-maintenance:view-clear', 120)->andReturn($activeLock);
        Artisan::shouldReceive('call')->with('view:clear')->twice()->andThrow(new \RuntimeException('private technical detail'));
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $result = (new SystemMaintenanceService())->run('cache-clear', 1);
            $this->assertTrue($result['busy']);
            $this->assertSame(__('maintenance.busy'), $result['output']);
            $result = (new SystemMaintenanceService())->run('view-clear', 1);
            $this->assertFalse($result['success']);
            $this->assertSame(__('maintenance.failed'), $result['output']);
            $this->assertStringNotContainsString('private', $result['output']);
        }
    }

    public function test_page_and_flashed_results_follow_current_locale_and_escape_raw_output(): void
    {
        $this->app->instance(MenuService::class, Mockery::mock(MenuService::class, function ($mock) {
            $mock->shouldReceive('getAccessibleMenus')->andReturn(collect());
        }));
        $this->actingAs((new User())->forceFill(['id' => 1, 'name' => 'Reviewer']));
        $controller = new SystemMaintenanceController(new SystemMaintenanceService());
        $request = new Request();
        $request->setLaravelSession(app('session.store'));
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            session(['maintenance_result' => ['key' => 'cache-clear', 'label' => 'Old locale label', 'success' => false, 'busy' => true, 'output' => 'Old locale message', 'output_key' => 'busy']]);
            $html = $controller->index($request)->render();
            $this->assertStringContainsString(__('maintenance.Clear application cache'), $html);
            $this->assertStringContainsString(__('maintenance.busy'), $html);
            $this->assertStringContainsString(e(__('maintenance.Cache & Optimization')), $html);
            $this->assertStringNotContainsString('Old locale', $html);
            $this->assertDoesNotMatchRegularExpression('/\bmaintenance\.[A-Za-z]/', $html);
            session(['maintenance_result' => ['key' => 'cache-clear', 'label' => 'Old label', 'success' => true, 'output' => '<script>technical</script>']]);
            $html = $controller->index($request)->render();
            $this->assertStringContainsString('&lt;script&gt;technical&lt;/script&gt;', $html);
            $this->assertStringNotContainsString('<script>technical</script>', $html);
        }
    }

    public function test_invalid_command_validation_is_localized_and_never_runs_a_command(): void
    {
        Artisan::shouldReceive('call')->never();
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            try {
                (new SystemMaintenanceController(new SystemMaintenanceService()))->execute(new Request(['command' => 'db:wipe']));
                $this->fail('Unknown command must fail validation.');
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->assertSame(__('maintenance.validation.in'), $e->errors()['command'][0]);
            }
        }
    }
}
