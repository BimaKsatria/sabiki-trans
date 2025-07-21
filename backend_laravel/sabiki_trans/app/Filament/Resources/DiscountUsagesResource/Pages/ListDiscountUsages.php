<?php

namespace App\Filament\Resources\DiscountUsagesResource\Pages;

use App\Filament\Resources\DiscountUsagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscountUsages extends ListRecords
{
    protected static string $resource = DiscountUsagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
