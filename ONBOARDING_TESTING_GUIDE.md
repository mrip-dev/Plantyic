# Onboarding API - Quick Testing Guide

## Prerequisites
1. Register a new customer account
2. Get the JWT token from login response
3. Use Postman, cURL, or any API client

## Step-by-Step Testing

### Step 1: Register a New Customer
**Endpoint:** `POST /api/auth/register/customer`

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "+1234567890",
  "country": "USA",
  "state": "California",
  "address": "123 Main St"
}
```

**Expected Response (201):**
```json
{
  "status": "success",
  "message": "Customer registered successfully",
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { ... }
}
```

**Save the `access_token` for next steps!**

---

### Step 2: Complete Onboarding
**Endpoint:** `POST /api/auth/onboarding/complete`

**Headers:**
```
Authorization: Bearer YOUR_ACCESS_TOKEN
Content-Type: application/json
```

**Request Body (Full Example):**
```json
{
  "organization": {
    "name": "Acme Corp",
    "description": "Our awesome company"
  },
  "workspace": {
    "name": "Marketing Team",
    "description": "Marketing department workspace",
    "icon": "briefcase",
    "color": "#FF6B6B",
    "plan": "pro"
  },
  "project": {
    "name": "Q1 Campaign",
    "description": "First quarter marketing campaign",
    "status": "active"
  },
  "onboarding": {
    "questions": [
      {
        "id": 1,
        "question": "What is your primary business focus?",
        "type": "select",
        "options": ["B2B", "B2C", "B2B2C"]
      },
      {
        "id": 2,
        "question": "How many employees do you have?",
        "type": "number"
      }
    ],
    "answers": [
      {
        "question_id": 1,
        "answer": "B2B"
      },
      {
        "question_id": 2,
        "answer": "50"
      }
    ]
  }
}
```

**Expected Response (201):**
```json
{
  "status": "success",
  "message": "Onboarding completed successfully",
  "data": {
    "organization": {
      "id": 1,
      "name": "Acme Corp",
      "slug": "acme-corp"
    },
    "workspace": {
      "id": 1,
      "name": "Marketing Team",
      "slug": "marketing-team"
    },
    "project": {
      "id": 1,
      "name": "Q1 Campaign",
      "slug": "q1-campaign"
    }
  }
}
```

---

## Minimal Request (Only Required Fields)

```json
{
  "organization": {
    "name": "My Organization"
  },
  "workspace": {
    "name": "My Workspace"
  },
  "project": {
    "name": "My Project"
  }
}
```

---

## Testing Without Onboarding Data

```json
{
  "organization": {
    "name": "Test Company"
  },
  "workspace": {
    "name": "Default Workspace"
  },
  "project": {
    "name": "First Project"
  }
}
```

---

## Error Test Cases

### Missing Required Fields
**Request:**
```json
{
  "organization": {
    "description": "Missing name"
  },
  "workspace": {
    "name": "Test"
  },
  "project": {
    "name": "Test"
  }
}
```

**Expected Response (422):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "organization.name": ["The organization.name field is required."]
  }
}
```

---

### Without Authentication Token
**Headers:**
```
Content-Type: application/json
(No Authorization header)
```

**Expected Response (401):**
```json
{
  "message": "Unauthenticated."
}
```

---

### Invalid Token
**Headers:**
```
Authorization: Bearer invalid_token_here
Content-Type: application/json
```

**Expected Response (401):**
```json
{
  "message": "Invalid token."
}
```

---

## Verifying Created Data

After successful onboarding, verify in your database:

```sql
-- Check Organization
SELECT * FROM organizations WHERE id = 1;

-- Check Workspace
SELECT * FROM workspaces WHERE organization_id = 1;

-- Check Project
SELECT * FROM projects WHERE workspace_id = 1;

-- Check User Onboarding Status
SELECT id, name, onboarding_completed, onboarding_completed_at FROM users WHERE id = 1;
```

---

## cURL Examples

### Register Customer
```bash
curl -X POST "http://localhost:8000/api/auth/register/customer" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890"
  }'
```

