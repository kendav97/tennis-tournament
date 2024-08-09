<?php

use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')
    ->group(function () {
        Route::get('participants', [ParticipantController::class, 'index']);
        Route::post('participant', [ParticipantController::class, 'create']);
        Route::post('participants/seed', [ParticipantController::class, 'requestSeed']);
        Route::post('participants/clear', [ParticipantController::class, 'clear']);
        
        
        Route::post('game/play', [ParticipantController::class, 'requestPlay']);
        Route::post('game/replay', [ParticipantController::class, 'requestReplay']);
        Route::post('game/reset', [ParticipantController::class, 'reset']);
    });