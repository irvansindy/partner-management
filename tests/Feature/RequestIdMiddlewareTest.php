<?php

namespace Tests\Feature;

use App\Http\Middleware\RequestIdMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class RequestIdMiddlewareTest extends TestCase
{
    /** @test */
    public function it_generates_and_returns_a_request_id(): void
    {
        $request = Request::create('/health', 'GET');

        $response = (new RequestIdMiddleware())->handle(
            $request,
            fn () => response('ok')
        );

        $requestId = $request->attributes->get('request_id');
        $this->assertTrue(Str::isUuid($requestId));
        $this->assertSame($requestId, $response->headers->get('X-Request-ID'));
    }

    /** @test */
    public function it_accepts_a_uuid_and_replaces_an_invalid_identifier(): void
    {
        $accepted = (string) Str::uuid();
        $validRequest = Request::create('/health', 'GET', [], [], [], ['HTTP_X_REQUEST_ID' => $accepted]);
        (new RequestIdMiddleware())->handle($validRequest, fn () => response('ok'));

        $invalidRequest = Request::create('/health', 'GET', [], [], [], ['HTTP_X_REQUEST_ID' => "invalid\r\nvalue"]);
        (new RequestIdMiddleware())->handle($invalidRequest, fn () => response('ok'));

        $this->assertSame($accepted, $validRequest->attributes->get('request_id'));
        $this->assertTrue(Str::isUuid($invalidRequest->attributes->get('request_id')));
        $this->assertNotSame("invalid\r\nvalue", $invalidRequest->attributes->get('request_id'));
    }
}
