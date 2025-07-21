<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\APaymentStats;
use App\Filament\Widgets\ChartPayment;
use App\Filament\Widgets\DailyIncomeChart;
use App\Filament\Widgets\DiskonUsageTable;
use Filament\Pages\Dashboard as BaseDashboard;

class AdminDashboard extends BaseDashboard
{

    protected function getHeaderWidgets(): array
    {
        return [
            APaymentStats::class,
            ChartPayment::class,
            DailyIncomeChart::class,
            DiskonUsageTable::class,
        ];
    }


}
