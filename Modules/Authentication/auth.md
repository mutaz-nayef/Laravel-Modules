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
- Refresh Access Token

All endpoints return JSON responses.

---

## Register

“When a new user registers, the user will receive a verification email to confirm the email address.”

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
    "success": true,
    "data": {
        "user": {
            "id": 6,
            "name": "test",
            "email": "test3@email.com",
            "roles": [
                {
                    "id": 4,
                    "name": "user",
                    "display_name": "User",
                    "permissions": [
                        {
                            "id": 11,
                            "name": "home:view",
                            "group": "home"
                        },
                        {
                            "id": 12,
                            "name": "posts:view",
                            "group": "posts"
                        }
                    ]
                }
            ]
        },
        "permissions": [],
        "token": {
            "access_token": "19|LhCq8ecL5k3SssXTPG6WaLRQglldhI4TWEEtf7z40d902771",
            "refresh_token": "20|Q15588xniw9tU2vsx3k1XiWAZUpxdb6tJBFnbXTt7cf21f2a",
            "token_type": "Bearer"
        }
    },
    "errors": "",
    "message": "Authenticated, We Sent you email to verify you email!",
    "status": 200
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
                            "name": "roles:view",
                            "group": "roles"
                        },
                        {
                            "id": 2,
                            "name": "roles:create",
                            "group": "roles"
                        },
                        {
                            "id": 3,
                            "name": "roles:edit",
                            "group": "roles"
                        },
                        {
                            "id": 4,
                            "name": "roles:delete",
                            "group": "roles"
                        },
                        {
                            "id": 5,
                            "name": "permissions:view",
                            "group": "permissions"
                        },
                        {
                            "id": 6,
                            "name": "permissions:create",
                            "group": "permissions"
                        },
                        {
                            "id": 7,
                            "name": "permissions:edit",
                            "group": "permissions"
                        },
                        {
                            "id": 8,
                            "name": "permissions:delete",
                            "group": "permissions"
                        },
                        {
                            "id": 9,
                            "name": "users:view",
                            "group": "users"
                        },
                        {
                            "id": 10,
                            "name": "users:manage",
                            "group": "users"
                        },
                        {
                            "id": 11,
                            "name": "home:view",
                            "group": "home"
                        },
                        {
                            "id": 12,
                            "name": "posts:view",
                            "group": "posts"
                        },
                        {
                            "id": 13,
                            "name": "posts:create",
                            "group": "posts"
                        },
                        {
                            "id": 14,
                            "name": "posts:edit",
                            "group": "posts"
                        },
                        {
                            "id": 15,
                            "name": "posts:delete",
                            "group": "posts"
                        }
                    ]
                }
            ]
        },
        "permissions": [],
        "token": {
            "access_token": "17|vjzP5Yyvqxhm7VvH8Rw1tKzkPMJ9Oxx1JFgGkvwde0d449c8",
            "refresh_token": "18|t3Jee7SkhesedngB5BFY5Vta4298ucDXACDwtCrbacd363b4",
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

## Refresh Access Token

“When a user logs in for the first time, the user receives an access token used for authenticated requests. The access
token is valid for 10 hours before it expires. The user also receives a refresh token, which expires after 30 days and
is used to generate a new access token without requiring the user to log in again. ”

#### Endpoint

``POST
``
http://localhost:8000/api/refresh-token

### Request Body

| Field         | Type   | Required | Rules   | Description        |
|---------------|--------|----------|---------|--------------------|
| refresh_token | string | Yes      | rquired | user refresh token |

```json
{
    "refresh_token": "9|ITN8dfvU5lEvsH8dqBsU9pA8zrAU3P46NJv3HNMS28cbd6c8"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "access_token": "21|840vJNw6k4f68FhTqY3K0w8Khq6E7IDrCWZcHiQj22e17149"
    },
    "errors": "",
    "message": "Authenticated, We Sent you email to verify you email!",
    "status": 200
}
```

## Conclusion

This Authentication module provides a complete flow for user management, including registration, login, logout, password
reset, and email verification.

To successfully integrate with this API:

- Always store and send the `access_token` using the `Authorization: Bearer {token}` header for protected endpoints.
- Follow the validation rules specified for each request body.
- Handle error responses properly, especially validation and authentication errors.
- Use the email-based flows (password reset and email verification) as designed—these rely on secure links sent to the
  user and should not be constructed manually.

---

## Notes

- All responses are returned in JSON format.
- Tokens are required for authenticated routes.
- Email verification and password reset links are time-limited and signed for security.
- Make sure your client application handles token storage securely.

---

## Support

If you encounter issues or need further clarification, refer back to this documentation or contact the API provider.
