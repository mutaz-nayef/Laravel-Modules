# Authorization Module API Documentation

This document describes how to use the Authorization (ABAC) module built with Laravel using DDD architecture.

---

## Base URL

http://localhost:8000/api

---

## Authentication Overview

This module provides:

- Role (CRUD)
- Permission (CRUD)
- Role & Permission
- User & Role
- User & Permission

All endpoints return JSON responses.

---

## Role

### Get All Roles

``GET
``
http://localhost:8000/api/roles

#### Headers

``
Authorization: Bearer {token}
``

### Success Response Json

```json
{
    "success": true,
    "data": [
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
        },
        {
            "id": 2,
            "name": "editor",
            "display_name": "Editor",
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
                }
            ]
        },
        {
            "id": 3,
            "name": "viewer",
            "display_name": "Viewer",
            "permissions": [
                {
                    "id": 1,
                    "name": "posts:view",
                    "group": "posts"
                }
            ]
        },
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
    ],
    "errors": "",
    "message": "Roles returned",
    "status": 200
}
```

----

### STORE Role

#### Endpoint

``POST
``
http://localhost:8000/api/roles

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field        | Type   | Required | Rules                     | Description       |
|--------------|--------|----------|---------------------------|-------------------|
| name         | string | Yes      | unique in database, min:2 | Role unique name  |
| display_name | string | Yes      | min:2                     | Role display name |

```json
{
    "name": "admin",
    "display_name": "System Administrator"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 5,
        "name": "admin",
        "display_name": "System Administrator",
        "permissions": []
    },
    "errors": "",
    "message": "Role Created.",
    "status": 200
}
```

--- 

### Update Role

#### Endpoint

``PATCH
``
http://localhost:8000/api/roles/1

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field        | Type   | Required | Rules                     | Description       |
|--------------|--------|----------|---------------------------|-------------------|
| name         | string | Yes      | unique in database, min:2 | Role unique name  |
| display_name | string | Yes      | min:2                     | Role display name |

```json
{
    "name": "admin",
    "display_name": "Updated Administrator"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 4,
        "name": "admin",
        "display_name": "Updated Administrator",
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
    },
    "errors": "",
    "message": "RoleModel updated.",
    "status": 200
}
```

---

### DELETE Role

#### Endpoint

``DELETE
``
http://localhost:8000/api/roles/1

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
    "message": "Role deleted successfully.",
    "status": 200
}
```

### SHOW Role

#### Endpoint

``GET
``
http://localhost:8000/api/roles/4

#### Headers

``
Authorization: Bearer {token}
``

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 4,
        "name": "user",
        "display_name": "System User",
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
    },
    "errors": "",
    "message": "RoleModel Returned.",
    "status": 200
}
```

---

## Permission

### Get All Permissions

``GET
``
http://localhost:8000/api/permissions

#### Headers

``
Authorization: Bearer {token}
``

### Success Response Json

```json
{
    "success": true,
    "data": [
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
    ],
    "errors": "",
    "message": "Permissions returned",
    "status": 200
}
```

----

### STORE Permission

#### Endpoint

``POST
``
http://localhost:8000/api/permissions

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field | Type   | Required | Rules                                                                 | Description                        |
|-------|--------|----------|-----------------------------------------------------------------------|------------------------------------|
| name  | string | Yes      | unique in database, must be like this resource:action e.g. posts:edit | Permission unique name, posts:view |
| group | string | Yes      | min:2,   resource name                                                | Resource name, posts               |

```json
{
    "name": "posts:delete",
    "group": "posts"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 8,
        "name": "home:test",
        "group": "home"
    },
    "errors": "",
    "message": "Permission Created.",
    "status": 200
}
```

--- 

### Update Permission

#### Endpoint

``PATCH
``
http://localhost:8000/api/permissions/1

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field | Type   | Required | Rules                                                                 | Description                        |
|-------|--------|----------|-----------------------------------------------------------------------|------------------------------------|
| name  | string | Yes      | unique in database, must be like this resource:action e.g. posts:edit | Permission unique name, posts:view |
| group | string | Yes      | min:2,   resource name                                                | Resource name, posts               |

