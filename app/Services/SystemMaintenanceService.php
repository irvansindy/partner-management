<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class SystemMaintenanceService
{
    private const OUTPUT_LIMIT = 12000;

    public function commands(): array
    {
        return [
            'optimize' => $this->command('optimize', 'Optimize application', 'Build framework bootstrap caches.', 'Cache & Optimization'),
            'optimize-clear' => $this->command('optimize:clear', 'Clear optimization caches', 'Clear compiled bootstrap, route, config, event, and view caches.', 'Cache & Optimization'),
            'cache-clear' => $this->command('cache:clear', 'Clear application cache', 'Flush application cache entries.', 'Cache & Optimization'),
            'config-cache' => $this->command('config:cache', 'Cache configuration', 'Rebuild the combined configuration cache.', 'Cache & Optimization'),
            'config-clear' => $this->command('config:clear', 'Clear configuration cache', 'Remove the generated configuration cache.', 'Cache & Optimization'),
            'route-cache' => $this->command('route:cache', 'Cache routes', 'Rebuild the route cache for faster registration.', 'Cache & Optimization'),
            'route-clear' => $this->command('route:clear', 'Clear route cache', 'Remove the generated route cache.', 'Cache & Optimization'),
            'view-cache' => $this->command('view:cache', 'Compile views', 'Compile all Blade templates.', 'Cache & Optimization'),
            'view-clear' => $this->command('view:clear', 'Clear compiled views', 'Remove compiled Blade templates.', 'Cache & Optimization'),
            'event-cache' => $this->command('event:cache', 'Cache events', 'Discover and cache application events and listeners.', 'Cache & Optimization'),
            'event-clear' => $this->command('event:clear', 'Clear event cache', 'Remove cached events and listeners.', 'Cache & Optimization'),
            'permission-cache-reset' => $this->command('permission:cache-reset', 'Reset permission cache', 'Refresh Spatie role and permission cache.', 'Cache & Optimization'),
            'queue-restart' => $this->command('queue:restart', 'Restart queue workers', 'Ask queue workers to restart after their current job.', 'Worker & Scheduler'),
            'schedule-clear-cache' => $this->command('schedule:clear-cache', 'Clear scheduler locks', 'Remove stale scheduler mutex files.', 'Worker & Scheduler'),
            'auth-clear-resets' => $this->command('auth:clear-resets', 'Clear expired reset tokens', 'Delete expired password reset tokens.', 'Security & Cleanup'),
            'sanctum-prune-expired' => $this->command('sanctum:prune-expired', 'Prune expired API tokens', 'Delete expired Sanctum personal access tokens.', 'Security & Cleanup'),
            'backup-monitor' => $this->command('backup:monitor', 'Monitor backup health', 'Check whether configured backups are healthy.', 'Backup', false),
            'backup-list' => $this->command('backup:list', 'List backups', 'Show backups available on configured disks.', 'Backup', false),
            'backup-run' => $this->command('backup:run', 'Run full backup', 'Create a full application backup. This may take several minutes.', 'Backup', true, true),
            'backup-db' => $this->command('backup:db', 'Back up database', 'Create a database backup. This may take several minutes.', 'Backup', true, true),
            'migrate-status' => $this->command('migrate:status', 'Migration status', 'Show applied and pending database migrations.', 'Diagnostics', false),
            'queue-failed' => $this->command('queue:failed', 'Failed queue jobs', 'Show failed jobs without modifying the queue.', 'Diagnostics', false),
        ];
    }

    public function groupedCommands(): array
    {
        $groups = [];
        foreach ($this->commands() as $key => $command) {
            $groups[$command['group']][$key] = $command;
        }

        return $groups;
    }

    public function run(string $key, int $userId): array
    {
        $commands = $this->commands();
        if (!isset($commands[$key])) {
            throw new InvalidArgumentException('Unknown maintenance command.');
        }

        $definition = $commands[$key];
        $startedAt = microtime(true);
        $lock = Cache::lock('system-maintenance:'.$key, $definition['long_running'] ? 900 : 120);

        $result = $lock->get(function () use ($key, $definition, $userId, $startedAt) {
            try {
                if ($definition['long_running'] && function_exists('set_time_limit')) {
                    set_time_limit(900);
                }

                $exitCode = Artisan::call($definition['command']);
                $result = [
                    'success' => $exitCode === 0,
                    'key' => $key,
                    'label' => $definition['label'],
                    'exit_code' => $exitCode,
                    'output' => $this->sanitizeOutput(Artisan::output()),
                    'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                ];

                Log::info('System maintenance command completed.', [
                    'user_id' => $userId,
                    'command_key' => $key,
                    'exit_code' => $exitCode,
                    'duration_ms' => $result['duration_ms'],
                ]);

                return $result;
            } catch (Throwable $exception) {
                Log::error('System maintenance command failed.', [
                    'user_id' => $userId,
                    'command_key' => $key,
                    'exception' => get_class($exception),
                ]);

                return [
                    'success' => false,
                    'key' => $key,
                    'label' => $definition['label'],
                    'exit_code' => 1,
                    'output' => 'Command failed. Review the application log for details.',
                    'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                ];
            }
        });

        if ($result === false) {
            return [
                'success' => false,
                'busy' => true,
                'key' => $key,
                'label' => $definition['label'],
                'exit_code' => null,
                'output' => 'This command is already running. Please wait and try again.',
                'duration_ms' => 0,
            ];
        }

        return $result;
    }

    private function command(
        string $command,
        string $label,
        string $description,
        string $group,
        bool $confirmation = true,
        bool $longRunning = false
    ): array {
        return [
            'command' => $command,
            'label' => $label,
            'description' => $description,
            'group' => $group,
            'confirmation' => $confirmation,
            'long_running' => $longRunning,
        ];
    }

    private function sanitizeOutput(string $output): string
    {
        $output = preg_replace('/\x1B(?:[@-Z\\-_]|\[[0-?]*[ -\/]*[@-~])/', '', $output) ?? '';
        $output = preg_replace('/[^\P{C}\n\t]/u', '', $output) ?? '';
        $output = trim($output);

        if ($output === '') {
            $output = 'Command completed without output.';
        }

        return mb_substr($output, 0, self::OUTPUT_LIMIT);
    }
}
