<?php

use App\Http\Controllers\Tenant\PaymentMethodController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('payment-methods', PaymentMethodController::class);
});
