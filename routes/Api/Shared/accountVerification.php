<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shared\AccountVerificationController;

Route::middleware('auth:api')->controller(AccountVerificationController::class)->group(function () {    
    Route::post('/verify-account', 'verifyAccount');
    Route::get('/verification-status', 'getVerificationStatus');
});