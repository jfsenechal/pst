<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Constant\ActionStateEnum;
use App\Filament\Resources\ActionResource;
use App\Filament\Resources\OddResource;
use App\Filament\Resources\OperationalObjectiveResource;
use App\Filament\Resources\Pages\Concerns\CanPaginateViewRecord;
use App\Filament\Resources\StrategicObjectiveResource;
use App\Form\ActionForm;
use App\Infolists\Components\ProgressEntry;
use App\Mail\ActionReminderMail;
use App\Models\Action as ActionModel;
use App\Models\Odd;
use App\Models\Partner;
use App\Models\Service;
use App\Models\User;
use App\Repository\ActionRepository;
use Filament\Actions;
use Filament\Actions\Action as ActionAction;
use Filament\Actions\ActionGroup;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class ViewAction extends ViewRecord
{
    use CanPaginateViewRecord;

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
            //  PreviousAction::make(),
            //  NextAction::make(),
            ActionGroup::make([
                    ActionAction::make('rapport')
                        ->label('Export en pdf')
                        ->icon('tabler-pdf')
                        ->url(fn(ActionModel $record) => route('download.action', $record))
                        ->action(function () {
                            Notification::make()
                                ->title('Pdf exporté')
                                ->success()
                                ->send();
                        }),
                    //->openUrlInNewTab(),
                    ActionAction::make('reminder')
                        ->label('Houspiller')
                        ->icon('tabler-school-bell')
                        ->modal()
                        ->modalDescription('Envoyer un mail aux agents')
                        ->modalHeading('Où en sommes-nous actuellement ?')
                        ->modalContentFooter(new HtmlString('Un lien vers l\'action sera automatiquement ajouté'))
                        ->modalContent(
                            view('filament.resources.action-resource.reminder-modal-description', [
                                'emails' => ActionRepository::findByActionEmailAgents($this->record->id),
                            ])
                        )
                        ->form(
                            ActionForm::fieldsReminder()
                        )
                        ->action(function (array $data, ActionModel $action) {
                            $emails = ActionRepository::findByActionEmailAgents($action->id);
                            if ($emails->count() == 0) {
                                $emails = ['jf@marche.be'];
                            }
                            try {
                                Mail::to($emails)
                                    ->send(new ActionReminderMail($action, $data));
                            } catch (\Exception $e) {
                                dd($e->getMessage());
                            }
                        }),
                    Actions\DeleteAction::make()
                        ->icon('tabler-trash'),
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
                        Fieldset::make('team')
                            ->label('Team')
                            ->schema([
                                TextEntry::make('users')
                                    ->label('Agents pilotes')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn(User $state): string => $state->last_name.' '.$state->first_name
                                    ),
                                TextEntry::make('mandataries')
                                    ->label('Mandataires')
                                    ->badge()
                                    ->formatStateUsing(
                                        fn(User $state): string => $state->last_name.' '.$state->first_name
                                    ),
                                TextEntry::make('leaderServices')
                                    ->label('Services porteurs')
                                    ->badge()
                                    ->formatStateUsing(fn(Service $state): string => $state->name),
                                TextEntry::make('partnerServices')
                                    ->label('Services partenaires')
                                    ->badge()
                                    ->formatStateUsing(fn(Service $state): string => $state->name),
                                TextEntry::make('partners')
                                    ->label('Partenaires')
                                    ->badge()
                                    ->formatStateUsing(fn(Partner $state): string => $state->name),
                            ]),
                    ]),
                    Section::make('Etat')->schema([
                        TextEntry::make('state')
                            ->label('Etat d\'avancement')
                            ->formatStateUsing(fn(ActionStateEnum $state) => $state->getLabel() ?? 'Unknown')
                            ->icon(fn(ActionStateEnum $state) => $state->getIcon())
                            ->color(fn(ActionStateEnum $state) => $state->getColor()),
                        ProgressEntry::make('state_percentage')
                            ->label('Pourcentage d\'avancement'),
                        TextEntry::make('due_date')
                            ->label('Date d\'échéance')
                            ->visible(fn(?\DateTime $date) => $date instanceof \DateTime)
                            ->dateTime(),
                        TextEntry::make('created_at')
                            ->label('Créé le')
                            ->dateTime(),
                        TextEntry::make('user_add')
                            ->label('Créé par'),
                    ])->grow(false),
                ])
                    ->columnSpanFull()
                    ->from('md'),
                Fieldset::make('odd_tab')
                    ->label('Objectifs de développement durable')
                    ->schema([
                        RepeatableEntry::make('odds')
                            ->label(false)
                            ->columnSpanFull()
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nom')
                                    ->color('secondary')
                                    ->columnSpanFull()
                                    ->url(
                                        fn(Odd $record): string => OddResource::getUrl(
                                            'view',
                                            ['record' => $record]
                                        )
                                    ),
                            ]),
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
            ]);
    }
}
