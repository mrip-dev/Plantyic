<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zentra API | Gateway</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center text-slate-200">

    <div class="max-w-2xl w-full p-8">
        <header class="text-center mb-10">
            <div class="inline-block p-3 bg-blue-600 rounded-2xl mb-4 shadow-lg shadow-blue-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Zentra <span class="text-blue-400">API</span></h1>
            <p class="mt-3 text-slate-400 text-lg">The robust backbone powering the Zentra ecosystem.</p>
        </header>

        <div class="bg-slate-800/50 border border-slate-700 rounded-3xl p-6 backdrop-blur-sm shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <span class="flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="font-semibold uppercase tracking-wider text-xs text-green-400">System Operational</span>
                </span>
                <span class="text-xs text-slate-500 font-mono">v1.0.4</span>
            </div>

            <div class="space-y-4">
                <p class="text-slate-300">Welcome to the Zentra API Gateway. This environment is restricted to authorized applications and developers.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                    <a href="/docs" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                        View Documentation
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                    <a href="/health" class="flex items-center justify-center gap-2 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                        Health Check
                    </a>
                </div>
            </div>
        </div>

        <footer class="mt-12 text-center">
            <p class="text-slate-500 text-sm">
                &copy; 2026 Zentra Technologies. All rights reserved.
            </p>
        </footer>
    </div>

</body>
</html>