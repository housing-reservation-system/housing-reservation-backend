<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->other_user->id,
                'name' => $this->other_user->name,
                'photo' => $this->other_user->getFirstMediaUrl('photo'),
            ],
            'last_message' => [
                'id' => $this->last_message->id,
                'message' => $this->last_message->message,
                'created_at' => $this->last_message->created_at->format('Y-m-d H:i:s'),
                'is_mine' => $this->last_message->sender_id === Auth::id(),
            ],
            'unread_count' => $this->unread_count,
        ];
    }
}
