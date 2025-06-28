<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware(['auth'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::patch('update', [AuthController::class, 'update']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome');
    });
});

Route::get('/terms-of-service', function () {
    return Inertia::render('TermsOfService');
});

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    });
    Route::get('/trip', function () {
        return Inertia::render('Trip');
    });
    Route::get('/friend', function () {
        return Inertia::render('Friend');
    });
    Route::get('/setting', function () {
        return Inertia::render('Setting');
    });
});
