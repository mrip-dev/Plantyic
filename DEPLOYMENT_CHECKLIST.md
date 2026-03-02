# ✅ Onboarding Implementation - Deployment Checklist

## Pre-Deployment Verification

### Phase 1: Database Setup
- [ ] **1.1** Verify all migration files exist:
  - [ ] `2026_03_02_000001_create_organizations_table.php`
  - [ ] `2026_03_02_000002_update_workspaces_table.php`
  - [ ] `2026_03_02_000003_update_projects_table.php`
  - [ ] `2026_03_02_000004_add_onboarding_columns_to_users_table.php`

- [ ] **1.2** Run migrations:
  ```bash
  php artisan migrate
  ```
  Expected output: All migrations should run successfully

- [ ] **1.3** Verify tables in database:
  ```sql
  SHOW TABLES; -- Should include: organizations, workspaces, projects, users
  DESCRIBE organizations; -- Verify columns exist
  DESCRIBE workspaces; -- Should have organization_id, slug
  DESCRIBE projects; -- Should have workspace_id, slug
  DESCRIBE users; -- Should have onboarding_completed, onboarding_completed_at
  ```

### Phase 2: Code Files Verification
- [ ] **2.1** Verify Model files exist:
  - [ ] `app/Models/Organization.php` - NEW
  - [ ] `app/Models/Workspace.php` - UPDATED
  - [ ] `app/Models/Project.php` - UPDATED

- [ ] **2.2** Verify Service files exist:
  - [ ] `app/Services/OnboardingService.php` - NEW

- [ ] **2.3** Verify Controller was updated:
  - [ ] `app/Http/Controllers/Api/AuthController.php`
  - [ ] Check that `OnboardingService` is imported
  - [ ] Check that `completeOnboarding()` method exists

- [ ] **2.4** Verify routes were updated:
  - [ ] `routes/api.php` should have new route:
    ```php
    Route::post('/onboarding/complete', [AuthController::class, 'completeOnboarding']);
    ```

### Phase 3: Code Quality Checks
- [ ] **3.1** Check for PHP syntax errors:
  ```bash
  php artisan tinker
  > require_once 'app/Models/Organization.php';
  > require_once 'app/Services/OnboardingService.php';
  ```

- [ ] **3.2** Verify models can be instantiated:
  ```bash
  php artisan tinker
  > $org = new \App\Models\Organization();
  > echo "Organization model OK";
  > $service = new \App\Services\OnboardingService();
  > echo "OnboardingService model OK";
  ```

- [ ] **3.3** Check namespace and imports:
  - [ ] All `use` statements correct
  - [ ] All classes properly namespaced
  - [ ] No missing imports

### Phase 4: API Route Testing
- [ ] **4.1** List all routes:
  ```bash
  php artisan route:list | grep onboarding
  ```
  Expected: Should see `/api/auth/onboarding/complete` POST route

- [ ] **4.2** Check route is protected:
  ```bash
  php artisan route:list | grep -A5 "onboarding/complete"
  ```
  Expected: Should show `auth:api` middleware

### Phase 5: Functional Testing

#### Test 5.1: Customer Registration
```bash
curl -X POST http://localhost:8000/api/auth/register/customer \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890"
  }'
```
- [ ] Status code: **201**
- [ ] Response includes: `access_token`, `token_type: bearer`
- [ ] Save the `access_token` for next tests

#### Test 5.2: Minimal Onboarding Request
```bash
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "organization": {"name": "Test Org"},
    "workspace": {"name": "Test Workspace"},
    "project": {"name": "Test Project"}
  }'
```
- [ ] Status code: **201**
- [ ] Response structure correct
- [ ] Response includes IDs for org, workspace, project
- [ ] Slugs are auto-generated

#### Test 5.3: Full Onboarding Request
```bash
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "organization": {
      "name": "Complete Test Corp",
      "description": "Full test with all fields"
    },
    "workspace": {
      "name": "Complete Test Workspace",
      "description": "Workspace with full data",
      "icon": "rocket",
      "color": "#FF6B6B",
      "plan": "pro"
    },
    "project": {
      "name": "Complete Test Project",
      "description": "Project with full data",
      "status": "active"
    },
    "onboarding": {
      "questions": [
        {"id": 1, "question": "Test?", "type": "text"}
      ],
      "answers": [
        {"question_id": 1, "answer": "Yes"}
      ]
    }
  }'
```
- [ ] Status code: **201**
- [ ] All fields in response
- [ ] Slugs generated correctly

#### Test 5.4: Validation Errors
```bash
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "workspace": {"name": "Missing Org"},
    "project": {"name": "Test"}
  }'
```
- [ ] Status code: **422**
- [ ] Response includes: `"status": "error"`
- [ ] Response includes: `errors` object with field names

