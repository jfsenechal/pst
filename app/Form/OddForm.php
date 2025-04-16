<?php

namespace App\Form;

use Filament\Forms;
use Filament\Forms\Form;

class OddForm
{
    public static function createForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('icon')
                    ->label('Icone'),
                Forms\Components\ColorPicker::make('color')
                    ->label('Couleur'),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}
