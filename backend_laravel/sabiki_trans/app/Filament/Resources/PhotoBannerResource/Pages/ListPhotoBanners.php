<?php

namespace App\Filament\Resources\PhotoBannerResource\Pages;

use App\Filament\Resources\PhotoBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhotoBanners extends ListRecords
{
    protected static string $resource = PhotoBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
