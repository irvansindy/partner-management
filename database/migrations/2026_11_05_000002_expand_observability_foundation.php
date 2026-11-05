<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExpandObservabilityFoundation extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('audit_events')) {
            Schema::create('audit_events', function (Blueprint $table) {
                $table->id();
                $table->uuid('request_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('actor_type', 20)->default('system');
                $table->string('event')->index();
                $table->string('action', 40)->index();
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->text('description')->nullable();
                $table->string('route')->nullable();
                $table->string('method', 10)->nullable();
                $table->string('ip_hash', 64)->nullable();
                $table->string('user_agent')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['model_type', 'model_id']);
            });
        }

        if (!Schema::hasTable('audit_event_changes')) {
            Schema::create('audit_event_changes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('audit_event_id')->constrained('audit_events')->cascadeOnDelete();
                $table->string('field')->index();
                $table->json('old_value')->nullable();
                $table->json('new_value')->nullable();
                $table->boolean('sensitive')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('operation_runs')) {
            Schema::create('operation_runs', function (Blueprint $table) {
                $table->id();
                $table->uuid('request_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('actor_type', 20)->default('system');
                $table->string('operation')->index();
                $table->string('status', 30)->default('running')->index();
                $table->json('stats')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamp('started_at');
                $table->timestamp('finished_at')->nullable();
                $table->unsignedBigInteger('duration_ms')->nullable();
                $table->timestamps();
            });
        }

        $requestLogColumns = array_fill_keys([
            'request_id', 'user_id', 'actor_type', 'method', 'route_name',
            'status_code', 'duration_ms', 'response_size',
        ], false);
        foreach ($requestLogColumns as $column => $_) {
            $requestLogColumns[$column] = Schema::hasColumn('request_logs', $column);
        }
        Schema::table('request_logs', function (Blueprint $table) use ($requestLogColumns) {
            if (!$requestLogColumns['request_id']) $table->uuid('request_id')->nullable()->index();
            if (!$requestLogColumns['user_id']) $table->unsignedBigInteger('user_id')->nullable()->index();
            if (!$requestLogColumns['actor_type']) $table->string('actor_type', 20)->nullable();
            if (!$requestLogColumns['method']) $table->string('method', 10)->nullable();
            if (!$requestLogColumns['route_name']) $table->string('route_name')->nullable();
            if (!$requestLogColumns['status_code']) $table->unsignedSmallInteger('status_code')->nullable()->index();
            if (!$requestLogColumns['duration_ms']) $table->unsignedBigInteger('duration_ms')->nullable();
            if (!$requestLogColumns['response_size']) $table->unsignedBigInteger('response_size')->nullable();
        });

        $masterHasRequestId = Schema::hasColumn('master_activity_logs', 'request_id');
        $masterHasActorType = Schema::hasColumn('master_activity_logs', 'actor_type');
        Schema::table('master_activity_logs', function (Blueprint $table) use ($masterHasRequestId, $masterHasActorType) {
            if (!$masterHasRequestId) $table->uuid('request_id')->nullable()->index();
            if (!$masterHasActorType) $table->string('actor_type', 20)->nullable();
        });

        if (!Schema::hasColumn('blocked_requests', 'time')) {
            Schema::table('blocked_requests', function (Blueprint $table) {
                $table->timestamp('time')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('blocked_requests', function (Blueprint $table) {
                $table->dropColumn('time');
            });

            Schema::table('master_activity_logs', function (Blueprint $table) {
                $table->dropColumn(['request_id', 'actor_type']);
            });

            Schema::table('request_logs', function (Blueprint $table) {
                $table->dropColumn([
                    'request_id', 'user_id', 'actor_type', 'method', 'route_name',
                    'status_code', 'duration_ms', 'response_size',
                ]);
            });
        }

        Schema::dropIfExists('operation_runs');
        Schema::dropIfExists('audit_event_changes');
        Schema::dropIfExists('audit_events');
    }
}
