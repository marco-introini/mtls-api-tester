<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum APITypeEnum: string implements HasColor, HasLabel
{
    case SOAP = 'SOAP';
    case REST = 'REST';

    public function getColor(): string
    {
        return match ($this) {
            self::SOAP => 'warning',
            self::REST => 'success',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SOAP => 'SOAP',
            self::REST => 'REST',
        };
    }
}
