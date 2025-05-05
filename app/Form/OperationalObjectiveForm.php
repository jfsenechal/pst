<?php

namespace App\Form;

use App\Models\OperationalObjective;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class OperationalObjectiveForm
{
    public static function formRelation(Form $form, Model|OperationalObjective|null $owner = null): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Intitulé')
                    ->maxLength(255),
                Forms\Components\Select::make('strategic_objective_id')
                    ->relationship('strategicObjective', 'name')
                    ->label('Objectif Opérationnel')
                    ->default($owner?->id)
                    ->required(),
                Forms\Components\Hidden::make('department')
                    ->default($owner->department),
            ]);
    }
}
