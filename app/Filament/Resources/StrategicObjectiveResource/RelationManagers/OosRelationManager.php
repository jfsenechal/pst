<?php

namespace App\Filament\Resources\StrategicObjectiveResource\RelationManagers;

use App\Constant\ActionStateEnum;
use App\Filament\Resources\OperationalObjectiveResource;
use App\Form\ActionForm;
use App\Models\Action as ActionPst;
use App\Models\OperationalObjective;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Intitulé')
                    ->maxLength(255),
                Forms\Components\Select::make('strategic_objective_id')
                    ->relationship('strategicObjective', 'name')
                    ->label('Objectif Opérationnel')
                    ->helperText('Vous pouvez le déplacer')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(50)
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('actions_count')
                    ->label('Actions')
                    ->counts('actions'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter un Oo'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(
                        fn(OperationalObjective $record): string => OperationalObjectiveResource::getUrl(
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

    private function saveAction(array $data, OperationalObjective $record): void
    {
        ActionPst::create([
            "operational_objective_id" => $data['operational_objective_id'],
            "name" => $data['name'],
            "progress_indicator" => $record->id,
            "due_date" => $data['due_date'],
            "description" => $data['description'],
            "evaluation_indicator" => $data['evaluation_indicator'],
            "work_plan" => $data['work_plan'],
            "budget_estimate" => $data['budget_estimate'],
            "financing_mode" => $data['financing_mode'],
        ]);
    }

    private function btnAddActionBug(): Action
    {
        return Tables\Actions\Action::make('add_action')
            ->label('Ajouter une action')
            ->icon('heroicon-s-plus')
            ->form(function (Form $form): Form {
                return ActionForm::createForm($form, $this->getOwnerRecord());
            })
            ->action(
                (function (array $data, OperationalObjective $record): void {

                    dd($data);
                    //j'ai pas acces a services,users,...
                    $this->saveAction($data, $record);

                    Notification::make()
                        ->success()
                        ->title(__('messages.form.registration.notification.finish.title'))
                        ->body(__('messages.form.registration.notification.finish.body'))
                        ->send();
                })
            );
    }


}
