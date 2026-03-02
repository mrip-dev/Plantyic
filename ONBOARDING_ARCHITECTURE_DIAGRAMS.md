# 🏗️ Onboarding System Architecture & Data Flow

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                        CLIENT APPLICATION                           │
│                    (Web/Mobile/Desktop)                             │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                      API Request (HTTP)
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        API GATEWAY / ROUTING                         │
│                      (routes/api.php)                               │
│  POST /api/auth/register/customer                                   │
│  POST /api/auth/login                                               │
│  POST /api/auth/onboarding/complete ◄── YOUR NEW ENDPOINT          │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│              AuthController::completeOnboarding()                    │
│                                                                     │
│  1. Validate Input ✓                                                │
│  2. Authenticate User ✓                                             │
│  3. Inject OnboardingService ✓                                      │
│  4. Call Service Method ✓                                           │
│  5. Update User Status ✓                                            │
│  6. Return Response ✓                                               │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│                  OnboardingService                                   │
│              (Business Logic Layer)                                  │
│                                                                     │
│  ┌──────────────────────────────────────────────────────────┐      │
│  │  completeOnboarding()                                    │      │
│  │  ├─ BEGIN TRANSACTION                                   │      │
│  │  │                                                      │      │
│  │  ├─ createOrganization()                                │      │
│  │  │  └─ Generate Slug                                    │      │
│  │  │  └─ Create Record                                    │      │
│  │  │                                                      │      │
│  │  ├─ createWorkspace()                                   │      │
│  │  │  └─ Generate Slug (scoped to org)                    │      │
│  │  │  └─ Create Record                                    │      │
│  │  │                                                      │      │
│  │  ├─ createProject()                                     │      │
│  │  │  └─ Generate Slug (scoped to workspace)              │      │
│  │  │  └─ Create Record                                    │      │
│  │  │                                                      │      │
│  │  ├─ saveOnboardingData()                                │      │
│  │  │  └─ Update Org with Q&A JSON                         │      │
│  │  │                                                      │      │
│  │  ├─ COMMIT / ROLLBACK                                   │      │
│  │  └─ Return Result Array                                 │      │
│  └──────────────────────────────────────────────────────────┘      │
└────────────────────────────────┬────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      DATABASE                                        │
│                     (MySQL/PostgreSQL)                              │
│                                                                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │
│  │   users      │  │organizations │  │ workspaces   │              │
│  ├──────────────┤  ├──────────────┤  ├──────────────┤              │
│  │ id           │  │ id           │  │ id           │              │
│  │ user_id ────┼─►│ user_id      │  │organization_ │              │
│  │ name         │  │ name         │  │id ──────────→┤              │
│  │ email        │  │ slug         │  │ slug         │              │
│  │ ...          │  │ description  │  │ name         │              │
│  │onboarding_   │  │questions(JSON)  │ ...          │              │
│  │completed     │  │answers(JSON) │  └──────────────┘              │
│  │completed_at  │  └──────────────┘                                │
│  └──────────────┘                      ┌──────────────┐            │
│                                         │  projects    │            │
│                                         ├──────────────┤            │
│                                         │ id           │            │
│                                         │workspace_id ─┼───────┐    │
│                                         │ slug         │       │    │
│                                         │ name         │       │    │
│                                         │ ...          │       │    │
│                                         └──────────────┘       │    │
│                                                                │    │
│  Relationships:                                               │    │
│  ├─ User 1 → Many Organizations (user_id)                   │    │
│  ├─ Organization 1 → Many Workspaces (organization_id)      │    │
│  └─ Workspace 1 → Many Projects (workspace_id) ◄────────────┘    │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Request/Response Flow Diagram

