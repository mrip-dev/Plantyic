# Onboarding API Documentation

## Overview
After a customer successfully registers, they can complete the onboarding process which creates:
1. **Organization** - The top-level container (auto-generates slug)
2. **Workspace** - Belongs to Organization (auto-generates slug)
3. **Project** - Belongs to Workspace (auto-generates slug)

Additionally, onboarding questions and answers are saved as JSON in the organization.

## Endpoint

**POST** `/api/auth/onboarding/complete`

### Authentication
Required: Bearer Token (JWT)

### Request Headers
```
Authorization: Bearer YOUR_JWT_TOKEN
Content-Type: application/json
```

### Request Body

```json
{
  "organization": {
    "name": "Acme Corporation",
    "description": "Leading innovators in technology"
  },
  "workspace": {
    "name": "Product Development",
    "description": "Main workspace for product teams",
    "icon": "rocket",
    "color": "#FF6B6B",
    "plan": "pro"
  },
  "project": {
    "name": "Mobile App v2.0",
    "description": "Building the next generation mobile app",
    "status": "active"
  },
  "onboarding": {
    "questions": [
      {
        "id": 1,
        "question": "What is your business type?",
        "type": "select",
        "options": ["SaaS", "E-commerce", "Consulting", "Other"]
      },
      {
        "id": 2,
        "question": "How many team members do you have?",
        "type": "number"
      }
    ],
    "answers": [
      {
        "question_id": 1,
        "answer": "SaaS",
        "answered_at": "2026-03-02T10:30:00Z"
      },
      {
        "question_id": 2,
        "answer": "15",
        "answered_at": "2026-03-02T10:35:00Z"
      }
    ]
  }
}
```

### Request Field Descriptions

#### Organization Fields
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Organization name (max: 255 chars) |
| description | string | No | Organization description (max: 1000 chars) |

**Note:** Slug is automatically generated from the name

#### Workspace Fields
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Workspace name (max: 255 chars) |
| description | string | No | Workspace description (max: 1000 chars) |
| icon | string | No | Icon identifier (max: 100 chars) |
| color | string | No | Hex color code (max: 50 chars) |
| plan | string | No | Plan type: `free`, `pro`, `enterprise` (default: `free`) |

**Note:** Slug is automatically generated from the name

#### Project Fields
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Project name (max: 255 chars) |
| description | string | No | Project description (max: 1000 chars) |
| status | string | No | Project status: `active`, `inactive`, `archived` (default: `active`) |

**Note:** Slug is automatically generated from the name

#### Onboarding Fields (Optional)
| Field | Type | Description |
|-------|------|-------------|
| questions | array | Array of question objects with id, question, type, and options |
| answers | array | Array of answer objects with question_id, answer, and answered_at |

### Response (Success)

```json
{
  "status": "success",
  "message": "Onboarding completed successfully",
  "data": {
    "organization": {
      "id": 1,
      "name": "Acme Corporation",
      "slug": "acme-corporation"
    },
    "workspace": {
      "id": 1,
      "name": "Product Development",
      "slug": "product-development"
    },
    "project": {
      "id": 1,
      "name": "Mobile App v2.0",
      "slug": "mobile-app-v2-0"
    }
  }
}
```

### Response (Error - Validation)

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "organization.name": [
      "The organization.name field is required."
    ]
  }
}
```

### Response Codes

| Code | Status | Description |
|------|--------|-------------|
| 201 | Created | Onboarding completed successfully |
| 401 | Unauthorized | User not authenticated |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Internal Server Error | Server error during onboarding |

## Slug Generation

Slugs are automatically generated from names:
- Converted to lowercase
- Spaces and special characters replaced with hyphens
- If a slug already exists, a number is appended (e.g., `product-development-1`)
- Slugs are unique within their scope:
  - Organization slugs are unique globally
  - Workspace slugs are unique within an organization
  - Project slugs are unique within a workspace

## Database Relationships

```
User
├── Organization (one-to-many, user_id)
    ├── Workspace (one-to-many, organization_id)
    │   └── Project (one-to-many, workspace_id)
    └── onboarding_questions (JSON)
    └── onboarding_answers (JSON)
```

## Example cURL Request

```bash
curl -X POST "http://localhost:8000/api/auth/onboarding/complete" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "organization": {
      "name": "Acme Corporation",
      "description": "Our organization"
    },
    "workspace": {
      "name": "Product Development",
      "description": "Product team workspace",
      "icon": "rocket",
      "color": "#FF6B6B",
      "plan": "pro"
    },
    "project": {
      "name": "Mobile App v2.0",
      "description": "New mobile app",
      "status": "active"
    },
    "onboarding": {
      "questions": [
        {
          "id": 1,
          "question": "What is your business type?",
          "type": "select",
          "options": ["SaaS", "E-commerce", "Consulting"]
        }
      ],
      "answers": [
        {
          "question_id": 1,
          "answer": "SaaS"
        }
      ]
    }
  }'
```

## Example JavaScript Request (Fetch API)

```javascript
const completeOnboarding = async (token, onboardingData) => {
  try {
    const response = await fetch('/api/auth/onboarding/complete', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(onboardingData)
    });

    const data = await response.json();

    if (!response.ok) {
      console.error('Onboarding failed:', data.errors || data.message);
      throw new Error(data.message);
    }

    console.log('Onboarding completed:', data.data);
    return data.data;
  } catch (error) {
    console.error('Error:', error);
    throw error;
  }
};
```

## Notes

1. **Transaction Safety**: The entire onboarding process is wrapped in a database transaction. If any step fails, all changes are rolled back.

2. **User Association**: The authenticated user's ID is automatically associated with the organization.

3. **Cascading Relationships**:
   - When an organization is deleted, all associated workspaces and projects are deleted
   - When a workspace is deleted, all associated projects are deleted

4. **Default Values**:
   - If organization or workspace names are not provided, defaults are used
   - Project status defaults to 'active'
   - Workspace plan defaults to 'free'

5. **Optional Fields**: All fields except required names can be omitted from the request to use defaults or skip saving.

6. **One-Time Operation**: The endpoint can be called multiple times by the same user, creating new organizations and related items each time.
