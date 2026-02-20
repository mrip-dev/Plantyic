<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Workspace\WorkspaceController;

Route::prefix('workspace')->group(function () {
    Route::get('/', [WorkspaceController::class, 'index']);
    Route::post('/', [WorkspaceController::class, 'store']);
    Route::get('/{workspace}', [WorkspaceController::class, 'show']);
    Route::put('/{workspace}', [WorkspaceController::class, 'update']);
    Route::delete('/{workspace}', [WorkspaceController::class, 'destroy']);
});
