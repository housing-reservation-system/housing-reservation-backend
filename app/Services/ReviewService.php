<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Review;
use Exception;
use Illuminate\Support\Facades\DB;

class ReviewService
{

    public function storeReview(array $data, int $userId)
    {
        $booking = Booking::findOrFail($data['booking_id']);

        if ($booking->user_id !== $userId) {
            throw new Exception('You can only rate your own bookings');
        }

        if ($booking->status !== 'Completed') {
            throw new Exception('You can only rate completed bookings');
        }

        if (Review::where('booking_id', $booking->id)
                ->where('rater_id', $userId)
                ->where('rated_id', $booking->apartment->user_id)
                ->exists()) {
            throw new Exception('You have already rated this booking');
        }

        return DB::transaction(function () use ($data, $booking, $userId) {
            return Review::create([
                'booking_id' => $booking->id,
                'rater_id' => $userId,
                'rated_id' => $booking->apartment->user_id,
                'rating' => $data['rating'],
                'feedback' => $data['feedback'] ?? null,
            ]);
        });
    }
}

