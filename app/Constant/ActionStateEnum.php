<?php

namespace App\Constant;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ActionStateEnum: string implements HasColor, HasLabel, HasDescription, HasIcon
{
    case CANCELED = "CANCELED";
    case FINISHED = "FINISHED";
    case NEW = "NEW";
    case PENDING = "PENDING";
    case SUSPENDED = "SUSPENDED";

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
            self::CANCELED => 'Annulé',
            self::NEW => 'Nouveau',
            self::PENDING => 'En cours',
            self::SUSPENDED => "Suspendu",
            self::FINISHED => "Terminé",
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CANCELED => 'danger',
            self::NEW => 'success',
            self::FINISHED => 'success',
            self::PENDING => 'warning',
            self::SUSPENDED => "warning",
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::CANCELED => 'danger',
            self::NEW => 'success',
            self::FINISHED => "success",
            self::PENDING => 'warning',
            self::SUSPENDED => "warning",
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CANCELED => 'heroicon-m-clock',
            self::NEW => 'heroicon-m-exclamation-circle',
            self::FINISHED => "heroicon-m-check",
            self::PENDING => 'heroicon-m-check',
            self::SUSPENDED => "heroicon-m-check",
        };
    }
}
