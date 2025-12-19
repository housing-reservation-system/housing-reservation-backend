<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $photo = $this->getFirstMedia('photo');
        $defaultPhoto = $this->getFirstMediaUrl('photo', 'default');
        return [
            "first_name" => $this->first_name ?? null,
            "last_name" => $this->last_name ?? null,
            "role" => $this->role ?? null,
            "photo" => $photo ? $photo->getUrl() : $defaultPhoto,
            "status" => $this->status ?? null,
            "token" => $request->token ?? null,
        ];
    }
}
