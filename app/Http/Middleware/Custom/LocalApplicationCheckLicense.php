<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalApplicationCheckLicense
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
        // Always bypass license check in local environments
        $host = $request->getHost();
        $env = app()->environment();
        
        // Debug information
        logger()->debug('LocalApplicationCheckLicense middleware', [
            'host' => $host,
            'environment' => $env,
            'request_path' => $request->path(),
            'request_url' => $request->url(),
        ]);
        
        logger()->debug('LocalApplicationCheckLicense: Bypassing license check for local environment');
        return $next($request);
    }
}
