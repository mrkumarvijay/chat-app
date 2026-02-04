# API Documentation

Complete REST API documentation for the Laravel 12 Chat Application.

## Base URL

```
http://localhost:8000/api
```

## Authentication

All authenticated endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
```

## Endpoints

### Authentication

#### Register User

**POST** `/register`

Register a new user account.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201 Created):**
```json
{
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2026-02-03T12:30:00.000000Z",
            "updated_at": "2026-02-03T12:30:00.000000Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
    }
}
```

#### Login User

**POST** `/login`

Authenticate user and receive access token.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200 OK):**
```json
{
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2026-02-03T12:30:00.000000Z",
            "updated_at": "2026-02-03T12:30:00.000000Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
    }
}
```

#### Logout User

**POST** `/logout`

Invalidate the current user's token.

**Headers:**
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
```

**Response (200 OK):**
```json
{
    "message": "Successfully logged out"
}
```

### Chat

#### Get Online Users

**GET** `/chat/users`

Get list of all users (excluding current user).

**Headers:**
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
```

**Response (200 OK):**
```json
[
    {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com",
        "created_at": "2026-02-03T12:35:00.000000Z",
        "updated_at": "2026-02-03T12:35:00.000000Z"
    },
    {
        "id": 3,
        "name": "Bob Wilson",
        "email": "bob@example.com",
        "created_at": "2026-02-03T12:40:00.000000Z",
        "updated_at": "2026-02-03T12:40:00.000000Z"
    }
]
```

#### Get Messages with User

**GET** `/chat/{user}`

Get message history with a specific user.

**Path Parameters:**
- `user` (integer): The ID of the user to get messages with

**Headers:**
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
```

**Response (200 OK):**
```json
[
    {
        "id": 1,
        "sender_id": 1,
        "receiver_id": 2,
        "message": "Hello Jane!",
        "created_at": "2026-02-03T13:00:00.000000Z",
        "updated_at": "2026-02-03T13:00:00.000000Z",
        "read_at": null
    },
    {
        "id": 2,
        "sender_id": 2,
        "receiver_id": 1,
        "message": "Hi John! How are you?",
        "created_at": "2026-02-03T13:01:00.000000Z",
        "updated_at": "2026-02-03T13:01:00.000000Z",
        "read_at": "2026-02-03T13:02:00.000000Z"
    }
]
```

#### Send Message

**POST** `/chat/send`

Send a message to another user.

**Headers:**
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
Content-Type: application/json
```

**Request Body:**
```json
{
    "receiver_id": 2,
    "message": "This is a test message"
}
```

**Response (201 Created):**
```json
{
    "message": "Message sent successfully",
    "data": {
        "id": 3,
        "sender_id": 1,
        "receiver_id": 2,
        "message": "This is a test message",
        "created_at": "2026-02-03T13:30:00.000000Z",
        "updated_at": "2026-02-03T13:30:00.000000Z",
        "read_at": null
    }
}
```

## Error Responses

### 401 Unauthorized

When authentication is required but missing or invalid:

```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden

When user is not authorized to access a resource:

```json
{
    "message": "This action is unauthorized."
}
```

### 422 Unprocessable Entity

When validation fails:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

### 500 Internal Server Error

When an unexpected server error occurs:

```json
{
    "message": "Server Error"
}
```

## Postman Collection

A complete Postman collection is available in `docs/postman/chat-application.postman_collection.json` with all endpoints pre-configured for testing.

## Usage Examples

### Using cURL

```bash
# Register a new user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Get users (with token)
curl -X GET http://localhost:8000/api/chat/users \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
```

### Using JavaScript Fetch

```javascript
// Login and get token
const loginResponse = await fetch('/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password123'
    })
});

const { token } = await loginResponse.json();

// Get users
const usersResponse = await fetch('/api/chat/users', {
    headers: { 'Authorization': `Bearer ${token}` }
});

const users = await usersResponse.json();
```

## Rate Limiting

The API includes rate limiting to prevent abuse:

- **Login attempts**: 5 attempts per minute
- **Registration**: 3 attempts per minute
- **General API**: 60 requests per minute

Rate limit headers are included in responses:
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1643865600
