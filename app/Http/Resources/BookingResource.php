<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "user" => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'apartment' => [
                'id' => $this->apartment->id,
                'title' => $this->apartment->title,
                'rent_price' => $this->apartment->rent_price,
                'rent_period' => $this->apartment->rent_period,
            ],
            "location" => [
                "province" => $this->apartment->location->province,
                'city' => $this->apartment->location->city,
                'street' => $this->apartment->location->street,
            ],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_price' => (float) $this->total_price,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
