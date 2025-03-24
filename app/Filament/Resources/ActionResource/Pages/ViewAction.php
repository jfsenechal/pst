<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Constant\ActionStateEnum;
use App\Filament\Resources\ActionResource;
use App\Filament\Resources\OperationalObjectiveResource;
use App\Filament\Resources\StrategicObjectiveResource;
use App\Form\ActionForm;
use App\Models\Media;
use App\Models\Partner;
use App\Models\Service;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action as ActionAction;
use Filament\Actions\ActionGroup;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\ActionSize;
use Illuminate\Database\Eloquent\Model;

class ViewAction extends ViewRecord
{
    protected static string $resource = ActionResource::class;

    public function getTitle(): string
    {
        return $this->record->name ?? 'Empty name';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('tabler-edit'),
            Actions\DeleteAction::make()
                ->icon('tabler-trash'),
            ActionGroup::make([
                    ActionAction::make('add_media')
                        ->label('Ajouter un média')
                        ->icon('tabler-plus')
                        ->form(
                            ActionForm::fieldsAttachment()
                        )
                        ->action(function (array $data) {
                            Media::create([
                                'action_id' => $this->record->id,
                                'name' => $data['file_name'],
                                'file_name' => $data['media'],
                                'mime_type' => $data['file_mime'],
                                'disk' => 'public',
                                'size' => $data['file_size'],
                            ]);
                            Notification::make()
                                ->title('Media added successfully')
                                ->success()
                                ->send();
                        }),
                    ActionAction::make('rapport')
                        ->label('Rapport')
                        ->icon('tabler-pdf')
                        ->modal()
                        ->modalHeading('Exporter en pdf')
                        ->modalDescription('Export en pdf'),
                    ActionAction::make('reminder')
                        ->label('Houspiller')
                        ->icon('tabler-school-bell')
                        ->modal()
                        ->modalHeading('Ou ça en est ?')
                        ->modalDescription('Vous trouvez que le projet n\'avance pas. Houspiller les agents!')
                        ->form(
                            ActionForm::fieldsReminder()
                        ),
                ]
            )
                ->label('Autres actions')
                ->button()
                ->size(ActionSize::Large)
                ->color('secondary'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        $oo = $this->record->operationalObjective()->first();
        $os = $oo->strategicObjective()->first();

        return [
            StrategicObjectiveResource::getUrl('index') => 'Objectifs Stratégiques',
            StrategicObjectiveResource::getUrl('view', ['record' => $os]) => $os->name,
            OperationalObjectiveResource::getUrl('view', ['record' => $oo]) => $oo->name,
            'Action',
            //$this->getBreadcrumb(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Section::make([
                        TextEntry::make('description')
                            ->label(false)
                            ->html()
                            ->prose(),
                        TextEntry::make('work_plan')
                            ->label('Plan de travail')
                            ->html()
                            ->prose(),
                        TextEntry::make('evaluation_indicator')
                            ->label('Indicateur d\'évaluation')
                            ->html()
                            ->prose(),
                    ]),
                    Section::make([
                        TextEntry::make('progress_indicator')
                            ->label('Statut')
                            ->label('Indicateur d\'avancement')
                            ->formatStateUsing(fn($state) => ActionStateEnum::tryFrom($state)?->getLabel() ?? 'Unknown')
                            ->icon(
                                fn($state) => ActionStateEnum::tryFrom($state)?->getIcon(
                                ) ?? 'heroicon-m-question-mark-circle'
                            )
                            ->color(fn($state) => ActionStateEnum::tryFrom($state)?->getColor() ?? 'gray'),
                        TextEntry::make('created_at')
                            ->label('Créé le')
                            ->dateTime(),
                        TextEntry::make('due_date')
                            ->label('Date d\'échéance')
                            ->dateTime(),
                    ])->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('md'),
                Fieldset::make('team')
                    ->label('Team')
                    ->schema([
                        TextEntry::make('users')
                            ->label('Agents')
                            ->badge()
                            ->formatStateUsing(fn(User $state): string => $state->last_name.' '.$state->first_name),
                        TextEntry::make('services')
                            ->label('Services')
                            ->badge()
                            ->formatStateUsing(fn(Service $state): string => $state->name),
                        TextEntry::make('partners')
                            ->label('Partenaires')
                            ->badge()
                            ->formatStateUsing(fn(Partner $state): string => $state->name),
                    ]),
                Fieldset::make('budget')
                    ->label('Financement')
                    ->schema([
                        TextEntry::make('budget_estimate')
                            ->markdown()
                            ->label('Budget estimé')
                            ->prose(),
                        TextEntry::make('financing_mode')
                            ->markdown()
                            ->label('Mode de financement')
                            ->prose(),
                    ]),
                Fieldset::make('medias_tab')
                    ->relationship('medias')
                    ->label('Médias')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nom')
                            ->formatStateUsing(
                                fn($state) => "<a href='/storage/uploads/{$state}' target='_blank'>Download</a>"
                            )
                            ->hint('Documentation? What documentation?!')
                            ->suffixAction(
                                Action::make('download')
                                    ->icon('tabler-download')
                                    ->action(function (Media|Model $record) {
                                        dd($record);
                                    }),
                            ),
                        TextEntry::make('mime_type')
                            ->label('Type'),
                        TextEntry::make('file_size')
                            ->label('Size')->formatStateUsing(
                                fn($state) => number_format($state / 1024, 2).' KB'
                            )
                        ,
                    ]),
            ]);
    }

    private function tableft(): array
    {
        return [
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Tab 1')
                        ->schema([

                        ]),
                    Tabs\Tab::make('Tab 2')
                        ->schema([
                            // ...
                        ]),
                    Tabs\Tab::make('Budget')
                        ->schema([
                            TextEntry::make('budget_estimate'),
                            TextEntry::make('financing_mode'),
                        ]),
                ]),

            Section::make('Rate limiting')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->schema([

                    RepeatableEntry::make('services')
                        ->schema([
                            TextEntry::make('name'),
                        ])
                        ->columns(2),
                ]),
            TextEntry::make('description')->columnSpanFull(2),
            TextEntry::make('due_date')->dateTime(),
            TextEntry::make('evaluation_indicator'),
            TextEntry::make('work_plan'),
            TextEntry::make('progress_indicator'),
            TextEntry::make('users'),
            TextEntry::make('partners'),
        ];
    }
}
