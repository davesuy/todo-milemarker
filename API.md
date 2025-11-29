# API Documentation

## Quick Start

**Base URL:** `http://localhost:8000/api`

**Health Check:** `GET /api` - Lists all available endpoints

## Authentication

All protected endpoints require a Bearer token:
```bash
Authorization: Bearer {your-token}
```

### Register
```bash
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
```bash
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

Response includes `token` for authentication.

## Categories

All category endpoints require authentication.

```bash
GET    /api/categories           # List all categories
POST   /api/categories           # Create category
GET    /api/categories/{id}      # Get category
PUT    /api/categories/{id}      # Update category
DELETE /api/categories/{id}      # Delete category
```

**Create/Update Body:**
```json
{
  "name": "Work",
  "color": "#3B82F6"
}
```

## Todos

All todo endpoints require authentication.

```bash
GET    /api/todos                # List all todos (with filters)
POST   /api/todos                # Create todo
GET    /api/todos/{id}           # Get todo
PUT    /api/todos/{id}           # Update todo
DELETE /api/todos/{id}           # Delete todo
POST   /api/todos/{id}/toggle    # Toggle complete status
```

**Create/Update Body:**
```json
{
  "title": "Complete project",
  "description": "Finish the todo app",
  "category_id": 1,
  "due_date": "2025-12-31",
  "is_completed": false
}
```

**Filter Parameters:**
```bash
GET /api/todos?search=project&category_id=1&status=pending&sort_by=due_date&sort_order=asc
```

## Testing with cURL

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Use token from login response
TOKEN="your-token-here"

# List categories
curl http://localhost:8000/api/categories \
  -H "Authorization: Bearer $TOKEN"

# Create category
curl -X POST http://localhost:8000/api/categories \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Work","color":"#3B82F6"}'
```

## Error Responses

```json
{
  "message": "Error description",
  "errors": {
    "field": ["Error detail"]
  }
}
```

Common status codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error

