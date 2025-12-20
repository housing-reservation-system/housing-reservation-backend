<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'card_brand' => 'sometimes|in:VISA,MASTERCARD',
            'last_four_digits' => 'sometimes|string|size:4',
            'card_holder_name' => 'sometimes|nullable|string|max:255',
            'expiry_date' => 'sometimes|date|after:today',
            'is_default' => 'sometimes|boolean',
        ];
    }
}
