<?php

use Illuminate\Support\Facades\Route;


// Workspace module routes
require __DIR__.'/auth.php';
require __DIR__.'/workspace.php';
require __DIR__.'/settings.php';
require __DIR__.'/notifications.php';
require __DIR__.'/integrations.php';
require __DIR__.'/team.php';
require __DIR__.'/goals.php';
require __DIR__.'/notes.php';
require __DIR__.'/projects.php';
require __DIR__.'/tasks.php';
require __DIR__.'/push-notifications.php';
require __DIR__.'/organization.php';



// Health check route (optional)
Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Plantyic API is running',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Admin Panel Routes
Route::prefix('admin')->middleware(['middleware' => 'auth:api'])->group(function () {});
Route::group(['middleware' => 'auth:api'], function () {});
