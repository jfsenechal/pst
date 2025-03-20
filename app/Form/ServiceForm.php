<?php

namespace App\Form;

use App\Constant\SynergyEnum;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class ServiceForm
{
    public static function createForm(Form $form, Model|Service $record = null): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('initials')
                    ->maxLength(30),
                Forms\Components\Select::make('synergy')
                    ->label('Synergie')
                    ->default(SynergyEnum::COMMON->value)
                    ->options(SynergyEnum::class),
            ]);
    }
}
