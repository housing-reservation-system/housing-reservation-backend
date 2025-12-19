<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning')
                ->label('Edit User')
                ->icon('heroicon-m-pencil-square'),

            Actions\Action::make('unverify_email')
                ->label('Unverify Email')
                ->icon('heroicon-m-x-circle')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->email_verified_at = null;
                    $this->record->save();
                })
                ->visible(fn () => filled($this->record->email_verified_at)),

            Actions\Action::make('approve')
                ->label('Approve User')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = \App\Enums\StatusType::APPROVED;
                    $this->record->save();
                })
                ->visible(fn () => $this->record->status !== \App\Enums\StatusType::APPROVED),

            Actions\Action::make('decline')
                ->label('Decline User')
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = \App\Enums\StatusType::REJECTED;
                    $this->record->save();
                })
                ->visible(fn () => $this->record->status !== \App\Enums\StatusType::REJECTED),

            Actions\Action::make('suspend')
                ->label('Suspend User')
                ->icon('heroicon-m-no-symbol')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = \App\Enums\StatusType::SUSPENDED;
                    $this->record->save();
                })
                ->visible(fn () => $this->record->status !== \App\Enums\StatusType::SUSPENDED),

            Actions\Action::make('reset_password')
                ->label('Reset Password')
                ->icon('heroicon-m-key')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    \Filament\Notifications\Notification::make()
                        ->title('Password reset instructions sent')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('send_notification')
                ->label('Send Notification')
                ->icon('heroicon-m-bell')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\TextInput::make('title')
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('message')
                        ->required(),
                ])
                ->action(function (array $data) {
                    \App\Models\Notification::create([
                        'user_id' => $this->record->id,
                        'title' => $data['title'],
                        'message' => $data['message'],
                        'type' => 'info',
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Notification sent')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('Delete User'),
        ];
    }
}
