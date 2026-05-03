<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Authentication\Presentation\Http\Controllers\AuthController;
use Modules\Authentication\Presentation\Http\Controllers\EmailVerificationController;
use Modules\Authentication\Presentation\Http\Controllers\PasswordResetController;
use Modules\Authentication\Presentation\Http\Middleware\EnsureEmailIsVerified;
use Modules\Authentication\Presentation\Http\Middleware\EnsureUserCanStayLoggedIn;

Route::get('/test', function () {
    return 'test';
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [PasswordResetController::class, 'forget'])->name('password.request');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->name('verification.send');

    Route::get('/verify-email/{id}/{hash}',
        [EmailVerificationController::class, 'verify'])->name('verification.verify');


    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', EnsureEmailIsVerified::class, EnsureUserCanStayLoggedIn::class])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
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
