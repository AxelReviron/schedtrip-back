<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // daily/perMinute
        // Directions V2 (2000 / 40)
        // Geocode Search (1000 / 100)
        RateLimiter::for('directions', function (Request $request) {
            $userId = $request->user()->getKey();

            return [
                Limit::perMinute(4)->by($userId),
                Limit::perDay(200)->by($userId),
            ];
        });

        RateLimiter::for('geocode', function (Request $request) {
            $userId = $request->user()->getKey();

            return [
                Limit::perMinute(10)->by($userId),
                Limit::perDay(100)->by($userId),
            ];
        });
    }
}
