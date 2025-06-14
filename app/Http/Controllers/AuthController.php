<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): Response
    {
        $credentials = $request->validate([// TODO: Use FormRequest
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response(null, 201);
        }

        return response('The provided credentials do not match our records.', 401);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response(204);
    }

    //TODO: Implement register method

    public function user(Request $request): Response
    {
        return response($request->user(), 200);
    }
}
