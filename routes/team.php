<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Team\TeamController;

Route::middleware('auth:api')->prefix('team')->group(function () {
    Route::get('/', [TeamController::class, 'index']);
    Route::post('/', [TeamController::class, 'store']);
    Route::get('/{team}', [TeamController::class, 'show']);
    Route::put('/{team}', [TeamController::class, 'update']);
    Route::delete('/{team}', [TeamController::class, 'destroy']);

    Route::get('/{team}/members', [TeamController::class, 'listMembers']);
    Route::post('/{team}/members', [TeamController::class, 'addMember']);
    Route::put('/{team}/members/{memberId}', [TeamController::class, 'updateMember']);
    Route::delete('/{team}/members/{memberId}', [TeamController::class, 'removeMember']);

    Route::get('/{team}/projects', [TeamController::class, 'listProjects']);
    Route::post('/{team}/projects/{project}', [TeamController::class, 'assignProject']);
    Route::delete('/{team}/projects/{project}', [TeamController::class, 'unassignProject']);
});
