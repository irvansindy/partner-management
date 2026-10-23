<?php

namespace Tests\Unit;

use App\Support\SecurityLogSanitizer;
use Illuminate\Http\Request;
use RuntimeException;
use Tests\TestCase;

class SecurityLogSanitizerTest extends TestCase
{
    /** @test */
    public function request_and_exception_context_never_include_submitted_pii(): void
    {
        $sensitiveValues = [
            'csrf-secret', '3201010101010001', '9876543210', 'person@example.com',
            '+628123456789', 'Jalan Rahasia 12', 'nested-secret',
        ];
        $request = Request::create('/f/token', 'POST', [
            '_token' => $sensitiveValues[0],
            'nik' => [$sensitiveValues[1]],
            'account_number' => [$sensitiveValues[2]],
            'contact_email' => [$sensitiveValues[3]],
            'contact_telephone' => [$sensitiveValues[4]],
            'address' => [$sensitiveValues[5]],
            'nested' => ['value' => $sensitiveValues[6]],
        ], [], [], [
            'REMOTE_ADDR' => '203.0.113.42',
            'HTTP_USER_AGENT' => 'QA Browser/1.0',
            'HTTP_AUTHORIZATION' => 'Bearer credential-secret',
            'HTTP_COOKIE' => 'session=cookie-secret',
        ]);
        $request->attributes->set('request_id', 'request-123');
        $request->attributes->set('response_status', 422);

        $sanitizer = new SecurityLogSanitizer();
        $contexts = [
            $sanitizer->requestMetadata($request),
            $sanitizer->exceptionContext(new RuntimeException('internal failure'), $request),
        ];
        $encoded = json_encode($contexts, JSON_THROW_ON_ERROR);

        foreach (array_merge($sensitiveValues, ['credential-secret', 'cookie-secret', '203.0.113.42']) as $value) {
            $this->assertStringNotContainsString($value, $encoded);
        }

        $this->assertSame('POST', $contexts[0]['method']);
        $this->assertSame(422, $contexts[0]['status']);
        $this->assertSame('request-123', $contexts[0]['request_id']);
        $this->assertSame(RuntimeException::class, $contexts[1]['exception']);
    }
}
