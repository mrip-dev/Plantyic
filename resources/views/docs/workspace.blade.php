@extends('layouts.main')
@section('title', 'Auth API Documentation')
@section('content')

<div class="layout">

    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h2>Workspace</h2>
            <br>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-label">Overview</div>
            <a href="#introduction" class="nav-item active"><span class="nav-dot"></span> Introduction</a>
            <a href="#authentication" class="nav-item"><span class="nav-dot"></span> Authentication</a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-label">All Endpoints</div>

        </div>


    </aside>

    <!-- ─── MAIN ─── -->
    <main class="main">



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
                    <pre>
                        <span class="key">Authorization</span><span class="punc">: </span><span class="str">Bearer YOUR_TOKEN_HERE</span>
                        <span class="key">Content-Type</span><span class="punc">: </span><span class="str">application/json</span>
                        <span class="key">Accept</span><span class="punc">: </span><span class="str">application/json</span>
                    </pre>
                </div>
            </section>


        </div><!-- /content -->
    </main>
</div>

@endsection

