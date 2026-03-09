# Password Reset Setup Guide

## Quick Start

The password reset OTP flow has been fully implemented with 3 API endpoints. Follow these steps to activate it:

## 1️⃣ Run Database Migration

Execute the new migration to update the `password_reset_tokens` table:

```bash
php artisan migrate
```

This will add these columns to `password_reset_tokens`:
- `otp` - Hashed OTP code
- `otp_expires_at` - OTP expiration timestamp
- `otp_verified` - Verification status
- `verified_at` - When OTP was verified

## 2️⃣ Configure Email Service (Important!)

The OTP is currently sent to the response for testing. For production, configure email sending:

**Update your `.env` file:**
```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io  # or your email service
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Plantyic"
```

**Create email template** (optional):
Create file: `resources/views/emails/otp.blade.php`
```blade
<h2>Password Reset OTP</h2>
<p>Your OTP is: <strong>{{ $otp }}</strong></p>
<p>This OTP is valid for 10 minutes.</p>
<p>If you didn't request this, please ignore this email.</p>
```

**Uncomment email sending** in AuthController.php `forgotPassword()` method:
```php
Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function($message) use ($request) {
    $message->to($request->email)->subject('Your Password Reset OTP');
});
```

## 3️⃣ Test the Endpoints

### Test Flow (using Postman or cURL)

**Step 1: Request OTP**
```bash
curl -X POST http://localhost/api/auth/forgot-password \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com"}'
```

Expected response:
```json
{
  "status": "success",
  "message": "OTP sent to your email address",
  "otp_expires_in": 600,
  "otp": "123456"  // Save this for next step
}
```

**Step 2: Verify OTP**
```bash
curl -X POST http://localhost/api/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "otp": "123456"}'
```

Expected response:
```json
{
  "status": "success",
  "message": "OTP verified successfully",
  "reset_token": "abcdef123456..."
}
```

**Step 3: Reset Password**
```bash
curl -X POST http://localhost/api/auth/reset-password \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123",
    "reset_token": "abcdef123456..."
  }'
```

Expected response:
```json
{
  "status": "success",
  "message": "Password reset successfully. You can now login with your new password."
}
```

## 4️⃣ Remove Testing OTP from Production

**IMPORTANT:** Before deploying to production, remove the OTP from the response:

In `AuthController.php`, `forgotPassword()` method, change:
```php
// FROM THIS:
return response()->json([
    'status' => 'success',
    'message' => 'OTP sent to your email address',
    'otp_expires_in' => 600,
    'otp' => $otp // ❌ REMOVE THIS
]);

// TO THIS:
return response()->json([
    'status' => 'success',
    'message' => 'OTP sent to your email address',
    'otp_expires_in' => 600
]);
```

## 5️⃣ Optional Enhancements

### Add Rate Limiting
Prevent abuse of OTP requests:

```php
// In routes/api.php
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->middleware('throttle:3,60');  // 3 requests per 60 minutes
```

### Add Resend OTP Functionality
Create new endpoint if users need to resend OTP:

```php
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])
    ->middleware('throttle:2,60');  // 2 resends per 60 minutes
```

### Add Audit Logging
Log password reset attempts:

```php
Log::info('Password reset requested', ['email' => $request->email]);
```

## 📋 Endpoint Summary

| Endpoint | Method | Purpose | Auth Required |
|----------|--------|---------|---|
| `/api/auth/forgot-password` | POST | Send OTP | ❌ No |
| `/api/auth/verify-otp` | POST | Verify OTP | ❌ No |
| `/api/auth/reset-password` | POST | Reset password | ❌ No |
| `/api/auth/login` | POST | Login with new password | ❌ No |

## 🔐 Security Features

✅ OTP hashing using Laravel's `Hash::make()`
✅ OTP expiration (10 minutes)
✅ Reset token expiration (24 hours after OTP verification)
✅ One-time use (records deleted after reset)
✅ Email validation before OTP generation
✅ Comprehensive error handling

## 📚 Resources

- **Full API Documentation:** [PASSWORD_RESET_FLOW.md](./PASSWORD_RESET_FLOW.md)
- **AuthController:** [app/Http/Controllers/Api/AuthController.php](./app/Http/Controllers/Api/AuthController.php)
- **Routes:** [routes/api.php](./routes/api.php)
- **Migration:** [database/migrations/2026_03_09_000000_update_password_reset_tokens_for_otp.php](./database/migrations/2026_03_09_000000_update_password_reset_tokens_for_otp.php)

## ❓ Troubleshooting

### OTP not being sent
- Check email configuration in `.env`
- Check Mail service credentials
- Ensure `Mail::send()` is uncommented in forgot-password method

### OTP expiration issues
- OTP expires 10 minutes after generation
- User must verify within this time
- If expired, user must request new OTP

### Reset token issues
- Reset token expires 24 hours after OTP verification
- User must complete password reset within this time

## 🚀 Deployment Checklist

- [ ] Run `php artisan migrate`
- [ ] Configure `.env` mail settings
- [ ] Create email template
- [ ] Uncomment email sending code
- [ ] Remove OTP from response
- [ ] Add rate limiting (optional)
- [ ] Test all endpoints
- [ ] Update frontend to use new endpoints
- [ ] Deploy to production
