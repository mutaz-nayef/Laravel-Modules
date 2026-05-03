<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Presentation\Http\Middleware\EnsureEmailIsVerified;
use Modules\Authentication\Presentation\Http\Middleware\EnsureUserCanStayLoggedIn;
use Modules\Authorization\Presentation\Http\Controllers\PermissionController;
use Modules\Authorization\Presentation\Http\Controllers\RoleController;
use Modules\Authorization\Presentation\Http\Controllers\RolePermissionController;
use Modules\Authorization\Presentation\Http\Controllers\UserPermissionController;
use Modules\Authorization\Presentation\Http\Controllers\UserRoleController;

Route::get('/test-authorization', function () {
    return 'test-authorization';
});


Route::middleware(['auth:sanctum', EnsureEmailIsVerified::class, EnsureUserCanStayLoggedIn::class])->group(function () {

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('users.roles', UserRoleController::class);
    Route::apiResource('roles.permissions', RolePermissionController::class);
    Route::apiResource('users.permissions', UserPermissionController::class);
    Route::put('/users/{user}/roles', [UserRoleController::class, 'sync']);
    Route::put('/users/{user}/permissions', [UserPermissionController::class, 'sync']);
    Route::put('/roles/{role}/permissions', [RolePermissionController::class, 'sync']);
    Route::patch('/roles/{role}/permissions', [RolePermissionController::class, 'update']);
//    Route::post('/roles/{role}/hasAnyPermission/permissions', [RolePermissionController::class, 'hasAnyPermission']);
//    Route::delete('/roles/{role}/revoke/permissions', [RolePermissionController::class, 'revoke']);

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
