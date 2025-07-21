<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use App\Models\payments;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PaymentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaymentResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class PaymentResource extends Resource
{
    protected static ?string $model = payments::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $pluralLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('rental_id')
                    ->label('Rental')
                    ->relationship('rental', 'id') // pastikan relasi 'rental' ada di model Payment
                    ->required(),

                TextInput::make('amount')
                    ->label('Amount')
                    ->prefix('Rp')
                    ->numeric()
                    ->required(),

                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'bank_transfer' => 'Bank Transfer',
                        'gopay' => 'GoPay',
                        'qris' => 'QRIS',
                        'credit_card' => 'Credit Card',
                    ])
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                        'failed' => 'Failed',
                    ])
                    ->default('pending')
                    ->required(),

                TextInput::make('order_id')
                    ->label('Order ID')
                    ->unique(ignoreRecord: true)
                    ->required(),

                TextInput::make('transaction_id')
                    ->label('Transaction ID')
                    ->disabled()
                    ->dehydrated(), // tetap disimpan walaupun disabled

                TextInput::make('snap_token')
                    ->label('Snap Token')
                    ->disabled()
                    ->dehydrated(),

                DateTimePicker::make('payment_date')
                    ->label('Payment Date')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rental.id'),
                TextColumn::make('amount'),
                TextColumn::make('payment_method'),
                TextColumn::make('status'),
                TextColumn::make('order_id'),
                TextColumn::make('transaction_id'),
                TextColumn::make('snap_token'),
                TextColumn::make('payment_date'),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
