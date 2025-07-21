<?php

namespace App\Filament\Resources\PaymentReportResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\payments;
use Illuminate\Support\Carbon;

class ReportStats extends BaseWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $monthlyTotal = payments::whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->where('status', 'paid')
            ->sum('amount');

        $totalTransactions = payments::where('status', 'paid')->count();

        $totalIncome = payments::where('status', 'paid')->sum('amount');

        return [
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthlyTotal, 0, ',', '.')),
            Stat::make('Total Transaksi', $totalTransactions),
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalIncome, 0, ',', '.')),
        ];
    }
}
