<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'API Documentation')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #391d1d 0%, #1e293b 100%); }
        .copy-btn { cursor:pointer; }
    </style>
</head>
<body class="gradient-bg min-h-screen flex flex-col text-slate-200">
    <div class="w-full px-0 md:px-8 py-0">
        <header class="flex flex-col md:flex-row items-center justify-between px-8 py-6  shadow-lg">
            <div class="flex items-center gap-4">
                <div class="inline-block p-3 bg-white rounded-2xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#BA1A1A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Plantyic <span class="text-[#BA1A1A]">API Docs</span></h1>
            </div>
            <nav class="mt-4 md:mt-0 flex flex-wrap gap-2">
                <a href="/" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Home</a>
                <a href="/docs/auth" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Auth</a>
                <a href="/docs/workspace" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Workspace</a>
                <!-- <a href="/docs/tasks" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Tasks</a>
                <a href="/docs/projects" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Projects</a>
                <a href="/docs/notes" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Notes</a>
                <a href="/docs/goals" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Goals</a>
                <a href="/docs/team" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Team</a>
                <a href="/docs/integrations" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Integrations</a>
                <a href="/docs/notifications" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Notifications</a>
                <a href="/docs/settings" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Settings</a>
                <a href="/docs/bidding" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Bidding</a>
                <a href="/docs/vehicle" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Vehicle</a>
                <a href="/docs/inspection" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Inspection</a>
                <a href="/docs/user" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">User</a>
                <a href="/docs/blog" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Blog</a>
                <a href="/docs/contact" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Contact</a>
                <a href="/docs/favorite" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Favorite</a>
                <a href="/docs/testimonial" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Testimonial</a>
                <a href="/docs/ckeditor" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">CKEditor</a>
                <a href="/docs/search" class="text-white hover:text-[#BA1A1A] font-semibold px-4 py-2 rounded transition">Global Search</a> -->
            </nav>
        </header>
        <main class="w-full  mx-auto px-2 md:px-8 py-8">
            @yield('content')
        </main>
        <footer class="mt-12 text-center">
            <p class="text-slate-500 text-sm">
                &copy; {{ date('Y') }} Plantyic Technologies. All rights reserved.
            </p>
        </footer>
    </div>
    <script>
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.previousElementSibling.innerText;
                navigator.clipboard.writeText(code);
                this.innerText = 'Copied!';
                setTimeout(() => { this.innerText = 'Copy'; }, 1200);
            });
        });
    </script>
</body>
</html>
