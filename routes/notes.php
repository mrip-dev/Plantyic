<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Notes\NoteController;

Route::prefix('notes')->group(function () {
    Route::get('/', [NoteController::class, 'index']);
    Route::post('/', [NoteController::class, 'store']);
    Route::get('/{note}', [NoteController::class, 'show']);
    Route::put('/{note}', [NoteController::class, 'update']);
    Route::delete('/{note}', [NoteController::class, 'destroy']);
});
