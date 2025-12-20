<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'card_brand' => 'required|in:VISA,MASTERCARD',
            'last_four_digits' => 'required|string|size:4',
            'card_holder_name' => 'nullable|string|max:255',
            'expiry_date' => 'required|date|after:today',
            'is_default' => 'boolean',
        ];
    }
}
