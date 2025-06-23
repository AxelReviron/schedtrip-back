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
        $user = User::where('pseudo', $request->getPseudo())->first();

        return response()->json($user);
    }

    public function sendFriendRequest(SendFriendRequest $request): JsonResponse
    {
        $targetUser = User::find($request->input('user_id'));
        $targetUserId = $targetUser->getKey();
        $authUser = Auth::user();
        $authUserId = $authUser->getKey();

        if ($request->canResend()) {
            DB::table('user_friend')
                ->where('from_user_id', $authUserId)// Auth user made request
                ->where('to_user_id', $targetUserId)// To this user
                ->update(['status' => 'pending']);

            return response()->json(['message' => 'Friend request re-sent.'], 200);
        }

        $authUser->friendsOutgoing()->syncWithoutDetaching([
            $targetUserId => ['status' => 'pending']
        ]);

        return response()->json(['message' => 'Friend request sent'], 200);
    }

    public function handleFriendRequest(FriendActionRequest $request): JsonResponse
    {
        $authUserId = Auth::user()->getKey();
        $friendId = $request->input('user_id');
        $status = $request->input('status');

        DB::table('user_friend')
            ->where('from_user_id', $friendId)// User who friend request other user
            ->where('to_user_id', $authUserId)// Auth user who received the friend request
            ->where('status', 'pending')
            ->update(['status' => $status]);

        return response()->json(['message' => "Friend request {$status}."], 200);
    }

    public function removeFriend(DeleteFriendRequest $request): JsonResponse
    {
        $userId = Auth::user()->getKey();
        $friendId = $request->input('user_id');

        DB::table('user_friend')
            ->where(function ($query) use ($userId, $friendId) {
                $query->where('from_user_id', $userId)
                    ->where('to_user_id', $friendId);
            })
            ->orWhere(function ($query) use ($userId, $friendId) {
                $query->where('from_user_id', $friendId)
                    ->where('to_user_id', $userId);
            })
            ->delete();

        return response()->json(['message' => 'Friend removed'], 200);
    }
}
