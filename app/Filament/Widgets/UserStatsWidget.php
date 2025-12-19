<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected static ?int $columns = 4;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', \App\Models\User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
            Stat::make('Active Users', \App\Models\User::where('status', \App\Enums\StatusType::APPROVED)->count())
                ->description('Currently active users')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
            Stat::make('Verified Emails', \App\Models\User::whereNotNull('email_verified_at')->count())
                ->description('Users with verified emails')
                ->descriptionIcon('heroicon-m-envelope')
                ->chart([10, 2, 8, 3, 12, 4, 15])
                ->color('info'),
            Stat::make('Pending Users', \App\Models\User::where('status', \App\Enums\StatusType::PENDING)->count())
                ->description('Awaiting verification')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([2, 5, 3, 4, 2, 5, 2])
                ->color('warning'),
            Stat::make('Hosts', \App\Models\User::where('role', \App\Enums\UserRole::HOST)->count())
                ->description('Users with host profiles')
                ->descriptionIcon('heroicon-m-home-modern')
                ->chart([3, 7, 5, 8, 4, 9, 6])
                ->color('gray'),
            Stat::make('Tenants', \App\Models\User::where('role', \App\Enums\UserRole::TENANT)->count())
                ->description('Users with tenant profiles')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([5, 3, 8, 4, 6, 9, 10])
                ->color('gray'),
            Stat::make('Suspended Users', \App\Models\User::where('status', \App\Enums\StatusType::SUSPENDED)->count())
                ->description('Currently suspended accounts')
                ->descriptionIcon('heroicon-m-minus-circle')
                ->chart([1, 2, 1, 3, 2, 1, 2])
                ->color('danger'),
             Stat::make('New This Month', \App\Models\User::whereMonth('created_at', now()->month)->count())
                ->description('Registered this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([10, 5, 12, 8, 15, 10, 17])
                ->color('success'),
        ];
    }
}
