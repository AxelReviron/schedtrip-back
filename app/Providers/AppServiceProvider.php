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
        $this->app->bind(OpenRouteServiceClient::class, function ($app) {
            $user = Auth::user();

            if ($user && isset($user->ors_api_key)) {
                $apiKey = $user->ors_api_key;
            } else {
                $apiKey = config('openrouteservice.apiKey');
            }

            return new OpenRouteServiceClient($apiKey);
        });

        $this->app->bind(GeoCode::class, function ($app) {
            return new GeoCode($app->make(OpenRouteServiceClient::class));
        });

        $this->app->bind(Directions::class, function ($app) {
            return new Directions($app->make(OpenRouteServiceClient::class));
        });
    }
}
