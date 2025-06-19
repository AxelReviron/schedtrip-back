<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(LoginFormRequest $request): Response
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response('User login successfully', 200);
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

    public function register(StoreUserRequest $request): Response
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);
        return response('User registered successfully', 201);
    }

    public function update(UpdateUserRequest $request): Response
    {
        $data = $request->validated();
        $user = $request->user();
        $user->update($data);

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        }
        return response('User updated successfully', 200);
    }

    public function user(Request $request): Response
    {
        return response($request->user(), 200);
    }
}
