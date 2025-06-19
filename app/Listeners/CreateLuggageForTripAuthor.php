<?php

namespace App\Listeners;

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
    public function handle(Trip $trip): void
    {
        $trip->luggages()->create([
            'user_id' => $trip->author_id,
        ]);
    }
}
