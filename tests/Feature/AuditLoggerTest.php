<?php

namespace Tests\Feature;

use App\Audit\AuditLogger;
use App\Models\AuditEvent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditLoggerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        $this->createAuditTables();

        $request = Request::create('/office-setting', 'POST', [], [], [], [
            'REMOTE_ADDR' => '203.0.113.42',
            'HTTP_USER_AGENT' => 'Audit Test Browser',
        ]);
        $request->attributes->set('request_id', '066f7f8a-97f2-4a8f-8ad2-d716738e158f');
        $this->app->instance('request', $request);
    }

    /** @test */
    public function it_persists_a_redacted_event_and_field_changes(): void
    {
        $event = app(AuditLogger::class)->log(
            'office.updated',
            'update',
            null,
            ['name' => 'Old Office', 'password' => 'old-secret'],
            ['name' => 'New Office', 'password' => 'new-secret'],
            'Office updated'
        );

        $this->assertInstanceOf(AuditEvent::class, $event);
        $this->assertSame('office.updated', $event->event);
        $this->assertSame('066f7f8a-97f2-4a8f-8ad2-d716738e158f', $event->request_id);
        $this->assertCount(2, $event->changes);
        $this->assertSame('[REDACTED]', $event->changes->firstWhere('field', 'password')->old_value);
    }

    /** @test */
    public function persistence_failure_is_logged_without_becoming_an_application_error(): void
    {
        Schema::drop('audit_event_changes');
        Log::spy();

        $result = app(AuditLogger::class)->log(
            'office.updated',
            'update',
            null,
            ['name' => 'Old'],
            ['name' => 'New']
        );

        $this->assertNull($result);
        Log::shouldHaveReceived('warning')->once();
    }

    private function createAuditTables(): void
    {
        Schema::create('audit_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('request_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('actor_type');
            $table->string('event');
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->string('route')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_event_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_event_id');
            $table->string('field');
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->boolean('sensitive')->default(false);
            $table->timestamps();
        });
    }
}
