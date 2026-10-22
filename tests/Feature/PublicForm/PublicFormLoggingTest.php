<?php

namespace Tests\Feature\PublicForm;

use App\Http\Middleware\SanitizeInputMiddleware;
use App\Models\RequestLog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicFormLoggingTest extends TestCase
{
    /** @test */
    public function a_rejected_public_form_request_is_logged_without_its_payload(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
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

        $request = Request::create('/f/public-token', 'POST', [
            '_token' => 'csrf-secret',
            'nik' => ['3201010101010001'],
            'contact_email' => ['person@example.com'],
            'account_number' => ['9876543210'],
        ], [], [], ['REMOTE_ADDR' => '203.0.113.42', 'HTTP_USER_AGENT' => 'QA Browser/1.0']);

        $response = (new SanitizeInputMiddleware())->handle(
            $request,
            fn () => response()->json(['message' => 'validation failed'], 422)
        );

        $this->assertSame(422, $response->getStatusCode());
        $log = RequestLog::query()->sole();
        $encoded = $log->toJson();
        foreach (['csrf-secret', '3201010101010001', 'person@example.com', '9876543210', '203.0.113.42'] as $value) {
            $this->assertStringNotContainsString($value, $encoded);
        }
        $this->assertSame(422, $log->extra['status']);
    }
}
