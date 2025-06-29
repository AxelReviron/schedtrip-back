<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\OpenRouteServiceController;
use App\Http\Controllers\TripParticipantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ---- Auth ----
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware(['auth'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::patch('update', [AuthController::class, 'update']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

// ---- Views ----
Route::middleware(['guest'])->group(function () {
    Route::get('/', fn() => Inertia::render('Welcome'));
});

Route::get('/terms-of-service', fn() => Inertia::render('TermsOfService'));
Route::get('/privacy-policy', fn() => Inertia::render('PrivacyPolicy'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'));
    Route::get('/trip', fn() => Inertia::render('Trip'));
    Route::get('/friend', fn() => Inertia::render('Friend'));
    Route::get('/setting', fn() => Inertia::render('Setting'));
});

// ---- API ----
Route::prefix('/api')->middleware(['auth'])->group(function () {
    Route::prefix('/trips')->group(function () {
        Route::post('/{id}/participants', [TripParticipantController::class, 'store']);
        Route::patch('/{id}/participants', [TripParticipantController::class, 'updatePermissions']);
        Route::delete('/{id}/participants', [TripParticipantController::class, 'destroy']);
    });

    Route::prefix('/users')->group(function () {
        Route::post('/ors/add-api-key', [UserController::class, 'addOrsApiKey']);
        Route::get('/pseudo/{pseudo}', [UserController::class, 'searchByUserPseudo']);
        Route::post('/friends/send', [FriendController::class, 'sendFriendRequest']);
        Route::patch('/friends/action', [FriendController::class, 'handleFriendRequest']);
        Route::delete('/friends/remove', [FriendController::class, 'removeFriend']);
    });

    Route::prefix('/ors')->middleware(['set.ors.api.key'])->group(function () {
        Route::middleware(['openrouteservice.rate.limit:geocode'])->group(function () {
            Route::get('/search/{place}', [OpenRouteServiceController::class, 'searchPlace']);
            Route::post('/reverse-search', [OpenRouteServiceController::class, 'reverseSearchPlace']);
        });
        Route::middleware(['openrouteservice.rate.limit:directions'])->group(function () {
            Route::post('/route', [OpenRouteServiceController::class, 'getRoute']);
        });
    });
});
