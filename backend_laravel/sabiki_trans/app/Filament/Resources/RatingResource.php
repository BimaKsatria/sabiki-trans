<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Rating;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RatingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RatingResource\RelationManagers;
use App\Models\cars;

class RatingResource extends Resource
{
    protected static ?string $model = Rating::class;

     protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Rating';

    protected static ?string $pluralLabel = 'Rating';

    protected static ?string $modelLabel = 'Rating';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),


                Select::make('cars_id')
                    ->label('Cars')
                    ->options(cars::all()->pluck('model', 'id'))
                    ->searchable()
                    ->required(),


                TextInput::make('score')
                    ->label('Score (1-5)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('users.name')->label('User'),
                TextColumn::make('car.model')->label('Car'),
                TextColumn::make('score')->label('Score'),
                TextColumn::make('created_at')->dateTime('d M Y'),
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
            'index' => Pages\ListRatings::route('/'),
            'create' => Pages\CreateRating::route('/create'),
            'edit' => Pages\EditRating::route('/{record}/edit'),
        ];
    }
}
