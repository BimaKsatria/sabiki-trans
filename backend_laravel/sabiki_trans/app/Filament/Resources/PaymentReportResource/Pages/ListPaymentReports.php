<?php

namespace App\Filament\Resources\PaymentReportResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PaymentReportResource;
use App\Filament\Resources\PaymentReportResource\Widgets\ReportChart;
use App\Filament\Resources\PaymentReportResource\Widgets\ReportStats;

class ListPaymentReports extends ListRecords
{
    protected static string $resource = PaymentReportResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ReportStats::class,
            ReportChart::class,
        ];
    }
}
