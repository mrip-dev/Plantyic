<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantyic API — v1.0.0 Documentation</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f0a0a;
            --surface: #160e0e;
            --surface2: #1e1212;
            --border: #2a1515;
            --border2: #3a1f1f;
            --primary: #BA1A1A;
            --primary-bright: #e03030;
            --primary-muted: #6b1010;
            --primary-glow: rgba(186, 26, 26, 0.12);
            --amber: #fbbf24;
            --amber-dim: rgba(251, 191, 36, 0.15);
            --red: #f87171;
            --red-dim: rgba(248, 113, 113, 0.15);
            --blue: #60a5fa;
            --blue-dim: rgba(96, 165, 250, 0.15);
            --text: #f0e6e6;
            --text-muted: #9e7a7a;
            --text-dim: #5e3a3a;
            --radius: 10px;
            --mono: 'DM Mono', monospace;
            --serif: 'Fraunces', serif;
            --sans: 'DM Sans', sans-serif;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            font-size: 15px;
            line-height: 1.7;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Ambient background */
        body::before {
            content: '';
            position: fixed;
            top: -30%;
            left: -10%;
            width: 60%;
            height: 60%;
            background: radial-gradient(ellipse, rgba(186, 26, 26, 0.07) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ─── LAYOUT ─── */
        .layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border2);
            border-radius: 4px;
        }

        .sidebar-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            font-family: var(--serif);
            flex-shrink: 0;
            letter-spacing: -0.02em;
        }

        .logo-text {
            font-family: var(--serif);
            font-size: 17px;
            font-weight: 600;
            color: var(--text);
        }

        .logo-badge {
            margin-left: auto;
            font-family: var(--mono);
            font-size: 10px;
            background: var(--primary-muted);
            color: var(--primary);
            padding: 2px 7px;
            border-radius: 20px;
            letter-spacing: 0.04em;
        }

        .sidebar-section {
            padding: 20px 0 8px;
        }

        .sidebar-section-label {
            padding: 0 20px 8px;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-dim);
            font-family: var(--mono);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 20px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px;
            transition: all 0.15s;
            border-left: 2px solid transparent;
            cursor: pointer;
        }

        .nav-item:hover {
            color: var(--text);
            background: var(--primary-glow);
            border-left-color: var(--primary-bright);
        }

        .nav-item.active {
            color: var(--primary);
            background: var(--primary-glow);
            border-left-color: var(--primary);
        }

        .nav-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.5;
            flex-shrink: 0;
        }

        .nav-method {
            font-family: var(--mono);
            font-size: 9px;
            font-weight: 500;
            padding: 2px 5px;
            border-radius: 4px;
            margin-left: auto;
            letter-spacing: 0.04em;
            flex-shrink: 0;
        }

        .nav-method.post {
            background: rgba(186, 26, 26, 0.15);
            color: var(--primary);
        }

        .nav-method.get {
            background: var(--blue-dim);
            color: var(--blue);
        }

        .nav-method.put {
            background: var(--amber-dim);
            color: var(--amber);
        }

        .nav-method.delete {
            background: var(--red-dim);
            color: var(--red);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-dim);
        }

        .sidebar-footer a {
            color: var(--primary-bright);
            text-decoration: none;
        }

        .sidebar-footer a:hover {
            color: var(--primary);
        }

        /* ─── MAIN CONTENT ─── */
        .main {
            padding: 0;
            /* max-width: 900px; */
        }

        /* ─── HERO ─── */
        .hero {
            padding: 56px 56px 48px;
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            display: none;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: var(--mono);
            font-size: 11px;
            color: var(--primary);
            background: var(--primary-glow);
            border: 1px solid rgba(186, 26, 26, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 20px;
            letter-spacing: 0.06em;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: var(--primary);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.6;
                transform: scale(1.3);
            }
        }

        .hero h1 {
            font-family: var(--serif);
            font-size: 42px;
            font-weight: 700;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 14px;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero-desc {
            color: var(--text-muted);
            font-size: 16px;
            max-width: 480px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .hero-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .hero-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: var(--text-muted);
            font-family: var(--mono);
        }

        .hero-chip .icon {
            opacity: 0.6;
        }

        .base-url-box {
            display: flex;
            align-items: center;
            gap: 0;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 8px;
            /* overflow: hidden; */
            /* max-width: 440px; */
            margin-top: 24px;
        }

        .base-url-label {
            padding: 9px 14px;
            font-family: var(--mono);
            font-size: 10px;
            color: var(--primary);
            background: rgba(186, 26, 26, 0.08);
            border-right: 1px solid var(--border2);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .base-url-value {
            padding: 9px 14px;
            font-family: var(--mono);
            font-size: 12.5px;
            color: var(--text-muted);
            flex: 1;
        }

        .copy-btn {
            padding: 9px 14px;
            background: none;
            border: none;
            border-left: 1px solid var(--border2);
            color: var(--text-dim);
            cursor: pointer;
            font-size: 13px;
            transition: color 0.15s;
        }

        .copy-btn:hover {
            color: var(--primary);
        }

        /* ─── CONTENT SECTIONS ─── */
        .content {
            padding: 0 56px 80px;
        }

        .section {
            padding-top: 56px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 48px;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 28px;
        }

        .section-number {
            font-family: var(--mono);
            font-size: 11px;
            color: var(--primary-bright);
            background: var(--primary-glow);
            border: 1px solid rgba(186, 26, 26, 0.15);
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .section-title {
            font-family: var(--serif);
            font-size: 26px;
            font-weight: 600;
            color: #fff;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .prose {
            color: var(--text-muted);
            line-height: 1.75;
            max-width: 640px;
        }

        /* ─── ENDPOINT CARD ─── */
        .endpoint-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 16px;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .endpoint-card:hover {
            border-color: var(--border2);
        }

        .endpoint-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            cursor: pointer;
            user-select: none;
        }

        .method-badge {
            font-family: var(--mono);
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 5px;
            letter-spacing: 0.06em;
            min-width: 52px;
            text-align: center;
            flex-shrink: 0;
        }

        .method-badge.POST {
            background: rgba(186, 26, 26, 0.15);
            color: var(--primary);
            border: 1px solid rgba(186, 26, 26, 0.2);
        }

        .method-badge.GET {
            background: var(--blue-dim);
            color: var(--blue);
            border: 1px solid rgba(96, 165, 250, 0.2);
        }

        .method-badge.PUT {
            background: var(--amber-dim);
            color: var(--amber);
            border: 1px solid rgba(251, 191, 36, 0.2);
        }

        .method-badge.DELETE {
            background: var(--red-dim);
            color: var(--red);
            border: 1px solid rgba(248, 113, 113, 0.2);
        }

        .endpoint-path {
            font-family: var(--mono);
            font-size: 13px;
            color: var(--text);
        }

        .endpoint-path .base {
            color: var(--text-dim);
        }

        .endpoint-desc {
            font-size: 13px;
            color: var(--text-muted);
            margin-left: auto;
        }

        .endpoint-arrow {
            color: var(--text-dim);
            margin-left: 8px;
            font-size: 12px;
            transition: transform 0.2s;
        }

        .endpoint-body {
            border-top: 1px solid var(--border);
            padding: 20px 18px;
            display: none;
        }

        .endpoint-body.open {
            display: block;
        }

        .endpoint-card.open .endpoint-arrow {
            transform: rotate(90deg);
        }

        /* ─── TABS ─── */
        .tabs {
            display: flex;
            gap: 2px;
            margin-bottom: 16px;
            background: var(--bg);
            border-radius: 7px;
            padding: 3px;
            width: fit-content;
        }

        .tab {
            padding: 6px 14px;
            font-size: 12px;
            font-family: var(--mono);
            color: var(--text-dim);
            background: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.15s;
            letter-spacing: 0.04em;
        }

        .tab.active {
            background: var(--surface2);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .tab:hover:not(.active) {
            color: var(--text-muted);
        }

        /* ─── CODE BLOCKS ─── */
        .code-block {
            background: #080c07;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            font-family: var(--mono);
            font-size: 12.5px;
        }

        .code-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 14px;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }

        .code-lang {
            font-size: 10px;
            color: var(--text-dim);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .code-copy {
            font-size: 11px;
            color: var(--text-dim);
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.15s;
            font-family: var(--mono);
        }

        .code-copy:hover {
            color: var(--primary);
        }

        pre {
            margin: 0;
            padding: 16px;
            overflow-x: auto;
            line-height: 1.65;
        }

        pre::-webkit-scrollbar {
            height: 4px;
        }

        pre::-webkit-scrollbar-track {
            background: transparent;
        }

        pre::-webkit-scrollbar-thumb {
            background: var(--border2);
            border-radius: 4px;
        }

        /* Syntax highlighting */
        .kw {
            color: var(--primary-bright);
        }

        .str {
            color: #fca5a5;
        }

        .num {
            color: var(--amber);
        }

        .key {
            color: #93c5fd;
        }

        .punc {
            color: var(--text-dim);
        }

        .cm {
            color: var(--text-dim);
            font-style: italic;
        }

        /* ─── PARAMS TABLE ─── */
        .params-label {
            font-size: 11px;
            font-family: var(--mono);
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 10px;
        }

        .params-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .params-table th {
            text-align: left;
            padding: 7px 12px;
            background: var(--surface2);
            color: var(--text-dim);
            font-size: 10px;
            font-family: var(--mono);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 500;
            border-bottom: 1px solid var(--border);
        }

        .params-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }

        .params-table tr:last-child td {
            border-bottom: none;
        }

        .param-name {
            font-family: var(--mono);
            color: var(--text);
            font-size: 12.5px;
        }

        .param-type {
            font-family: var(--mono);
            font-size: 11px;
            color: var(--blue);
            background: var(--blue-dim);
            padding: 1px 6px;
            border-radius: 4px;
        }

        .param-required {
            font-family: var(--mono);
            font-size: 10px;
            color: var(--red);
            background: var(--red-dim);
            padding: 1px 6px;
            border-radius: 4px;
        }

        .param-optional {
            font-family: var(--mono);
            font-size: 10px;
            color: var(--text-dim);
            background: var(--surface2);
            padding: 1px 6px;
            border-radius: 4px;
        }

        .param-desc {
            color: var(--text-muted);
            font-size: 13px;
        }

        /* ─── RESPONSE BADGES ─── */
        .response-tabs {
            display: flex;
            gap: 6px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .response-badge {
            font-family: var(--mono);
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 5px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s;
        }

        .response-badge.s200 {
            background: rgba(186, 26, 26, 0.1);
            color: var(--primary);
            border-color: rgba(186, 26, 26, 0.2);
        }

        .response-badge.s401 {
            background: var(--amber-dim);
            color: var(--amber);
            border-color: rgba(251, 191, 36, 0.2);
        }

        .response-badge.s422 {
            background: var(--red-dim);
            color: var(--red);
            border-color: rgba(248, 113, 113, 0.2);
        }

        /* ─── AUTH NOTICE ─── */
        .auth-notice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-left: 3px solid var(--amber);
            padding: 14px 16px;
            border-radius: var(--radius);
            margin-bottom: 24px;
            font-size: 13.5px;
            color: var(--text-muted);
        }

        .auth-notice .icon {
            font-size: 16px;
            flex-shrink: 0;
        }

        /* ─── ROLE CHIPS ─── */
        .roles {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .role-chip {
            font-family: var(--mono);
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid var(--border2);
            color: var(--text-muted);
        }

        .role-chip.super {
            border-color: rgba(251, 191, 36, 0.4);
            color: var(--amber);
            background: var(--amber-dim);
        }

        .role-chip.admin {
            border-color: rgba(248, 113, 113, 0.3);
            color: var(--red);
            background: var(--red-dim);
        }

        .role-chip.vendor {
            border-color: rgba(186, 26, 26, 0.3);
            color: var(--primary);
            background: var(--primary-glow);
        }

        .role-chip.customer {
            border-color: rgba(96, 165, 250, 0.3);
            color: var(--blue);
            background: var(--blue-dim);
        }

        /* ─── INFO GRID ─── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 20px;
        }

        .info-card {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
        }

        .info-card-label {
            font-size: 11px;
            font-family: var(--mono);
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .info-card-value {
            font-size: 14px;
            color: var(--text);
        }

        /* ─── DIVIDER ─── */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 24px 0;
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 768px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                left: -260px;
                z-index: 100;
                transition: left 0.3s;
            }

            .sidebar.open {
                left: 0;
            }

            .hero,
            .content {
                padding: 28px 20px;
            }
        }

        /* ─── ANIMATIONS ─── */
        .section {
            animation: fadeUp 0.4s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border2);
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="layout">

        <!-- ─── SIDEBAR ─── -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="17" rx="2.5" stroke="white" stroke-width="1.8" fill="none" />
                        <path d="M8 2v4M16 2v4" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M3 9h18" stroke="white" stroke-width="1.8" />
                        <path d="M7 13h2M11 13h2M15 13h2" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M7 17h2M11 17h2" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                    </svg></div>
                <span class="logo-text">Plantyic</span>
                <span class="logo-badge">v1.0.0</span>
            </div>


            <div class="sidebar-section">
                <div class="sidebar-section-label">Auth Endpoints</div>
                <a href="/" class="nav-item"><span class="nav-dot"></span> Home </a>
                <a href="/docs/auth" class="nav-item"><span class="nav-dot"></span> Auth </a>
                <a href="/docs/workspace" class="nav-item"><span class="nav-dot"></span> Workspace </a>
            </div>

        </aside>

        <!-- ─── MAIN ─── -->
        <main class="main">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleEndpoint(id) {
            const card = document.getElementById(id);
            const body = card.querySelector('.endpoint-body');
            const isOpen = card.classList.contains('open');
            card.classList.toggle('open', !isOpen);
            body.classList.toggle('open', !isOpen);
        }

        function switchTab(btn, targetId) {
            const container = btn.closest('.endpoint-body');
            // deactivate all tabs
            container.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            // hide all tab content (siblings with IDs)
            const allContent = container.querySelectorAll('[id]');
            allContent.forEach(el => {
                if (!el.classList.contains('tabs')) el.style.display = 'none';
            });
            // show target
            const target = container.querySelector('#' + targetId);
            if (target) target.style.display = 'block';
        }

        function copyCode(btn) {
            const pre = btn.closest('.code-block').querySelector('pre');
            navigator.clipboard.writeText(pre.innerText).then(() => {
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 1500);
            });
        }

        function copyBaseUrl() {
            const url = document.getElementById('base-url').textContent;
            navigator.clipboard.writeText(url).then(() => {
                const btn = document.querySelector('.copy-btn');
                btn.textContent = '✓';
                setTimeout(() => btn.textContent = '⎘', 1500);
            });
        }

        // Active nav highlight on scroll
        const sections = document.querySelectorAll('.section');
        const navItems = document.querySelectorAll('.nav-item');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id;
                    navItems.forEach(item => {
                        item.classList.toggle('active', item.getAttribute('href') === '#' + id);
                    });
                }
            });
        }, {
            rootMargin: '-20% 0px -70% 0px'
        });

        sections.forEach(section => observer.observe(section));
    </script>

</body>

</html>