<?php

namespace App\Services\Shared;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    public function getProfile(): User
    {
        return Auth::user();
    }

    public function updateProfile(Request $request): User
    {
        $user = Auth::user();

        $fillableFields = ['first_name', 'last_name', 'phone', 'date_of_birth', 'gender'];
        
        foreach ($fillableFields as $field) {
            if ($request->has($field)) {
                $user->$field = $request->$field;
            }
        }

        if ($request->hasFile('photo')) {
            $user->clearMediaCollection('photo');
            $user->addMediaFromRequest('photo')
                ->toMediaCollection('photo','cloudinary');
        }
        $user->save();
        return $user->fresh();
    }
}
