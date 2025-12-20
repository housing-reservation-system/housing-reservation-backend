<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkCompletedBookings extends Command
{
    protected $signature = 'bookings:mark-completed';
    protected $description = 'Mark bookings as completed if their end date has passed';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $ongoingCount = Booking::where('status', 'Approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>', $today)
            ->update(['status' => 'Ongoing']);

        $completedCount = Booking::whereIn('status', ['Approved', 'Ongoing'])
            ->where('end_date', '<=', $today)
            ->update(['status' => 'Completed']);

        $this->info("Successfully updated: {$ongoingCount} to Ongoing, {$completedCount} to Completed.");
    }
}
