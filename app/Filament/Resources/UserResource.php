<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_of_birth'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Account Security')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255),
                        Forms\Components\Select::make('role')
                            ->options(\App\Enums\UserRole::class)
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options(\App\Enums\StatusType::class)
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Verification')
                    ->schema([
                        Forms\Components\DateTimePicker::make('email_verified_at'),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('photo')
                            ->avatar(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('id_front')
                            ->collection('id_front'),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('id_back')
                            ->collection('id_back'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Avatar')
                    ->getStateUsing(function (User $record): string {
                        try {
                            return $record->getFirstMediaUrl('photo') ?:
                                'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF';
                        } catch (\Throwable $e) {
                            return 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF';
                        }
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('email_verification_code')
                    ->label('Verification Code')
                    ->searchable()
                    ->badge()
                    ->state(fn (User $record): string => $record->email_verification_code ?? 'Verified')
                    ->color(fn ($state): string => $state === 'Verified' ? 'success' : 'warning')
                    ->icon(fn ($state): ?string => $state === 'Verified' ? 'heroicon-m-check-circle' : null)
                    ->copyable(),

                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        \App\Enums\UserRole::ADMIN, \App\Enums\UserRole::ADMIN->value => 'danger',
                        \App\Enums\UserRole::HOST, \App\Enums\UserRole::HOST->value => 'success',
                        \App\Enums\UserRole::TENANT, \App\Enums\UserRole::TENANT->value => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        \App\Enums\StatusType::APPROVED, \App\Enums\StatusType::APPROVED->value => 'success',
                        \App\Enums\StatusType::PENDING, \App\Enums\StatusType::PENDING->value => 'warning',
                        \App\Enums\StatusType::REJECTED, \App\Enums\StatusType::REJECTED->value => 'danger',
                        \App\Enums\StatusType::SUSPENDED, \App\Enums\StatusType::SUSPENDED->value => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('User Profile')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                ImageEntry::make('photo')
                                    ->circular()
                                    ->label('Profile Photo')
                                    ->getStateUsing(function (User $record): string {
                                        try {
                                            return $record->getFirstMediaUrl('photo') ?:
                                                'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF';
                                        } catch (\Throwable $e) {
                                            return 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF';
                                        }
                                    }),
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Full Name')
                                            ->icon('heroicon-m-user'),
                                        TextEntry::make('role')
                                            ->badge()
                                            ->color(fn($state): string => match ($state) {
                                                \App\Enums\UserRole::ADMIN, \App\Enums\UserRole::ADMIN->value => 'danger',
                                                \App\Enums\UserRole::HOST, \App\Enums\UserRole::HOST->value => 'success',
                                                \App\Enums\UserRole::TENANT, \App\Enums\UserRole::TENANT->value => 'info',
                                                default => 'gray',
                                            }),
                                        TextEntry::make('email')
                                            ->icon('heroicon-m-envelope'),
                                        TextEntry::make('status')
                                            ->badge()
                                            ->color(fn($state): string => match ($state) {
                                                \App\Enums\StatusType::APPROVED, \App\Enums\StatusType::APPROVED->value => 'success',
                                                \App\Enums\StatusType::PENDING, \App\Enums\StatusType::PENDING->value => 'warning',
                                                \App\Enums\StatusType::REJECTED, \App\Enums\StatusType::REJECTED->value => 'danger',
                                                \App\Enums\StatusType::SUSPENDED, \App\Enums\StatusType::SUSPENDED->value => 'danger',
                                                default => 'gray',
                                            }),
                                        TextEntry::make('phone')
                                            ->placeholder('No phone number'),
                                        TextEntry::make('email_verification_code')
                                            ->label('Verification Code')
                                            ->badge()
                                            ->state(fn (User $record): string => $record->email_verification_code ?? 'Verified')
                                            ->color(fn ($state): string => $state === 'Verified' ? 'success' : 'warning')
                                            ->icon(fn ($state): string => $state === 'Verified' ? 'heroicon-m-check-circle' : 'heroicon-m-shield-check')
                                            ->copyable(),
                                        IconEntry::make('email_verified_at')
                                            ->label('Email Verified')
                                            ->boolean()
                                            ->getStateUsing(fn($record) => filled($record->email_verified_at)),
                                    ])->columnSpan(2),
                            ]),
                    ]),

                Section::make('Identification Documents')
                    ->description('Verification documents uploaded by the user.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                ImageEntry::make('id_front')
                                    ->label('ID Card (Front)')
                                    ->width(400)
                                    ->height(250)
                                    ->getStateUsing(function (User $record): ?string {
                                        try {
                                            return $record->getFirstMediaUrl('id_front') ?: null;
                                        } catch (\Throwable $e) {
                                            return null;
                                        }
                                    }),
                                ImageEntry::make('id_back')
                                    ->label('ID Card (Back)')
                                    ->width(400)
                                    ->height(250)
                                    ->getStateUsing(function (User $record): ?string {
                                        try {
                                            return $record->getFirstMediaUrl('id_back') ?: null;
                                        } catch (\Throwable $e) {
                                            return null;
                                        }
                                    }),
                            ]),
                    ]),

                Section::make('Account Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
