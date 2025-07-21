<?php

namespace App\Filament\Widgets;

use App\Models\payments;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ChartPayment extends ChartWidget
{
    protected static ?string $heading = 'Penjualan 7 hari terakhir';

    protected function getData(): array
    {
        // Ambil data dari tabel payments 7 hari terakhir dengan status "paid"
        $data = DB::table('payments')
            ->selectRaw('DATE(payment_date) as date, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereBetween('payment_date', [now()->subDays(6), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $data->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        })->toArray();

        $values = $data->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan (Rp)',
                    'data' => $values,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // warna biru transparan
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
