<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "sender_id" => $this->sender_id,
            "receiver_id" => $this->receiver_id,
            "message" => $this->message,
            "is_read" => $this->is_read,
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "sender" => [
                "id" => $this->sender->id,
                "first_name" => $this->sender->first_name,
                "last_name" => $this->sender->last_name,
                "photo" => $this->sender->getFirstMediaUrl('photo'),
                "created_at" => $this->sender->created_at->format('Y-m-d H:i:s'),
            ],
            "receiver" => [
                "id" => $this->receiver->id,
                "first_name" => $this->receiver->first_name,
                "last_name" => $this->receiver->last_name,
                "photo" => $this->receiver->getFirstMediaUrl('photo'),
                "created_at" => $this->receiver->created_at->format('Y-m-d H:i:s'),
            ]
        ];
    }
}
