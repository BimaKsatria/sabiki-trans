<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\discount;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\discount_usages;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DiscountUsagesResource\Pages;
use App\Filament\Resources\DiscountUsagesResource\RelationManagers;
use App\Models\User;

class DiscountUsagesResource extends Resource
{
    protected static ?string $model = discount_usages::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Riwayat Diskon';

    protected static ?string $pluralLabel = 'Riwayat Diskon';

    protected static ?string $modelLabel = 'Riwayat Diskon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('discount_id')
                    ->label('Kode Diskon')
                    ->options(
                        Discount::all()->pluck('code', 'id')
                    ) // ✅ diperbaiki dari 'user' . 'name'
                    ->searchable()
                    ->required(),

                Select::make('user_id')
                    ->label('Pengguna')
                    ->options(
                        User::all()->pluck('name', 'id')
                    ) // ✅ diperbaiki dari 'user' . 'name'
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('discount.code')->label('Kode Diskon')->searchable(),
                TextColumn::make('discount.user.name')->label('Dibuat Oleh (Admin)')->searchable(), // ← ini pakai relasi 'user'
                TextColumn::make('user.name')->label('Nama Pengguna')->searchable(),
                TextColumn::make('used_at')->label('Waktu Pemakaian')->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListDiscountUsages::route('/'),
            'create' => Pages\CreateDiscountUsages::route('/create'),
            'edit' => Pages\EditDiscountUsages::route('/{record}/edit'),
        ];
    }
}
