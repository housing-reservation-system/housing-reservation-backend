<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApartmentResource\Pages;
use App\Filament\Resources\ApartmentResource\RelationManagers;
use App\Models\Apartment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApartmentResource extends Resource
{
    protected static ?string $model = Apartment::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Listing Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Host and Location')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('host', 'first_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('location_id')
                            ->relationship('location', 'city')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Apartment Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('rooms')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('area')
                            ->required()
                            ->numeric()
                            ->suffix('m²'),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing and Availability')
                    ->schema([
                        Forms\Components\TextInput::make('rent_price')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('rent_period')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->inline(false),
                    ])->columns(3),

                Forms\Components\Section::make('Amenities')
                    ->schema([
                        Forms\Components\TagsInput::make('amenities')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Gallery')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                            ->collection('images')
                            ->multiple()
                            ->reorderable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('images')
                    ->collection('images')
                    ->limit(1)
                    ->label('Photo'),
                Tables\Columns\TextColumn::make('host.first_name')
                    ->sortable()
                    ->label('Host')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location.city')
                    ->sortable()
                    ->label('City'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('rent_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rent_period')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status'),
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
            'index' => Pages\ListApartments::route('/'),
            'create' => Pages\CreateApartment::route('/create'),
            'view' => Pages\ViewApartment::route('/{record}'),
            'edit' => Pages\EditApartment::route('/{record}/edit'),
        ];
    }
}
