<?php

namespace App\Providers;

use App\Events\ParticipantAddedToTrip;
use App\Listeners\CreateLuggageForParticipants;
use App\Listeners\CreateLuggageForTripAuthor;
use App\Models\Trip;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Trip events
        Event::listen('eloquent.created: '.Trip::class, CreateLuggageForTripAuthor::class);
        Event::listen(ParticipantAddedToTrip::class, CreateLuggageForParticipants::class);
    }
}
