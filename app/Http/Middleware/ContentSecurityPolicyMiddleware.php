<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Add CSP headers to allow WebSocket connections
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://analytics.google.com https://www.recaptcha.net https://www.gstatic.com; " .
            "connect-src 'self' wss: ws: https://*.pusher.com https://*.pusherapp.com https://*.ingest.sentry.io https://www.google-analytics.com https://analytics.google.com https://play.google.com https://www.recaptcha.net https://www.gstatic.com https://api.segment.io https://csp.withgoogle.com; " .
            "img-src 'self' data: blob: https:; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com data:; " .
            "frame-src 'self' https://www.recaptcha.net https://www.google.com; " .
            "media-src 'self' blob: data:;"
        );

        return $response;
    }
}
