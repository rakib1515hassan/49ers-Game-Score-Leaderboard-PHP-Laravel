<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;




Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::prefix('teams')->group(function () {
    Route::get('/', [TeamController::class, 'index']); 
    Route::post('/', [TeamController::class, 'store']); 
    Route::get('/{id}', [TeamController::class, 'show']); 
    Route::put('/{id}', [TeamController::class, 'update']); 
    Route::delete('/{id}', [TeamController::class, 'destroy']); 
});

