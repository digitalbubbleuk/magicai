<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BypassLicenseForLocalhost
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
        // Check if we're on localhost or in a development environment
        if ($this->isLocalEnvironment($request)) {
            // Set a session variable to indicate we've bypassed the license check
            session(['license_bypassed_for_localhost' => true]);
            
            // Continue to the next middleware/request
            return $next($request);
        }
        
        // Not localhost, continue with normal flow
        return $next($request);
    }
    
    /**
     * Determine if the application is in a local environment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isLocalEnvironment(Request $request): bool
    {
        $host = $request->getHost();
        
        // Check if we're in a local environment
        return app()->environment('local', 'development') || 
               in_array($host, ['localhost', '127.0.0.1']) ||
               str_ends_with($host, '.test') ||
               str_ends_with($host, '.local');
    }
}
