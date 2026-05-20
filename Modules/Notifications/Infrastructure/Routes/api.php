<?php

use Illuminate\Support\Facades\Route;
use Modules\Notifications\Presentation\Http\Controllers\NotificationController;
use Modules\Notifications\Presentation\Http\Controllers\NotificationPreferencesController;


Route::get('/test-notifications', function () {
    return 'test-notifications';
});

Route::middleware(['auth:sanctum', 'isVerified'])->group(function () {

    Route:
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications_preferences', [NotificationPreferencesController::class, 'index']);
    Route::fallback(function () {
        return response()->json([
            'success' => false,
            'data' => [],
            'errors' => 'Page not found',
            'message' => '',
            'status' => 404
        ], 404);

    });
});

