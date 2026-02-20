<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PushNotificationsController;

Route::middleware('auth:api')->group(function () {
    Route::get('/push-notifications', [PushNotificationsController::class, 'index']);
});
