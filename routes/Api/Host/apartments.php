<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Host\ApartmentController;

Route::middleware(['auth:api', 'role:Host','approved'])->prefix('host')->name('host.')->group(function () {
    Route::apiResource('apartments', ApartmentController::class);
    Route::post('apartments/{apartment}/images', [ApartmentController::class, 'updateImages'])
        ->name('apartments.images.update');
});
