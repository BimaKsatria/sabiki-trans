<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DailyIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Pemasukan Harian';

    protected function getData(): array
    {
        $today = now()->toDateString();

        $data = DB::table('payments')
            ->selectRaw('HOUR(payment_date) as hour, SUM(amount) as total')
            ->whereDate('payment_date', $today)
            ->where('status', 'paid')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $hours = range(0, 23);
        $values = [];

        foreach ($hours as $hour) {
            $found = $data->firstWhere('hour', $hour);
            $values[] = $found ? $found->total : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan per Jam (Rp)',
                    'data' => $values,
                ],
            ],
            'labels' => collect($hours)->map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
