<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantyic API Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #BA1A1A 0%, #1e293b 100%); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center text-slate-200">
    <div class="max-w-3xl w-full p-8">
        <header class="text-center mb-10">
            <div class="inline-block p-3 bg-[#BA1A1A] rounded-2xl mb-4 shadow-lg shadow-red-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Plantyic <span class="text-[#BA1A1A]">API Docs</span></h1>
            <p class="mt-3 text-slate-400 text-lg">Comprehensive guide to Plantyic API endpoints for developers.</p>
        </header>
        <div class="bg-slate-800/50 border border-slate-700 rounded-3xl p-6 backdrop-blur-sm shadow-xl">
            <h2 class="text-2xl font-bold mb-4 text-[#BA1A1A]">Authentication Endpoints</h2>
            <ul class="space-y-2 text-slate-300 text-base">
                <li><span class="font-semibold">POST</span> <span class="text-white">/api/auth/register/customer</span> — Register a new customer</li>
                <li><span class="font-semibold">POST</span> <span class="text-white">/api/auth/register/vendor</span> — Register a new vendor</li>
                <li><span class="font-semibold">POST</span> <span class="text-white">/api/auth/login</span> — Login</li>
                <li><span class="font-semibold">POST</span> <span class="text-white">/api/auth/forgot-password</span> — Forgot password</li>
                <li><span class="font-semibold">POST</span> <span class="text-white">/api/auth/reset-password</span> — Reset password</li>
                <li><span class="font-semibold">POST</span> <span class="text-white">/api/auth/refresh</span> — Refresh JWT token</li>
                <li><span class="font-semibold">POST</span> <span class="text-white">/api/auth/logout</span> — Logout</li>
                <li><span class="font-semibold">GET</span> <span class="text-white">/api/auth/profile</span> — Get profile</li>
                <li><span class="font-semibold">PUT</span> <span class="text-white">/api/auth/profile</span> — Update profile</li>
                <li><span class="font-semibold">POST</span> <span class="text-white">/api/auth/verify-email</span> — Verify email</li>
                <li><span class="font-semibold">GET</span> <span class="text-white">/api/auth/vendor/status</span> — Check vendor status</li>
            </ul>
            <h2 class="text-2xl font-bold mt-8 mb-4 text-[#BA1A1A]">Other Endpoints</h2>
            <ul class="space-y-2 text-slate-300 text-base">
                <li><span class="font-semibold">GET</span> <span class="text-white">/api/health</span> — API health check</li>
            </ul>
            <div class="mt-8 text-xs text-slate-400">
                <p>All protected endpoints require <span class="font-mono">Authorization: Bearer &lt;token&gt;</span> header.</p>
                <p>All responses are JSON. Validation errors return 422 with error details.</p>
            </div>
        </div>
        <footer class="mt-12 text-center">
            <p class="text-slate-500 text-sm">
                &copy; 2026 Plantyic Technologies. All rights reserved.
            </p>
        </footer>
    </div>
</body>
</html>
