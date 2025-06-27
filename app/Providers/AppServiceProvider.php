<?php

namespace App\Providers;

use App\Services\OpenRouteService\Client as OpenRouteServiceClient;
use App\Services\OpenRouteService\Services\Directions;
use App\Services\OpenRouteService\Services\GeoCode;
use Illuminate\Support\Facades\Auth;
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
        //
    }
}
