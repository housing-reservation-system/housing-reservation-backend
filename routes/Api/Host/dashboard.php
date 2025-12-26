<?php

use App\Http\Controllers\Host\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'role:Host'])->prefix('host')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
