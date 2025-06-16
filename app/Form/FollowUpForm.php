<?php

namespace App\Form;

use Filament\Forms;
use Filament\Schemas\Schema;

class FollowUpForm
{
    public static function createForm(Schema $form): Schema
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
