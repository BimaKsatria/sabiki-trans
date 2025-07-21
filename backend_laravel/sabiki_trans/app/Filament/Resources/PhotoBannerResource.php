<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\photo_banner;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PhotoBannerResource\Pages;
use App\Filament\Resources\PhotoBannerResource\RelationManagers;

class PhotoBannerResource extends Resource
{
    protected static ?string $model = photo_banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Photo Banners';

    protected static ?string $pluralLabel = 'Photo Banners';

    protected static ?string $modelLabel = 'Photo Banner';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Banner Name')
                    ->required()
                    ->maxLength(255),

               FileUpload::make('file_path')
                    ->label('File Gambar')
                    ->directory('banners') // storage/app/public/banners
                    ->disk('public')
                    ->image()
                    ->imagePreviewHeight('200')
                    ->loadingIndicatorPosition('start')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

                TextInput::make('order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Banner')
                    ->disk('public') // gunakan disk yang benar
                    ->height(60)
                    ->width(60)
                    ->circular(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('order')
                    ->label('Order'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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
            'index' => Pages\ListPhotoBanners::route('/'),
            'create' => Pages\CreatePhotoBanner::route('/create'),
            'edit' => Pages\EditPhotoBanner::route('/{record}/edit'),
        ];
    }
}
