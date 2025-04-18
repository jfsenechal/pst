<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OperationalObjectiveResource\Pages;
use App\Filament\Resources\OperationalObjectiveResource\RelationManagers\ActionsRelationManager;
use App\Form\OperationalObjectiveForm;
use App\Models\OperationalObjective;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class OperationalObjectiveResource extends Resource
{
    protected static ?string $model = OperationalObjective::class;

    protected static ?string $navigationIcon = 'tabler-target';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'Objectif Opérationnel (OO)';
    }

    public static function form(Form $form): Form
    {
        return OperationalObjectiveForm::createForm($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('position')
                    ->label('Numéro')
                    ->state(
                        fn(OperationalObjective $objective): string => $objective->strategicObjective()->first(
                            )->position.'.'.' '.$objective->position
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->icon('tabler-edit'),
                Tables\Actions\DeleteAction::make()
                    ->icon('tabler-trash'),
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
            ActionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOperationalObjectives::route('/'),
            'create' => Pages\CreateOperationalObjective::route('/create'),
            'view' => Pages\ViewOperationalObjective::route('/{record}'),
            'edit' => Pages\EditOperationalObjective::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }
}
