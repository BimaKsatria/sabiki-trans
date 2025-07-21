<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rentals;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\RentalStatus;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RentalsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RentalsResource\RelationManagers;

class RentalsResource extends Resource
{
    protected static ?string $model = Rentals::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationLabel = 'Rental';

    protected static ?string $pluralLabel = 'Rental';

    protected static ?string $modelLabel = 'Rental';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('booking_id')
                    ->label('Booking')
                    ->relationship('booking', 'id')
                    ->required(),
                DatePicker::make('pickup_date')
                    ->label('Tanggal Ambil')
                    ->format('Y-m-d H:i') // Jika menggunakan timestamp
                    ->required(),

                DatePicker::make('return_date')
                    ->label('Tanggal Kembali')
                    ->format('Y-m-d H:i')
                    ->required(),
                Select::make('driver_id')
                    ->label('driver')
                    ->relationship('driver', 'service'),
                Select::make('status')
                    ->label('Status')
                    ->options(RentalStatus::options())
                    ->required(),
                TextInput::make('pickup_location')
                    ->label('Lokasi Pengambilan')
                    ->required(),

                TextInput::make('return_location')
                    ->label('Lokasi Pengembalian')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.id'),
                TextColumn::make('pickup_date'),
                TextColumn::make('return_date'),
                TextColumn::make('driver.service'),
                TextColumn::make('status'),
                TextColumn::make('pickup_location')
                    ->label('Lokasi Ambil'),

                TextColumn::make('return_location')
                    ->label('Lokasi Kembali'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRentals::route('/'),
            'create' => Pages\CreateRentals::route('/create'),
            'edit' => Pages\EditRentals::route('/{record}/edit'),
        ];
    }
}
