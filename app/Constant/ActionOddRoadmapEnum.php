<?php

namespace App\Constant;

use Filament\Support\Contracts\HasLabel;

enum ActionOddRoadmapEnum: string implements HasLabel
{
    case YES = "TO_VALIDATE";
    case NO = "START";
    case NULL = "";

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
            self::YES => 'Oui',
            self::NO => 'Non',
            self::NULL => '',
        };
    }

}
