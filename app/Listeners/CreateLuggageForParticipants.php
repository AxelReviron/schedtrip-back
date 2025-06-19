<?php

namespace App\Listeners;

use App\Events\ParticipantAddedToTrip;
use App\Models\Luggage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateLuggageForParticipants
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
    public function handle(ParticipantAddedToTrip $event): void
    {
        foreach ($event->participantIds as $userId) {
            Luggage::firstOrCreate([
                'user_id' => $userId,
                'trip_id' => $event->trip->getKey(),
            ]);
        }
    }
}