```
┌──────────────────────────────────────────────────────────────────┐
│  CLIENT REQUEST                                                  │
│  POST /api/auth/onboarding/complete                             │
│  Authorization: Bearer {JWT_TOKEN}                              │
│                                                                  │
│  Body:                                                           │
│  {                                                               │
│    "organization": {"name": "Acme Corp"},                        │
│    "workspace": {"name": "Engineering"},                         │
│    "project": {"name": "Mobile App"},                            │
│    "onboarding": {"questions": [...], "answers": [...]}         │
│  }                                                               │
└──────────────────────────┬───────────────────────────────────────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────────────┐
│  AUTHCONTROLLER VALIDATION                                       │
│  ├─ Check JWT Token ✓                                            │
│  ├─ Validate Request Fields ✓                                    │
│  ├─ Check Required Fields ✓                                      │
│  └─ Respond with Errors (422) if Invalid ✗                       │
└──────────────────────────────┬───────────────────────────────────┘
                               │ (Valid)
                               ▼
┌──────────────────────────────────────────────────────────────────┐
│  ONBOARDING SERVICE EXECUTION                                   │
│  ├─ START TRANSACTION                                            │
│  ├─ Create Organization                                          │
│  │  ├─ Generate Slug: "acme-corp"                               │
│  │  └─ INSERT INTO organizations                                │
│  │                                                              │
│  ├─ Create Workspace                                            │
│  │  ├─ Generate Slug: "engineering"                             │
│  │  ├─ Add organization_id = 1                                  │
│  │  └─ INSERT INTO workspaces                                   │
│  │                                                              │
│  ├─ Create Project                                              │
│  │  ├─ Generate Slug: "mobile-app"                              │
│  │  ├─ Add workspace_id = 1                                     │
│  │  └─ INSERT INTO projects                                     │
│  │                                                              │
│  ├─ Save Onboarding Data                                        │
│  │  ├─ UPDATE organizations SET                                 │
│  │  │  onboarding_questions = {...},                            │
│  │  │  onboarding_answers = {...}                               │
│  │  │                                                            │
│  │                                                              │
│  └─ COMMIT TRANSACTION (on success) or ROLLBACK (on error)     │
└──────────────────────────────┬───────────────────────────────────┘
                               │
                               ▼
┌──────────────────────────────────────────────────────────────────┐
│  UPDATE USER STATUS                                              │
│  UPDATE users SET                                                │
│    onboarding_completed = true,                                  │
│    onboarding_completed_at = NOW()                               │
│  WHERE id = {authenticated_user_id}                              │
└──────────────────────────────┬───────────────────────────────────┘
                               │
                               ▼
┌──────────────────────────────────────────────────────────────────┐
│  SUCCESSRESPONSE (201 CREATED)                                   │
│  {                                                               │
│    "status": "success",                                          │
│    "message": "Onboarding completed successfully",               │
│    "data": {                                                     │
│      "organization": {                                           │
│        "id": 1,                                                  │
│        "name": "Acme Corp",                                      │
│        "slug": "acme-corp"                                       │
│      },                                                          │
│      "workspace": {                                              │
│        "id": 1,                                                  │
│        "name": "Engineering",                                    │
│        "slug": "engineering"                                     │
│      },                                                          │
│      "project": {                                                │
│        "id": 1,                                                  │
│        "name": "Mobile App",                                     │
│        "slug": "mobile-app"                                      │
│      }                                                           │
│    }                                                             │
│  }                                                               │
└──────────────────────────────┬───────────────────────────────────┘
                               │
                               ▼
                      ┌─────────────────┐
                      │ CLIENT RECEIVES │
                      │ RESPONSE (201)  │
                      │ IDs OF CREATED  │
                      │ ENTITIES        │
                      └─────────────────┘
```

---

## Slug Generation Flow

```
Input: "My Organization Name"
        │
        ▼
   Convert to Slug
   ├─ Lowercase: "my organization name"
   ├─ Remove special chars
   ├─ Replace spaces: "my-organization-name"
        │
        ▼
   Check Uniqueness
   ├─ Query DB for "my-organization-name"
   ├─ Already exists? YES ──┐
   │                         │
   │                         ▼
   │                    Append Counter
   │                    ├─ Try "my-organization-name-1"
   │                    ├─ Exists? Yes → try "my-organization-name-2"
   │                    ├─ Exists? No ← USE THIS
   │                         │
   └─────────────────────────┘
        │
        ▼
   Return Slug: "my-organization-name-2"
```

---

