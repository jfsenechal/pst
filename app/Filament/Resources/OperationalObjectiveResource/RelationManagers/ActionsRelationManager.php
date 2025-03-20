<?php

namespace App\Filament\Resources\OperationalObjectiveResource\RelationManagers;

use App\Filament\Resources\ActionResource;
use App\Models\Action;
use Filament\Forms;
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

    public function form22(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Intitulé')
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->label('Description'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Ajouter une Oo'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(
                        fn(Action $record): string => ActionResource::getUrl(
                            'view',
                            ['record' => $record]
                        )
                    ),
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
