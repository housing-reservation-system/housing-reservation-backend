<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shared\AuthController;
use App\Http\Controllers\Shared\PasswordResetController;


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/verify-email', 'verifyEmail');
    Route::post('/resend-verification-code', 'resendVerificationCode');
});

Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/forgot-password', 'sendResetLink');
    Route::post('/reset-password', 'resetPassword');
});

Route::post('/update-password', [AuthController::class, 'updatePassword']);
