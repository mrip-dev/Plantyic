<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Authentication Routes
Route::prefix('auth')->group(function () {
    // Public routes
    Route::post('/register/customer', [AuthController::class, 'registerCustomer']);
    Route::post('/register/vendor', [AuthController::class, 'registerVendor']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Protected routes (require authentication)
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);
        Route::post('/update-password', [AuthController::class, 'updatePassword']);
        Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
        Route::get('/vendor/status', [AuthController::class, 'checkVendorStatus']);
        Route::post('/onboarding/complete', [AuthController::class, 'completeOnboarding']);

        // Account deletion routes
        Route::delete('/account', [AuthController::class, 'deleteMyAccount']);
        Route::delete('/users/{id}', [AuthController::class, 'deleteUser']);
    });
});
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
