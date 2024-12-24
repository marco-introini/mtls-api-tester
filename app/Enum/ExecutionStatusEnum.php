<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ExecutionStatusEnum: string implements HasColor, HasLabel, HasIcon
{
    case CREATED = 'CREATED';
    case EXECUTING = 'EXECUTING';
    case FINISHED = 'FINISHED';
    case ERROR = 'ERROR';


    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CREATED => 'gray',
            self::EXECUTING => 'warning',
            self::FINISHED => 'success',
            self::ERROR => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CREATED => 'heroicon-o-plus-circle',
            self::EXECUTING => 'heroicon-o-clock',
            self::FINISHED => 'heroicon-o-check-circle',
            self::ERROR => 'heroicon-o-x-circle',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::EXECUTING => 'Executing',
            self::FINISHED => 'Finished',
            self::ERROR => 'Error',
        };
    }
}
