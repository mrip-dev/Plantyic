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
        <div class="sidebar-section">
            <div class="sidebar-section-label">Workspace Module</div>
            <a href="#list-workspaces" class="nav-item"><span class="nav-dot"></span> List All Workspaces</a>
            <a href="#create-workspace" class="nav-item"><span class="nav-dot"></span> Create Workspace</a>
            <a href="#workspace-details" class="nav-item"><span class="nav-dot"></span> Get Details</a>
            <a href="#update-workspace" class="nav-item"><span class="nav-dot"></span> Update Workspace</a>
            <a href="#delete-workspace" class="nav-item"><span class="nav-dot"></span> Delete / Archive</a>
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
            <section class="section" id="list-workspaces">
                <div class="section-header">
                    <div class="section-number">03</div>
                    <div>
                        <div class="section-title">List Workspaces</div>
                        <div class="section-subtitle"><span class="method get">GET</span> /api/workspace</div>
                    </div>
                </div>
                <p class="prose">Returns a list of all workspaces the authenticated user has access to.</p>

                <div class="code-block">
                    <div class="code-header"><span class="code-lang">Sample Response</span></div>
                    <pre>{
    "success": true,
    "message": "Workspace list fetched successfully",
    "error_code": null,
    "data": [
        {
            "id": 5,
            "name": "Demo Workspace",
            "description": "Workspace for testing",
            "icon": "icon.png",
            "color": "blue",
            "plan": "pro",
            "created_at": "2026-02-20T11:13:37.000000Z",
            "updated_at": "2026-02-20T11:13:37.000000Z"
        }
    ]
}</pre>
                </div>
            </section>

            <section class="section" id="create-workspace">
                <div class="section-header">
                    <div class="section-number">04</div>
                    <div>
                        <div class="section-title">Create Workspace</div>
                        <div class="section-subtitle"><span class="method post">POST</span> /api/workspace</div>
                    </div>
                </div>
                <p class="prose">Initialize a new workspace. The creator is automatically assigned the <span class="role-chip super">Super Admin</span> role for this workspace.</p>

                <p class="params-label">Body Parameters</p>
                <table class="params-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>name</td>
                            <td>string</td>
                            <td>Required. The display name of the workspace.</td>
                        </tr>
                        <tr>
                            <td>description</td>
                            <td>string</td>
                            <td>Optional. A brief summary of the workspace purpose.</td>
                        </tr>
                        <tr>
                            <td>color</td>
                            <td>string</td>
                            <td>Optional. The color theme for the workspace (e.g., "blue", "green").</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="section" id="workspace-details">
                <div class="section-header">
                    <div class="section-number">05</div>
                    <div>
                        <div class="section-title">Get Workspace Details</div>
                        <div class="section-subtitle"><span class="method get">GET</span> /api/workspace/{id}</div>
                    </div>
                </div>
                <p class="prose">Fetch metadata, member counts, and recent activity for a specific workspace.</p>
            </section>

            <section class="section" id="update-workspace">
                <div class="section-header">
                    <div class="section-number">06</div>
                    <div>
                        <div class="section-title">Update Workspace</div>
                        <div class="section-subtitle"><span class="method put">PUT</span> /api/workspace/{id}</div>
                    </div>
                </div>
                <p class="prose">Modify settings, names, or branding for an existing workspace.</p>
            </section>

            <section class="section" id="delete-workspace">
                <div class="section-header">
                    <div class="section-number">07</div>
                    <div>
                        <div class="section-title">Delete Workspace</div>
                        <div class="section-subtitle"><span class="method delete">DELETE</span> /api/workspace/{id}</div>
                    </div>
                </div>
                <p class="prose" style="color:#ff5555">Warning: This action is permanent and will remove all associated projects and data.</p>
            </section>

        </div><!-- /content -->
    </main>
</div>

@endsection