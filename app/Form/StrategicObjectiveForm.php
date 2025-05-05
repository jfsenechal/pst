<?php

namespace App\Form;

use App\Constant\DepartmentEnum;
use App\Repository\UserRepository;
use Filament\Forms;
use Filament\Forms\Form;

class StrategicObjectiveForm
{
    public static function createForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('department')
                    ->label('Département')
                    ->required()
                    ->columns(4)
                    ->default(UserRepository::departmentSelected())
                    ->options(DepartmentEnum::class)
                    ->enum(DepartmentEnum::class)
                    ->visible($form->getOperation() === 'create'),
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->numeric(),
            ]);
    }
}
