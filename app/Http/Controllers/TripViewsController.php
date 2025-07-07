<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trip\EditTripViewRequest;
use App\Http\Requests\Trip\ViewTripViewRequest;
use App\Models\Trip;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TripViewsController extends Controller
{
    public function view(ViewTripViewRequest $request): InertiaResponse
    {
        $tripId = $request->validated()['tripId'];
        $trip = Trip::with('stops', 'participants', 'luggage')->findOrFail($tripId);

        return Inertia::render('ViewTrip', [
            'trip' => $trip
        ]);
    }

    public function edit(EditTripViewRequest $request): InertiaResponse
    {
        $tripId = $request->validated()['tripId'];
        $trip = Trip::with('stops', 'participants', 'luggage')->findOrFail($tripId);

        return Inertia::render('EditTrip', [
            'trip' => $trip
        ]);
    }
}
