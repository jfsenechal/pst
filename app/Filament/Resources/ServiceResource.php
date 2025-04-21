<?php

namespace App\Filament\Resources;

use App\Constant\NavigationGroupEnum;
use App\Filament\Resources\OddResource\RelationManagers\ActionsRelationManager;
use App\Filament\Resources\ServiceResource\Pages;
use App\Form\ServiceForm;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
        return $table
            ->defaultPaginationPageOption(50)
            ->defaultSort('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initials')
                    ->label('Initiales')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Agents')
                    ->counts('users'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
