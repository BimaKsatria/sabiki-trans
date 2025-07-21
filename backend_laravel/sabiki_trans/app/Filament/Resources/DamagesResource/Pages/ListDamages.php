<?php

namespace App\Filament\Resources\DamagesResource\Pages;

use App\Filament\Resources\DamagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDamages extends ListRecords
{
    protected static string $resource = DamagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
