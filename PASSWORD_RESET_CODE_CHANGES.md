# Code Changes Summary

## Files Modified/Created

### 1. New Migration File
**File:** `database/migrations/2026_03_09_000000_update_password_reset_tokens_for_otp.php`

**Changes:** Added OTP-related columns to `password_reset_tokens` table

```php
// New columns added:
$table->string('otp')->nullable()->after('token');
$table->timestamp('otp_expires_at')->nullable()->after('otp');
$table->boolean('otp_verified')->default(false)->after('otp_expires_at');
$table->timestamp('verified_at')->nullable()->after('otp_verified');
```

---

### 2. Updated AuthController
**File:** `app/Http/Controllers/Api/AuthController.php`

#### Method 1: `forgotPassword()` - UPDATED
**Purpose:** Generate and send OTP

**Key changes:**
- Generates 6-digit OTP using `random_int()`
- Sets expiration to 10 minutes from now
- Hashes OTP using `Hash::make()`
- Stores in `password_reset_tokens` table
- Deletes any previous reset records for same email
- Returns OTP in response (remove in production)

```php
public function forgotPassword(Request $request)
{
    // Generate 6-digit OTP
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiresAt = Carbon::now()->addMinutes(10);

    // Delete existing password reset record
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    // Store OTP
    DB::table('password_reset_tokens')->insert([
        'email' => $request->email,
        'otp' => Hash::make($otp),
        'otp_expires_at' => $expiresAt,
        'otp_verified' => false,
        'token' => Str::random(60),
        'created_at' => Carbon::now(),
    ]);

    // TODO: Send OTP via email
}
```

#### Method 2: `verifyOtp()` - NEW
**Purpose:** Validate OTP and prepare for password reset

**Key steps:**
1. Validates email and OTP format
2. Retrieves password reset record
3. Checks OTP hasn't expired
4. Verifies OTP hash using `Hash::check()`
5. Marks OTP as verified
6. Returns reset_token for next step

```php
public function verifyOtp(Request $request)
{
    // Validate request
    // Get password reset record
    // Check OTP expiration
    if (Carbon::parse($resetRecord->otp_expires_at)->isPast()) {
        // OTP expired
    }

    // Verify OTP hash
    if (!Hash::check($request->otp, $resetRecord->otp)) {
        // Invalid OTP
    }

    // Mark as verified
    DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->update([
            'otp_verified' => true,
            'verified_at' => Carbon::now()
        ]);
}
```

#### Method 3: `resetPassword()` - UPDATED
**Purpose:** Update password after OTP verification

**Key changes:**
- Now requires `reset_token` in addition to email and password
- Checks that OTP has been verified first
- Validates reset token hasn't expired (24 hours)
- Hashes new password
- Deletes password reset record after successful reset

```php
public function resetPassword(Request $request)
{
    // Validation includes reset_token

    // Verify reset token and OTP status
    if (!$resetRecord->otp_verified) {
        // OTP not verified
    }

    // Check token validity (24 hours)
    if (Carbon::parse($resetRecord->verified_at)->addHours(24)->isPast()) {
        // Token expired
    }

    // Update password
    $user->update(['password' => Hash::make($request->password)]);

    // Delete password reset record
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();
}
```

---

### 3. Updated Routes
**File:** `routes/api.php`

**Change:** Added new route to public auth routes

```php
Route::prefix('auth')->group(function () {
    // Public routes
    Route::post('/register/customer', [AuthController::class, 'registerCustomer']);
    Route::post('/register/vendor', [AuthController::class, 'registerVendor']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']); // ← NEW
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // ... protected routes
});
```

---

## Database Schema Changes

### password_reset_tokens Table

**Before:**
| Column | Type |
|--------|------|
| email | string (PK) |
| token | string |
| created_at | timestamp |

**After:**
| Column | Type | Purpose |
|--------|------|---------|
| email | string (PK) | User's email |
| token | string | Reset validation token |
| otp | string | Hashed 6-digit OTP |
| otp_expires_at | timestamp | OTP expiration time |
| otp_verified | boolean | Verification status |
| verified_at | timestamp | OTP verification timestamp |
| created_at | timestamp | Record creation time |

---

## Request/Response Examples

### Forgot Password Request
```json
{
  "email": "user@example.com"
}
```

### Forgot Password Response
```json
{
  "status": "success",
  "message": "OTP sent to your email address",
  "otp_expires_in": 600,
  "otp": "123456"
}
```

### Verify OTP Request
```json
{
  "email": "user@example.com",
  "otp": "123456"
}
```

### Verify OTP Response
```json
{
  "status": "success",
  "message": "OTP verified successfully",
  "reset_token": "abcdef123456..."
}
```

### Reset Password Request
```json
{
  "email": "user@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123",
  "reset_token": "abcdef123456..."
}
```

### Reset Password Response
```json
{
  "status": "success",
  "message": "Password reset successfully. You can now login with your new password."
}
```

---

## Error Handling

| Scenario | Status Code | Message |
|----------|------------|---------|
| Invalid email format | 422 | Validation failed |
| Email not registered | 422 | Email doesn't exist |
| No reset request | 404 | No password reset request found |
| Invalid OTP | 401 | Invalid OTP |
| OTP expired | 410 | OTP has expired |
| Invalid reset token | 401 | Invalid reset token |
| OTP not verified | 403 | OTP has not been verified |
| Token expired | 410 | Reset token has expired |
| Server error | 500 | Specific error message |

---

## Important Notes

1. **OTP Format:** 6-digit numeric string
2. **OTP Expiration:** 10 minutes
3. **Token Expiration:** 24 hours after OTP verification
4. **OTP Hashing:** Hashed using `Hash::make()` for security
5. **One-time Use:** Password reset records deleted after successful reset
6. **Email Sending:** TODO - Configure in `.env` and uncomment in code
7. **Production:** Remove OTP from response before deploying

---

## Testing Checklist

- [ ] OTP generated correctly (6 digits)
- [ ] OTP stored hashed in database
- [ ] OTP expires after 10 minutes
- [ ] Valid OTP verifies successfully
- [ ] Invalid OTP rejected
- [ ] Reset token returned after OTP verification
- [ ] Password changed successfully with valid reset token
- [ ] Invalid reset token rejected
- [ ] Old password reset records cleaned up
- [ ] User can login with new password
- [ ] All error responses return correct status codes

---

## Files Created for Documentation

1. `PASSWORD_RESET_FLOW.md` - Complete API documentation
2. `PASSWORD_RESET_SETUP.md` - Setup and configuration guide
3. This file - Code changes summary
