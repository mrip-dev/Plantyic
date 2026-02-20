<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantyic API Health Check</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #BA1A1A 0%, #1e293b 100%); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center text-slate-200">
    <div class="max-w-lg w-full p-8">
        <header class="text-center mb-10">
            <div class="inline-block p-3 bg-[#BA1A1A] rounded-2xl mb-4 shadow-lg shadow-red-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">API <span class="text-[#BA1A1A]">Health Check</span></h1>
        </header>
        <div class="bg-slate-800/50 border border-slate-700 rounded-3xl p-6 backdrop-blur-sm shadow-xl text-center">
            <div class="flex flex-col items-center gap-2 mb-4">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="font-semibold uppercase tracking-wider text-xs text-green-400">System Operational</span>
            </div>
            <p class="text-slate-300 mb-2">Plantyic API is running and healthy.</p>
            <div class="text-xs text-slate-400 mb-2">Version: <span class="font-mono">1.0.4</span></div>
            <div class="text-xs text-slate-400">Timestamp: <span class="font-mono">{{ date('Y-m-d H:i:s') }}</span></div>
        </div>
        <footer class="mt-12 text-center">
            <p class="text-slate-500 text-sm">
                &copy; 2026 Plantyic Technologies. All rights reserved.
            </p>
        </footer>
    </div>
</body>
</html>
