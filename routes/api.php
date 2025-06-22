<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TripParticipantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/trips/{id}/participants', [TripParticipantController::class, 'store']);
    Route::patch('/trips/{id}/participants', [TripParticipantController::class, 'updatePermissions']);
    Route::delete('/trips/{id}/participants', [TripParticipantController::class, 'destroy']);

    Route::get('/users/pseudo/{pseudo}', [UserController::class, 'searchByUserPseudo']);
    Route::post('/users/friends/send', [UserController::class, 'sendFriendRequest']);
    Route::patch('/users/friends/action', [UserController::class, 'handleFriendRequest']);
    Route::delete('/users/friends/remove', [UserController::class, 'removeFriend']);

});
