<?php

namespace App\Filament\Resources\StrategicObjectiveResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OosRelationManager extends RelationManager
{
    protected static string $relationship = 'oos';

    /**
     * @param Model $ownerRecord
     * @param string $pageClass
     * @return string|null
     */
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return $ownerRecord->oos()->count().' Objectifs Opérationnels (OO)';
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('strategic_objective_id')
                    ->relationship('strategicObjective', 'name')
                    ->label('Objectif Opérationnel')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Intitulé')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
