<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::patch('update', [AuthController::class, 'update']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/terms-of-service', function () {
    return Inertia::render('TermsOfService');
});

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
});
