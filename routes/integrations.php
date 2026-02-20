<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Integrations\IntegrationController;

Route::prefix('integrations')->group(function () {
    Route::get('/', [IntegrationController::class, 'index']);
    Route::post('/', [IntegrationController::class, 'store']);
    Route::get('/{integration}', [IntegrationController::class, 'show']);
    Route::put('/{integration}', [IntegrationController::class, 'update']);
    Route::delete('/{integration}', [IntegrationController::class, 'destroy']);
});
