<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentTenantFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
            'is_favorited'=>$this->is_favorited ?? false,
            'location' => [
                'province' => $this->location->province,
                'city' => $this->location->city,
                'street' => $this->location->street,
                'latitude' => $this->location->latitude,
                'longitude' => $this->location->longitude,
            ],
            'images' => [
                'main_image' => $mainImage ? $mainImage->getUrl() : null,
                'other_images' => $otherImages->map(fn($m) => $m->getUrl())->values(),
            ],
            'created_at' => $this->created_at,
        ];
    }
}

