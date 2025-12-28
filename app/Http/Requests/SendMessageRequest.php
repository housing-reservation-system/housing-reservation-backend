<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => 'required|integer|exists:users,id|different:' . auth()->id(),
            'message' => 'required|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_id.required' => 'Receiver ID is required',
            'receiver_id.exists' => 'Receiver not found',
            'receiver_id.different' => 'You cannot send a message to yourself',
            'message.required' => 'Message content is required',
            'message.max' => 'Message cannot exceed 5000 characters',
        ];
    }
}
