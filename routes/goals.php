<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Goals\GoalController;

Route::prefix('goals')->group(function () {
    Route::get('/', [GoalController::class, 'index']);
    Route::post('/', [GoalController::class, 'store']);
    Route::get('/{goal}', [GoalController::class, 'show']);
    Route::put('/{goal}', [GoalController::class, 'update']);
    Route::delete('/{goal}', [GoalController::class, 'destroy']);
});
