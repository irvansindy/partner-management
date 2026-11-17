<?php

namespace Tests\Feature;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Tests\TestCase;

class RateLimitPageTest extends TestCase
{
    public function test_browser_rate_limit_is_a_localized_html_page_with_retry_header(): void
    {
        foreach (['id', 'en'] as $locale) {
            app()->setLocale($locale);
            $request = Request::create('/f/example');
            $response = app(Handler::class)->render($request, new TooManyRequestsHttpException(25));
            $this->assertSame(429, $response->getStatusCode());
            $this->assertSame('25', $response->headers->get('Retry-After'));
            $this->assertStringContainsString('text/html', $response->headers->get('Content-Type'));
            $this->assertStringContainsString(__('rate_limit.title'), $response->getContent());
            $this->assertStringContainsString(__('rate_limit.wait', ['seconds' => 25]), $response->getContent());
        }
    }

    public function test_ajax_and_api_requests_continue_to_receive_json(): void
    {
        foreach ([['/f/example', ['HTTP_ACCEPT' => 'application/json']], ['/api/example', []]] as [$url, $headers]) {
            $request = Request::create($url, 'GET', [], [], [], $headers);
            $response = app(Handler::class)->render($request, new TooManyRequestsHttpException(12));
            $this->assertSame(429, $response->getStatusCode());
            $this->assertSame('12', $response->headers->get('Retry-After'));
            $this->assertStringContainsString('application/json', $response->headers->get('Content-Type'));
            $this->assertSame(__('rate_limit.message'), json_decode($response->getContent(), true)['message']);
        }
    }
}
