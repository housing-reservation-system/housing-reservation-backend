<?php

namespace App\Policies;

use App\Models\Apartment;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Auth\Access\Response;

class ApartmentPolicy
{
    public function view(User $user, Apartment $apartment): bool
    {
        return true;
    }
    
    public function create(User $user): bool
    {
        return $user->role === UserRole::HOST;
    }

    public function update(User $user, Apartment $apartment): bool
    {
        return $user->id === $apartment->user_id;
    }

    public function delete(User $user, Apartment $apartment): bool
    {
        return $user->id === $apartment->user_id;
    }
}
