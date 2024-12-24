<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MethodEnum: string implements HasLabel, HasColor
{
    case POST = 'POST';
    case GET = 'GET';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';

    public function getColor(): string
    {
        return match ($this) {
            self::POST => 'warning',
            self::GET => 'success',
            self::PUT => 'info',
            self::PATCH => 'gray',
            self::DELETE => 'danger',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::POST => 'POST',
            self::GET => 'GET',
            self::PUT => 'PUT',
            self::PATCH => 'PATCH',
            self::DELETE => 'DELETE',
        };
    }
}
