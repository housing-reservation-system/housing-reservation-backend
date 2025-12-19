<?php

namespace App\Services\Shared;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\StatusType;

class AccountVerificationService
{
    public function verifyAccount(Request $request): User
    {
        $user = Auth::user();
        if ($user->status !== StatusType::NEW->value) {
            throw new \Exception('Your account has already been verified.', 400);
        }

        if ($request->hasFile('id_front')) {
            $user->addMediaFromRequest('id_front')
                ->toMediaCollection('id_front', 'cloudinary');
        }

        if ($request->hasFile('id_back')) {
            $user->addMediaFromRequest('id_back')
                ->toMediaCollection('id_back', 'cloudinary');
        }

        $user->status = StatusType::PENDING->value;
        $user->save();

        return $user;
    }

    public function getVerificationStatus(): array
    {
        $user = Auth::user();

        return [
            'status' => $user->status,
            'id_front_uploaded' => $user->getFirstMedia('id_front') !== null,
            'id_back_uploaded' => $user->getFirstMedia('id_back') !== null,
        ];
    }
}
