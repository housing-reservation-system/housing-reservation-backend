<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shared\ChatController;

Route::middleware('auth:api')->prefix('chats')->controller(ChatController::class)->group(function () {
    Route::post('/send', 'sendMessage');
    Route::get('/conversation/{userId}', 'getConversation');
    Route::get('/conversations', 'getConversations');
    Route::post('/mark-read/{userId}', 'markAsRead');
    Route::get('/unread-count', 'getUnreadCount');
});
