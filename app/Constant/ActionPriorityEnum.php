<?php

namespace App\Constant;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ActionPriorityEnum: string implements HasColor, HasLabel, HasIcon
{
    case AVERAGE = "AVERAGE";
    case MAXIMUM = "MAXIMUM";
    case MINIMUM = "MINIMUM";
    case UNDETERMINED = "UNDETERMINED";

    public static function toArray(): array
    {
        $values = [];
        foreach (self::cases() as $actionStateEnum) {
            $values[] = $actionStateEnum->value;
        }

        return $values;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::AVERAGE => 'Moyenne',
            self::MINIMUM => 'Minimale',
            self::MAXIMUM => 'Maximum',
            self::UNDETERMINED => 'Non déterminée',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::AVERAGE => 'success',
            self::MINIMUM => 'primary',
            self::MAXIMUM => 'danger',
            self::UNDETERMINED => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::AVERAGE => 'tabler-cell-signal-4',
            self::MINIMUM => 'tabler-cell-signal-2',
            self::MAXIMUM => 'tabler-cell-signal-5',
            self::UNDETERMINED => 'tabler-cell-signal-1',
        };
    }
}
