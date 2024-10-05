<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});


Route::prefix('teams')->middleware('auth:api')->group(function () {
    Route::get('/', [TeamController::class, 'index']); 
    Route::post('/', [TeamController::class, 'store']); 
    Route::get('/{id}', [TeamController::class, 'show']); 
    Route::put('/{id}', [TeamController::class, 'update']); 
    Route::delete('/{id}', [TeamController::class, 'destroy']); 
});



Route::prefix('players')->middleware('auth:api')->group(function () {
    Route::get('/', [PlayerController::class, 'index']);
    Route::post('/', [PlayerController::class, 'store']);
    Route::get('/{id}', [PlayerController::class, 'show']);
    Route::put('/{id}', [PlayerController::class, 'update']);
    Route::delete('/{id}', [PlayerController::class, 'destroy']);
});


Route::prefix('games')->middleware('auth:api')->group(function () {
    Route::get('/', [GameController::class, 'index']);
    Route::get('/{id}', [GameController::class, 'show']);
    Route::post('/', [GameController::class, 'store']);
    Route::put('/{id}', [GameController::class, 'update']);
    Route::delete('/{id}', [GameController::class, 'destroy']);
});