```json
{
    "name": "posts:view",
    "group": "posts"
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 8,
        "name": "home:test",
        "group": "home"
    },
    "errors": "",
    "message": "Permission updated.",
    "status": 200
}
```

---

### DELETE Permission

#### Endpoint

``DELETE
``
http://localhost:8000/api/permission/1

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
    "message": "Permission deleted successfully.",
    "status": 200
}
```

---

### SHOW Permission

#### Endpoint

``GET
``
http://localhost:8000/api/permissions/7

#### Headers

``
Authorization: Bearer {token}
``

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 7,
        "name": "home:view",
        "group": "home"
    },
    "errors": "",
    "message": "Permission Returned.",
    "status": 200
}
```

---

## Role & Permissions

### STORE Permission for given Role

#### EndPoint

``POST
``  
http://localhost:8000/api/roles/4/permissions

#### Headers

``
Authorization: Bearer {token}
``

#### Request Body

| Field       | Type  | Required | Rules                                                      | Description                        |
|-------------|-------|----------|------------------------------------------------------------|------------------------------------|
| permissions | array | Yes      | the permissions must be exists in database e.g. posts:edit | Permission unique name, posts:view |

```json
{
    "permissions": [
        "home:view"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 4,
        "name": "user",
        "display_name": "System User",
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
    },
    "errors": "",
    "message": "Role stored permissions successfully.",
    "status": 200
}
```

---

### SYNC Permission for given Role

#### EndPoint

``PUT
``  
http://localhost:8000/api/roles/4/permissions

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field       | Type  | Required | Rules                                                      | Description                        |
|-------------|-------|----------|------------------------------------------------------------|------------------------------------|
| permissions | array | Yes      | the permissions must be exists in database e.g. posts:edit | Permission unique name, posts:view |

```json
{
    "permissions": [
        "home:view"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "admin",
        "display_name": "Administrator",
        "permissions": [
            {
                "id": 7,
                "name": "home:view",
                "group": "home"
            }
        ]
    },
    "errors": "",
    "message": "Role permission synced",
    "status": 200
}
```

---

### Bulk Remove Permission for given Role

#### EndPoint

``DELETE
``  
http://localhost:8000/api/roles/1/permissions

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field       | Type  | Required | Rules                                                      | Description                        |
|-------------|-------|----------|------------------------------------------------------------|------------------------------------|
| permissions | array | Yes      | the permissions must be exists in database e.g. posts:edit | Permission unique name, posts:view |

```json
{
    "permissions": [
        "home:view"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
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
    },
    "errors": "",
    "message": "Permissions posts:view removed successfully.",
    "status": 200
}
```

---

### DELETE Permission for given Role

#### Endpoint

``DELETE
``
http://localhost:8000/api/roles/4/permissions/7

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
    "message": "Role deleted permission successfully.",
    "status": 200
}
```

---

## User & Permissions

### GET Permission for given User

#### EndPoint

``GET
``  
http://localhost:8000/api/users/1/permissions

#### Headers

``
Authorization: Bearer {token}
``

#### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Mutaz Nayef",
        "email": "mutaz@example.com",
        "permissions": []
    },
    "errors": "",
    "message": "User permissions returned successfully.",
    "status": 200
}
```

---

### STORE Permission for given User

#### EndPoint

``POST
``  
http://localhost:8000/api/users/2/permissions

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field       | Type  | Required | Rules                                                      | Description                        |
|-------------|-------|----------|------------------------------------------------------------|------------------------------------|
| permissions | array | Yes      | the permissions must be exists in database e.g. posts:edit | Permission unique name, posts:view |

```json
{
    "permissions": [
        "home:view"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "test",
        "email": "ayman12@email.com",
        "permissions": [
            {
                "id": 7,
                "name": "home:view",
                "group": "home"
            }
        ]
    },
    "errors": "",
    "message": "User stored permissions successfully.",
    "status": 200
}
```

---

### SYNC Permission for given User

#### EndPoint

