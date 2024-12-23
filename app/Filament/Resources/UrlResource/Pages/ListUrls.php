<?php

namespace App\Filament\Resources\UrlResource\Pages;

use App\Filament\Resources\UrlResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUrls extends ListRecords
{
    protected static string $resource = UrlResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

}
