<?php

namespace App\Filament\Resources\PaymentReportResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\payments;

class ReportChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Transaksi Harian per Bulan';

    protected function getData(): array
{
    $now = now();
    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();

    $data = payments::selectRaw("DATE(payment_date) as day, COUNT(*) as total")
        ->where('status', 'paid')
        ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
        ->groupBy('day')
        ->orderBy('day')
        ->get();

    return [
        'datasets' => [
            [
                'label' => 'Jumlah Transaksi Harian',
                'data' => $data->pluck('total'),
                'backgroundColor' => '#22C55E', // hijau
            ],
        ],
        'labels' => $data->pluck('day')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')),
    ];
}



    protected function getType(): string
    {
        return 'bar';
    }

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }
}
