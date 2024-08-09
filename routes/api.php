<?php

use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')
    ->group(function () {
        Route::get('participants', [ParticipantController::class, 'index']);
        Route::post('participants', [ParticipantController::class, 'create']);
        Route::post('participants/seed', [ParticipantController::class, 'seed']);
        Route::post('participants/clear', [ParticipantController::class, 'clear']);
        
        
        Route::post('game/play', [GameController::class, 'play']);
        Route::post('game/replay', [GameController::class, 'replay']);
        Route::post('game/reset', [GameController::class, 'reset']);
    });