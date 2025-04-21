<?php

namespace App\Filament\Resources\OperationalObjectiveResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Form\ActionForm;
use App\Models\Action;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActionsRelationManager extends RelationManager
{
    protected static string $relationship = 'actions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return $ownerRecord->actions()->count().' Actions';
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->defaultSort('name')
            ->recordUrl(fn(Action $record) => ActionResource::getUrl('view', [$record]))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Intitulé')
                    ->limit(120)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter une action')
                    ->icon('tabler-plus')
                    ->form(fn(Form $form): Form => ActionForm::createForm($form, $this->ownerRecord)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('tabler-edit'),
            ]);
    }
}
