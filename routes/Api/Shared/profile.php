<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shared\ProfileController;

Route::middleware('auth:api')->controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'show');
    Route::put('/profile', 'update');
    Route::post('/profile', 'update');
});