``PUT
``  
http://localhost:8000/api/users/4/permissions

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field       | Type  | Required | Rules                                                      | Description                        |
|-------------|-------|----------|------------------------------------------------------------|------------------------------------|
| permissions | array | Yes      | the permissions must be exists in database e.g. posts:edit | Permission unique name, posts:view |

```json
{
    "permissions": [
        "home:view"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "test",
        "email": "ayman12@email.com",
        "permissions": [
            {
                "id": 7,
                "name": "home:view",
                "group": "home"
            }
        ]
    },
    "errors": "",
    "message": "User permissions synced",
    "status": 200
}
```

### Bulk Remove Permission for given user

#### EndPoint

``DELETE
``  
http://localhost:8000/api/users/1/permissions

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field       | Type  | Required | Rules                                                      | Description                        |
|-------------|-------|----------|------------------------------------------------------------|------------------------------------|
| permissions | array | Yes      | the permissions must be exists in database e.g. posts:edit | Permission unique name, posts:view |

```json
{
    "permissions": [
        "home:view"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "editor",
        "email": "editor@example.com",
        "roles": [
            {
                "id": 2,
                "name": "editor",
                "display_name": "Editor",
                "permissions": [
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
        ],
        "permissions": []
    },
    "errors": "",
    "message": "Permissions home:view removed successfully.",
    "status": 200
}
```

### DELETE Permission for given User

#### Endpoint

``DELETE
``
http://localhost:8000/api/users/4/permissions/7

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
    "message": "User removed permission successfully.",
    "status": 200
}
```

---         


If you encounter issues or need further clarification, refer back to this documentation or contact the API provider.

## User & Role

### GET Role for given User

#### EndPoint

``GET
``  
http://localhost:8000/api/users/1/roles

#### Headers

``
Authorization: Bearer {token}
``

#### Success Response Json

```json
{
    "success": true,
    "data": {
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
                    }
                ]
            }
        ],
        "permissions": []
    },
    "errors": "",
    "message": "User roles returned successfully.",
    "status": 200
}
```

---

### STORE Role for given User

#### EndPoint

``POST
``  
http://localhost:8000/api/users/2/roles

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field | Type  | Required | Rules                                | Description      |
|-------|-------|----------|--------------------------------------|------------------|
| roles | array | Yes      | the roles must be exists in database | Role unique name |

```json
{
    "roles": [
        "editor"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
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
                    }
                ]
            },
            {
                "id": 2,
                "name": "editor",
                "display_name": "Editor",
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
                    }
                ]
            }
        ],
        "permissions": []
    },
    "errors": "",
    "message": "User stored roles successfully.",
    "status": 200
}
```

---

### SYNC Permission for given User

#### EndPoint

``PUT
``  
http://localhost:8000/api/users/4/roles

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field | Type  | Required | Rules                                | Description      |
|-------|-------|----------|--------------------------------------|------------------|
| roles | array | Yes      | the roles must be exists in database | Role unique name |

```json
{
    "roles": [
        "admin"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "test",
        "email": "ayman12@email.com",
        "roles": [
            {
                "id": 2,
                "name": "editor",
                "display_name": "Editor",
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
                    }
                ]
            }
        ],
        "permissions": []
    },
    "errors": "",
    "message": "User roles synced",
    "status": 200
}
```

### Bulk Remove Role for given user

#### EndPoint

``DELETE
``  
http://localhost:8000/api/users/1/roles

#### Headers

``
Authorization: Bearer {token}
``

### Request Body

| Field | Type  | Required | Rules                                | Description |
|-------|-------|----------|--------------------------------------|-------------|
| roles | array | Yes      | the roles must be exists in database | ["editor"]  |

```json
{
    "roles": [
        "editor"
    ]
}
```

### Success Response Json

```json
{
    "success": true,
    "data": {
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
    },
    "errors": "",
    "message": "Permissions posts:view removed successfully.",
    "status": 200
}
```

### DELETE Role for given User

#### Endpoint

``DELETE
``
http://localhost:8000/api/users/2/roles/2

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
    "message": "User removed role successfully.",
    "status": 200
}
```

---         


If you encounter issues or need further clarification, refer back to this documentation or contact the API provider.
