<?php

namespace App\Form;

use App\Models\StrategicObjective;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class StrategicObjectiveForm
{
    public static function createForm(Form $form, Model|StrategicObjective $record = null): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->numeric()                ,
            ]);
    }
}