## Transaction Safety Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                  TRANSACTION START                              │
│              BEGIN TRANSACTION                                 │
└────────────────────────┬────────────────────────────────────────┘
                         │
         ┌───────────────┼───────────────┐
         ▼               ▼               ▼
    ┌─────────┐   ┌─────────┐   ┌─────────┐
    │   Org   │   │Workspace│   │Project  │
    │ Created │   │ Created │   │ Created │
    └────┬────┘   └────┬────┘   └────┬────┘
         │             │             │
         └─────────────┼─────────────┘
                       │
              ┌────────┴────────┐
              │                 │
         SUCCESS           ERROR
              │                 │
              ▼                 ▼
         ┌─────────┐       ┌──────────┐
         │ COMMIT  │       │ ROLLBACK │
         │         │       │          │
         │ ALL OK  │       │ DELETE   │
         │ ✓✓✓     │       │ ALL ✗✗✗  │
         └─────────┘       └──────────┘
              │                 │
              ▼                 ▼
    Data Saved       Data Unchanged
    & Consistent     Database Clean
```

---

## Authentication Flow

```
┌─────────────────────────────────────────────────────────────────┐
│  USER REGISTRATION                                              │
│  POST /api/auth/register/customer                               │
│  → Returns JWT Token                                            │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
             ┌─────────────────────────┐
             │  JWT Token Obtained     │
             │  eyJ0eXAiOiJKV1QiLCJh  │
             │  lGc5NJ...             │
             └────────────┬────────────┘
                          │
                          ▼
      ┌───────────────────────────────────┐
      │ USER CALLS ONBOARDING ENDPOINT    │
      │ POST /api/auth/onboarding/complete│
      │ Header: Authorization: Bearer...  │
      └────────────┬──────────────────────┘
                   │
                   ▼
         ┌─────────────────────┐
         │  MIDDLEWARE CHECK   │
         │  'auth:api'         │
         └────────┬────────────┘
                  │
         ┌────────┴────────┐
         │                 │
    TOKEN VALID        TOKEN INVALID
         │                 │
         ▼                 ▼
    ┌─────────┐       ┌────────────┐
    │ Proceed │       │ 401 Error  │
    │   ✓     │       │ Abort      │
    └─────────┘       └────────────┘
         │
         ▼
   EXECUTE ENDPOINT
```

---

## Database Relationships Diagram

```
USERS TABLE
┌─────────────────────────────────────┐
│ id (PK)                             │
│ name                                │
│ email                               │
│ onboarding_completed (NEW)          │
│ onboarding_completed_at (NEW)       │
│ ...                                 │
└─────────────────────────────────────┘
         │ (1)
         │ user_id
         │
         │ (Many)
         ▼
ORGANIZATIONS TABLE (NEW)
┌─────────────────────────────────────┐
│ id (PK)                             │
│ user_id (FK)                        │
│ name                                │
│ slug (Unique)                       │
│ description                         │
│ onboarding_questions (JSON)         │
│ onboarding_answers (JSON)           │
│ created_at, updated_at              │
└─────────────────────────────────────┘
         │ (1)
         │ organization_id (NEW)
         │
         │ (Many)
         ▼
WORKSPACES TABLE (UPDATED)
┌─────────────────────────────────────┐
│ id (PK)                             │
│ organization_id (FK) (NEW)          │
│ user_id (FK)                        │
│ name                                │
│ slug (NEW - Unique)                 │
│ description                         │
│ icon, color                         │
│ plan                                │
│ created_at, updated_at              │
└─────────────────────────────────────┘
         │ (1)
         │ workspace_id (NEW)
         │
         │ (Many)
         ▼
PROJECTS TABLE (UPDATED)
┌─────────────────────────────────────┐
│ id (PK)                             │
│ workspace_id (FK) (NEW)             │
│ name                                │
│ slug (NEW - Unique)                 │
│ description                         │
│ tasks, completed, members           │
│ status, dueDate, createdAt          │
│ created_at, updated_at              │
└─────────────────────────────────────┘

CASCADE DELETE RULES:
- DELETE User → DELETE Organizations → DELETE Workspaces → DELETE Projects
- DELETE Organization → DELETE Workspaces → DELETE Projects
- DELETE Workspace → DELETE Projects
```

---

## Summary

The onboarding system provides a **complete, transactional, and validated** solution for creating the three-level hierarchy with automatic slug generation, JSON data storage, and proper relationships. Every step is auditable, and the system handles errors gracefully with automatic rollback.
