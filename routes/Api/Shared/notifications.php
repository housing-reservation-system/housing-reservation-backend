<?php

use App\Http\Controllers\Shared\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getAllNotifications']);
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
});
