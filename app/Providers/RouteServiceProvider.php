<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Marketplace\Http\Middleware\NewExtensionInstalled;
use App\Http\Middleware\ViewSharedMiddleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware([
                'web',  ViewSharedMiddleware::class, NewExtensionInstalled::class,
            ])->group(base_path('routes/web.php'));

            Route::middleware([
                'web', ViewSharedMiddleware::class, NewExtensionInstalled::class,
            ])->group(base_path('routes/admin.php'));


        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', static function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
