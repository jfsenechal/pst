<?php

namespace App\Form;

use Filament\Forms;
use Filament\Forms\Form;

class FollowUpForm
{
    public static function createForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('icon')
                    ->label('Icone'),
            ]);
    }
}
