<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Filament\Resources\OperationalObjectiveResource;
use App\Filament\Resources\Pages\Concerns\CanPaginateViewRecordTrait;
use App\Filament\Resources\StrategicObjectiveResource;
use App\Form\ActionForm;
use App\Infolists\ActionInfolist;
use App\Mail\ActionReminderMail;
use App\Models\Action as ActionModel;
use App\Repository\ActionRepository;
use Filament\Actions;
use Filament\Actions\Action as ActionAction;
use Filament\Actions\ActionGroup;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class ViewAction extends ViewRecord
{
    use CanPaginateViewRecordTrait;

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
        return ActionInfolist::infolist($infolist);
    }
}
