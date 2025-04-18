<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrategicObjectiveResource\Pages;
use App\Filament\Resources\StrategicObjectiveResource\RelationManagers\OosRelationManager;
use App\Form\StrategicObjectiveForm;
use App\Models\StrategicObjective;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class StrategicObjectiveResource extends Resource
{
    protected static ?string $model = StrategicObjective::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'Objectif Stratégique (OS)';
    }

    public static function form(Form $form): Form
    {
        return StrategicObjectiveForm::createForm($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->recordTitleAttribute('name')
            ->defaultSort('position')
            ->columns([
                Tables\Columns\TextColumn::make('position')
                    ->label('Numéro')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Intitulé')
                    ->limit(90)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('oos_count')
                    ->label('Objectifs Opérationnels (OO)')
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
                Tables\Actions\EditAction::make(),
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
            OosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOs::route('/'),
            'create' => Pages\CreateStrategicObjective::route('/create'),
            'view' => Pages\ViewStrategicObjective::route('/{record}'),
            'edit' => Pages\EditStrategicObjective::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }
}
