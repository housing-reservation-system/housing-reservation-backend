<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }


    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->id === $booking->apartment->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role->value === 'Tenant';
    }


    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id && !in_array($booking->status, ['Cancelled', 'Rejected']);
    }


    public function delete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id && $booking->status === 'Pending';
    }

    public function manage(User $user, Booking $booking): bool
    {
        return $user->id === $booking->apartment->user_id;
    }
}
