# Onboarding Feature - Quick Reference Card

## 📋 What's New

Complete onboarding system with 3-level hierarchy: **Organization → Workspace → Project**

## 🔗 Endpoint

```
POST /api/auth/onboarding/complete
Authorization: Bearer {JWT_TOKEN}
```

## 📊 Created Data Hierarchy

```
Organization (user_id, name, slug, onboarding_questions, onboarding_answers)
    ↓
Workspace (organization_id, user_id, name, slug, description, icon, color, plan)
    ↓
Project (workspace_id, name, slug, description, status)
```

## 📝 Request Body Structure

```json
{
  "organization": {
    "name": "string*",
    "description": "string?"
  },
  "workspace": {
    "name": "string*",
    "description": "string?",
    "icon": "string?",
    "color": "string?",
    "plan": "string?" // free|pro|enterprise
  },
  "project": {
    "name": "string*",
    "description": "string?",
    "status": "string?" // active|inactive|archived
  },
  "onboarding": {
    "questions": "array?",
    "answers": "array?"
  }
}
```
*Required | ?Optional

## ✅ Response Format

```json
{
  "status": "success",
  "message": "Onboarding completed successfully",
  "data": {
    "organization": { "id": 1, "name": "...", "slug": "..." },
    "workspace": { "id": 1, "name": "...", "slug": "..." },
    "project": { "id": 1, "name": "...", "slug": "..." }
  }
}
```

## 📦 Files Created

| File | Type | Purpose |
|------|------|---------|
| `Organization.php` | Model | Organization entity with relationships |
| `OnboardingService.php` | Service | Core onboarding business logic |
| `2026_03_02_000001_*.php` | Migration | Create organizations table |
| `2026_03_02_000002_*.php` | Migration | Add organization_id to workspaces |
| `2026_03_02_000003_*.php` | Migration | Add workspace_id to projects |
| `2026_03_02_000004_*.php` | Migration | Add onboarding to users table |

## 📝 Files Updated

| File | Changes |
|------|---------|
| `Workspace.php` | Added organization relationship + slug generation |
| `Project.php` | Added workspace relationship + slug generation |
| `AuthController.php` | Added `completeOnboarding()` method |
| `routes/api.php` | Added `/api/auth/onboarding/complete` route |

## 🎯 Key Features

| Feature | Details |
|---------|---------|
| Auto-Slug Generation | Names converted to URL-safe slugs with collision detection |
| Transaction Safety | All-or-nothing database operations |
| JSON Storage | Questions/answers stored as JSON for flexibility |
| Cascade Delete | Deleting organization cascades to workspaces → projects |
| User Tracking | User ID, completion flag, and timestamp recorded |
| Service Pattern | Reusable service layer for business logic |

## 🔐 Database Schema Changes

### Organizations Table
```sql
CREATE TABLE organizations (
  id BIGINT PRIMARY KEY,
  user_id BIGINT NOT NULL,
  name VARCHAR(255),
  slug VARCHAR(255) UNIQUE,
  description TEXT,
  onboarding_questions JSON,
  onboarding_answers JSON,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### Workspaces Table (Updated)
```sql
ALTER TABLE workspaces ADD organization_id BIGINT;
ALTER TABLE workspaces ADD slug VARCHAR(255) UNIQUE;
```

### Projects Table (Updated)
```sql
ALTER TABLE projects ADD workspace_id BIGINT;
ALTER TABLE projects ADD slug VARCHAR(255) UNIQUE;
```

### Users Table (Updated)
```sql
ALTER TABLE users ADD onboarding_completed BOOLEAN DEFAULT false;
ALTER TABLE users ADD onboarding_completed_at TIMESTAMP;
```

## 🚀 Quick Test

1. **Register**: `POST /api/auth/register/customer`
2. **Copy token** from response
3. **Onboard**: `POST /api/auth/onboarding/complete` with token in Authorization header
4. **Verify**: Check database for organization, workspace, project records

## 📊 HTTP Status Codes

| Code | Scenario |
|------|----------|
| 201 | ✅ Onboarding successful |
| 401 | ❌ Not authenticated |
| 422 | ❌ Validation failed |
| 500 | ❌ Server error |

## 🔑 Important Notes

- **Slugs are unique** within their scope (org globally, workspace in org, project in workspace)
- **Transaction-based** - if any step fails, all changes rollback
- **User ID auto-set** from authenticated user
- **Questions/Answers as JSON** - flexible structure for any q&a format
- **Default values** provided if optional fields omitted

## 📖 Documentation Files

1. `ONBOARDING_API_DOCS.md` - Complete API documentation
2. `ONBOARDING_TESTING_GUIDE.md` - Testing examples and cURL commands
3. `ONBOARDING_IMPLEMENTATION.md` - Implementation details
4. `ONBOARDING_QUICK_REFERENCE.md` - This file

## 🔄 Service Method Signature

```php
class OnboardingService {
    public function completeOnboarding(User $user, array $data): array
}
```

**Returns:**
```php
[
    'status' => 'success|error',
    'message' => 'Description',
    'data' => [
        'organization' => ['id', 'name', 'slug'],
        'workspace' => ['id', 'name', 'slug'],
        'project' => ['id', 'name', 'slug']
    ]
]
```

## 🎓 Usage Example

```php
// In controller
$onboardingService = new OnboardingService(
    new WorkspaceService(),
    new ProjectService()
);

$result = $onboardingService->completeOnboarding($user, [
    'organization' => ['name' => 'My Org'],
    'workspace' => ['name' => 'My Workspace'],
    'project' => ['name' => 'My Project'],
    'onboarding' => [
        'questions' => [...],
        'answers' => [...]
    ]
]);
```

## 🛠️ Admin/Dev Tasks

- [ ] Run migrations: `php artisan migrate`
- [ ] Test registration endpoint
- [ ] Get JWT token from login
- [ ] Test onboarding endpoint
- [ ] Verify database records
- [ ] Test validation errors
- [ ] Test slug uniqueness
- [ ] Verify cascading deletes

## ❓ Troubleshooting

| Problem | Solution |
|---------|----------|
| "Unauthenticated" | Add Authorization header with Bearer token |
| "Required field missing" | Check request body has all required fields |
| "Slug already exists" | System auto-appends number (my-org → my-org-1) |
| "Migration errors" | Ensure fresh database or run migrate:fresh |

## 📞 Support Files

- **API Docs**: See `ONBOARDING_API_DOCS.md`
- **Testing**: See `ONBOARDING_TESTING_GUIDE.md`
- **Implementation**: See `ONBOARDING_IMPLEMENTATION.md`
- **Code**: Check `AuthController.php` → `completeOnboarding()`

---

**Last Updated**: March 2, 2026
**Status**: ✅ Ready for Use
