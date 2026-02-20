<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Notifications\NotificationController;

Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/', [NotificationController::class, 'store']);
    Route::get('/{notification}', [NotificationController::class, 'show']);
    Route::put('/{notification}', [NotificationController::class, 'update']);
    Route::delete('/{notification}', [NotificationController::class, 'destroy']);
});
