<?php

namespace App\Filament\Resources\CertificateResource\Pages;

use App\Filament\Resources\CertificateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCertificates extends ListRecords
{
    protected static string $resource = CertificateResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
