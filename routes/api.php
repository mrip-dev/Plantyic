<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Workspace module routes
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

// Authentication Routes
Route::prefix('auth')->group(function () {
    // Public routes
    Route::post('/register/customer', [AuthController::class, 'registerCustomer']);
    Route::post('/register/vendor', [AuthController::class, 'registerVendor']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Protected routes (require authentication)
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
        Route::get('/vendor/status', [AuthController::class, 'checkVendorStatus']);
    });
});

// Health check route (optional)
Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Plantyic API is running',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

// Admin Panel Routes
Route::prefix('admin')->middleware(['middleware' => 'auth:api'])->group(function () {});
Route::group(['middleware' => 'auth:api'], function () {});
