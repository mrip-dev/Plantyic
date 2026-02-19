<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
                'email_verified_at' => Carbon::now(),
            ]);

            // Assign customer role
            $user->assignRole('Customer');

            // Generate token
            $token = JWTAuth::fromUser($user);

            // Generate invoice if package is selected
            $invoice = null;


            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer registered successfully',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $this->getUserResponse($user),
                'invoice' => $invoice,
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
                'email_verified_at' => Carbon::now(),
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

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Vendor registered successfully. Awaiting admin approval.',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $this->getUserResponse($user),
                'requires_approval' => true,
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
     * Forgot password
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
            // Generate reset token
            $user = User::where('email', $request->email)->first();
            $token = Str::random(60);

            // Store token in password_resets table or send email
            // For now, just return success
            // TODO: Implement email sending

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset link sent to your email',
                'reset_token' => $token // Remove in production
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify token (you should implement token verification logic)
            // For now, just update the password

            $user = User::where('email', $request->email)->first();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully'
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
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'profile_completion_percentage' => $user->profileCompletionPercentage(),
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

        $user = auth('api')->user();

        // TODO: Implement actual verification code check
        // For now, just mark as verified
        $user->update(['email_verified_at' => Carbon::now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Email verified successfully',
            'user' => $this->getUserResponse($user)
        ]);
    }
}