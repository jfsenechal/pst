<?php

namespace App\Form;

use App\Models\OperationalObjective;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class OperationalObjectiveForm
{
    public static function createForm(Form $form, Model|OperationalObjective |null $record = null): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('strategic_objective_id')
                    ->label('Objectif Stratégique (OS)')
                    ->relationship('strategicObjective', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
