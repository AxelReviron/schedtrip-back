<?php

use App\Http\Controllers\SearchUserByPseudo;
use App\Http\Controllers\TripParticipantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/trips/{id}/participants', [TripParticipantController::class, 'store']);
    Route::patch('/trips/{id}/participants', [TripParticipantController::class, 'updatePermissions']);
    Route::delete('/trips/{id}/participants', [TripParticipantController::class, 'destroy']);

    Route::get('/users/pseudo/{pseudo}', [SearchUserByPseudo::class, 'search']);
});

