<?php

namespace App\Form;

use Filament\Forms;
use Filament\Schemas\Schema;

class OddForm
{
    public static function createForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('icon')
                    ->label('Icône')
                    ->previewable(false)
                    ->maxFiles(1)
                    ->image(),
                Forms\Components\ColorPicker::make('color')
                    ->label('Couleur'),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}
