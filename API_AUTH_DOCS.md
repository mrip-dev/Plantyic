# Plantyic API Authentication Endpoints

This documentation covers the main authentication endpoints for Plantyic API. Use these endpoints in Postman, React, or any frontend client.

**Base URL:** `/api/auth`

---

## Register Customer
- **POST** `/api/auth/register/customer`
- **Body (JSON):**
  - `name` (string, required)
  - `email` (string, required, unique)
  - `password` (string, required, min:6, confirmed)
  - `password_confirmation` (string, required)
  - `phone` (string, required)
  - `country` (string, optional)
  - `state` (string, optional)
  - `address` (string, optional)
- **Response:**
  - `access_token`, `user`, `invoice`, etc.

---

## Register Vendor
- **POST** `/api/auth/register/vendor`
- **Body (JSON):**
  - `name` (string, required)
  - `email` (string, required, unique)
  - `password` (string, required, min:6, confirmed)
  - `password_confirmation` (string, required)
  - `phone` (string, required)
  - `user_type` (string, required, one of: vendor_individual, vendor_company)
  - `vendor_type` (string, required, e.g. moving_company, transportation, etc.)
  - `company_name` (string, required if user_type is vendor_company)
  - `trade_license` (string, required if user_type is vendor_company)
  - `emirates_id` (string, required if vendor_type is moving_company)
  - `contact_person_name` (string, required if user_type is vendor_company)
  - `contact_person_designation` (string, optional)
  - `country` (string, required)
  - `state` (string, required)
  - `service_area` (array of strings, required)
  - `address` (string, required)
- **Response:**
  - `access_token`, `user`, `requires_approval`, etc.

---

## Login
- **POST** `/api/auth/login`
- **Body (JSON):**
  - `email` (string, required)
  - `password` (string, required)
- **Response:**
  - `access_token`, `user`, etc.

---

## Forgot Password
- **POST** `/api/auth/forgot-password`
- **Body (JSON):**
  - `email` (string, required)
- **Response:**
  - `reset_token` (for dev/testing), message

---

## Reset Password
- **POST** `/api/auth/reset-password`
- **Body (JSON):**
  - `token` (string, required)
  - `email` (string, required)
  - `password` (string, required, min:6, confirmed)
  - `password_confirmation` (string, required)
- **Response:**
  - Success message

---

## Refresh Token
- **POST** `/api/auth/refresh`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Response:**
  - New `access_token`, `user`, etc.

---

## Logout
- **POST** `/api/auth/logout`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Response:**
  - Success message

---

## Get Profile
- **GET** `/api/auth/profile`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Response:**
  - `user` object

---

## Update Profile
- **PUT** `/api/auth/profile`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Body (JSON or multipart):**
  - Any updatable user fields (see registration)
- **Response:**
  - Updated `user` object

---

## Verify Email
- **POST** `/api/auth/verify-email`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Body (JSON):**
  - `verification_code` (string, required, 6 chars)
- **Response:**
  - Success message, updated `user`

---

## Check Vendor Status
- **GET** `/api/auth/vendor/status`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Response:**
  - `is_approved`, `status`, etc.

---

## Health Check
- **GET** `/api/health`
- **Response:**
  - API status, version, timestamp

---

### Notes
- All protected endpoints require `Authorization: Bearer <token>` header.
- Validation errors return 422 with error details.
- All responses are JSON.

---

For more endpoints, see `/api/docs` if available or contact backend team.