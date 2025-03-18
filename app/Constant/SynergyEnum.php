<?php

namespace App\Constant;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SynergyEnum: string implements HasColor, HasLabel, HasDescription, HasIcon
{
    case CPAS = "CPAS";
    case VILLE = "VILLE";
    case COMMON = "COMMON";

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
             self::COMMON => 'Cpas et Ville',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CPAS => 'danger',
            self::VILLE => 'success',
            self::COMMON => 'warning',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::CPAS => 'danger',
            self::VILLE => 'success',
            self::COMMON => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CPAS => 'heroicon-m-clock',
            self::VILLE => 'heroicon-m-exclamation-circle',
            self::COMMON => 'heroicon-m-check',
        };
    }
}
