<?php

namespace Tests\Feature\PublicForm;

use App\Http\Middleware\SanitizeInputMiddleware;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicFormSecurityHeadersTest extends TestCase
{
    /** @test */
    public function public_responses_receive_browser_security_headers(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id(); $table->string('type'); $table->ipAddress('ip')->nullable();
            $table->json('user_agent')->nullable(); $table->string('url')->nullable();
            $table->json('extra')->nullable(); $table->timestamp('time')->nullable(); $table->timestamps();
        });
        $request = Request::create('/f/token', 'GET', [], [], [], ['HTTP_USER_AGENT' => 'QA Browser']);

        $response = (new SanitizeInputMiddleware())->handle($request, fn () => response('ok'));

        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertSame('no-referrer', $response->headers->get('Referrer-Policy'));
        $this->assertStringContainsString("object-src 'none'", $response->headers->get('Content-Security-Policy'));
        $this->assertStringContainsString("frame-ancestors 'none'", $response->headers->get('Content-Security-Policy'));
    }
}
