<?php

namespace App\Constant;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ActionStateEnum: string implements HasColor, HasLabel, HasDescription, HasIcon
{
    case TO_VALIDATE = "TO_VALIDATE";
    case START = "START";
    case PENDING = "PENDING";
    case FINISHED = "FINISHED";
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
            self::TO_VALIDATE => 'A valider',
            self::START => 'A démarrer',
            self::PENDING => 'En cours',
            self::SUSPENDED => "Suspendu",
            self::FINISHED => "Terminé",
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::TO_VALIDATE => 'danger',
            self::START => 'success',
            self::FINISHED => 'success',
            self::PENDING => 'warning',
            self::SUSPENDED => "danger",
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::TO_VALIDATE => 'success',
            self::START => 'success',
            self::FINISHED => "success",
            self::PENDING => 'warning',
            self::SUSPENDED => "warning",
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::START => 'success',
            self::TO_VALIDATE => 'heroicon-m-exclamation-circle',
            self::FINISHED => "heroicon-m-check",
            self::PENDING => 'heroicon-m-check',
            self::SUSPENDED => "heroicon-m-check",
        };
    }
}
