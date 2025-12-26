<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentTenantListResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $mainImage = $this->getMedia('apartments')->where('custom_properties.main', true)->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'rent_price' => $this->rent_price,
            'rent_period' => $this->rent_period,
            'style' => $this->style,
            'main_image' => $mainImage ? $mainImage->getUrl() : null,
        ];
    }
}
