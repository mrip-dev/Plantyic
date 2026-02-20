<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Team\TeamController;

Route::prefix('team')->group(function () {
    Route::get('/', [TeamController::class, 'index']);
    Route::post('/', [TeamController::class, 'store']);
    Route::get('/{team}', [TeamController::class, 'show']);
    Route::put('/{team}', [TeamController::class, 'update']);
    Route::delete('/{team}', [TeamController::class, 'destroy']);
});
