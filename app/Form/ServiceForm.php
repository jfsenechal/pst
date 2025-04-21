<?php

namespace App\Form;

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
                Forms\Components\Select::make('users')
                    ->label('Agents membres')
                    ->relationship('users', 'first_name')
                    ->multiple()
                    ->columnSpanFull(),
            ]);
    }
}
