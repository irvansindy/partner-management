<?php

namespace Tests\Unit;

use Tests\TestCase;

class ObservabilityMigrationTest extends TestCase
{
    /** @test */
    public function wave_one_migration_is_expand_only_and_defines_observability_storage(): void
    {
        $path = database_path('migrations/2026_11_05_000002_expand_observability_foundation.php');

        $this->assertFileExists($path);
        $migration = file_get_contents($path);

        foreach (['audit_events', 'audit_event_changes', 'operation_runs'] as $table) {
            $this->assertStringContainsString("Schema::create('{$table}'", $migration);
        }

        foreach (['request_id', 'user_id', 'status_code', 'duration_ms', 'response_size'] as $column) {
            $this->assertStringContainsString($column, $migration);
        }

        $this->assertStringContainsString("Schema::table('request_logs'", $migration);
        $this->assertStringContainsString("Schema::table('master_activity_logs'", $migration);
        $this->assertStringContainsString("Schema::table('blocked_requests'", $migration);
        $this->assertStringNotContainsString("Schema::dropIfExists('activity_logs')", $migration);
        $this->assertStringNotContainsString("Schema::dropIfExists('master_activity_logs')", $migration);
    }
}
