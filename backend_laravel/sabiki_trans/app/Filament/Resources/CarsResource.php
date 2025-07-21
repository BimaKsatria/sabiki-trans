<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Cars;
use Filament\Tables;
use App\Enums\CarStatus;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;

use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\CarsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CarsResource\RelationManagers;

class CarsResource extends Resource
{
    protected static ?string $model = Cars::class;

        protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Mobil';

    protected static ?string $pluralLabel = 'Mobil';

    protected static ?string $modelLabel = 'Mobil';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                CheckboxList::make('categories')
                    ->label('Kategori')
                    ->relationship('categories', 'name')
                    ->columns(2)
                    ->required(),
                TextInput::make('brand'),
                TextInput::make('model'),
                TextInput::make('license_plate'),
                TextInput::make('year')
                    ->label('Tahun')
                    ->numeric()
                    ->length(4)
                    ->required(),
                TextInput::make('description'),
                TextInput::make('price_per_day')
                    ->numeric()
                    ->prefix('Rp')
                    ->maxValue(42949672.95),
                Select::make('status')
                    ->label('Status')
                    ->options(CarStatus::options())
                    ->required(),
                Repeater::make('photos')
                    ->label('Foto Mobil')
                    ->relationship('photos') // sesuai nama relasi di model Car
                    ->schema([
                        FileUpload::make('file_path')
                            ->loadingIndicatorPosition('start')
                            ->label('Foto')
                            ->directory('car_photos') // folder penyimpanan di storage
                            ->image()
                            ->imagePreviewHeight('200')
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name'),
                TextColumn::make('brand'),
                TextColumn::make('model'),
                TextColumn::make('license_plate'),
                TextColumn::make('year'),
                TextColumn::make('description'),
                TextColumn::make('price_per_day'),
                TextColumn::make('status'),
                ImageColumn::make('thumbnail')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->height(60)
                    ->width(60)

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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCars::route('/create'),
            'edit' => Pages\EditCars::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('photos');
    }
}
