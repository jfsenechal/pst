<?php

namespace App\Constant;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ActionStateEnum: int implements HasColor, HasLabel, HasDescription, HasIcon
{
    case CANCELED = 0;
    case NEW = 1;
    case PENDING = 2;

    public static function toArray(): array
    {
        $values = [];
        foreach (ActionStateEnum::cases() as $actionStateEnum) {
            $values[] = $actionStateEnum->value;
        }

        return $values;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::CANCELED => 'Paid',
            self::NEW => 'Not paid',
            self::PENDING => 'Pending',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CANCELED => 'danger',
            self::NEW => 'success',
            self::PENDING => 'warning',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::CANCELED => 'danger',
            self::NEW => 'success',
            self::PENDING => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CANCELED => 'heroicon-m-clock',
            self::NEW => 'heroicon-m-exclamation-circle',
            self::PENDING => 'heroicon-m-check',
        };
    }
}
