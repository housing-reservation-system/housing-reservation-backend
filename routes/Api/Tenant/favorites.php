<?php

use App\Http\Controllers\Tenant\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix('tenant/favorites')->group(function () {
    Route::post('{apartmentsId}/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/', [FavoriteController::class, 'index']);
});
