<?php

namespace App\Form;

use App\Constant\ActionPriorityEnum;
use App\Constant\ActionStateEnum;
use App\Models\OperationalObjective;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ActionForm
{
    public static function createForm(Form $form, Model|OperationalObjective|null $record): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Wizard::make([
                    Wizard\Step::make('necessary_data')
                        ->label('Projet')
                        ->schema(
                            self::fieldsProject($record),
                        ),
                    Wizard\Step::make('team')
                        ->label('Equipes')
                        ->schema(
                            self::fieldsTeam(),
                        ),
                    Wizard\Step::make('info')
                        ->label('Informations')
                        ->schema(
                            self::fieldsDescription(),
                        ),
                    Wizard\Step::make('financing')
                        ->label('Financement')
                        ->schema(
                            self::fieldsFinancing(),
                        ),
                ])
                    ->skippable()
                    ->nextAction(
                        fn(Action $action) => $action
                            ->label('Suivant')
                            ->color('success'),
                    )->previousAction(
                        fn(Action $action) => $action
                            ->label('Précédent')
                            ->color('secondary'),
                    )
                    ->submitAction(view('components.btn_add', ['label' => 'Ajouter l\'action'])),
            ]);
    }


    public static function editForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title'),
                                Forms\Components\Textarea::make('body'),
                            ])
                            ->columnSpan(2),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title2'),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    private static function fieldsProject(Model|OperationalObjective|null $record): array
    {
        return [
            Forms\Components\Select::make('operational_objective_id')
                ->label('Objectif opérationel')
                ->relationship(name: 'operationalObjective', titleAttribute: 'name')
                ->searchable(['name'])
                ->preload()
                ->suffixIcon('tabler-ladder')
                ->visible(fn() => $record === null),
            Forms\Components\TextInput::make('name')
                ->label('Intitulé')
                ->required()
                ->maxLength(150),
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('state')
                        ->label('Etat d\'avancement')
                        ->default(ActionStateEnum::NEW->value)
                        ->options(ActionStateEnum::class)
                        ->suffixIcon('tabler-ladder'),
                    Forms\Components\Select::make('priority')
                        ->label('Priorité')
                        ->default(ActionPriorityEnum::UNDETERMINED->value)
                        ->options(ActionPriorityEnum::class)
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
                    modifyQueryUsing: fn(Builder $query) => $query->orderBy('last_name')
                        ->orderBy('first_name'),
                )
                ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->first_name} {$record->last_name}")
                ->searchable(['first_name', 'last_name'])
                ->multiple(),
            Forms\Components\Select::make('action_service')
                ->label('Services')
                ->relationship(name: 'services', titleAttribute: 'name')
                ->preload()
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
            Forms\Components\Select::make('odds')
                ->label('Odds')
                ->relationship(name: 'odds', titleAttribute: 'name')
                ->multiple()
            ->preload(),
        ];
    }

    public static function fieldsAttachment(): array
    {
        return
            [
                Forms\Components\Hidden::make('file_mime'),
                Forms\Components\Hidden::make('file_size'),
                Forms\Components\TextInput::make('file_name')
                    ->label('Nom du média')
                    ->required()
                    ->maxLength(150),
                FileUpload::make('media')
                    ->label('Pièce jointe')
                    ->required()
                    ->maxFiles(1)
                    ->disk('public')
                    ->directory('uploads')
                    //->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                    //->preserveFilenames()
                    ->downloadable()
                    ->maxSize(10240)
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state instanceof TemporaryUploadedFile) {
                            $set('file_mime', $state->getMimeType());
                            $set('file_size', $state->getSize());
                        }
                    }),
            ];
    }

    public static function fieldsReminder(): array
    {
        return
            [
                Forms\Components\Textarea::make('content')
                    ->label('Contenu')
                    ->required(),
            ];
    }
}
