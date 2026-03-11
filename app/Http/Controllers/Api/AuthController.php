<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Package;
use App\Services\OnboardingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Register a new customer
     */
    public function registerCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create customer user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'Customer',
                'user_type' => 'customer',
                'country' => $request->country,
                'state' => $request->state,
                'address' => $request->address,
                'is_approved' => true, // Customers are auto-approved
                'profile_completed' => true,
                'status' => 'active',
                // Do not auto-verify email; send verification OTP below
            ]);

            // Assign customer role
            $user->assignRole('Customer');

            // Generate token
            $token = JWTAuth::fromUser($user);

            // Generate invoice if package is selected
            $invoice = null;

            // Send email verification OTP
            try {
                // Generate 6-digit OTP
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expiresAt = Carbon::now()->addMinutes(10); // OTP valid for 10 minutes

                // Remove any existing verification OTPs for this email
                DB::table('email_verification_tokens')->where('email', $request->email)->delete();

                // Store OTP (hashed) for verification
                DB::table('email_verification_tokens')->insert([
                    'email' => $request->email,
                    'otp_hash' => Hash::make($otp),
                    'otp_expires_at' => $expiresAt,
                    'otp_verified' => false,
                    'token' => Str::random(60),
                    'created_at' => Carbon::now(),
                ]);

                Mail::send('emails.verify', ['otp' => $otp, 'user' => $user], function ($message) use ($request) {
                    $message->to($request->email)->subject('Verify your email address');
                });

                $verificationEmailSent = true;
            } catch (\Exception $mailException) {
                Log::error('Failed to send registration verification email: ' . $mailException->getMessage());
                // Remove OTP record if sending failed
                DB::table('email_verification_tokens')->where('email', $request->email)->delete();
                $verificationEmailSent = false;
            }


            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer registered successfully',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $this->getUserResponse($user),
                'invoice' => $invoice,
                'verification_email_sent' => isset($verificationEmailSent) ? $verificationEmailSent : false,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register a new vendor (individual or company)
     */
    public function registerVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'user_type' => 'required|in:vendor_individual,vendor_company',
            'vendor_type' => 'required|in:moving_company,transportation,packing_material,manpower,storage,local_moving,international_moving',
            'company_name' => 'required_if:user_type,vendor_company|nullable|string|max:255',
            'trade_license' => 'required_if:user_type,vendor_company|nullable|string|max:100',
            'emirates_id' => 'required_if:vendor_type,moving_company|nullable|string|max:50',
            'contact_person_name' => 'required_if:user_type,vendor_company|nullable|string|max:255',
            'contact_person_designation' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'service_area' => 'required|array',
            'service_area.*' => 'string',
            'address' => 'required|string|max:500',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create vendor user
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'Vendor',
                'user_type' => $request->user_type,
                'vendor_type' => $request->vendor_type,
                'company_name' => $request->company_name,
                'trade_license' => $request->trade_license,
                'emirates_id' => $request->emirates_id,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_designation' => $request->contact_person_designation,
                'country' => $request->country,
                'state' => $request->state,
                'service_area' => json_encode($request->service_area),
                'address' => $request->address,
                'is_approved' => false, // Vendors need admin approval
                'profile_completed' => true,
                'status' => 'pending',
                // Do not auto-verify email; send verification OTP below
            ];

            // Remove null values for optional fields
            $userData = array_filter($userData, function($value) {
                return !is_null($value);
            });

            $user = User::create($userData);

            // Assign vendor role
            $user->assignRole('Vendor');

            // Generate token (vendor can login but will see pending status)
            $token = JWTAuth::fromUser($user);

            // Send email verification OTP for vendor
            try {
                // Generate 6-digit OTP
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expiresAt = Carbon::now()->addMinutes(10); // OTP valid for 10 minutes

                // Remove any existing verification OTPs for this email
                DB::table('email_verification_tokens')->where('email', $request->email)->delete();

                // Store OTP (hashed) for verification
                DB::table('email_verification_tokens')->insert([
                    'email' => $request->email,
                    'otp_hash' => Hash::make($otp),
                    'otp_expires_at' => $expiresAt,
                    'otp_verified' => false,
                    'token' => Str::random(60),
                    'created_at' => Carbon::now(),
                ]);

                Mail::send('emails.verify', ['otp' => $otp, 'user' => $user], function ($message) use ($request) {
                    $message->to($request->email)->subject('Verify your email address');
                });

                $verificationEmailSent = true;
            } catch (\Exception $mailException) {
                Log::error('Failed to send vendor registration verification email: ' . $mailException->getMessage());
                // Remove OTP record if sending failed
                DB::table('email_verification_tokens')->where('email', $request->email)->delete();
                $verificationEmailSent = false;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Vendor registered successfully. Awaiting admin approval.',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $this->getUserResponse($user),
                'requires_approval' => true,
                'verification_email_sent' => isset($verificationEmailSent) ? $verificationEmailSent : false,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Vendor registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = auth('api')->user();

        // Check if user is active
        if ($user->status !== 'active') {
            if ($user->status === 'pending') {
                return response()->json([
                    'status' => 'pending',
                    'message' => 'Your account is pending approval',
                    'user' => $this->getUserResponse($user)
                ], 403);
            } elseif ($user->status === 'suspended') {
                return response()->json([
                    'status' => 'suspended',
                    'message' => 'Your account has been suspended',
                    'user' => $this->getUserResponse($user)
                ], 403);
            }
        }

        // Update last login
        $user->update(['last_login_at' => Carbon::now()]);

        return $this->respondWithToken($token);
    }

    /**
     * Get authenticated user profile
     */
    public function profile()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'user' => $this->getUserResponse($user)
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'country' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|max:100',
            'address' => 'sometimes|string|max:500',
            'company_name' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('profiles', 'public');
                $request->merge(['photo' => $path]);
            }

            // Update user
            $user->update($request->only([
                'name', 'phone', 'photo', 'country',
                'state', 'address', 'company_name', 'bio'
            ]));

            // Update profile completion status
            $user->update(['profile_completed' => true]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'user' => $this->getUserResponse($user)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profile update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        try {
            auth('api')->logout();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth('api')->refresh());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token refresh failed: ' . $e->getMessage()
            ], 401);
        }
    }

    /**
     * Forgot password - Send OTP to email
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            // Generate 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(10); // OTP valid for 10 minutes

            // Delete existing password reset record for this email
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Store OTP in password_reset_tokens table
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'otp' => Hash::make($otp),
                'otp_expires_at' => $expiresAt,
                'otp_verified' => false,
                'token' => Str::random(60),
                'created_at' => Carbon::now(),
            ]);

            try {
                Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($request) {
                    $message->to($request->email)->subject('Your Password Reset OTP');
                });
            } catch (\Exception $mailException) {
                // Log the email sending error
                Log::error('Failed to send OTP email: ' . $mailException->getMessage());
                // Remove OTP record if email sending fails so unusable tokens are not kept.
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send OTP email. Please try again.'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent to your email address',
                'otp_expires_in' => 600, // 10 minutes in seconds
                'otp' => $otp // Remove in production - only for testing
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP for password reset
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get password reset record
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No password reset request found for this email'
                ], 404);
            }

            // Check if OTP has expired
            if (Carbon::parse($resetRecord->otp_expires_at)->isPast()) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired. Please request a new one.'
                ], 410);
            }

            // Verify OTP
            if (!Hash::check($request->otp, $resetRecord->otp)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP'
                ], 401);
            }

            // Mark OTP as verified
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->update([
                    'otp_verified' => true,
                    'verified_at' => Carbon::now()
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully',

                'reset_token' => $resetRecord->token // Backward compatible key
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password after OTP verification
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify reset token and OTP verification status
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid reset token'
                ], 401);
            }

            if (!$resetRecord->otp_verified) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has not been verified. Please verify OTP first.'
                ], 403);
            }

            // Check if verification is still valid (24 hours)
            if (Carbon::parse($resetRecord->verified_at)->addHours(24)->isPast()) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Reset token has expired. Please request a new password reset.'
                ], 410);
            }

            // Update user password
            $user = User::where('email', $request->email)->first();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Delete password reset record
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully. You can now login with your new password.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password reset failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check vendor approval status
     */
    public function checkVendorStatus()
    {
        $user = auth('api')->user();

        if (!$user || !$user->isVendor()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'is_approved' => $user->is_approved,
            'status' => $user->status,
            'approved_at' => $user->approved_at,
            'message' => $user->is_approved ? 'Vendor is approved' : 'Vendor is pending approval'
        ]);
    }

    /**
     * Format token response
     */
    protected function respondWithToken($token)
    {
        $user = auth('api')->user();

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            // 'refresh_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $this->getUserResponse($user),
        ]);
    }

    /**
     * Format user response
     */
    protected function getUserResponse($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'user_type' => $user->user_type,
            'vendor_type' => $user->vendor_type,
            'company_name' => $user->company_name,
            'is_approved' => (bool) $user->is_approved,
            'profile_completed' => (bool) $user->profile_completed,
            'onboarding_completed' => (bool) $user->onboarding_completed,
            'onboarding_completed_at' => $user->onboarding_completed_at,
            'status' => $user->status,
            'country' => $user->country,
            'state' => $user->state,
            'service_area' => $user->service_area ? json_decode($user->service_area) : [],
            'address' => $user->address,
            'wallet_balance' => (float) $user->wallet_balance,
            'loyalty_points' => (int) $user->loyalty_points,
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
            'email_verified_at' => $user->email_verified_at,
            'approved_at' => $user->approved_at,
            'last_login_at' => $user->last_login_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'profile_completion_percentage' => $user->profileCompletionPercentage(),
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),

        ];
    }

    /**
     * Verify email with code
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get latest OTP record for this email
            $resetRecord = DB::table('email_verification_tokens')
                ->where('email', $user->email)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No verification request found for this email'
                ], 404);
            }

            // Check if OTP has expired
            if (isset($resetRecord->otp_expires_at) && Carbon::parse($resetRecord->otp_expires_at)->isPast()) {
                DB::table('email_verification_tokens')->where('email', $user->email)->delete();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Verification code has expired. Please request a new one.'
                ], 410);
            }

            // Verify OTP
            $storedHash = $resetRecord->otp_hash ?? ($resetRecord->otp ?? null);
            if (!Hash::check($request->verification_code, $storedHash)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid verification code'
                ], 401);
            }

            // Mark OTP as verified and update user
            DB::table('email_verification_tokens')
                ->where('email', $user->email)
                ->update([
                    'otp_verified' => true,
                    'verified_at' => Carbon::now()
                ]);

            $user->update(['email_verified_at' => Carbon::now()]);

            /// Delete OTP record after successful verification
            DB::table('email_verification_tokens')->where('email', $user->email)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Email verified successfully',
                'user' => $this->getUserResponse($user)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend email verification OTP
     */
    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $email = $request->email;
            $user = User::where('email', $email)->first();

            // Check rate-limit: prevent resending too frequently (60s)
            $last = DB::table('email_verification_tokens')
                ->where('email', $email)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last && isset($last->created_at) && Carbon::parse($last->created_at)->addSeconds(60)->isFuture()) {
                $retryAfter = Carbon::parse($last->created_at)->addSeconds(60)->diffInSeconds(Carbon::now());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many requests. Please wait before requesting another code.',
                    'retry_after_seconds' => $retryAfter
                ], 429);
            }

            // Generate and store new OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(10);

            DB::table('email_verification_tokens')->where('email', $email)->delete();
            DB::table('email_verification_tokens')->insert([
                'email' => $email,
                'otp_hash' => Hash::make($otp),
                'otp_expires_at' => $expiresAt,
                'otp_verified' => false,
                'token' => Str::random(60),
                'created_at' => Carbon::now(),
            ]);

            try {
                Mail::send('emails.verify', ['otp' => $otp, 'user' => $user], function ($message) use ($email) {
                    $message->to($email)->subject('Verify your email address');
                });
            } catch (\Exception $mailException) {
                Log::error('Failed to send verification email: ' . $mailException->getMessage());
                DB::table('email_verification_tokens')->where('email', $email)->delete();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send verification email. Please try again.'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Verification code resent',
                'otp_expires_in' => 600
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend verification code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete user onboarding with organization, workspace, and projects
     */
    public function completeOnboarding(Request $request, OnboardingService $onboardingService)
    {
        $validator = Validator::make($request->all(), [
            'organization.name' => 'required|string|max:255',
            'organization.description' => 'nullable|string|max:1000',
            'workspace.name' => 'required|string|max:255',
            'workspace.description' => 'nullable|string|max:1000',
            'workspace.icon' => 'nullable|string|max:100',
            'workspace.color' => 'nullable|string|max:50',
            'workspace.plan' => 'nullable|string|in:free,pro,enterprise',
            'project.name' => 'required|string|max:255',
            'project.description' => 'nullable|string|max:1000',
            'project.status' => 'nullable|string|in:active,inactive,archived',
            'onboarding.questions' => 'nullable|array',
            'onboarding.answers' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Complete onboarding using service
            $result = $onboardingService->completeOnboarding($user, $request->all());

            if ($result['status'] === 'error') {
                // Return 422 for duplicate/validation errors, 500 for other errors
                $statusCode = (strpos($result['message'], 'duplicate') !== false ||
                              strpos($result['message'], 'already exists') !== false) ? 422 : 500;
                return response()->json($result, $statusCode);
            }

            // Update user's onboarding status
            $user->update([
                'onboarding_completed' => true,
                'onboarding_completed_at' => Carbon::now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $result['message'],
                'data' => $result['data']
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred during onboarding. Please try again.'
            ], 500);
        }
    }
}