#### Test 5.5: Missing Authentication Token
```bash
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -H "Content-Type: application/json" \
  -d '{
    "organization": {"name": "Test"},
    "workspace": {"name": "Test"},
    "project": {"name": "Test"}
  }'
```
- [ ] Status code: **401**
- [ ] Response includes authentication error message

#### Test 5.6: Invalid Authentication Token
```bash
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -H "Authorization: Bearer INVALID_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "organization": {"name": "Test"},
    "workspace": {"name": "Test"},
    "project": {"name": "Test"}
  }'
```
- [ ] Status code: **401**
- [ ] Response includes invalid token message

### Phase 6: Database Verification

#### Test 6.1: Check Organizations Table
```sql
SELECT * FROM organizations;
```
- [ ] Records created
- [ ] Slugs are unique
- [ ] user_id correctly set
- [ ] JSON fields populated if provided

#### Test 6.2: Check Workspaces Table
```sql
SELECT * FROM workspaces;
```
- [ ] organization_id populated
- [ ] slugs are unique
- [ ] workspace-specific data saved

#### Test 6.3: Check Projects Table
```sql
SELECT * FROM projects;
```
- [ ] workspace_id populated
- [ ] slugs are unique
- [ ] project-specific data saved

#### Test 6.4: Check User Status
```sql
SELECT id, name, onboarding_completed, onboarding_completed_at FROM users WHERE id = 1;
```
- [ ] `onboarding_completed` = true
- [ ] `onboarding_completed_at` has timestamp

#### Test 6.5: Check Relationships
```sql
SELECT o.id, o.name, o.slug, w.name, w.slug, p.name, p.slug
FROM organizations o
LEFT JOIN workspaces w ON o.id = w.organization_id
LEFT JOIN projects p ON w.id = p.workspace_id
WHERE o.user_id = 1;
```
- [ ] Proper hierarchy visible
- [ ] All IDs link correctly

### Phase 7: Slug Generation Testing

#### Test 7.1: Duplicate Slug Handling
```bash
# First onboarding
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -H "Authorization: Bearer TOKEN1" \
  -d '{"organization":{"name":"Acme Corp"},"workspace":{"name":"Test"},"project":{"name":"Test"}}'
# Returns: slug = "acme-corp"

# Second onboarding (same org name)
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -H "Authorization: Bearer TOKEN2" \
  -d '{"organization":{"name":"Acme Corp"},"workspace":{"name":"Test"},"project":{"name":"Test"}}'
# Should return: slug = "acme-corp-1"
```
- [ ] First org: `acme-corp`
- [ ] Second org: `acme-corp-1`
- [ ] Both slugs are unique

#### Test 7.2: Special Characters in Slug
```bash
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -H "Authorization: Bearer TOKEN" \
  -d '{"organization":{"name":"Acme & Co!"},"workspace":{"name":"Test"},"project":{"name":"Test"}}'
```
- [ ] Slug properly formatted: `acme-co` (special chars removed)
- [ ] No errors despite special chars

#### Test 7.3: Workspace Slug Scoping
```bash
# Create workspace in org 1
# Create workspace in org 2 with same name
# Both should be allowed (since scoped to organization)
```
- [ ] Both have same slug (since in different orgs)
- [ ] No uniqueness conflict

### Phase 8: Transaction Safety Testing

#### Test 8.1: Insert Middleware (Intentional Error Testing)
- [ ] Create modified service that throws error on project creation
- [ ] Call endpoint
- [ ] Verify: Organization and workspace NOT created (rollback worked)

#### Test 8.2: Database Consistency
- [ ] Call endpoint 10 times with different data
- [ ] Verify all 10 organizations created
- [ ] Verify count of workspaces = 10
- [ ] Verify count of projects = 10
- [ ] No orphaned records

### Phase 9: Documentation Verification
- [ ] **9.1** Documentation files exist:
  - [ ] `ONBOARDING_API_DOCS.md`
  - [ ] `ONBOARDING_TESTING_GUIDE.md`
  - [ ] `ONBOARDING_IMPLEMENTATION.md`
  - [ ] `ONBOARDING_QUICK_REFERENCE.md`
  - [ ] `ONBOARDING_COMPLETION_SUMMARY.md`
  - [ ] `ONBOARDING_ARCHITECTURE_DIAGRAMS.md`

- [ ] **9.2** Documentation is accurate:
  - [ ] Code examples match actual implementation
  - [ ] Endpoint paths are correct
  - [ ] Field names are correct
  - [ ] Validation rules are accurate

### Phase 10: Performance Testing
- [ ] **10.1** Measure response time:
  ```bash
  time curl -X POST http://localhost:8000/api/auth/onboarding/complete \
    -H "Authorization: Bearer TOKEN" \
    -d '{...}' > /dev/null
  ```
  Expected: < 500ms

- [ ] **10.2** Test with concurrent requests:
  ```bash
  # Run 10 requests in parallel
  for i in {1..10}; do
    curl -X POST http://localhost:8000/api/auth/onboarding/complete \
      -H "Authorization: Bearer TOKEN$i" \
      -d "{...}" &
  done
  wait
  ```
  Expected: All 10 should complete without errors

