<?php

namespace App\Http\Controllers;

use App\Events\ParticipantAddedToTrip;
use App\Http\Requests\Trip\TripsByParticipantIdRequest;
use App\Http\Requests\User\TripParticipantsRequest;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;

class TripParticipantController extends Controller
{
    public function store(TripParticipantsRequest $request, string $id): JsonResponse
    {
        $trip = Trip::findOrFail($id);
        $participants = $request->validated()['participants'];
        $syncData = [];

        $existingParticipantsIds = $trip->participants()->pluck('users.id')->toArray();
        $newParticipantsIds = [];

        foreach ($participants as $participant) {
            $userId = $participant['user_id'];
            $permission = $participant['permission'];

            if (!in_array($userId, $existingParticipantsIds)) {
                $newParticipantsIds[] = $userId;
            }

            $syncData[$userId] = ['permission' => $permission];
        }

        $trip->participants()->syncWithoutDetaching($syncData);

        if (!empty($newParticipantsIds)) {
            Event::dispatch(new ParticipantAddedToTrip($trip, $newParticipantsIds));
        }

        return response()->json(['message' => 'Participants added successfully'], 201);
    }

    public function updatePermissions(TripParticipantsRequest $request, string $id): JsonResponse
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

    public function destroy(TripParticipantsRequest $request, string $id): JsonResponse
    {
        $trip = Trip::findOrFail($id);
        $participants = $request->validated()['participants'];

        $userIds = collect($participants)->pluck('user_id')->all();
        $trip->participants()->detach($userIds);

        return response()->json(['message' => 'Participants removed successfully'], 200);
    }

    public function getTripsByParticipantId(TripsByParticipantIdRequest $request): JsonResponse
    {
        $participantId = $request->validated()['participantId'];
        $trips = Trip::with('stops')->whereHas('participants', function ($query) use ($participantId) {
            $query->where('users.id', $participantId);
        })->get();

        return response()->json($trips);
    }

}
