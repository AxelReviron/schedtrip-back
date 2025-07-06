<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditTripRequest;
use App\Models\Trip;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class EditTripController extends Controller
{
    public function edit(EditTripRequest $request): InertiaResponse
    {
        $tripId = $request->validated()['tripId'];
        $trip = Trip::with('stops', 'participants', 'luggage')->findOrFail($tripId);

        return Inertia::render('EditTrip', [
            'trip' => $trip
        ]);
    }
}
