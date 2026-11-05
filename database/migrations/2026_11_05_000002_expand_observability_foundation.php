<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExpandObservabilityFoundation extends Migration
{
    public function up(): void
    {
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

        Schema::create('audit_event_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_event_id')->constrained('audit_events')->cascadeOnDelete();
            $table->string('field')->index();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->boolean('sensitive')->default(false);
            $table->timestamps();
        });

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

        Schema::table('request_logs', function (Blueprint $table) {
            $table->uuid('request_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('actor_type', 20)->nullable();
            $table->string('method', 10)->nullable();
            $table->string('route_name')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable()->index();
            $table->unsignedBigInteger('duration_ms')->nullable();
            $table->unsignedBigInteger('response_size')->nullable();
        });

        Schema::table('master_activity_logs', function (Blueprint $table) {
            $table->uuid('request_id')->nullable()->index();
            $table->string('actor_type', 20)->nullable();
        });

        Schema::table('blocked_requests', function (Blueprint $table) {
            $table->timestamp('time')->nullable()->index();
        });
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
