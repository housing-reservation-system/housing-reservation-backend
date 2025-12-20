<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    return $this->collection->map(function($apartment){
return[
'id'=>$apartment->id,
'title'=>$apartment->title,
'price'=>$apartment->price,
'price_per_month'=>$apartment->price_per_month,
'price_per_year'=>$apartment->price_per_year,
'number_of_rooms'=>$apartment->number_of_rooms,
'main_image_url'=>$apartment->getFirstMediaUrl('main_image')
];
    });

    }
}
