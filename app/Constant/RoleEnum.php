<?php

namespace App\Constant;


use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RoleEnum: string implements HasColor, HasLabel, HasDescription, HasIcon
{
    case ADMIN = "ROLE_ADMIN";
    case CHEF = "ROLE_CHEF";
    case AGENT = "ROLE_AGENT";
    case MANDATAIRE = "ROLE_MANDATAIRE";

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
            self::ADMIN => 'Administrateur',
            self::AGENT => 'Agent',
            self::MANDATAIRE => 'Mandataire',
            self::CHEF => 'Chef de projet',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ADMIN => 'success',
            self::AGENT => 'warning',
            self::CHEF => 'secondary',
            self::MANDATAIRE => 'primary',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::ADMIN => 'Accès total en écriture, peut aussi paramétrer l\'application (users,services...)',
            self::AGENT => 'Role standard',
            self::CHEF => 'Chef de projet',
            self::MANDATAIRE => 'Ne peux que lire, aucune modification possible',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ADMIN => 'Administrateur',
            self::AGENT => 'Agent',
            self::MANDATAIRE => 'Consultation',
            self::CHEF => 'Chef de projet',
        };
    }
}
