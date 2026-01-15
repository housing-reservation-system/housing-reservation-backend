<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

use Illuminate\Broadcasting\PrivateChannel;

class GeneralNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    protected $title;
    protected $body;
    protected $data;

    public function __construct($title, $body, array $data = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return ['broadcast', FcmChannel::class];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
        ]);
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: $this->title,
            body: $this->body,
        )))->data($this->data);
    }

    public function broadcastOn(): array
    {
        $userId = $this->data['user_id'] ?? 'global';
        return [new PrivateChannel('user.' . $userId)];
    }

    public function broadcastAs(): string
    {
        return 'notification.received';
    }
}
