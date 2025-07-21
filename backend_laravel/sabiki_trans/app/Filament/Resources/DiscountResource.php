<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use App\Models\Discount;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DiscountResource\Pages;

use function Laravel\Prompts\select;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationLabel = 'Diskon';

    protected static ?string $pluralLabel = 'Diskon';

    protected static ?string $modelLabel = 'Diskon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Kode Diskon')
                    ->unique(ignoreRecord: true)
                    ->required(),

                Select::make('user_id')
                    ->label('Dibuat Oleh (Admin)')
                    ->options(function () {
                        return User::role('admin') // pakai Spatie method
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                Select::make('type')
                    ->label('Jenis Diskon')
                    ->options([
                        'percentage' => 'Persentase',
                        'fixed' => 'Tetap',
                    ])
                    ->required(),

                TextInput::make('value')
                    ->label('Nilai Diskon')
                    ->numeric()
                    ->required(),

                TextInput::make('max_discount')
                    ->label('Diskon Maksimum')
                    ->numeric()
                    ->nullable(),

                DatePicker::make('start_date')->label('Tanggal Mulai')->required(),
                DatePicker::make('end_date')->label('Tanggal Selesai')->required(),

                TextInput::make('usage_limit')->label('Batas Penggunaan')->numeric()->nullable(),
                TextInput::make('used_count')->label('Sudah Digunakan')->numeric()->disabled(),

                Toggle::make('active')->label('Aktif?')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Kode')->searchable(),
                TextColumn::make('user.name')->label('Admin')->searchable(),
                BadgeColumn::make('type')->label('Jenis'),
                TextColumn::make('value')->label('Nilai Diskon'),
                TextColumn::make('max_discount')->label('Max Diskon')->toggleable(),
                TextColumn::make('usage_limit')->label('Batas')->sortable(),
                TextColumn::make('used_count')->label('Terpakai')->sortable(),
                ToggleColumn::make('active')->label('Aktif'),
                TextColumn::make('start_date')->label('Mulai')->date(),
                TextColumn::make('end_date')->label('Selesai')->date(),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
