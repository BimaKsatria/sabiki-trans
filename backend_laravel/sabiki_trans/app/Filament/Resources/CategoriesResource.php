<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\car_categories;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoriesResource\Pages;
use App\Filament\Resources\CategoriesResource\RelationManagers;

class CategoriesResource extends Resource
{
    protected static ?string $model = car_categories::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategori Mobil';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(4)
                    ->maxLength(500),
                FileUpload::make('icon')
                    ->label('Icon')
                    ->image() // validasi file gambar
                    ->directory('icons') // folder penyimpanan di storage/app/public/icons
                    ->imagePreviewHeight('100') // pratinjau ikon
                    ->maxSize(1024) // maksimal 1MB
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/x-icon'])
                    ->helperText('Upload ikon dalam format PNG, SVG, JPG, atau ICO')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->sortable()->searchable(),
                TextColumn::make('description')->label('Deskripsi')->limit(50),
                TextColumn::make('cars_count')
                    ->counts('cars')
                    ->label('Jumlah Mobil')
                    ->sortable(),
                ImageColumn::make('icon')->label('Icon'),
                TextColumn::make('created_at')->label('Dibuat')->dateTime(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategories::route('/create'),
            'edit' => Pages\EditCategories::route('/{record}/edit'),
        ];
    }
}
