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
    --amber-dim: rgba(251,191,36,0.15);
    --red: #f87171;
    --red-dim: rgba(248,113,113,0.15);
    --blue: #60a5fa;
    --blue-dim: rgba(96,165,250,0.15);
    --text: #f0e6e6;
    --text-muted: #9e7a7a;
    --text-dim: #5e3a3a;
    --radius: 10px;
    --mono: 'DM Mono', monospace;
    --serif: 'Fraunces', serif;
    --sans: 'DM Sans', sans-serif;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  html { scroll-behavior: smooth; }

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
    background: radial-gradient(ellipse, rgba(186,26,26,0.07) 0%, transparent 70%);
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

  .sidebar::-webkit-scrollbar { width: 4px; }
  .sidebar::-webkit-scrollbar-track { background: transparent; }
  .sidebar::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }

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

  .nav-method.post { background: rgba(186,26,26,0.15); color: var(--primary); }
  .nav-method.get { background: var(--blue-dim); color: var(--blue); }
  .nav-method.put { background: var(--amber-dim); color: var(--amber); }
  .nav-method.delete { background: var(--red-dim); color: var(--red); }

  .sidebar-footer {
    margin-top: auto;
    padding: 16px 20px;
    border-top: 1px solid var(--border);
    font-size: 12px;
    color: var(--text-dim);
  }

  .sidebar-footer a { color: var(--primary-bright); text-decoration: none; }
  .sidebar-footer a:hover { color: var(--primary); }

  /* ─── MAIN CONTENT ─── */
  .main {
    padding: 0;
    max-width: 900px;
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
    border: 1px solid rgba(186,26,26,0.2);
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
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.3); }
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

  .hero h1 span { color: var(--primary); }

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

  .hero-chip .icon { opacity: 0.6; }

  .base-url-box {
    display: flex;
    align-items: center;
    gap: 0;
    background: var(--surface2);
    border: 1px solid var(--border2);
    border-radius: 8px;
    overflow: hidden;
    max-width: 440px;
    margin-top: 24px;
  }

  .base-url-label {
    padding: 9px 14px;
    font-family: var(--mono);
    font-size: 10px;
    color: var(--primary);
    background: rgba(186,26,26,0.08);
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

  .copy-btn:hover { color: var(--primary); }

  /* ─── CONTENT SECTIONS ─── */
  .content {
    padding: 0 56px 80px;
  }

  .section {
    padding-top: 56px;
    border-bottom: 1px solid var(--border);
    padding-bottom: 48px;
  }

  .section:last-child { border-bottom: none; }

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
    border: 1px solid rgba(186,26,26,0.15);
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

  .method-badge.POST { background: rgba(186,26,26,0.15); color: var(--primary); border: 1px solid rgba(186,26,26,0.2); }
  .method-badge.GET { background: var(--blue-dim); color: var(--blue); border: 1px solid rgba(96,165,250,0.2); }
  .method-badge.PUT { background: var(--amber-dim); color: var(--amber); border: 1px solid rgba(251,191,36,0.2); }
  .method-badge.DELETE { background: var(--red-dim); color: var(--red); border: 1px solid rgba(248,113,113,0.2); }

  .endpoint-path {
    font-family: var(--mono);
    font-size: 13px;
    color: var(--text);
  }

  .endpoint-path .base { color: var(--text-dim); }

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

  .endpoint-body.open { display: block; }
  .endpoint-card.open .endpoint-arrow { transform: rotate(90deg); }

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

  .tab.active { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
  .tab:hover:not(.active) { color: var(--text-muted); }

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

  .code-copy:hover { color: var(--primary); }

  pre {
    margin: 0;
    padding: 16px;
    overflow-x: auto;
    line-height: 1.65;
  }

  pre::-webkit-scrollbar { height: 4px; }
  pre::-webkit-scrollbar-track { background: transparent; }
  pre::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }

  /* Syntax highlighting */
  .kw { color: var(--primary-bright); }
  .str { color: #fca5a5; }
  .num { color: var(--amber); }
  .key { color: #93c5fd; }
  .punc { color: var(--text-dim); }
  .cm { color: var(--text-dim); font-style: italic; }

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

  .params-table tr:last-child td { border-bottom: none; }

  .param-name { font-family: var(--mono); color: var(--text); font-size: 12.5px; }
  .param-type { font-family: var(--mono); font-size: 11px; color: var(--blue); background: var(--blue-dim); padding: 1px 6px; border-radius: 4px; }
  .param-required { font-family: var(--mono); font-size: 10px; color: var(--red); background: var(--red-dim); padding: 1px 6px; border-radius: 4px; }
  .param-optional { font-family: var(--mono); font-size: 10px; color: var(--text-dim); background: var(--surface2); padding: 1px 6px; border-radius: 4px; }
  .param-desc { color: var(--text-muted); font-size: 13px; }

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

  .response-badge.s200 { background: rgba(186,26,26,0.1); color: var(--primary); border-color: rgba(186,26,26,0.2); }
  .response-badge.s401 { background: var(--amber-dim); color: var(--amber); border-color: rgba(251,191,36,0.2); }
  .response-badge.s422 { background: var(--red-dim); color: var(--red); border-color: rgba(248,113,113,0.2); }

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

  .auth-notice .icon { font-size: 16px; flex-shrink: 0; }

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

  .role-chip.super { border-color: rgba(251,191,36,0.4); color: var(--amber); background: var(--amber-dim); }
  .role-chip.admin { border-color: rgba(248,113,113,0.3); color: var(--red); background: var(--red-dim); }
  .role-chip.vendor { border-color: rgba(186,26,26,0.3); color: var(--primary); background: var(--primary-glow); }
  .role-chip.customer { border-color: rgba(96,165,250,0.3); color: var(--blue); background: var(--blue-dim); }

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

  .info-card-label { font-size: 11px; font-family: var(--mono); color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; }
  .info-card-value { font-size: 14px; color: var(--text); }

  /* ─── DIVIDER ─── */
  .divider {
    height: 1px;
    background: var(--border);
    margin: 24px 0;
  }

  /* ─── RESPONSIVE ─── */
  @media (max-width: 768px) {
    .layout { grid-template-columns: 1fr; }
    .sidebar { position: fixed; left: -260px; z-index: 100; transition: left 0.3s; }
    .sidebar.open { left: 0; }
    .hero, .content { padding: 28px 20px; }
  }

  /* ─── ANIMATIONS ─── */
  .section { animation: fadeUp 0.4s ease both; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* scrollbar */
  ::-webkit-scrollbar { width: 6px; }
  ::-webkit-scrollbar-track { background: var(--bg); }
  ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }
</style>
</head>
<body>

<div class="layout">

  <!-- ─── SIDEBAR ─── -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="4" width="18" height="17" rx="2.5" stroke="white" stroke-width="1.8" fill="none"/>
          <path d="M8 2v4M16 2v4" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
          <path d="M3 9h18" stroke="white" stroke-width="1.8"/>
          <path d="M7 13h2M11 13h2M15 13h2" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
          <path d="M7 17h2M11 17h2" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
        </svg></div>
      <span class="logo-text">Plantyic</span>
      <span class="logo-badge">v1.0.0</span>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-section-label">Overview</div>
      <a href="#introduction" class="nav-item active"><span class="nav-dot"></span> Introduction</a>
      <a href="#authentication" class="nav-item"><span class="nav-dot"></span> Authentication</a>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-section-label">Auth Endpoints</div>
      <a href="#register" class="nav-item"><span class="nav-dot"></span> Register <span class="nav-method post">POST</span></a>
      <a href="#login" class="nav-item"><span class="nav-dot"></span> Login <span class="nav-method post">POST</span></a>
      <a href="#logout" class="nav-item"><span class="nav-dot"></span> Logout <span class="nav-method post">POST</span></a>
      <a href="#profile" class="nav-item"><span class="nav-dot"></span> Profile <span class="nav-method get">GET</span></a>
      <a href="#forgot-password" class="nav-item"><span class="nav-dot"></span> Forgot Password <span class="nav-method post">POST</span></a>
      <a href="#reset-password" class="nav-item"><span class="nav-dot"></span> Reset Password <span class="nav-method post">POST</span></a>
      <a href="#verify-email" class="nav-item"><span class="nav-dot"></span> Verify Email <span class="nav-method post">POST</span></a>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-section-label">Admin</div>
      <a href="#user-management" class="nav-item"><span class="nav-dot"></span> User Management <span class="nav-method get">GET</span></a>
      <a href="#vendor-status" class="nav-item"><span class="nav-dot"></span> Vendor Status <span class="nav-method put">PUT</span></a>
    </div>

    <div class="sidebar-footer">
      Need help? <a href="mailto:support@plantyic.com">support@plantyic.com</a>
    </div>
  </aside>

  <!-- ─── MAIN ─── -->
  <main class="main">

    <!-- HERO -->
    <div class="hero">
      <div class="hero-eyebrow"><span class="status-dot"></span> API Live</div>
      <h1>Plantyic <span>API</span><br>Documentation</h1>
      <p class="hero-desc">Everything you need to integrate with Plantyic. REST-based, JSON-first, and built for speed.</p>

      <div class="hero-meta">
        <div class="hero-chip"><span class="icon">📄</span> REST API</div>
        <div class="hero-chip"><span class="icon">🔐</span> Bearer Auth</div>
        <div class="hero-chip"><span class="icon">⚡</span> JSON</div>
      </div>

      <div class="base-url-box">
        <span class="base-url-label">Base URL</span>
        <span class="base-url-value" id="base-url">https://yourapp.com/api</span>
        <button class="copy-btn" onclick="copyBaseUrl()" title="Copy">⎘</button>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

      <!-- INTRODUCTION -->
      <section class="section" id="introduction">
        <div class="section-header">
          <div class="section-number">01</div>
          <div>
            <div class="section-title">Introduction</div>
            <div class="section-subtitle">Getting started with the Plantyic API</div>
          </div>
        </div>
        <p class="prose">The Plantyic API is organized around REST. All requests should be made to the base URL using <code style="font-family:var(--mono);color:var(--green);font-size:12px">application/json</code> content type.</p>

        <div class="info-grid">
          <div class="info-card">
            <div class="info-card-label">Format</div>
            <div class="info-card-value">JSON only</div>
          </div>
          <div class="info-card">
            <div class="info-card-label">Auth Method</div>
            <div class="info-card-value">Bearer Token</div>
          </div>
          <div class="info-card">
            <div class="info-card-label">API Version</div>
            <div class="info-card-value">v1.0.0</div>
          </div>
          <div class="info-card">
            <div class="info-card-label">HTTPS</div>
            <div class="info-card-value">Required</div>
          </div>
        </div>

        <div class="divider"></div>

        <p class="params-label" style="margin-bottom:12px">User Roles</p>
        <div class="roles">
          <span class="role-chip super">Super Admin</span>
          <span class="role-chip admin">Admin</span>
          <span class="role-chip">Staff</span>
          <span class="role-chip vendor">Vendor</span>
          <span class="role-chip customer">Customer</span>
        </div>
      </section>

      <!-- AUTHENTICATION -->
      <section class="section" id="authentication">
        <div class="section-header">
          <div class="section-number">02</div>
          <div>
            <div class="section-title">Authentication</div>
            <div class="section-subtitle">How to authenticate your requests</div>
          </div>
        </div>

        <p class="prose" style="margin-bottom:20px">Plantyic uses Bearer token authentication. After logging in, include the token in the <code style="font-family:var(--mono);color:var(--green);font-size:12px">Authorization</code> header for all protected routes.</p>

        <div class="code-block">
          <div class="code-header">
            <span class="code-lang">HTTP Header</span>
            <button class="code-copy" onclick="copyCode(this)">Copy</button>
          </div>
          <pre><span class="key">Authorization</span><span class="punc">: </span><span class="str">Bearer YOUR_TOKEN_HERE</span>
<span class="key">Content-Type</span><span class="punc">: </span><span class="str">application/json</span>
<span class="key">Accept</span><span class="punc">: </span><span class="str">application/json</span></pre>
        </div>
      </section>

      <!-- REGISTER -->
      <section class="section" id="register">
        <div class="section-header">
          <div class="section-number">03</div>
          <div>
            <div class="section-title">Register</div>
            <div class="section-subtitle">Create a new user account</div>
          </div>
        </div>

        <div class="endpoint-card open" id="ep-register">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-register')">
            <span class="method-badge POST">POST</span>
            <span class="endpoint-path"><span class="base">/api</span>/auth/register</span>
            <span class="endpoint-desc">Create account</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body open">
            <div class="tabs">
              <button class="tab active" onclick="switchTab(this, 'reg-params')">Parameters</button>
              <button class="tab" onclick="switchTab(this, 'reg-req')">Request</button>
              <button class="tab" onclick="switchTab(this, 'reg-res')">Response</button>
            </div>

            <div id="reg-params">
              <div class="params-label">Body Parameters</div>
              <table class="params-table">
                <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
                <tbody>
                  <tr><td><span class="param-name">name</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">User's full name</td></tr>
                  <tr><td><span class="param-name">email</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Valid email address</td></tr>
                  <tr><td><span class="param-name">password</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Min 8 characters</td></tr>
                  <tr><td><span class="param-name">password_confirmation</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Must match password</td></tr>
                </tbody>
              </table>
            </div>

            <div id="reg-req" style="display:none">
              <div class="code-block">
                <div class="code-header"><span class="code-lang">cURL</span><button class="code-copy" onclick="copyCode(this)">Copy</button></div>
<pre><span class="kw">curl</span> --request POST \
  --url https://yourapp.com/api/auth/register \
  --header <span class="str">'Content-Type: application/json'</span> \
  --data <span class="str">'{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}'</span></pre>
              </div>
            </div>

            <div id="reg-res" style="display:none">
              <div class="response-tabs">
                <span class="response-badge s200">200 Success</span>
                <span class="response-badge s422">422 Validation Error</span>
              </div>
              <div class="code-block">
                <div class="code-header"><span class="code-lang">JSON</span><button class="code-copy" onclick="copyCode(this)">Copy</button></div>
<pre><span class="punc">{</span>
  <span class="key">"status"</span><span class="punc">: </span><span class="str">"success"</span><span class="punc">,</span>
  <span class="key">"data"</span><span class="punc">: {</span>
    <span class="key">"token"</span><span class="punc">: </span><span class="str">"10|A7v8...9a0z"</span><span class="punc">,</span>
    <span class="key">"user"</span><span class="punc">: {</span>
      <span class="key">"id"</span><span class="punc">: </span><span class="num">1</span><span class="punc">,</span>
      <span class="key">"name"</span><span class="punc">: </span><span class="str">"John Doe"</span><span class="punc">,</span>
      <span class="key">"email"</span><span class="punc">: </span><span class="str">"john@example.com"</span><span class="punc">,</span>
      <span class="key">"role"</span><span class="punc">: </span><span class="str">"customer"</span>
    <span class="punc">}</span>
  <span class="punc">}</span>
<span class="punc">}</span></pre>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- LOGIN -->
      <section class="section" id="login">
        <div class="section-header">
          <div class="section-number">04</div>
          <div>
            <div class="section-title">Login</div>
            <div class="section-subtitle">Authenticate and get your Bearer token</div>
          </div>
        </div>

        <div class="endpoint-card open" id="ep-login">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-login')">
            <span class="method-badge POST">POST</span>
            <span class="endpoint-path"><span class="base">/api</span>/auth/login</span>
            <span class="endpoint-desc">Get token</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body open">
            <div class="tabs">
              <button class="tab active" onclick="switchTab(this, 'login-params')">Parameters</button>
              <button class="tab" onclick="switchTab(this, 'login-req')">Request</button>
              <button class="tab" onclick="switchTab(this, 'login-res')">Response</button>
            </div>

            <div id="login-params">
              <div class="params-label">Body Parameters</div>
              <table class="params-table">
                <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
                <tbody>
                  <tr><td><span class="param-name">email</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Registered email</td></tr>
                  <tr><td><span class="param-name">password</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Account password</td></tr>
                </tbody>
              </table>
              <div style="margin-top:14px">
                <div class="params-label" style="margin-bottom:8px">Can be used by any role</div>
                <div class="roles">
                  <span class="role-chip super">Super Admin</span>
                  <span class="role-chip admin">Admin</span>
                  <span class="role-chip">Staff</span>
                  <span class="role-chip vendor">Vendor</span>
                  <span class="role-chip customer">Customer</span>
                </div>
              </div>
            </div>

            <div id="login-req" style="display:none">
              <div class="code-block">
                <div class="code-header"><span class="code-lang">cURL</span><button class="code-copy" onclick="copyCode(this)">Copy</button></div>
<pre><span class="kw">curl</span> --request POST \
  --url https://yourapp.com/api/auth/login \
  --header <span class="str">'Content-Type: application/json'</span> \
  --data <span class="str">'{
  "email": "user@example.com",
  "password": "secret_password"
}'</span></pre>
              </div>
            </div>

            <div id="login-res" style="display:none">
              <div class="response-tabs">
                <span class="response-badge s200">200 Success</span>
                <span class="response-badge s401">401 Unauthorized</span>
                <span class="response-badge s422">422 Validation</span>
              </div>
              <div class="code-block">
                <div class="code-header"><span class="code-lang">JSON</span><button class="code-copy" onclick="copyCode(this)">Copy</button></div>
<pre><span class="punc">{</span>
  <span class="key">"status"</span><span class="punc">: </span><span class="str">"success"</span><span class="punc">,</span>
  <span class="key">"data"</span><span class="punc">: {</span>
    <span class="key">"token"</span><span class="punc">: </span><span class="str">"10|A7v8...9a0z"</span><span class="punc">,</span>
    <span class="key">"user"</span><span class="punc">: {</span>
      <span class="key">"id"</span><span class="punc">: </span><span class="num">1</span><span class="punc">,</span>
      <span class="key">"name"</span><span class="punc">: </span><span class="str">"John Doe"</span><span class="punc">,</span>
      <span class="key">"role"</span><span class="punc">: </span><span class="str">"customer"</span>
    <span class="punc">}</span>
  <span class="punc">}</span>
<span class="punc">}</span></pre>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- LOGOUT -->
      <section class="section" id="logout">
        <div class="section-header">
          <div class="section-number">05</div>
          <div>
            <div class="section-title">Logout</div>
            <div class="section-subtitle">Invalidate the current Bearer token</div>
          </div>
        </div>

        <div class="auth-notice">
          <span class="icon">🔐</span>
          Requires a valid Bearer token in the <code style="font-family:var(--mono);font-size:12px;color:var(--amber)">Authorization</code> header.
        </div>

        <div class="endpoint-card" id="ep-logout">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-logout')">
            <span class="method-badge POST">POST</span>
            <span class="endpoint-path"><span class="base">/api</span>/auth/logout</span>
            <span class="endpoint-desc">Revoke token</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="code-block">
              <div class="code-header"><span class="code-lang">cURL</span><button class="code-copy" onclick="copyCode(this)">Copy</button></div>
<pre><span class="kw">curl</span> --request POST \
  --url https://yourapp.com/api/auth/logout \
  --header <span class="str">'Authorization: Bearer YOUR_TOKEN'</span> \
  --header <span class="str">'Content-Type: application/json'</span></pre>
            </div>
            <div style="margin-top:14px">
              <div class="code-block">
                <div class="code-header"><span class="code-lang">Response 200</span></div>
<pre><span class="punc">{</span>
  <span class="key">"status"</span><span class="punc">: </span><span class="str">"success"</span><span class="punc">,</span>
  <span class="key">"message"</span><span class="punc">: </span><span class="str">"Logged out successfully"</span>
<span class="punc">}</span></pre>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- PROFILE -->
      <section class="section" id="profile">
        <div class="section-header">
          <div class="section-number">06</div>
          <div>
            <div class="section-title">Profile</div>
            <div class="section-subtitle">Get the authenticated user's profile</div>
          </div>
        </div>

        <div class="auth-notice">
          <span class="icon">🔐</span>
          Requires a valid Bearer token in the <code style="font-family:var(--mono);font-size:12px;color:var(--amber)">Authorization</code> header.
        </div>

        <div class="endpoint-card" id="ep-profile">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-profile')">
            <span class="method-badge GET">GET</span>
            <span class="endpoint-path"><span class="base">/api</span>/auth/profile</span>
            <span class="endpoint-desc">Get user data</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="code-block">
              <div class="code-header"><span class="code-lang">cURL</span><button class="code-copy" onclick="copyCode(this)">Copy</button></div>
<pre><span class="kw">curl</span> --request GET \
  --url https://yourapp.com/api/auth/profile \
  --header <span class="str">'Authorization: Bearer YOUR_TOKEN'</span></pre>
            </div>
            <div style="margin-top:14px">
              <div class="code-block">
                <div class="code-header"><span class="code-lang">Response 200</span></div>
<pre><span class="punc">{</span>
  <span class="key">"status"</span><span class="punc">: </span><span class="str">"success"</span><span class="punc">,</span>
  <span class="key">"data"</span><span class="punc">: {</span>
    <span class="key">"id"</span><span class="punc">: </span><span class="num">1</span><span class="punc">,</span>
    <span class="key">"name"</span><span class="punc">: </span><span class="str">"John Doe"</span><span class="punc">,</span>
    <span class="key">"email"</span><span class="punc">: </span><span class="str">"john@example.com"</span><span class="punc">,</span>
    <span class="key">"role"</span><span class="punc">: </span><span class="str">"customer"</span><span class="punc">,</span>
    <span class="key">"email_verified_at"</span><span class="punc">: </span><span class="str">"2024-01-01T00:00:00Z"</span><span class="punc">,</span>
    <span class="key">"created_at"</span><span class="punc">: </span><span class="str">"2024-01-01T00:00:00Z"</span>
  <span class="punc">}</span>
<span class="punc">}</span></pre>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FORGOT PASSWORD -->
      <section class="section" id="forgot-password">
        <div class="section-header">
          <div class="section-number">07</div>
          <div>
            <div class="section-title">Forgot Password</div>
            <div class="section-subtitle">Send a password reset link to email</div>
          </div>
        </div>

        <div class="endpoint-card" id="ep-forgot">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-forgot')">
            <span class="method-badge POST">POST</span>
            <span class="endpoint-path"><span class="base">/api</span>/auth/forgot-password</span>
            <span class="endpoint-desc">Send reset link</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="params-label">Body Parameters</div>
            <table class="params-table">
              <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
              <tbody>
                <tr><td><span class="param-name">email</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Registered email address</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- RESET PASSWORD -->
      <section class="section" id="reset-password">
        <div class="section-header">
          <div class="section-number">08</div>
          <div>
            <div class="section-title">Reset Password</div>
            <div class="section-subtitle">Reset password using the token from email</div>
          </div>
        </div>

        <div class="endpoint-card" id="ep-reset">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-reset')">
            <span class="method-badge POST">POST</span>
            <span class="endpoint-path"><span class="base">/api</span>/auth/reset-password</span>
            <span class="endpoint-desc">Set new password</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="params-label">Body Parameters</div>
            <table class="params-table">
              <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
              <tbody>
                <tr><td><span class="param-name">token</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Reset token from email</td></tr>
                <tr><td><span class="param-name">email</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">User's email address</td></tr>
                <tr><td><span class="param-name">password</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">New password (min 8 chars)</td></tr>
                <tr><td><span class="param-name">password_confirmation</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Must match password</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- VERIFY EMAIL -->
      <section class="section" id="verify-email">
        <div class="section-header">
          <div class="section-number">09</div>
          <div>
            <div class="section-title">Verify Email</div>
            <div class="section-subtitle">Verify the user's email address</div>
          </div>
        </div>

        <div class="endpoint-card" id="ep-verify">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-verify')">
            <span class="method-badge POST">POST</span>
            <span class="endpoint-path"><span class="base">/api</span>/auth/verify-email</span>
            <span class="endpoint-desc">Confirm email</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="params-label">Body Parameters</div>
            <table class="params-table">
              <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
              <tbody>
                <tr><td><span class="param-name">token</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">Verification token from email</td></tr>
                <tr><td><span class="param-name">email</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc">User's email address</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- USER MANAGEMENT -->
      <section class="section" id="user-management">
        <div class="section-header">
          <div class="section-number">10</div>
          <div>
            <div class="section-title">User Management</div>
            <div class="section-subtitle">Admin panel — requires Super Admin role</div>
          </div>
        </div>

        <div class="auth-notice">
          <span class="icon">🛡️</span>
          These endpoints require <span class="role-chip super" style="display:inline;padding:2px 8px;margin:0 4px">Super Admin</span> role and a valid Bearer token.
        </div>

        <div class="endpoint-card" id="ep-users-list">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-users-list')">
            <span class="method-badge GET">GET</span>
            <span class="endpoint-path"><span class="base">/api</span>/admin/users</span>
            <span class="endpoint-desc">List all users</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="params-label">Query Parameters</div>
            <table class="params-table">
              <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
              <tbody>
                <tr><td><span class="param-name">page</span></td><td><span class="param-type">integer</span></td><td><span class="param-optional">optional</span></td><td class="param-desc">Page number (default: 1)</td></tr>
                <tr><td><span class="param-name">per_page</span></td><td><span class="param-type">integer</span></td><td><span class="param-optional">optional</span></td><td class="param-desc">Results per page (default: 15)</td></tr>
                <tr><td><span class="param-name">role</span></td><td><span class="param-type">string</span></td><td><span class="param-optional">optional</span></td><td class="param-desc">Filter by role</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="endpoint-card" id="ep-user-get">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-user-get')">
            <span class="method-badge GET">GET</span>
            <span class="endpoint-path"><span class="base">/api</span>/admin/users/<span style="color:var(--amber)">{id}</span></span>
            <span class="endpoint-desc">Get single user</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="params-label">URL Parameters</div>
            <table class="params-table">
              <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
              <tbody>
                <tr><td><span class="param-name">id</span></td><td><span class="param-type">integer</span></td><td><span class="param-required">required</span></td><td class="param-desc">User ID</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="endpoint-card" id="ep-user-delete">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-user-delete')">
            <span class="method-badge DELETE">DELETE</span>
            <span class="endpoint-path"><span class="base">/api</span>/admin/users/<span style="color:var(--amber)">{id}</span></span>
            <span class="endpoint-desc">Delete user</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="params-label">URL Parameters</div>
            <table class="params-table">
              <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
              <tbody>
                <tr><td><span class="param-name">id</span></td><td><span class="param-type">integer</span></td><td><span class="param-required">required</span></td><td class="param-desc">User ID to delete</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- VENDOR STATUS -->
      <section class="section" id="vendor-status">
        <div class="section-header">
          <div class="section-number">11</div>
          <div>
            <div class="section-title">Vendor Status</div>
            <div class="section-subtitle">Approve or reject vendor accounts</div>
          </div>
        </div>

        <div class="auth-notice">
          <span class="icon">🛡️</span>
          Requires <span class="role-chip super" style="display:inline;padding:2px 8px;margin:0 4px">Super Admin</span> role and Bearer token.
        </div>

        <div class="endpoint-card" id="ep-vendor">
          <div class="endpoint-header" onclick="toggleEndpoint('ep-vendor')">
            <span class="method-badge PUT">PUT</span>
            <span class="endpoint-path"><span class="base">/api</span>/admin/vendors/<span style="color:var(--amber)">{id}</span>/status</span>
            <span class="endpoint-desc">Update status</span>
            <span class="endpoint-arrow">▶</span>
          </div>
          <div class="endpoint-body">
            <div class="params-label">Body Parameters</div>
            <table class="params-table">
              <thead><tr><th>Field</th><th>Type</th><th>Status</th><th>Description</th></tr></thead>
              <tbody>
                <tr><td><span class="param-name">status</span></td><td><span class="param-type">string</span></td><td><span class="param-required">required</span></td><td class="param-desc"><code style="font-family:var(--mono);font-size:12px">approved</code>, <code style="font-family:var(--mono);font-size:12px">rejected</code>, or <code style="font-family:var(--mono);font-size:12px">pending</code></td></tr>
                <tr><td><span class="param-name">reason</span></td><td><span class="param-type">string</span></td><td><span class="param-optional">optional</span></td><td class="param-desc">Reason for rejection</td></tr>
              </tbody>
            </table>
            <div style="margin-top:14px">
              <div class="code-block">
                <div class="code-header"><span class="code-lang">cURL</span><button class="code-copy" onclick="copyCode(this)">Copy</button></div>
<pre><span class="kw">curl</span> --request PUT \
  --url https://yourapp.com/api/admin/vendors/5/status \
  --header <span class="str">'Authorization: Bearer YOUR_TOKEN'</span> \
  --header <span class="str">'Content-Type: application/json'</span> \
  --data <span class="str">'{
  "status": "approved"
}'</span></pre>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div><!-- /content -->
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
    allContent.forEach(el => { if (!el.classList.contains('tabs')) el.style.display = 'none'; });
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
  }, { rootMargin: '-20% 0px -70% 0px' });

  sections.forEach(section => observer.observe(section));
</script>

</body>
</html>