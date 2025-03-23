<?php

namespace App\Filament\Resources;

use App\Constant\NavigationGroupEnum;
use App\Filament\Resources\PartnerResource\Pages;
use App\Form\PartnerForm;
use App\Models\Partner;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'tabler-user-share';

    public static function getNavigationGroup(): ?string
    {
        return NavigationGroupEnum::SETTINGS->getLabel();
    }

    public static function getNavigationLabel(): string
    {
        return 'Partenaires';
    }

    public static function getModelLabel(): string
    {
        return 'Partenaire';
    }

    public static function form(Form $form): Form
    {
        return PartnerForm::createForm($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('initials')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
