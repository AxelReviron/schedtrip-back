<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripParticipantsRequest;
use App\Models\Trip;

class TripParticipantController extends Controller
{
    public function store(TripParticipantsRequest $request, string $id)
    {
        $trip = Trip::findOrFail($id);
        $participants = $request->validated()['participants'];
        $syncData = [];

        foreach ($participants as $participant) {
            $userId = $participant['user_id'];
            $permission = $participant['permission'];
            $syncData[$userId] = ['permission' => $permission];
        }

        $trip->participants()->syncWithoutDetaching($syncData);
        return response()->json(['message' => 'Participants added successfully'], 201);
    }

    public function updatePermissions(TripParticipantsRequest $request, string $id)
    {
        $trip = Trip::findOrFail($id);
        $participants = $request->validated()['participants'];

        foreach ($participants as $participant) {
            $trip->participants()->updateExistingPivot(
                $participant['user_id'],
                ['permission' => $participant['permission']]
            );
        }

        return response()->json(['message' => 'Participant\'s permissions updated'], 200);
    }

    public function destroy(TripParticipantsRequest $request, string $id)
    {
        $trip = Trip::findOrFail($id);
        $participants = $request->validated()['participants'];

        $userIds = collect($participants)->pluck('user_id')->all();
        $trip->participants()->detach($userIds);

        return response()->json(['message' => 'Participants removed successfully'], 200);
    }

}
