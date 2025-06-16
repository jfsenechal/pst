<?php

namespace App\Filament\Resources;

use App\Constant\NavigationGroupEnum;
use App\Filament\Resources\PartnerResource\Pages;
use App\Form\PartnerForm;
use App\Models\Partner;
use App\Tables\PartnerTables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|null|\BackedEnum $navigationIcon = 'tabler-user-share';

    public static function getNavigationGroup(): ?string
    {
        return NavigationGroupEnum::SETTINGS->getLabel();
    }

    public static function getNavigationLabel(): string
    {
        return 'Partenaires externes';
    }

    public static function getModelLabel(): string
    {
        return 'Partenaire externe';
    }

    public static function form(Schema $schema): Schema
    {
        return PartnerForm::createForm($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnerTables::table($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'view' => Pages\ViewPartner::route('/{record}'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