### Complete Onboarding
```bash
curl -X POST "http://localhost:8000/api/auth/onboarding/complete" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "organization": {
      "name": "TechStart Inc",
      "description": "Innovation-focused tech startup"
    },
    "workspace": {
      "name": "Engineering",
      "description": "Engineering team workspace",
      "plan": "pro"
    },
    "project": {
      "name": "Backend API",
      "description": "RESTful API development",
      "status": "active"
    }
  }'
```

---

## Postman Collection Template

```json
{
  "info": {
    "name": "Onboarding API Testing",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Register Customer",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "url": {
          "raw": "{{base_url}}/api/auth/register/customer",
          "host": ["{{base_url}}"],
          "path": ["api", "auth", "register", "customer"]
        },
        "body": {
          "mode": "raw",
          "raw": "{\n  \"name\": \"Test User\",\n  \"email\": \"test@example.com\",\n  \"password\": \"password123\",\n  \"password_confirmation\": \"password123\",\n  \"phone\": \"+1234567890\"\n}"
        }
      }
    },
    {
      "name": "Complete Onboarding",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}"
          },
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "url": {
          "raw": "{{base_url}}/api/auth/onboarding/complete",
          "host": ["{{base_url}}"],
          "path": ["api", "auth", "onboarding", "complete"]
        },
        "body": {
          "mode": "raw",
          "raw": "{\n  \"organization\": {\n    \"name\": \"Test Organization\",\n    \"description\": \"Test description\"\n  },\n  \"workspace\": {\n    \"name\": \"Test Workspace\",\n    \"description\": \"Workspace description\",\n    \"plan\": \"free\"\n  },\n  \"project\": {\n    \"name\": \"Test Project\",\n    \"description\": \"Project description\"\n  }\n}"
        }
      }
    }
  ]
}
```

---

## Common Issues & Solutions

### Issue: "Token not provided" or "Unauthenticated"
**Solution:** Make sure you include the Authorization header with Bearer token from login response

### Issue: "organization.name is required"
**Solution:** Ensure all required fields are included in the request body

### Issue: Slug already exists error
**Solution:** This is handled automatically with numeric suffixes (e.g., `my-org-1`, `my-org-2`)

### Issue: Database migration errors
**Solution:** Ensure all migrations are run with `php artisan migrate` before testing

---

## Success Indicators

✅ User can register successfully and receives JWT token
✅ Onboarding endpoint accepts valid request and returns 201 status
✅ Organization, Workspace, and Project records created in database
✅ Slugs are auto-generated and unique
✅ User's `onboarding_completed` flag is set to true
✅ Timestamp `onboarding_completed_at` is recorded
✅ Onboarding questions and answers stored as JSON in organization

---

## Response Status Codes Reference

| Code | Meaning | When It Occurs |
|------|---------|---|
| 201 | Created | Onboarding completed successfully |
| 400 | Bad Request | Malformed JSON or request format issue |
| 401 | Unauthorized | Missing or invalid authentication token |
| 422 | Unprocessable Entity | Validation failed (missing/invalid fields) |
| 500 | Internal Server Error | Database error or unexpected server error |

---

## Database Queries to Verify

```sql
-- Get all organizations for a user
SELECT * FROM organizations WHERE user_id = 1;

-- Get all workspaces in an organization
SELECT * FROM workspaces WHERE organization_id = 1;

-- Get all projects in a workspace
SELECT * FROM projects WHERE workspace_id = 1;

-- Get onboarding data
SELECT onboarding_questions, onboarding_answers FROM organizations WHERE id = 1;
```

---

## Notes for Testing

1. **Each test run creates new records** - Use different organization/workspace names each time to avoid slug conflicts
2. **JWT tokens expire** - The `expires_in` field shows token lifetime in seconds
3. **Cascading deletes** - Deleting an organization will delete all its workspaces and projects
4. **Slug uniqueness scope**:
   - Organization slugs: globally unique
   - Workspace slugs: unique per organization
   - Project slugs: unique per workspace
