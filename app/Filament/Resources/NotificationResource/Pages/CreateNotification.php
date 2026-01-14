<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use App\Notifications\GeneralNotification;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected function afterCreate(): void
    {
        $notification = $this->record;
        $user = $notification->user;

        if ($user) {
            $user->notify(new GeneralNotification(
                $notification->title,
                $notification->message,
                array_merge($notification->metadata ?? [], [
                    'user_id' => $user->id,
                    'type' => $notification->type,
                ])
            ));
        }
    }
}
