<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends BaseWidget
{
    protected static ?int $columns = 4;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Apartments', \App\Models\Apartment::count())
                ->description('Total listings in the system')
                ->descriptionIcon('heroicon-m-home')
                ->chart([5, 10, 8, 12, 10, 15, 13])
                ->color('success'),
            Stat::make('Total Bookings', \App\Models\Booking::count())
                ->description('Total reservations made')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->chart([2, 8, 5, 10, 8, 12, 11])
                ->color('primary'),
            Stat::make('Total Revenue', '$' . number_format(\App\Models\Booking::where('status', 'Approved')->sum('total_price'), 2))
                ->description('From approved bookings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([1000, 5000, 3000, 8000, 6000, 12000, 10000])
                ->color('success'),
            // Stat::make('Average Rating', number_format(\App\Models\Review::avg('rating'), 1))
            //     ->description('User satisfaction')
            //     ->descriptionIcon('heroicon-m-star')
            //     ->chart([3.5, 4.2, 4.0, 4.5, 4.3, 4.8, 4.6])
            //     ->color('warning'),
        ];
    }
}
