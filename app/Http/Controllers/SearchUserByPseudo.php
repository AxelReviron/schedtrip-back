<?php

namespace App\Http\Controllers;

use App\Models\User;

class SearchUserByPseudo extends Controller
{
    public function search(string $pseudo) {
        $user = User::where('pseudo', $pseudo)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }
}
