<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\UserStatsWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => \Filament\Resources\Components\Tab::make('All Users')
                ->badge(\App\Models\User::count())
                ->badgeColor('primary'),
            'active' => \Filament\Resources\Components\Tab::make('Active')
                ->badge(\App\Models\User::where('status', \App\Enums\StatusType::APPROVED)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Enums\StatusType::APPROVED)),
            'pending' => \Filament\Resources\Components\Tab::make('Pending')
                ->badge(\App\Models\User::where('status', \App\Enums\StatusType::PENDING)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Enums\StatusType::PENDING)),
            'suspended' => \Filament\Resources\Components\Tab::make('Suspended')
                ->badge(\App\Models\User::where('status', \App\Enums\StatusType::SUSPENDED)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->where('status', \App\Enums\StatusType::SUSPENDED)),
            'hosts' => \Filament\Resources\Components\Tab::make('Hosts')
                ->badge(\App\Models\User::where('role', \App\Enums\UserRole::HOST)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn ($query) => $query->where('role', \App\Enums\UserRole::HOST)),
            'tenants' => \Filament\Resources\Components\Tab::make('Tenants')
                ->badge(\App\Models\User::where('role', \App\Enums\UserRole::TENANT)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn ($query) => $query->where('role', \App\Enums\UserRole::TENANT)),
            'admins' => \Filament\Resources\Components\Tab::make('Admins')
                ->badge(\App\Models\User::where('role', \App\Enums\UserRole::ADMIN)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn ($query) => $query->where('role', \App\Enums\UserRole::ADMIN)),
        ];
    }
}
