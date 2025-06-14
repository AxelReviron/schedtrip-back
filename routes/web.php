<?php

use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('web');;
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
