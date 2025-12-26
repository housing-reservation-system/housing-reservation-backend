<?php

namespace App\Services\Host;

use App\Models\Apartment;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getHostDashboardStats(int $userId): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        return [
            'total_apartments' => $this->getTotalApartments($userId, $currentMonth, $lastMonth, $lastMonthEnd),
            'active_bookings' => $this->getActiveBookings($userId, $currentMonth, $lastMonth, $lastMonthEnd),
            'total_revenue' => $this->getTotalRevenue($userId, $currentMonth, $lastMonth, $lastMonthEnd),
            'awaiting_reviews' => $this->getAwaitingReviews($userId, $currentMonth, $lastMonth, $lastMonthEnd),
            'recent_bookings' => $this->getRecentBookings($userId),
        ];
    }

    private function getTotalApartments(int $userId, Carbon $currentMonth, Carbon $lastMonth, Carbon $lastMonthEnd): array
    {
        $currentCount = Apartment::where('user_id', $userId)
            ->where('created_at', '<=', Carbon::now())
            ->count();

        $lastMonthCount = Apartment::where('user_id', $userId)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();

        return [
            'count' => $currentCount,
            'percentage_change' => $this->calculatePercentageChange($currentCount, $lastMonthCount),
        ];
    }

    private function getActiveBookings(int $userId, Carbon $currentMonth, Carbon $lastMonth, Carbon $lastMonthEnd): array
    {
        $currentCount = Booking::whereHas('apartment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereIn('status', ['Approved', 'Ongoing'])
            ->count();

        $lastMonthCount = Booking::whereHas('apartment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereIn('status', ['Approved', 'Ongoing'])
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();

        return [
            'count' => $currentCount,
            'percentage_change' => $this->calculatePercentageChange($currentCount, $lastMonthCount),
        ];
    }

    private function getTotalRevenue(int $userId, Carbon $currentMonth, Carbon $lastMonth, Carbon $lastMonthEnd): array
    {
        $currentRevenue = Booking::whereHas('apartment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', 'Completed')
            ->whereBetween('updated_at', [$currentMonth, Carbon::now()])
            ->sum('total_price');

        $lastMonthRevenue = Booking::whereHas('apartment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', 'Completed')
            ->whereBetween('updated_at', [$lastMonth, $lastMonthEnd])
            ->sum('total_price');

        return [
            'amount' => (float) $currentRevenue,
            'percentage_change' => $this->calculatePercentageChange($currentRevenue, $lastMonthRevenue),
        ];
    }

    private function getAwaitingReviews(int $userId, Carbon $currentMonth, Carbon $lastMonth, Carbon $lastMonthEnd): array
    {
        $currentCount = Booking::whereHas('apartment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', 'Completed')
            ->doesntHave('review')
            ->count();

        $lastMonthCount = Booking::whereHas('apartment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('status', 'Completed')
            ->where('updated_at', '<=', $lastMonthEnd)
            ->doesntHave('review')
            ->count();

        return [
            'count' => $currentCount,
            'percentage_change' => $this->calculatePercentageChange($currentCount, $lastMonthCount),
        ];
    }

    private function getRecentBookings(int $userId, int $limit = 5): array
    {
        $bookings = Booking::whereHas('apartment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with(['user:id,first_name,last_name,email', 'apartment:id,title'])
            ->latest()
            ->limit($limit)
            ->get();

        return $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'guest_name' => $booking->user->first_name . ' ' . $booking->user->last_name,
                'guest_email' => $booking->user->email,
                'apartment_title' => $booking->apartment->title,
                'photo' => $booking->user->getFirstMediaUrl('photo'),
                'status' => $booking->status,
                'booking_date' => $booking->created_at->format('M d, Y'),
                'start_date' => Carbon::parse($booking->start_date)->format('M d, Y'),
                'end_date' => Carbon::parse($booking->end_date)->format('M d, Y'),
                'total_price' => (float) $booking->total_price,
            ];
        })->toArray();
    }

    private function calculatePercentageChange(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        $change = (($current - $previous) / $previous) * 100;
        return round($change, 2);
    }
}
