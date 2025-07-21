<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Payments;
use Filament\Widgets\StatsOverviewWidget\Card;

class APaymentStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Total Pemasukan', 'Rp ' . number_format(
                Payments::where('status', 'paid')->sum('amount'), 0, ',', '.'
            ))->color('success'),

            Card::make('Transaksi Berhasil', Payments::where('status', 'paid')->count())
                ->description('Status: paid')->color('success'),

            Card::make('Transaksi Gagal', Payments::where('status', 'failed')->count())
                ->description('Status: failed')->color('danger'),
        ];
    }
}
