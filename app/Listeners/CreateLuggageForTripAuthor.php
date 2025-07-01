<?php

namespace App\Listeners;

use App\Events\TripSaved;
use App\Models\Trip;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateLuggageForTripAuthor
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TripSaved $event): void
    {
        $event->trip->luggage()->create([
            'user_id' => $event->trip->author_id,
        ]);
    }
}
