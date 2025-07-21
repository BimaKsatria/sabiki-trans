<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Damages;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DamagesResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DamagesResource\RelationManagers;

class DamagesResource extends Resource
{
    protected static ?string $model = Damages::class;

     protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static ?string $navigationLabel = 'Laporan Kembali';

    protected static ?string $pluralLabel = 'Laporan Kembali';

    protected static ?string $modelLabel = 'Laporan Kembali';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('car_id')
                    ->relationship('car', 'model')
                    ->required(),

                Select::make('rental_id')
                    ->relationship('rental', 'id')
                    ->required(),

                Textarea::make('description')
                    ->label('Damage Description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('car.model')->label('Car'),
                TextColumn::make('rental.id')->label('rental'),
                TextColumn::make('description')->limit(50),
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
            'index' => Pages\ListDamages::route('/'),
            'create' => Pages\CreateDamages::route('/create'),
            'edit' => Pages\EditDamages::route('/{record}/edit'),
        ];
    }
}
