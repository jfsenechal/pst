<?php

namespace App\Form;

use App\Constant\DepartmentEnum;
use App\Models\User;
use Filament\Forms;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class UserForm
{
    public static function createForm(Schema $form, Model|User|null $record = null): Schema
    {
        return $form
            ->schema([
                Forms\Components\CheckboxList::make('roles')
                    ->label('Rôles')
                    ->relationship('roles', 'name'),
                Forms\Components\ToggleButtons::make('departments')
                    ->label('Département(s)')
                    ->default(DepartmentEnum::VILLE->value)
                    ->options(
                        [
                            DepartmentEnum::VILLE->value => DepartmentEnum::VILLE->getLabel(),
                            DepartmentEnum::CPAS->value => DepartmentEnum::CPAS->getLabel(),
                        ]
                    )
                    ->multiple()
                    ->required(),
            ]);
    }

}
