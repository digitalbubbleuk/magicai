<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class GuzzleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Only disable SSL verification in local development
        if (app()->environment('local', 'development')) {
            $this->app->bind(Client::class, function ($app) {
                return new Client([
                    'verify' => false,
                ]);
            });

            Http::macro('insecure', function () {
                return Http::withOptions([
                    'verify' => false,
                ]);
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
