<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use App\Helpers\SanitizeHelper;
use App\Helpers\SecurityLogHelper;
use Illuminate\Support\Str;
use App\Models\BlockedRequest;
use App\Models\RequestLog;
use App\Models\Notifications;
use App\Events\SecurityAlertEvent;
use App\Support\SecurityLogSanitizer;
use App\Audit\AuditRedactor;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = hrtime(true);
        if (!$request->attributes->get('request_id')) {
            $request->attributes->set('request_id', (string) Str::uuid());
        }

        $ip = $request->ip();
        $ua = $request->userAgent();

        // 🛡️ Header Injection Protection
        if ($this->hasSuspiciousHeaders($request)) {
            $this->logThreat('HEADER_INJECTION', [
                'reason'  => 'Terdeteksi karakter berbahaya pada header',
                'headers' => $request->headers->all()
            ]);
            abort(400, 'Bad Request: Header tidak valid');
        }

        // 🚨 Rate limit per IP
        $rateKey = 'global:' . $ip;
        if (RateLimiter::tooManyAttempts($rateKey, 100)) {
            $this->logThreat('RATE_LIMIT', [
                'reason'   => 'Terlalu banyak request dalam 60 detik',
                'attempts' => RateLimiter::attempts($rateKey)
            ]);
            return response()->json([
                'message' => 'Terlalu banyak permintaan. Coba lagi nanti.'
            ], 429);
        }
        RateLimiter::hit($rateKey, 60);

        // 🚫 Block suspicious User-Agent
        $blockedAgents = ['curl', 'wget', 'python', 'nikto', 'sqlmap', 'fuzzer', 'scanner'];
        if (preg_match('/(' . implode('|', $blockedAgents) . ')/i', $ua)) {
            $this->logThreat('BLOCKED_UA', [
                'reason' => 'User-Agent terlarang: ' . $ua
            ]);
            abort(403, 'Forbidden: User-Agent tidak diizinkan.');
        }

        // 🚫 Block unsupported Content-Type
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
            $allowedContentTypes = [
                'application/json',
                'application/x-www-form-urlencoded',
                'multipart/form-data'
            ];
            $contentType = $request->header('Content-Type');
            if ($contentType && !Str::contains($contentType, $allowedContentTypes)) {
                $this->logThreat('BLOCKED_CONTENT_TYPE', [
                    'reason'        => 'Content-Type tidak didukung',
                    'content_type'  => $contentType
                ]);
                abort(415, 'Unsupported Media Type');
            }
        }

        // 🧼 Sanitize ALL inputs (query, body, headers)
        $sanitizedData = SanitizeHelper::sanitizeArray(
            $request->all(),                // Semua data (query + body)
            htmlFields: ['content', 'description'], // HTML whitelist
            excludeFields: ['password', 'token'],   // Exclude sensitive fields
            autoDetect: true
        );
        $request->merge($sanitizedData);

        // 🚀 Next middleware/controller
        try {
            $response = $next($request);
        } catch (Throwable $error) {
            $request->attributes->set(
                'response_status',
                $error instanceof HttpExceptionInterface ? $error->getStatusCode() : 500
            );
            $request->attributes->set('duration_ms', (int) ceil((hrtime(true) - $startedAt) / 1_000_000));
            $request->attributes->set('response_size', 0);
            $this->logRequest($request);
            throw $error;
        }

        $request->attributes->set('response_status', $response->getStatusCode());
        $request->attributes->set('duration_ms', (int) ceil((hrtime(true) - $startedAt) / 1_000_000));
        $request->attributes->set('response_size', strlen((string) $response->getContent()));
        $this->logRequest($request);

        // 🛡️ Security headers
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data: https:; font-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline' https:; connect-src 'self' https:"
        );
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        return $response;
    }
    /**
     * Log aktivitas mencurigakan & broadcast ke super-admin.
     */
    private function logThreat(string $type, array $extra = []): void
    {
        try {
            $logData = SecurityLogHelper::createLogData($type, $extra);
        } catch (Throwable $error) {
            $logData = [
                'type' => $type,
                'ip' => request()->ip(),
                'user_agent' => ['raw' => mb_substr((string) request()->userAgent(), 0, 255)],
                'url' => request()->fullUrl(),
                'extra' => $extra,
                'time' => now(),
            ];
            $this->reportSecuritySinkFailure('context', $error);
        }

        $logData['extra'] = app(AuditRedactor::class)->redact($extra);
        $logData['url'] = request()->url();

        $this->safeSecuritySink('database', fn () => BlockedRequest::create($logData));
        $this->safeSecuritySink('file', fn () => Log::channel('security')->warning($type, [
            'request_id' => request()->attributes->get('request_id'),
            'type' => $type,
            'route' => optional(request()->route())->getName(),
            'ip_hash' => hash_hmac('sha256', (string) request()->ip(), (string) config('app.key')),
            'reason' => $extra['reason'] ?? null,
        ]));
        $this->safeSecuritySink('notification', fn () => Notifications::create([
            'type' => $type,
            'message' => "[{$type}] " . ($extra['reason'] ?? 'Security event'),
            'is_read' => 0,
            'icon' => 'fas fa-shield-alt text-danger',
            'url' => $logData['url'] ?? request()->fullUrl(),
        ]));
        $this->safeSecuritySink('broadcast', fn () => broadcast(new SecurityAlertEvent($logData))->toOthers());
    }

    private function safeSecuritySink(string $sink, callable $callback): void
    {
        try {
            $callback();
        } catch (Throwable $error) {
            $this->reportSecuritySinkFailure($sink, $error);
        }
    }

    private function reportSecuritySinkFailure(string $sink, Throwable $error): void
    {
        Log::warning('Security observability sink failed', [
            'request_id' => request()->attributes->get('request_id'),
            'sink' => $sink,
            'exception' => get_class($error),
        ]);
    }

    /**
     * Cek apakah ada header mencurigakan
     */
    private function hasSuspiciousHeaders(Request $request): bool
    {
        foreach ($request->headers->all() as $key => $values) {
            foreach ($values as $value) {
                if (preg_match('/[\r\n\t]/', $value)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Log semua request ke DB
     */
    private function logRequest(Request $request)
    {
        $metadata = app(SecurityLogSanitizer::class)->requestMetadata($request);

        try {
            RequestLog::create([
                'type' => 'REQUEST',
                'ip' => null,
                'user_agent' => ['summary' => $metadata['user_agent']],
                'url' => $metadata['route'],
                'extra' => $metadata,
                'time' => now(),
                'request_id' => $metadata['request_id'],
                'user_id' => $metadata['user_id'],
                'actor_type' => $metadata['actor_type'],
                'method' => $metadata['method'],
                'route_name' => $metadata['route'],
                'status_code' => $metadata['status'],
                'duration_ms' => $metadata['duration_ms'],
                'response_size' => $metadata['response_size'],
            ]);
        } catch (\Throwable $error) {
            Log::warning('Request audit persistence failed', [
                'request_id' => $metadata['request_id'],
                'exception' => get_class($error),
            ]);
        }
    }
}
