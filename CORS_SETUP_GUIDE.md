# CORS Configuration - Testing Guide

## ✅ CORS Configuration Applied

I've configured CORS for your application to allow requests from `http://localhost:3001`.

### Files Updated/Created

1. **Created**: `config/cors.php` - CORS configuration file
   - Allows requests from `localhost:3001`, `localhost:3000`, `localhost:5173`, etc.
   - Supports credentials (Authorization headers)
   - Uses pattern matching for localhost on any port

2. **Updated**: `bootstrap/app.php` - Registered CORS middleware
   - Added `HandleCors` middleware to API routes

---

## 🔧 Configuration Details

### Allowed Origins
```
✓ http://localhost:3001      (Your frontend)
✓ http://localhost:3000      (Alternative port)
✓ http://localhost:5173      (Vite default)
✓ http://localhost:8000      (Laravel dev server)
✓ http://127.0.0.1:xxxx      (IPv4 localhost on any port)
```

### Pattern Matching
- Automatically allows any request from `localhost` on any port (4-digit)
- Regex pattern: `/http:\/\/localhost:\d+/`

---

## 🚀 How to Test

### Step 1: Clear Cache
```bash
php artisan config:cache
php artisan cache:clear
```

### Step 2: Test Login Endpoint with cURL
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Origin: http://localhost:3001" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

You should see these response headers:
```
access-control-allow-origin: http://localhost:3001
access-control-allow-credentials: true
access-control-allow-methods: GET, HEAD, PUT, PATCH, POST, DELETE, OPTIONS
access-control-allow-headers: origin, content-type, accept, authorization
```

### Step 3: Test from Browser (Frontend)
```javascript
// In your React/Vue/Angular frontend at http://localhost:3001
fetch('http://localhost:8000/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  credentials: 'include', // Important for CORS with credentials
  body: JSON.stringify({
    email: 'user@example.com',
    password: 'password123'
  })
})
.then(response => response.json())
.then(data => console.log('Success:', data))
.catch(error => console.error('Error:', error));
```

---

## 📋 Troubleshooting

### Issue 1: Still Getting CORS Error
**Solution**:
1. Make sure your frontend is actually running on `http://localhost:3001`
2. Clear browser cache (Ctrl+Shift+Delete or Cmd+Shift+Delete)
3. Restart Laravel dev server
4. Check console for exact origin being blocked

### Issue 2: CORS Works for Some Endpoints but Not Others
**Solution**:
- Verify endpoints are in the API routes (`routes/api.php`)
- Check if middleware is preventing the request
- Look at `config/cors.php` - it only applies to `api/*` paths

### Issue 3: OPTIONS Preflight Request Failing
**Solution**:
- This is normal - browser sends OPTIONS before actual request
- CORS middleware should handle automatically
- If still failing, add to `config/cors.php`: `'allowed_methods' => ['*']` (already done)

---

## 🔍 Verify CORS is Working

### Check Response Headers
Open Browser DevTools (F12) → Network tab → Click on API request → Headers tab

Look for these headers in the **Response Headers**:
```
Access-Control-Allow-Origin: http://localhost:3001
Access-Control-Allow-Credentials: true
Access-Control-Allow-Methods: GET, HEAD, PUT, PATCH, POST, DELETE, OPTIONS
Access-Control-Allow-Headers: origin, content-type, accept, authorization
```

### If You See This Error
```
Access to XMLHttpRequest at 'http://localhost:8000/api/auth/login'
from origin 'http://localhost:3001' has been blocked by CORS policy
```

**Solution**:
1. Verify `config/cors.php` includes `http://localhost:3001`
2. Run `php artisan config:cache`
3. Restart Laravel server
4. Clear browser cache

---

## 🛠️ Advanced: Custom CORS for Production

When deploying to production, update `config/cors.php`:

```php
'allowed_origins' => env('CORS_ALLOWED_ORIGINS', [
    'http://localhost:3001',
    'http://localhost:3000',
]),
```

Then in `.env`:
```
CORS_ALLOWED_ORIGINS=https://yourdomain.com,https://app.yourdomain.com
```

---

## 📝 CORS Configuration File Location

The file is located at:
```
config/cors.php
```

Contents:
```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3001',  // ← Your frontend
        'http://localhost:3000',
        // ... more origins
    ],
    'allowed_headers' => ['*'],
    'supports_credentials' => true, // Important for JWT Auth
];
```

---

## ✅ Checklist

- [ ] `config/cors.php` created with `localhost:3001` in allowed_origins
- [ ] `bootstrap/app.php` updated with CORS middleware
- [ ] Run `php artisan config:cache`
- [ ] Restart Laravel dev server
- [ ] Clear browser cache
- [ ] Test login endpoint from http://localhost:3001
- [ ] Check browser DevTools for CORS headers in response

---

## 🚀 Next Steps

1. **Test the endpoint**: Run the cURL command above
2. **Try from frontend**: Use the JavaScript fetch example
3. **Verify headers**: Check browser DevTools Network tab
4. **Deploy**: Update `config/cors.php` for production URLs

---

## 💡 Common Frontend Fixes

If still having issues in your frontend code:

### React with Fetch
```javascript
const response = await fetch('http://localhost:8000/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  credentials: 'include', // Send cookies/auth headers
  body: JSON.stringify({ email, password })
});
```

### Axios
```javascript
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withCredentials = true; // Important!

axios.post('/api/auth/login', { email, password })
  .then(res => console.log(res.data))
  .catch(err => console.error(err));
```

### Vue 3
```javascript
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true
```

---

## 📞 Need Help?

If CORS is still not working:
1. Check `config/cors.php` has your frontend origin
2. Run `php artisan config:cache`
3. Restart Laravel server (kill and re-run)
4. Check `.env` for any conflicting settings
5. Look at Laravel logs: `storage/logs/laravel.log`

---

**Last Updated**: March 2, 2026
**CORS Status**: ✅ Configured for localhost:3001
