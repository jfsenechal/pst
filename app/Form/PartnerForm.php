<?php

namespace App\Form;

use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class PartnerForm
{
    public static function createForm(Form $form, Model|Partner|null $record = null): Form
    {
        return $form
          //  ->fill($record->attributesToArray())
            ->columns(2)
            ->schema(self::schema());
    }

    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('initials')
                ->default(null)
                ->maxLength(30),
            Forms\Components\TextInput::make('phone')
                ->label('Téléphone')
                ->tel()
                ->maxLength(255)
                ->default(null),
            Forms\Components\TextInput::make('email')
                ->email()
                ->maxLength(255)
                ->default(null),
            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),
        ];
    }
}
