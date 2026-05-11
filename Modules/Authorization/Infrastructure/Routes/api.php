<?php

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Authorization\Presentation\Http\Controllers\PermissionController;
use Modules\Authorization\Presentation\Http\Controllers\RoleController;
use Modules\Authorization\Presentation\Http\Controllers\RolePermissionController;
use Modules\Authorization\Presentation\Http\Controllers\UserPermissionController;
use Modules\Authorization\Presentation\Http\Controllers\UserRoleController;

Route::get('/test-authorization', function () {
    $users = PersonalAccessToken::query()
        ->with('tokenable')
        ->where('expires_at', '>', now())
        ->get()
        ->pluck('tokenable')
        ->unique('id')
        ->toArray();
    dd($users);
    return 'test-authorization';
});

Route::middleware(['auth:sanctum', 'isVerified'])->group(function () {

//'check.login.time'

    Route::middleware('admin')->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::apiResource('users.roles', UserRoleController::class);
        Route::apiResource('roles.permissions', RolePermissionController::class);
        Route::apiResource('users.permissions', UserPermissionController::class);
        Route::put('/users/{user}/roles', [UserRoleController::class, 'sync']);
        Route::put('/users/{user}/permissions', [UserPermissionController::class, 'sync']);
        Route::put('/roles/{role}/permissions', [RolePermissionController::class, 'sync']);
        Route::delete('/roles/{role}/permissions', [RolePermissionController::class, 'bulkDelete']);
        Route::delete('/users/{user}/permissions', [UserPermissionController::class, 'bulkDelete']);
        Route::delete('/users/{user}/roles', [UserRoleController::class, 'bulkDelete']);
    });

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
