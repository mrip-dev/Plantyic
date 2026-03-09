# Password Reset Flow - OTP Based

This document describes the complete forgot password flow with OTP verification.

## Endpoints

### 1. Forgot Password (Send OTP)
**POST** `/api/auth/forgot-password`

**Purpose:** Send OTP to user's registered email address

**Request Body:**
```json
{
  "email": "user@example.com"
}
```

**Validations:**
- `email` - Required, must be a valid email format, must exist in users table

**Success Response (200):**
```json
{
  "status": "success",
  "message": "OTP sent to your email address",
  "otp_expires_in": 600,
  "otp": "123456"  // Remove in production - only for testing
}
```

**Error Response (422 - Validation Error):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

**Error Response (500 - Server Error):**
```json
{
  "status": "error",
  "message": "Failed to process request: [error details]"
}
```

**Notes:**
- OTP is valid for 10 minutes
- OTP is a 6-digit number sent to user's email
- Previous reset requests for the same email are deleted
- TODO: Configure email sending in `.env` and mail configuration

---

### 2. Verify OTP
**POST** `/api/auth/verify-otp`

**Purpose:** Verify the OTP received in email before resetting password

**Request Body:**
```json
{
  "email": "user@example.com",
  "otp": "123456"
}
```

**Validations:**
- `email` - Required, must be a valid email format, must exist in users table
- `otp` - Required, must be exactly 6 characters

**Success Response (200):**
```json
{
  "status": "success",
  "message": "OTP verified successfully",
  "reset_token": "abcdef123456..."
}
```

**Error Response (422 - Validation Error):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "otp": ["The otp must be 6 characters."]
  }
}
```

**Error Response (404 - Not Found):**
```json
{
  "status": "error",
  "message": "No password reset request found for this email"
}
```

**Error Response (401 - Unauthorized):**
```json
{
  "status": "error",
  "message": "Invalid OTP"
}
```

**Error Response (410 - Gone):**
```json
{
  "status": "error",
  "message": "OTP has expired. Please request a new one."
}
```

**Notes:**
- Returns `reset_token` needed for the next step
- OTP is verified against hashed value in database
- Marks OTP as verified and records verification timestamp

---

### 3. Reset Password
**POST** `/api/auth/reset-password`

**Purpose:** Update user password after OTP verification

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123",
  "reset_token": "abcdef123456..."
}
```

**Validations:**
- `email` - Required, must be a valid email format, must exist in users table
- `password` - Required, minimum 6 characters, must match `password_confirmation`
- `password_confirmation` - Required, must match `password`
- `reset_token` - Required, must match token from verify-otp response

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Password reset successfully. You can now login with your new password."
}
```

**Error Response (422 - Validation Error):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "password": ["The password must be at least 6 characters."]
  }
}
```

**Error Response (401 - Unauthorized):**
```json
{
  "status": "error",
  "message": "Invalid reset token"
}
```

**Error Response (403 - Forbidden):**
```json
{
  "status": "error",
  "message": "OTP has not been verified. Please verify OTP first."
}
```

**Error Response (410 - Gone):**
```json
{
  "status": "error",
  "message": "Reset token has expired. Please request a new password reset."
}
```

**Notes:**
- Reset token valid for 24 hours after OTP verification
- Password reset record is deleted after successful password reset
- User can immediately login with new password

---

## Complete Flow Example

### Step 1: User requests password reset
```
POST /api/auth/forgot-password
Content-Type: application/json

{
  "email": "john@example.com"
}

Response:
{
  "status": "success",
  "message": "OTP sent to your email address",
  "otp_expires_in": 600,
  "otp": "123456"  // Save this for testing
}
```

### Step 2: User receives OTP in email, enters and verifies it
```
POST /api/auth/verify-otp
Content-Type: application/json

{
  "email": "john@example.com",
  "otp": "123456"
}

Response:
{
  "status": "success",
  "message": "OTP verified successfully",
  "reset_token": "token_abcdef123456..."  // Save this for next step
}
```

### Step 3: User resets password
```
POST /api/auth/reset-password
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "newsecurepassword",
  "password_confirmation": "newsecurepassword",
  "reset_token": "token_abcdef123456..."
}

Response:
{
  "status": "success",
  "message": "Password reset successfully. You can now login with your new password."
}
```

### Step 4: User can now login with new password
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "newsecurepassword"
}

Response:
{
  "status": "success",
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { ... }
}
```

---

## Database Schema

### password_reset_tokens Table

| Column | Type | Description |
|--------|------|-------------|
| email | string | User's email (primary key) |
| token | string | Reset token for validation |
| otp | string | Hashed OTP code |
| otp_expires_at | timestamp | When OTP expires |
| otp_verified | boolean | Whether OTP has been verified |
| verified_at | timestamp | When OTP was verified |
| created_at | timestamp | Record creation time |

---

## Security Considerations

1. **OTP Hashing:** OTPs are hashed using `Hash::make()` before storage
2. **OTP Expiration:** OTPs expire after 10 minutes
3. **Token Expiration:** Reset tokens expire 24 hours after OTP verification
4. **One-time Use:** Password reset records are deleted after successful reset
5. **Email Verification:** Only users with registered emails can initiate reset
6. **Rate Limiting:** Consider implementing rate limiting on forgot-password endpoint
7. **Audit Logging:** Consider logging password reset attempts for security

---

## TODO Items

- [ ] Implement email sending for OTP (see Mail configuration)
- [ ] Add rate limiting to prevent OTP spam
- [ ] Add audit logging for password reset attempts
- [ ] Implement email templates for OTP
- [ ] Add RESEND OTP functionality if needed
- [ ] Consider adding SMS OTP as alternative

---

## Configuration

### Environment Variables Needed
```
MAIL_DRIVER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Plantyic"
```

---

## Testing

### Test with Valid Flow
1. POST to forget-password with valid email
2. Copy OTP from response (if not in production)
3. POST to verify-otp with email and OTP
4. Use returned reset_token with new password
5. POST to reset-password
6. Try logging in with new password

### Test Error Scenarios
- Invalid email format
- Email not registered
- Invalid OTP
- Expired OTP
- Invalid reset token
- Password mismatch
- Password too short
