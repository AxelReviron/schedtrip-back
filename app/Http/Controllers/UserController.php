<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\OrsApiKeyRequest;
use App\Http\Requests\User\SearchUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function searchByUserPseudo(SearchUserRequest $request): JsonResponse
    {
        $user = User::where('pseudo', $request->getPseudo())->first();

        return response()->json($user);
    }

    public function addOrsApiKey(OrsApiKeyRequest $request): JsonResponse
    {
        $apiKey = $request->validated()['ors_api_key'];
        $user = Auth::user();
        $user->update(['ors_api_key' => $apiKey]);

        return response()->json(['message' => 'API Key successfully added to user account'], 200);
    }
}
