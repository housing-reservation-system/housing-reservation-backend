<?php

use App\Http\Controllers\Host\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('host/bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::post('/{booking}/approve', [BookingController::class, 'approve']);
    Route::post('/{booking}/reject', [BookingController::class, 'reject']);
});
