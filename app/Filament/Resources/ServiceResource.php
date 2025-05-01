<?php

namespace App\Filament\Resources;

use App\Constant\NavigationGroupEnum;
use App\Filament\Resources\ServiceResource\Pages;
use App\Form\ServiceForm;
use App\Models\Service;
use App\Tables\ServiceTables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'tabler-users-group';

    public static function getNavigationGroup(): ?string
    {
        return NavigationGroupEnum::SETTINGS->getLabel();
    }

    public static function form(Form $form): Form
    {
        return ServiceForm::createForm($form);
    }

    public static function table(Table $table): Table
    {
        return ServiceTables::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
