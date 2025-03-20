<?php

namespace App\Form;

use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class PartnerForm
{
    public function createForm(Form $form, Model|Partner $record = null): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('initials')
                    ->maxLength(30),
                Forms\Components\Textarea::make('description'),
            ]);
    }
}
