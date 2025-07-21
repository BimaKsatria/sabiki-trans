<?php

namespace App\Filament\Widgets;

use App\Models\Discount_Usages;
use App\Models\Discount;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;

class DiskonUsageTable extends BaseWidget
{
    protected static ?string $heading = 'Laporan Penggunaan Diskon';
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Discount::query()
            ->select('discount.id', 'discount.code', 'users.name as admin_name')
            ->selectRaw('(SELECT COUNT(*) FROM discount_usages WHERE discount_usages.discount_id = discount.id) as usage_count')
            ->join('users', 'users.id', '=', 'discount.user_id')
            ->orderByDesc('usage_count');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('admin_name')->label('Admin Pembuat')->sortable()->searchable(),
            TextColumn::make('code')->label('Kode Diskon')->sortable()->searchable(),
            TextColumn::make('usage_count')->label('Total Penggunaan')->sortable(),
        ];
    }

    public static function canView(): bool
    {
        return true;
    }
}
