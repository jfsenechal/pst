<?php

namespace App\Form;

use App\Constant\DepartmentEnum;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class UserForm
{
    public static function createForm(Form $form, Model|User|null $record = null): Form
    {
        return $form
            ->schema([
                Forms\Components\CheckboxList::make('roles')
                    ->label('Rôles')
                    ->relationship('roles', 'name'),
                Forms\Components\Select::make('departments')
                    ->default(DepartmentEnum::VILLE->value)
                    ->options(DepartmentEnum::class)
                    ->multiple()
                    ->preload(),
            ]);
    }

}
