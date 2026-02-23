@extends('layouts.main')
@section('title', 'Auth API Documentation')
@section('content')

<div class="layout">

    <!-- ─── SIDEBAR ─── -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h2>Auth</h2>
            <br>
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
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="param-name">name</span></td>
                                        <td><span class="param-type">string</span></td>
                                        <td><span class="param-required">required</span></td>
                                        <td class="param-desc">User's full name</td>
                                    </tr>
                                    <tr>
                                        <td><span class="param-name">email</span></td>
                                        <td><span class="param-type">string</span></td>
                                        <td><span class="param-required">required</span></td>
                                        <td class="param-desc">Valid email address</td>
                                    </tr>
                                    <tr>
                                        <td><span class="param-name">password</span></td>
                                        <td><span class="param-type">string</span></td>
                                        <td><span class="param-required">required</span></td>
                                        <td class="param-desc">Min 8 characters</td>
                                    </tr>
                                    <tr>
                                        <td><span class="param-name">password_confirmation</span></td>
                                        <td><span class="param-type">string</span></td>
                                        <td><span class="param-required">required</span></td>
                                        <td class="param-desc">Must match password</td>
                                    </tr>
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
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="param-name">email</span></td>
                                        <td><span class="param-type">string</span></td>
                                        <td><span class="param-required">required</span></td>
                                        <td class="param-desc">Registered email</td>
                                    </tr>
                                    <tr>
                                        <td><span class="param-name">password</span></td>
                                        <td><span class="param-type">string</span></td>
                                        <td><span class="param-required">required</span></td>
                                        <td class="param-desc">Account password</td>
                                    </tr>
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
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="param-name">email</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">Registered email address</td>
                                </tr>
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
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="param-name">token</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">Reset token from email</td>
                                </tr>
                                <tr>
                                    <td><span class="param-name">email</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">User's email address</td>
                                </tr>
                                <tr>
                                    <td><span class="param-name">password</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">New password (min 8 chars)</td>
                                </tr>
                                <tr>
                                    <td><span class="param-name">password_confirmation</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">Must match password</td>
                                </tr>
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
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="param-name">token</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">Verification token from email</td>
                                </tr>
                                <tr>
                                    <td><span class="param-name">email</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">User's email address</td>
                                </tr>
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
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="param-name">page</span></td>
                                    <td><span class="param-type">integer</span></td>
                                    <td><span class="param-optional">optional</span></td>
                                    <td class="param-desc">Page number (default: 1)</td>
                                </tr>
                                <tr>
                                    <td><span class="param-name">per_page</span></td>
                                    <td><span class="param-type">integer</span></td>
                                    <td><span class="param-optional">optional</span></td>
                                    <td class="param-desc">Results per page (default: 15)</td>
                                </tr>
                                <tr>
                                    <td><span class="param-name">role</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-optional">optional</span></td>
                                    <td class="param-desc">Filter by role</td>
                                </tr>
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
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="param-name">id</span></td>
                                    <td><span class="param-type">integer</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">User ID</td>
                                </tr>
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
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="param-name">id</span></td>
                                    <td><span class="param-type">integer</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc">User ID to delete</td>
                                </tr>
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
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="param-name">status</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-required">required</span></td>
                                    <td class="param-desc"><code style="font-family:var(--mono);font-size:12px">approved</code>, <code style="font-family:var(--mono);font-size:12px">rejected</code>, or <code style="font-family:var(--mono);font-size:12px">pending</code></td>
                                </tr>
                                <tr>
                                    <td><span class="param-name">reason</span></td>
                                    <td><span class="param-type">string</span></td>
                                    <td><span class="param-optional">optional</span></td>
                                    <td class="param-desc">Reason for rejection</td>
                                </tr>
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

@endsection