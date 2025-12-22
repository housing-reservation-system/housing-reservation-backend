<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource{
    public function toArray(Request $request): array
    {
        $mainImage = $this->getMedia('apartments')->where('custom_properties.main', true)->first();
        $otherImages = $this->getMedia('apartments')->where('custom_properties.main', '!=', true);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'rooms' => $this->rooms,
            'area' => $this->area,
            'rent_price' => $this->rent_price,
            'rent_period' => $this->rent_period,
            'amenities' => $this->amenities ?? [],
            'is_active' => $this->is_active,
            'host' => $this->when($this->relationLoaded('host'), [
                'id' => $this->host?->id,
                'first_name' => $this->host?->first_name,
                'last_name' => $this->host?->last_name,
                'email' => $this->host?->email,
            ]),
            'location' => $this->when($this->relationLoaded('location'), [
                'id' => $this->location?->id,
                'province' => $this->location?->province,
                'city' => $this->location?->city,
                'street' => $this->location?->street,
                'latitude' => $this->location?->latitude ?? null,
                'longitude' => $this->location?->longitude ?? null,
            ]),
            'images' => [
                'main_image' => $mainImage ? $mainImage->getUrl() : null,
                'other_images' => $otherImages->map(fn($media) => $media->getUrl())->values(),
            ],
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
