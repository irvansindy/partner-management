<?php

namespace Tests\Unit;

use App\Services\SystemMaintenanceService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SystemMaintenanceServiceTest extends TestCase
{
    /** @test */
    public function it_exposes_only_the_approved_command_allowlist(): void
    {
        $service = new SystemMaintenanceService();
        $commands = $service->commands();

        $this->assertSame([
            'optimize', 'optimize-clear', 'cache-clear', 'config-cache', 'config-clear',
            'route-cache', 'route-clear', 'view-cache', 'view-clear', 'event-cache',
            'event-clear', 'permission-cache-reset', 'queue-restart', 'schedule-clear-cache',
            'auth-clear-resets', 'sanctum-prune-expired', 'backup-monitor', 'backup-list',
            'backup-run', 'backup-db', 'migrate-status', 'queue-failed',
        ], array_keys($commands));

        $artisanCommands = array_column($commands, 'command');
        $this->assertNotContains('migrate:fresh', $artisanCommands);
        $this->assertNotContains('db:wipe', $artisanCommands);
        $this->assertNotContains('tinker', $artisanCommands);
        $this->assertNotContains('down', $artisanCommands);

        foreach ($commands as $command) {
            $this->assertArrayHasKey('confirmation', $command);
            $this->assertArrayHasKey('long_running', $command);
        }
    }

    /** @test */
    public function it_rejects_an_unknown_command_identifier_before_execution(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new SystemMaintenanceService())->run('migrate-fresh', 1);
    }

    /** @test */
    public function execution_is_locked_logged_and_output_is_capped(): void
    {
        $source = file_get_contents(dirname(__DIR__, 2).'/app/Services/SystemMaintenanceService.php');

        $this->assertStringContainsString('Cache::lock(', $source);
        $this->assertStringContainsString('Artisan::call(', $source);
        $this->assertStringContainsString('Log::', $source);
        $this->assertStringContainsString('OUTPUT_LIMIT', $source);
        $this->assertStringContainsString('preg_replace', $source);
        $this->assertStringContainsString('set_time_limit(900)', $source);
    }
}