- [ ] **10.3** Test with large JSON payloads:
  ```bash
  # Include large questions/answers arrays
  curl -X POST http://localhost:8000/api/auth/onboarding/complete \
    -H "Authorization: Bearer TOKEN" \
    -d '{
      "onboarding": {
        "questions": [... 100 questions ...],
        "answers": [... 100 answers ...]
      }
    }'
  ```
  Expected: < 1000ms, success

### Phase 11: Security Testing
- [ ] **11.1** CSRF Protection:
  - [ ] If POST should require CSRF token (check middleware)

- [ ] **11.2** SQL Injection:
  - [ ] Test with special SQL characters in names
  - [ ] Should be sanitized automatically by Laravel

- [ ] **11.3** XSS Protection:
  - [ ] Test with HTML/JS in organization name
  - [ ] Should be escaped when returned

- [ ] **11.4** Rate Limiting:
  - [ ] Check if endpoint has rate limiting
  - [ ] If not, consider adding (optional)

### Phase 12: Edge Cases

#### Test 12.1: Very Long Names
```bash
curl -X POST http://localhost:8000/api/auth/onboarding/complete \
  -d '{"organization":{"name":"'$(python -c "print('A'*300)")'"}}' ...
```
- [ ] Validation rejects names > 255 chars
- [ ] Returns 422 error

#### Test 12.2: Null/Empty Values
```bash
curl ... -d '{
  "organization": {"name": "", "description": null},
  "workspace": {"name": null},
  "project": {"name": ""}
}'
```
- [ ] Validation catches empty required fields
- [ ] Returns 422 error

#### Test 12.3: Invalid Plan Value
```bash
curl ... -d '{
  "workspace": {"plan": "premium"}
}'
```
- [ ] Validation rejects invalid plan values
- [ ] Returns 422 error

#### Test 12.4: Invalid Status Value
```bash
curl ... -d '{
  "project": {"status": "pending"}
}'
```
- [ ] Validation rejects invalid status values
- [ ] Returns 422 error

### Phase 13: Rollback Procedure (If Issues Found)

If critical issues found during testing:
```bash
# Rollback last batch of migrations
php artisan migrate:rollback

# Verify tables removed
SHOW TABLES;
DESCRIBE organizations; -- Should fail

# Verify users table restored to original state
DESCRIBE users; -- Should not have onboarding columns
```
- [ ] Rollback executes successfully
- [ ] All onboarding tables removed
- [ ] User table reverted
- [ ] No orphaned data

---

## Final Sign-Off Checklist

### Code Quality
- [ ] No PHP syntax errors
- [ ] All imports correct
- [ ] All namespaces correct
- [ ] No deprecated methods used
- [ ] Code follows PSR-12 standards

### Functionality
- [ ] All CRUD operations working
- [ ] Validation working correctly
- [ ] Error handling working
- [ ] Transactions working
- [ ] Relationships working

### Database
- [ ] All migrations run successfully
- [ ] All tables created correctly
- [ ] All columns present
- [ ] All foreign keys created
- [ ] Cascading deletes working

### API
- [ ] Endpoint is accessible
- [ ] Authentication required
- [ ] Request validation working
- [ ] Response format correct
- [ ] HTTP status codes correct

### Documentation
- [ ] All docs are present
- [ ] All docs are accurate
- [ ] Examples are working
- [ ] API reference complete
- [ ] Troubleshooting guide present

### Testing
- [ ] Happy path tested
- [ ] Validation errors tested
- [ ] Auth errors tested
- [ ] Database verified
- [ ] Slugs verified

---

## Deployment Sign-Off

**Reviewed By**: _______________
**Date**: _______________
**Status**:
- [ ] ✅ APPROVED - Ready for Production
- [ ] ⚠️  CONDITIONAL - Approved with Notes
- [ ] ❌ NOT APPROVED - Requires Fixes

**Notes**:
```
_____________________________________________
_____________________________________________
_____________________________________________
```

---

## Post-Deployment

- [ ] Monitor error logs for 24 hours
- [ ] Check API response times
- [ ] Verify database performance
- [ ] Monitor user feedback
- [ ] Check for any issues reported

---

## Rollback Plan (If Needed Post-Deployment)

1. **Stop accepting new onboarding requests** - Set API to return 503
2. **Run migration rollback**:
   ```bash
   php artisan migrate:rollback
   ```
3. **Clear cache**:
   ```bash
   php artisan cache:clear
   ```
4. **Restart services**:
   ```bash
   php artisan cache:clear
   php artisan config:cache
   ```
5. **Verify system** - Check database and test endpoints
6. **Communicate** - Notify users of rollback

---

**Last Updated**: March 2, 2026
**Version**: 1.0
**Status**: Ready for Deployment ✅
