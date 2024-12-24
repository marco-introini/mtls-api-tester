<?php

namespace App\Filament\Resources\ApiResource\Pages;

use App\Filament\Resources\ApiResource;
use Filament\Resources\Pages\EditRecord;

class EditApi extends EditRecord
{
    protected static string $resource = ApiResource::class;

    // Aggiunto per tornare alla pagina indice
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
