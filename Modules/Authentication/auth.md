# Authentication Module API Documentation

This document describes how to use the Authentication module built with Laravel using DDD architecture.

---

## Base URL

http://localhost:8000/api

---

## Authentication Overview

This module provides:

- Register user
- Login user
- Logout user
- Reset password
- Email Verification

All endpoints return JSON responses.

---

## Register

#### Endpoint

``POST
``
http://localhost:8000/api/register

### Request Body

| Field                 | Type   | Required | Rules               | Description        |
|-----------------------|--------|----------|---------------------|--------------------|
| name                  | string | Yes      | min:3, max:255      | User full name     |
| email                 | string | Yes      | email, unique       | User email address |
| password              | string | Yes      | min:8, confirmed    | User password      |
| password_confirmation | string | Yes      | must match password | Confirm password   |

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "roles": [
                {
                    "id": 4,
                    "name": "user",
                    "display_name": "User",
                    "permissions": [
                        {
                            "id": 1,
                            "name": "posts:view",
                            "group": "posts"
                        },
                        {
                            "id": 7,
                            "name": "home:view",
                            "group": "home"
                        }
                    ]
                }
            ]
        },
        "permissions": [],
        "token": {
            "access_token": "1|y6CTGqjHqLUB8izodoBaWK3ZC8UFPtnCKcNzy2sM0cac398e",
            "token_type": "Bearer"
        }
    },
    "errors": "",
    "message": "Authenticated",
    "status": 200
}
```

----

## Login

#### Endpoint

``POST
``
http://localhost:8000/api/login

### Request Body

| Field    | Type   | Required | Rules                     | Description        |
|----------|--------|----------|---------------------------|--------------------|
| email    | string | Yes      | email, exists in database | User email address |
| password | string | Yes      | min:8                     | User password      |

```json
{
    "email": "mutaz@example.com",
    "password": "password"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Mutaz Nayef",
            "email": "mutaz@example.com",
            "roles": [
                {
                    "id": 1,
                    "name": "admin",
                    "display_name": "Administrator",
                    "permissions": [
                        {
                            "id": 1,
                            "name": "posts:view",
                            "group": "posts"
                        },
                        {
                            "id": 2,
                            "name": "posts:create",
                            "group": "posts"
                        },
                        {
                            "id": 3,
                            "name": "posts:edit",
                            "group": "posts"
                        },
                        {
                            "id": 4,
                            "name": "posts:delete",
                            "group": "posts"
                        },
                        {
                            "id": 5,
                            "name": "users:view",
                            "group": "users"
                        },
                        {
                            "id": 6,
                            "name": "users:manage",
                            "group": "users"
                        },
                        {
                            "id": 7,
                            "name": "home:view",
                            "group": "home"
                        }
                    ]
                }
            ]
        },
        "permissions": [],
        "token": {
            "access_token": "3|1NvZ2TVMBiIs5FWHkSLhcPMCe4RsglOzyac6msTMf21662b7",
            "token_type": "Bearer"
        }
    },
    "errors": "",
    "message": "Authenticated",
    "status": 200
}
```

--- 

## Logout

#### Endpoint

``POST
``
http://localhost:8000/api/logout

#### Headers

``
Authorization: Bearer {token}
``

### Success Response Json

```json
{
    "success": true,
    "data": null,
    "errors": "",
    "message": "Logged out successfully.",
    "status": 200
}
```

---

## Rest Password

- It's go with two steps:

#### First: Forget Password

### Endpoint

POST
`` http://localhost:8000/api/forget-password
``

### Request Body

| Field | Type   | Required | Rules                     | Description        |
|-------|--------|----------|---------------------------|--------------------|
| email | string | Yes      | email, exists in database | User email address |

```json
{
    "email": "mutaz@example.com"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": null,
    "errors": "",
    "message": "We have emailed your password reset link.",
    "status": 200
}
```

#### Second: Reset Password

### Endpoint

POST
`` http://localhost:8000/api/reset-password
``

### Request Body

| Field                 | Type   | Required | Rules                           | Description        |
|-----------------------|--------|----------|---------------------------------|--------------------|
| token                 | string | Yes      | -                               | User email address |
| email                 | string | Yes      | email, exists in database users | User email address |
| password              | string | Yes      | min:8, confirmed                | new password       |
| password_confirmation | string | Yes      | must match password             | Confirm password   |

```json
{
    "token": "fa30346f30411a37e0960049e1d3c36a5c61b8141e59b3cc17d48efe08b6b703",
    "email": "mutaz@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": null,
    "errors": "",
    "message": "Your password has been reset.",
    "status": 200
}
```

---


---

## Email Verification

After registering, the user will receive an email containing a verification link.

### How it works

- When a user registers, a verification email is sent to their email address.
- The email contains a **signed URL**.
- The user must click this link to verify their email.


- It's go with two steps:

#### First: Send Email

### Endpoint

POST
`` http://localhost:8000/api/email/verification-notification
``

#### Headers

``
Authorization: Bearer {token}
``

### Success Response Json

```json
{
    "success": true,
    "data": null,
    "errors": "",
    "message": "Verification link sent!",
    "status": 200
}
```

#### Second: Verify Email

### Endpoint

-- this the url you get from email that it send to you when you require to verify email
GET
`` http://localhost:8000/api/verify-email/2/{hash}?expires={timestamp}&signature={signature}
``

### URL Parameters

| Parameter | Type    | Description                                 |
|-----------|---------|---------------------------------------------|
| id        | integer | User ID                                     |
| hash      | string  | Email hash used to verify the user          |
| expires   | integer | Expiration timestamp of the link            |
| signature | string  | Security signature to prevent URL tampering |

#### Headers

``
Authorization: Bearer {token}
``

### Success Response Json

```json
{
    "success": true,
    "data": null,
    "errors": "",
    "message": "Email has been verified",
    "status": 200
}
```
---

## Conclusion

This Authentication module provides a complete flow for user management, including registration, login, logout, password reset, and email verification.

To successfully integrate with this API:

- Always store and send the `access_token` using the `Authorization: Bearer {token}` header for protected endpoints.
- Follow the validation rules specified for each request body.
- Handle error responses properly, especially validation and authentication errors.
- Use the email-based flows (password reset and email verification) as designed—these rely on secure links sent to the user and should not be constructed manually.

---

## Notes

- All responses are returned in JSON format.
- Tokens are required for authenticated routes.
- Email verification and password reset links are time-limited and signed for security.
- Make sure your client application handles token storage securely.

---

## Support

If you encounter issues or need further clarification, refer back to this documentation or contact the API provider.
