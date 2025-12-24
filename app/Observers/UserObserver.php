<?php

namespace App\Observers;

use App\Enums\StatusType;
use App\Models\User;
use App\Services\NotificationService;

class UserObserver
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function updated(User $user): void
    {
        $status = $user->status;
        $this->handleStatus($user, $status);
    }

    public function created(User $user): void
    {
        //
    }

    private function handleStatus(User $user, string $status): void
    {
        switch ($status) {
            case StatusType::APPROVED->value:
                $this->handleApprovedStatus($user);
                break;
            case StatusType::REJECTED->value:
                $this->handleRejectedStatus($user);
                break;
            case StatusType::SUSPENDED->value:
                $this->handleSuspendedStatus($user);
                break;
        }
    }

    public function handleApprovedStatus(User $user)
    {
        $this->notificationService->sendSuccessNotification(
            $user->id,
            'Your account has been approved',
            'Your account has been approved. You can now access all features of the platform.'
        );
    }

    public function handleRejectedStatus(User $user)
    {
        $this->notificationService->sendInfoNotification(
            $user->id,
            'Your account has been declined',
            'Your account registration has been declined. Please check your profile and ensure all required documents are properly uploaded. If you need assistance, contact our support team.'
        );
    }

    public function handleSuspendedStatus(User $user)
    {
        $this->notificationService->sendInfoNotification(
            $user->id,
            'Your account has been suspended',
            'Your account has been suspended. Please contact our support team for more information.'
        );
    }
}
