<?php

namespace App\Filament\Resources;

use App\Enums\BookingStatus;
use Filament\Forms;
use Filament\Tables;
use App\Models\Bookings;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\BookingsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingsResource\RelationManagers;

class BookingsResource extends Resource
{
    protected static ?string $model = Bookings::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Booking';

    protected static ?string $pluralLabel = 'Booking';

    protected static ?string $modelLabel = 'Booking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->label('Name')
                    ->relationship('customer.user', 'name')
                    ->required(),
                Select::make('customer_id')
                    ->label('Phone')
                    ->relationship('customer', 'phone')
                    ->required(),
                Select::make('car_id')
                    ->label('Car')
                    ->relationship('cars', 'model')
                    ->required(),
                DatePicker::make('pickup_date')
                    ->label('Tanggal Ambil')
                    ->format('Y-m-d H:i') // Jika menggunakan timestamp
                    ->required(),

                DatePicker::make('return_date')
                    ->label('Tanggal Kembali')
                    ->format('Y-m-d H:i')
                    ->required(),
                Select::make('discount_id')
                    ->label('discount')
                    ->relationship('discount', 'code'),
                Select::make('status')
                    ->label('Status')
                    ->options(Bookingstatus::options())
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
                TextColumn::make('customer.user.name'),
                TextColumn::make('customer.phone'),
                TextColumn::make('cars.model'),
                TextColumn::make('start_date'),
                TextColumn::make('end_date'),
                TextColumn::make('discount.code'),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBookings::route('/create'),
            'edit' => Pages\EditBookings::route('/{record}/edit'),
        ];
    }
}
