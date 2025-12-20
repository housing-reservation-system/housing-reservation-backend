<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'card_brand' => $this->card_brand,
            'last_four_digits' => $this->last_four_digits,
            'card_holder_name' => $this->card_holder_name,
            'expiry_date' => $this->expiry_date,
            'is_default' => (bool) $this->is_default,
            'created_at' => $this->created_at,
        ];
    }
}
