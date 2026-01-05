<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shared\AuthController;
use App\Http\Controllers\Shared\PasswordResetController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/tenant/register', 'registerTenant');
    Route::post('/host/register', 'registerHost');
    Route::post('/login', 'login');
    Route::post('/verify-email', 'verifyEmail');
    Route::post('/resend-verification-code', 'resendVerificationCode');
    Route::post('/logout', 'logout');
});

Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/forgot-password', 'sendResetLink');
    Route::post('/reset-password', 'resetPassword');
});

Route::middleware('auth:api')->controller(AuthController::class)->group(function () {
    Route::post('/update-password', 'updatePassword');
});
