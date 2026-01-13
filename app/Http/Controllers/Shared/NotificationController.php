<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Services\Shared\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use ApiResponse;
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getAllNotifications()
    {
        $user = Auth::user();
        $notifications = $this->notificationService->getAllNotifications($user);
        return $this->success(NotificationResource::collection($notifications), 'Notifications retrieved successfully');
    }

    public function getUnreadNotifications()
    {
        $user = Auth::user();
        $notifications = $this->notificationService->getUnreadNotifications($user);
        return $this->success(NotificationResource::collection($notifications), 'Unread notifications retrieved successfully');
    }

    public function markAsRead(Request $request, $id)
    {
        $user = Auth::user();
        $notification = \App\Models\Notification::where('id', $id)->where('user_id', $user->id)->first();
        if (!$notification) {
            return $this->error('Notification not found', 404);
        }
        $this->notificationService->markAsRead($notification);
        return $this->successMessage('Notification marked as read');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $this->notificationService->markAllAsRead($user);
        return $this->successMessage('All notifications marked as read');
    }
}
