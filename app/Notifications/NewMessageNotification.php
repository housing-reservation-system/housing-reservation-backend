<?php

namespace App\Notifications;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NewMessageNotification extends Notification // implements ShouldQueue
{
    // use Queueable;

    protected $chat;
    protected $sender;

    public function __construct(Chat $chat, User $sender)
    {
        $this->chat = $chat;
        $this->sender = $sender;
    }

    public function via($notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: $this->sender->name,
            body: $this->chat->message,
        )))->data([
            'type' => 'new_message',
            'chat_id' => $this->chat->id,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'sender_photo' => $this->sender->getFirstMediaUrl('photo'),
        ]);
    }
}
