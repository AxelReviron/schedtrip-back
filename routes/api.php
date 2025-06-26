<?php

use App\Http\Controllers\FriendController;
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
        Route::post('/ors/add-api-key', [UserController::class, 'addOrsApiKey']);
        Route::get('/pseudo/{pseudo}', [UserController::class, 'searchByUserPseudo']);
        Route::post('/friends/send', [FriendController::class, 'sendFriendRequest']);
        Route::patch('/friends/action', [FriendController::class, 'handleFriendRequest']);
        Route::delete('/friends/remove', [FriendController::class, 'removeFriend']);
    });

    Route::prefix('/ors')->group(function () {
        Route::middleware(['openrouteservice.rate.limit:geocode'])->group(function () {
            Route::get('/search/{place}', [OpenRouteServiceController::class, 'searchPlace']);
            Route::post('/reverse-search', [OpenRouteServiceController::class, 'reverseSearchPlace']);
        });
        Route::middleware(['openrouteservice.rate.limit:directions'])->group(function () {
            Route::post('/route', [OpenRouteServiceController::class, 'getRoute']);
        });
    });

});
