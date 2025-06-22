<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteFriendRequest;
use App\Http\Requests\FriendActionRequest;
use App\Http\Requests\SearchUserRequest;
use App\Http\Requests\SendFriendRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function searchByUserPseudo(SearchUserRequest $request): JsonResponse
    {
        $user = User::where('pseudo', $request->input('pseudo'))->first();

        return response()->json($user);
    }

    public function sendFriendRequest(SendFriendRequest $request): JsonResponse
    {
        $targetUser = User::find($request->input('user_id'));
        $currentUserId = Auth::user()->getKey();

        if ($request->canResend()) {
            DB::table('user_friend')
                ->where('user_id', $targetUser->getKey())
                ->where('friend_id', $currentUserId)
                ->update(['status' => 'pending']);

            return response()->json(['message' => 'Friend request re-sent.'], 200);
        }

        $targetUser->friendsOutgoing()->syncWithoutDetaching([
            $currentUserId => ['status' => 'pending']
        ]);

        return response()->json(['message' => 'Friend request sent'], 200);
    }

    public function handleFriendRequest(FriendActionRequest $request): JsonResponse
    {
        $friendId = $request->input('user_id');
        $status = $request->input('status');

        DB::table('user_friend')
            ->where('user_id', Auth::user()->getKey())
            ->where('friend_id', $friendId)
            ->where('status', 'pending')
            ->update(['status' => $status]);

        return response()->json(['message' => "Friend request {$status}."], 200);
    }

    public function removeFriend(DeleteFriendRequest $request): JsonResponse
    {
        $userId = Auth::user()->getKey();
        $friendId = $request->input('user_id');

        DB::table('user_friend')
            ->where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->delete();

        return response()->json(['message' => 'Friend removed'], 200);
    }
}
