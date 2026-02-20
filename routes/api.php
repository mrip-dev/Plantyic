<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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
