<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantyic API | Gateway</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #391d1d 0%, #1e293b 100%);
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center text-slate-200">

    <div class="w-full p-8">
        <header class="text-center mb-10">
            <div class="inline-block p-3 bg-[#BA1A1A] rounded-2xl mb-4 shadow-lg shadow-red-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Plantyic <span class="text-[#BA1A1A]">API</span></h1>
            <p class="mt-3 text-slate-400 text-lg">The robust backbone powering the Plantyic ecosystem.</p>
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
                <p class="text-slate-300">Welcome to the Plantyic API Gateway. This environment is restricted to authorized applications and developers.</p>

                <!-- Documentation Navigation -->
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-4 text-white">API Documentation Pages</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <a href="/docs/auth" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Auth</a>
                        <a href="/docs/workspace" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Workspace</a>
                        <!-- <a href="/docs/tasks" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Tasks</a>
                        <a href="/docs/projects" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Projects</a>
                        <a href="/docs/notes" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Notes</a>
                        <a href="/docs/goals" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Goals</a>
                        <a href="/docs/team" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Team</a>
                        <a href="/docs/integrations" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Integrations</a>
                        <a href="/docs/notifications" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Notifications</a>
                        <a href="/docs/settings" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Settings</a>
                        <a href="/docs/bidding" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Bidding</a>
                        <a href="/docs/vehicle" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Vehicle</a>
                        <a href="/docs/inspection" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Inspection</a>
                        <a href="/docs/user" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">User</a>
                        <a href="/docs/blog" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Blog</a>
                        <a href="/docs/contact" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Contact</a>
                        <a href="/docs/favorite" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Favorite</a>
                        <a href="/docs/testimonial" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Testimonial</a>
                        <a href="/docs/ckeditor" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">CKEditor</a>
                        <a href="/docs/search" class="block bg-slate-900/70 hover:bg-[#BA1A1A] text-white rounded-xl px-5 py-3 font-semibold transition-all">Global Search</a>
                    </div> -->
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4">

                        <a href="/health" class="flex items-center justify-center gap-2 bg-slate-700 hover:bg-slate-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                            Health Check
                        </a>
                    </div>
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