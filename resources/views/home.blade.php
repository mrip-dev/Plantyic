@extends('layouts.main')
@section('title', 'Bidding API Documentation')
@section('content')
<div class="layout">

  <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h2>Dashboard</h2>
            <br>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-label">Overview</div>

        </div>

    </aside>
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

        </div><!-- /content -->
    </main>
   </div>
    @endsection