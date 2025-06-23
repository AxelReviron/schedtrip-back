<?php

use App\Http\Controllers\OpenRouteServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TripParticipantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('/trips')->group(function () {
        Route::post('/{id}/participants', [TripParticipantController::class, 'store']);
        Route::patch('/{id}/participants', [TripParticipantController::class, 'updatePermissions']);
        Route::delete('/{id}/participants', [TripParticipantController::class, 'destroy']);
    });

    Route::prefix('/users')->group(function () {
        Route::get('/pseudo/{pseudo}', [UserController::class, 'searchByUserPseudo']);
        Route::post('/friends/send', [UserController::class, 'sendFriendRequest']);
        Route::patch('/friends/action', [UserController::class, 'handleFriendRequest']);
        Route::delete('/friends/remove', [UserController::class, 'removeFriend']);
    });

    Route::prefix('/ors')->group(function () {
        Route::get('/search/{place}', [OpenRouteServiceController::class, 'searchPlace'])
            ->middleware(['throttle:geocode']);
        Route::post('/route', [OpenRouteServiceController::class, 'getRoute'])
            ->middleware(['throttle:directions']);
    });

});
