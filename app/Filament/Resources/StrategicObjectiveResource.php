<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrategicObjectiveResource\Pages;
use App\Filament\Resources\StrategicObjectiveResource\RelationManagers\OosRelationManager;
use App\Models\StrategicObjective;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StrategicObjectiveResource extends Resource
{
    protected static ?string $model = StrategicObjective::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadgeColor22(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }

    public static function getModelLabel(): string
    {
        return 'Objectif Stratégique (OS)';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Intitulé')
                    ->searchable(),
                Tables\Columns\TextColumn::make('oos_count')
                    ->label('Oos')
                    ->tooltip('Objectif Opérationnel')
                    ->counts('oos'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function infolist222(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                RepeatableEntry::make('oos')
                    ->label('Objectifs Opérationnels (OO)')
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->inlineLabel(true)
                            ->columns(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Intitule'),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStrategicObjectives::route('/'),
            'create' => Pages\CreateStrategicObjective::route('/create'),
            'view' => Pages\ViewStrategicObjective::route('/{record}'),
            'edit' => Pages\EditStrategicObjective::route('/{record}/edit'),
        ];
    }
}
