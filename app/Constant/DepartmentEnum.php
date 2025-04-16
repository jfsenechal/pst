<?php

namespace App\Constant;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum DepartmentEnum: string implements HasColor, HasLabel, HasDescription, HasIcon
{
    case CPAS = "CPAS";
    case VILLE = "VILLE";

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
             self::CPAS => 'Cpas',
             self::VILLE => 'Ville',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CPAS => 'danger',
            self::VILLE => 'success',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::CPAS => 'danger',
            self::VILLE => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CPAS => 'heroicon-m-clock',
            self::VILLE => 'heroicon-m-exclamation-circle',
        };
    }
}
