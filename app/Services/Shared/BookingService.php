<?php

namespace App\Services\Shared;

use App\Models\Apartment;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function storeBooking(array $data, int $userId)
    {
        $apartment = Apartment::findOrFail($data['apartment_id']);

        $startDate = Carbon::parse($data['start_date']);
        $endDate = $this->calculateEndDate($startDate, $data['duration'], $apartment->rent_period);

        if ($this->hasConflict($apartment->id, $startDate, $endDate)) {
            throw new \Exception('This apartment is already booked or has a pending request for the selected period.');
        }

        return DB::transaction(function () use ($data, $userId, $apartment, $startDate, $endDate) {
            return Booking::create([
                'user_id' => $userId,
                'apartment_id' => $apartment->id,
                'payment_method_id' => $data['payment_method_id'],
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_price' => $apartment->rent_price * $data['duration'],
                'status' => 'Pending',
                'is_modified' => false,
            ]);
        });
    }

    public function updateBooking(Booking $booking, array $data)
    {
        $apartment = $booking->apartment;
        $startDate = Carbon::parse($data['start_date'] ?? $booking->start_date);
        $duration = $data['duration'] ?? $this->calculateDuration($booking->start_date, $booking->end_date, $apartment->rent_period);
        $endDate = $this->calculateEndDate($startDate, $duration, $apartment->rent_period);

        if ($this->hasConflict($apartment->id, $startDate, $endDate, $booking->id)) {
            throw ValidationException::withMessages([
                'start_date' => __('The modified period overlaps with another booking.'),
            ]);
        }

        return DB::transaction(function () use ($booking, $data, $apartment, $startDate, $endDate, $duration) {
            $booking->update([
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_price' => $apartment->rent_price * $duration,
                'status' => 'Pending',
                'is_modified' => true,
                'payment_method_id' => $data['payment_method_id'] ?? $booking->payment_method_id,
            ]);

            return $booking;
        });
    }

    public function approveBooking(Booking $booking)
    {
        if ($this->hasConflict($booking->apartment_id, Carbon::parse($booking->start_date), Carbon::parse($booking->end_date), $booking->id)) {
            throw ValidationException::withMessages([
                'status' => __('Cannot approve this booking due to a conflict with another approved booking.'),
            ]);
        }

        $booking->update(['status' => 'Approved']);
        return $booking;
    }

    public function rejectBooking(Booking $booking, ?string $reason = null)
    {
        $booking->update([
            'status' => 'Rejected',
            'cancellation_reason' => $reason
        ]);
        return $booking;
    }

    public function cancelBooking(Booking $booking, ?string $reason = null)
    {
        $booking->update([
            'status' => 'Cancelled',
            'cancellation_reason' => $reason
        ]);
        return $booking;
    }

    public function calculateEndDate(Carbon $startDate, int $duration, string $rentPeriod): Carbon
    {
        $endDate = clone $startDate;
        if ($rentPeriod === 'monthly') {
            return $endDate->addMonths($duration);
        } elseif ($rentPeriod === 'yearly') {
            return $endDate->addYears($duration);
        }

        return $endDate;
    }

    private function calculateDuration($start, $end, $rentPeriod): int
    {
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        if ($rentPeriod === 'monthly') {
            return (int) $startDate->diffInMonths($endDate);
        } elseif ($rentPeriod === 'yearly') {
            return (int) $startDate->diffInYears($endDate);
        }

        return 0;
    }

    public function hasConflict(int $apartmentId, Carbon $startDate, Carbon $endDate, ?int $excludeBookingId = null): bool
    {
        return Booking::where('apartment_id', $apartmentId)
            ->whereIn('status', ['Approved', 'Ongoing'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<', $endDate)
                        ->where('end_date', '>', $startDate);
                });
            })
            ->when($excludeBookingId, function ($query) use ($excludeBookingId) {
                $query->where('id', '!=', $excludeBookingId);
            })
            ->exists();
    }
}
