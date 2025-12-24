<?php

namespace App\Services;

use App\Models\Notification;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function createAndBroadcast(
        $user,
        string $type,
        string $title,
        string $message,
        ?array $metadata = null
    ): ?Notification {
        try {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'metadata' => $metadata,
            ]);

            $user->notify(new GeneralNotification(
                $title,
                $message,
                array_merge($metadata ?? [], ['user_id' => $user->id])
            ));

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }

    public function sendSuccessNotification($user, string $title, string $message, ?array $metadata = null): ?Notification
    {
        return $this->createAndBroadcast($user, 'success', $title, $message, $metadata);
    }

    public function sendInfoNotification($user, string $title, string $message, ?array $metadata = null): ?Notification
    {
        return $this->createAndBroadcast($user, 'info', $title, $message, $metadata);
    }

    public function sendWarningNotification($user, string $title, string $message, ?array $metadata = null): ?Notification
    {
        return $this->createAndBroadcast($user, 'warning', $title, $message, $metadata);
    }

    public function sendErrorNotification($user, string $title, string $message, ?array $metadata = null): ?Notification
    {
        return $this->createAndBroadcast($user, 'error', $title, $message, $metadata);
    }
}
