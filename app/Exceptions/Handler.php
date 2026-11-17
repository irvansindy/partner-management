<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception)
    {
        if ($request->routeIs('public.form.*', 'submit-partner', 'login', 'register', 'password.*')) {
            return response()->json([
                'message' => __('messages.Validation Error'),
                'errors' => $exception->errors(),
            ], $exception->status);
        }

        return parent::invalidJson($request, $exception);
    }

    public function render($request, Throwable $exception): Response
    {
        $response = parent::render($request, $exception);

        if ($response->getStatusCode() === 429) {
            $headers = $response->headers->all();
            unset($headers['content-type'], $headers['content-length']);
            if ($request->expectsJson() || $request->is('api/*')) {
                $response = response()->json(['message' => __('rate_limit.message')], 429, $headers);
            } else {
                $response = response()->view('errors.429', [
                    'retryAfter' => max(1, (int) $response->headers->get('Retry-After', 60)),
                ], 429, $headers);
                $response->headers->set('Content-Type', 'text/html; charset=UTF-8');
            }
        }

        if ($request instanceof Request && $request->attributes->has('request_id')) {
            $response->headers->set('X-Request-ID', $request->attributes->get('request_id'));
        }

        return $response;
    }
}
