<?php

use App\Http\Controllers\Tenant\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('tenant/bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::post('/', [BookingController::class, 'store']);
    Route::get('/{booking}', [BookingController::class, 'show']);
    Route::put('/{booking}', [BookingController::class, 'update']);
    Route::post('/{booking}/cancel', [BookingController::class, 'cancel']);
});
