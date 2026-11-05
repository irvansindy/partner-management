<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ObservabilityMigrationExecutionTest extends TestCase
{
    /** @test */
    public function observability_schema_can_be_applied_and_rolled_back_without_removing_legacy_tables(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        $this->createLegacyTables();

        require_once database_path('migrations/2026_11_05_000002_expand_observability_foundation.php');
        $migration = new \ExpandObservabilityFoundation();
        $migration->up();

        $this->assertTrue(Schema::hasTable('audit_events'));
        $this->assertTrue(Schema::hasTable('audit_event_changes'));
        $this->assertTrue(Schema::hasTable('operation_runs'));
        $this->assertTrue(Schema::hasColumn('request_logs', 'request_id'));
        $this->assertTrue(Schema::hasColumn('blocked_requests', 'time'));

        $migration->down();

        $this->assertFalse(Schema::hasTable('audit_events'));
        $this->assertTrue(Schema::hasTable('request_logs'));
        $this->assertTrue(Schema::hasTable('master_activity_logs'));
        $this->assertTrue(Schema::hasTable('blocked_requests'));
    }

    /** @test */
    public function observability_schema_can_resume_after_a_partial_run_and_existing_legacy_columns(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        $this->createLegacyTables(true);

        require_once database_path('migrations/2026_11_05_000002_expand_observability_foundation.php');
        $migration = new \ExpandObservabilityFoundation();

        $migration->up();
        $migration->up();

        $this->assertTrue(Schema::hasTable('audit_events'));
        $this->assertTrue(Schema::hasColumn('request_logs', 'request_id'));
        $this->assertTrue(Schema::hasColumn('blocked_requests', 'time'));
    }

    private function createLegacyTables(bool $blockedRequestHasTime = false): void
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('REQUEST');
            $table->ipAddress('ip')->nullable();
            $table->json('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->json('extra')->nullable();
            $table->timestamp('time')->nullable();
            $table->timestamps();
        });
        Schema::create('master_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->json('action')->nullable();
            $table->timestamps();
        });
        Schema::create('blocked_requests', function (Blueprint $table) use ($blockedRequestHasTime) {
            $table->id();
            $table->string('type');
            $table->ipAddress('ip');
            if ($blockedRequestHasTime) {
                $table->timestamp('time')->nullable();
            }
            $table->timestamps();
        });
    }
}
