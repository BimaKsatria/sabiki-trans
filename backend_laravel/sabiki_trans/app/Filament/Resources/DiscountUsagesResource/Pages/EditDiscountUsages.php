<?php

namespace App\Filament\Resources\DiscountUsagesResource\Pages;

use App\Filament\Resources\DiscountUsagesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscountUsages extends EditRecord
{
    protected static string $resource = DiscountUsagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
