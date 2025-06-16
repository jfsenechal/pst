<?php

namespace App\Filament\Resources;

use App\Constant\NavigationGroupEnum;
use App\Constant\RoleEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Form\UserForm;
use App\Models\User;
use App\Tables\UserTables;
use Filament\Schemas\Components\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-users';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return NavigationGroupEnum::SETTINGS->getLabel();
    }

    public static function getModelLabel(): string
    {
        return 'Agents';
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::createForm($schema);
    }

    public static function table(Table $table): Table
    {
        return UserTables::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Auth::getUser()->hasRole(RoleEnum::ADMIN->value);
    }
}
