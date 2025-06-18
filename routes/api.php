<?php

use App\Http\Controllers\TripParticipantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/trips/{id}/participants', [TripParticipantController::class, 'store']);
    Route::patch('/trips/{id}/participants', [TripParticipantController::class, 'updatePermissions']);
    Route::delete('/trips/{id}/participants', [TripParticipantController::class, 'destroy']);
});

