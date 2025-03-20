<?php

namespace App\Filament\Resources\StrategicObjectiveResource\RelationManagers;

use App\Constant\ActionStateEnum;
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
                Tables\Columns\TextColumn::make('actions_count')
                    ->label('Actions')
                    ->counts('actions'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Ajouter une Oo'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('add_action')
                    ->label('Ajouter une action')
                    ->icon('heroicon-s-plus')->form(function (Form $form): Form {
                        return self::createForm($form);
                    })
                    ->action(
                        (function (array $data, OperationalObjective $record): void {

                            dd($data);
                            $this->saveAction($data, $record);

                            Notification::make()
                                ->success()
                                ->title(__('messages.form.registration.notification.finish.title'))
                                ->body(__('messages.form.registration.notification.finish.body'))
                                ->send();
                        })
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

    public static function createForm(Form $form): Form
    {
        return $form
            ->model(ActionPst::class)
            ->live()
            ->columns(1)
            ->schema([
                Wizard::make([
                    Wizard\Step::make('necessary_data')
                        ->label('Projet')
                        ->schema(
                            self::fieldsProject(),
                        ),
                    Wizard\Step::make('team')
                        ->label('Equipes')
                        ->schema(
                            self::fieldsTeam(),
                        ),
                    Wizard\Step::make('Info')
                        ->label('info')
                        ->schema(
                            self::fieldsDescription(),
                        ),
                    Wizard\Step::make('financing')
                        ->label('Financement')
                        ->schema(
                            self::fieldsFinancing(),
                        ),
                ])
                    ->nextAction(
                        fn(Action $action) => $action
                            ->label('Suivant')
                            ->color('success'),
                    )->previousAction(
                        fn(Action $action) => $action
                            ->label('Précédent')
                            ->color('warning'),
                    )
                    ->submitAction(view('components.btn_add', ['label' => 'Ajouter l\'action'])),
            ]);
    }

    public static function fieldsProject(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Intitulé')
                ->required()
                ->maxLength(150),
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('progress_indicator')
                        ->label('Indicateur d\'avancement')
                        ->default(ActionStateEnum::NEW->value)
                        ->options(ActionStateEnum::class)
                        ->suffixIcon('tabler-ladder'),
                    Forms\Components\DatePicker::make('due_date')
                        ->label('Date d\'échéance')
                        ->suffixIcon('tabler-calendar-stats'),
                ]),
            Forms\Components\RichEditor::make('description'),
        ];
    }

    private static function fieldsTeam(): array
    {
        return [
            Forms\Components\Select::make('users')
                ->label('Agents')
                ->relationship(
                    name: 'users',
                    modifyQueryUsing: fn(Builder $query) => $query->orderBy('last_name')->orderBy('first_name'),
                )
                ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->first_name} {$record->last_name}")
                ->searchable(['first_name', 'last_name'])
                ->multiple(),
            Forms\Components\Select::make('services')
                ->label('Services')
                ->relationship(name: 'services', titleAttribute: 'name')
                ->multiple(),
            Forms\Components\Select::make('partners')
                ->label('Partenaires')
                ->relationship(name: 'partners', titleAttribute: 'name')
                ->multiple(),
        ];
    }

    private static function fieldsFinancing(): array
    {
        return [
            Forms\Components\Textarea::make('budget_estimate')
                ->label('Budget estimé'),

            Forms\Components\Textarea::make('financing_mode')
                ->label('Mode de financement'),
        ];
    }

    private static function fieldsDescription(): array
    {
        return [
            Forms\Components\Textarea::make('evaluation_indicator')
                ->label('Indicateur d\'évaluation'),
            Forms\Components\Textarea::make('work_plan')
                ->label('Plan de travail'),
        ];
    }

}
