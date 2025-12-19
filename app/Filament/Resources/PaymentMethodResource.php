<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Method Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'first_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('method_type')
                            ->options([
                                'Credit Card' => 'Credit Card',
                                'PayPal' => 'PayPal',
                                'Bank Transfer' => 'Bank Transfer',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('card_brand'),
                        Forms\Components\TextInput::make('card_holder_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_four_digits')
                            ->required()
                            ->maxLength(4)
                            ->numeric(),
                        Forms\Components\DatePicker::make('expiry_date')
                            ->required(),
                        Forms\Components\Toggle::make('is_default')
                            ->required()
                            ->inline(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.first_name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('method_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('card_brand')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_four_digits')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => "**** **** **** " . $state),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->label('Default'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'view' => Pages\ViewPaymentMethod::route('/{record}'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
