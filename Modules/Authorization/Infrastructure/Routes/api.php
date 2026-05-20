<?php

use Illuminate\Support\Facades\Route;
use Modules\Authorization\Presentation\Http\Controllers\PermissionController;
use Modules\Authorization\Presentation\Http\Controllers\RoleController;
use Modules\Authorization\Presentation\Http\Controllers\RolePermissionController;
use Modules\Authorization\Presentation\Http\Controllers\UserPermissionController;
use Modules\Authorization\Presentation\Http\Controllers\UserRoleController;

Route::get('/test-authorization', function () {
    return 'test-authorization';
});

Route::middleware(['auth:sanctum', 'isVerified'])->group(function () {

//'check.login.time'

    Route::apiResource('roles', RoleController::class)->middleware([
        'index' => 'abac:roles:view',
        'show' => 'abac:roles:view',
        'store' => 'abac:roles:create',
        'update' => 'abac:roles:edit',
        'destroy' => 'abac:roles:delete',
    ]);
    Route::apiResource('permissions', PermissionController::class)->middleware([
        'index' => 'abac:permissions:view',
        'show' => 'abac:permissions:view',
        'store' => 'abac:permissions:create',
        'update' => 'abac:permissions:edit',
        'destroy' => 'abac:permissions:delete',
    ]);

    Route::apiResource('users.roles', UserRoleController::class)->middleware([
        'index' => 'abac:roles:view',
        'store' => 'abac:roles:create',
        'destroy' => 'abac:roles:delete',
    ]);

    Route::apiResource('roles.permissions', RolePermissionController::class)->middleware([
        'index' => 'abac:permissions:view',
        'store' => 'abac:permissions:create',
        'destroy' => 'abac:permissions:delete',
    ]);

    Route::apiResource('users.permissions', UserPermissionController::class)->middleware([
        'index' => 'abac:permissions:view',
        'store' => 'abac:permissions:create',
        'destroy' => 'abac:permissions:delete',
    ]);

    Route::put('/users/{user}/roles', [UserRoleController::class, 'sync'])
        ->middleware('abac:roles:edit');

    Route::put('/users/{user}/permissions', [UserPermissionController::class, 'sync'])
        ->middleware('abac:permissions:edit');

    Route::put('/roles/{role}/permissions',
        [RolePermissionController::class, 'sync'])->middleware('abac:permissions:edit');
    Route::delete('/roles/{role}/permissions',
        [RolePermissionController::class, 'bulkDelete'])->middleware('abac:permissions:delete');
    Route::delete('/users/{user}/permissions',
        [UserPermissionController::class, 'bulkDelete'])->middleware('abac:permissions:edit');
    Route::delete('/users/{user}/roles', [UserRoleController::class, 'bulkDelete'])
        ->middleware('abac:roles:delete');


    // posts:view — viewer can only see published, editor/admin can see all
    Route::get('/posts', function () {
        return 'You are here';
    })->middleware(['abac:posts:view', 'field.guard:posts:view']);

    // posts:create — no conditions, just having the permission is enough
    Route::post('/posts', function () {
        return 'You are here';
    })->middleware(['abac:posts:create', 'field.guard:posts:create']);

    // posts:edit — editor can only edit their own posts (owner_only condition)
    // Route model binding passes {post} → AbacMiddleware extracts owner_id automatically
    Route::put('/posts/{post}', function () {
        return 'You are here';
    })->middleware(['abac:posts:edit', 'field.guard:posts:edit']);


    // posts:delete — same owner_only condition for editors
    Route::delete('/posts/{post}', function () {
        return 'You are here';
    })->middleware('abac:posts:delete');

    // users:manage — admin only, no conditions
    Route::get('/users', function () {

        return 'You are here';
    })->middleware('abac:users:manage');

});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'data' => [],
        'errors' => 'Page not found',
        'message' => '',
        'status' => 404
    ], 404);

});

//Route::get('/broadcast', function () {
//    broadcast(new PermissionAssignedForRole(
//        roleId: new RoleId(1),
//        permissionId: new PermissionId(1),
//    ));
//    echo 1;
//});
