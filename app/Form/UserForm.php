<?php

namespace App\Form;

use App\Constant\DepartmentEnum;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;

class UserForm
{
    public static function createForm(Form $form, Model|User $record = null): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('roles')
                    ->label('Rôles')
                    ->helperText(fn(Role $role) => $role->description)
                    ->relationship('roles', 'name')
                ->multiple(),
                Forms\Components\Select::make('departments')
                    ->default(DepartmentEnum::VILLE->value)
                    ->options(DepartmentEnum::class)
                    ->multiple()
                    ->preload(),
            ]);
    }

}
