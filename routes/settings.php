<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\SettingsController;

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingsController::class, 'index']);
    Route::post('/', [SettingsController::class, 'store']);
    Route::get('/{settings}', [SettingsController::class, 'show']);
    Route::put('/{settings}', [SettingsController::class, 'update']);
    Route::delete('/{settings}', [SettingsController::class, 'destroy']);
});
