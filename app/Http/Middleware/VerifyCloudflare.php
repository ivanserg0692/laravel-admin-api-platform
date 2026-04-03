<?php

namespace App\Http\Middleware;

use App\Services\CloudflareService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCloudflare
{
    public function __construct(protected  CloudflareService $cloudflare)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isValid = $this->cloudflare->verify(
            $request->input('cf-turnstile-response'),
            $request->ip()
        );

        if (! $isValid) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('The given data was invalid.'),
                    'errors' => [
                        'cf-turnstile-response' => [__('validation.captcha_failed')],
                    ],
                ], 422);
            }

            return back()
                ->withErrors(['cf-turnstile-response' => __('validation.captcha_failed')])
                ->withInput();
        }

        return $next($request);
    }
}
